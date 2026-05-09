<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AttendanceRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $selectedMonth    = $request->query('month', now()->format('Y-m'));
        $selectedPosition = $request->query('position');

        $query = AttendanceRecord::with('employee')
            ->whereRaw("DATE_FORMAT(attendance_date, '%Y-%m') = ?", [$selectedMonth]);

        if (! empty($selectedPosition)) {
            $query->whereHas('employee', fn ($q) => $q->where('position', $selectedPosition));
        }

        $summary = [
            'present' => (clone $query)->where('status', 'present')->count(),
            'late'    => (clone $query)->where('status', 'late')->count(),
            'absent'  => (clone $query)->where('status', 'absent')->count(),
            'hours'   => (float) (clone $query)->sum('hours_worked'),
        ];

        $attendanceRecords = $query
            ->orderByDesc('attendance_date')
            ->paginate(15)
            ->withQueryString();

        $employees = Employee::orderBy('last_name')->get();
        $positions = Employee::query()
            ->whereNotNull('position')
            ->distinct()
            ->orderBy('position')
            ->pluck('position');

        return view('attendance-records.index_clean', compact(
            'attendanceRecords',
            'employees',
            'positions',
            'selectedMonth',
            'selectedPosition',
            'summary'
        ));
    }

    /**
     * Store a newly created resource in storage (single record).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id'     => ['required', 'exists:employees,id'],
            'attendance_date' => [
                'required', 'date',
                Rule::unique('attendance_records')->where(
                    fn ($q) => $q->where('employee_id', $request->input('employee_id'))
                ),
            ],
            'time_in'     => ['nullable', 'date_format:H:i'],
            'time_out'    => ['nullable', 'date_format:H:i'],
            'status'      => ['required', Rule::in(['present', 'late', 'absent'])],
            'hours_worked'=> ['nullable', 'numeric', 'min:0', 'max:24'],
            'notes'       => ['nullable', 'string'],
        ]);

        $validated['hours_worked'] = $this->calcHours(
            $validated['hours_worked'] ?? null,
            $validated['time_in']      ?? null,
            $validated['time_out']     ?? null
        );

        AttendanceRecord::create($validated);

        return redirect()
            ->route('attendance-records.index')
            ->with('status', 'Attendance record added successfully.');
    }

    /**
     * Save attendance for many employees for one date (bulk create).
     */
    public function bulkStore(Request $request)
    {
        $request->merge(['entries' => array_values($request->input('entries', []))]);

        $validated = $request->validate([
            'attendance_date'           => ['required', 'date'],
            'entries'                   => ['required', 'array', 'min:1'],
            'entries.*.employee_id'     => ['required', 'exists:employees,id'],
            'entries.*.time_in'         => ['nullable', 'date_format:H:i'],
            'entries.*.time_out'        => ['nullable', 'date_format:H:i'],
            'entries.*.status'          => ['nullable', Rule::in(['present', 'late', 'absent'])],
            'entries.*.notes'           => ['nullable', 'string', 'max:500'],
        ]);

        $date  = $validated['attendance_date'];
        $saved = 0;

        DB::transaction(function () use ($validated, $date, &$saved): void {
            foreach ($validated['entries'] as $row) {
                $timeIn    = $row['time_in']  ?? null;
                $timeOut   = $row['time_out'] ?? null;
                $rawStatus = isset($row['status']) ? trim((string) $row['status']) : '';
                $status    = $rawStatus === '' ? null : $rawStatus;
                $notes     = $row['notes'] ?? null;

                $hasBoth = ! empty($timeIn) && ! empty($timeOut);
                $hasAny  = ! empty($timeIn) || ! empty($timeOut);

                // Skip completely blank rows
                if (! $hasAny && $status === null) {
                    continue;
                }

                // Allow time-in only (partial — no time-out yet)
                if (! empty($timeIn) && empty($timeOut)) {
                    $status = $status ?? 'present';
                    AttendanceRecord::updateOrCreate(
                        ['employee_id' => (int) $row['employee_id'], 'attendance_date' => $date],
                        ['time_in' => $timeIn, 'time_out' => null, 'status' => $status, 'hours_worked' => null, 'notes' => $notes]
                    );
                    $saved++;
                    continue;
                }

                // time-out without time-in — skip
                if (empty($timeIn) && ! empty($timeOut)) {
                    continue;
                }

                // Present/Late need at least time-in
                if (in_array($status, ['present', 'late'], true) && ! $hasAny) {
                    continue;
                }

                // Auto-set status when both times present
                if ($status === null && $hasBoth) {
                    $status = 'present';
                }

                if ($status === null) {
                    continue;
                }

                $hoursWorked = $this->calcHours(null, $timeIn, $timeOut);

                AttendanceRecord::updateOrCreate(
                    ['employee_id' => (int) $row['employee_id'], 'attendance_date' => $date],
                    [
                        'time_in'      => $timeIn  ?: null,
                        'time_out'     => $timeOut ?: null,
                        'status'       => $status,
                        'hours_worked' => $hoursWorked,
                        'notes'        => $notes,
                    ]
                );

                $saved++;
            }
        });

        if ($saved === 0) {
            return redirect()
                ->route('attendance-records.index', ['month' => Carbon::parse($date)->format('Y-m')])
                ->withErrors(['entries' => 'No rows saved. Enter time in (and optionally time out), or choose Absent.']);
        }

        return redirect()
            ->route('attendance-records.index', ['month' => Carbon::parse($date)->format('Y-m')])
            ->with('status', "Attendance saved for {$saved} employee(s) on ".Carbon::parse($date)->format('M j, Y').'.');
    }

    /**
     * Return all attendance records for a specific date as JSON
     * (used by the bulk-update modal's "Load Records" button).
     */
    public function day(Request $request)
    {
        $request->validate(['date' => ['required', 'date']]);
        $date = $request->query('date');

        $records = AttendanceRecord::with('employee')
            ->where('attendance_date', $date)
            ->orderBy('employee_id')
            ->get()
            ->map(fn ($r) => [
                'id'           => $r->id,
                'employee_id'  => $r->employee_id,
                'employee_name'=> $r->employee->last_name.', '.$r->employee->first_name,
                'position'     => $r->employee->position ?? '',
                'time_in'      => $r->time_in,
                'time_out'     => $r->time_out,
                'status'       => $r->status,
                'hours_worked' => $r->hours_worked,
                'notes'        => $r->notes,
            ]);

        return response()->json(['records' => $records]);
    }

    /**
     * Bulk-update all records for a given date.
     */
    public function bulkUpdate(Request $request)
    {
        $request->merge(['entries' => array_values($request->input('entries', []))]);

        $validated = $request->validate([
            'attendance_date'              => ['required', 'date'],
            'entries'                      => ['required', 'array', 'min:1'],
            'entries.*.id'                 => ['required', 'exists:attendance_records,id'],
            'entries.*.employee_id'        => ['required', 'exists:employees,id'],
            'entries.*.time_in'            => ['nullable', 'date_format:H:i'],
            'entries.*.time_out'           => ['nullable', 'date_format:H:i'],
            'entries.*.status'             => ['required', Rule::in(['present', 'late', 'absent'])],
            'entries.*.hours_worked'       => ['nullable', 'numeric', 'min:0', 'max:24'],
            'entries.*.notes'              => ['nullable', 'string', 'max:500'],
        ]);

        $date    = $validated['attendance_date'];
        $updated = 0;

        DB::transaction(function () use ($validated, &$updated): void {
            foreach ($validated['entries'] as $row) {
                $record = AttendanceRecord::find((int) $row['id']);
                if (! $record) {
                    continue;
                }

                $timeIn  = $row['time_in']  ?: null;
                $timeOut = $row['time_out'] ?: null;

                $hours = $this->calcHours(
                    isset($row['hours_worked']) && $row['hours_worked'] !== '' ? (float) $row['hours_worked'] : null,
                    $timeIn,
                    $timeOut
                );

                $record->update([
                    'employee_id'  => (int) $row['employee_id'],
                    'time_in'      => $timeIn,
                    'time_out'     => $timeOut,
                    'status'       => $row['status'],
                    'hours_worked' => $hours,
                    'notes'        => $row['notes'] ?? null,
                ]);

                $updated++;
            }
        });

        return redirect()
            ->route('attendance-records.index', ['month' => Carbon::parse($date)->format('Y-m')])
            ->with('status', "Updated {$updated} record(s) for ".Carbon::parse($date)->format('M j, Y').'.');
    }

    /**
     * Return a single record as JSON (for the view modal via JS, optional).
     */
    public function show(string $id)
    {
        $record = AttendanceRecord::with('employee')->findOrFail($id);

        return response()->json([
            'id'              => $record->id,
            'employee_id'     => $record->employee_id,
            'employee_name'   => $record->employee->last_name.', '.$record->employee->first_name,
            'position'        => $record->employee->position ?? '',
            'attendance_date' => $record->attendance_date,
            'time_in'         => $record->time_in,
            'time_out'        => $record->time_out,
            'status'          => $record->status,
            'hours_worked'    => $record->hours_worked,
            'notes'           => $record->notes,
        ]);
    }

    /**
     * Update a single attendance record.
     */
    public function update(Request $request, string $id)
    {
        $record = AttendanceRecord::findOrFail($id);

        $validated = $request->validate([
            'employee_id'     => ['required', 'exists:employees,id'],
            'attendance_date' => [
                'required', 'date',
                Rule::unique('attendance_records')
                    ->where(fn ($q) => $q->where('employee_id', $request->input('employee_id')))
                    ->ignore($record->id),
            ],
            'time_in'     => ['nullable', 'date_format:H:i'],
            'time_out'    => ['nullable', 'date_format:H:i'],
            'status'      => ['required', Rule::in(['present', 'late', 'absent'])],
            'hours_worked'=> ['nullable', 'numeric', 'min:0', 'max:24'],
            'notes'       => ['nullable', 'string'],
        ]);

        $validated['hours_worked'] = $this->calcHours(
            $validated['hours_worked'] ?? null,
            $validated['time_in']      ?? null,
            $validated['time_out']     ?? null
        );

        $record->update($validated);

        $month = Carbon::parse($validated['attendance_date'])->format('Y-m');

        return redirect()
            ->route('attendance-records.index', ['month' => $month])
            ->with('status', 'Attendance record updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        AttendanceRecord::findOrFail($id)->delete();

        return redirect()
            ->route('attendance-records.index')
            ->with('status', 'Attendance record deleted.');
    }

    // ── private helpers ──────────────────────────────────────────────────────

    /**
     * Calculate hours worked from time strings.
     * Returns existing value if already set, otherwise auto-calculates.
     */
    private function calcHours(?float $existing, ?string $timeIn, ?string $timeOut): ?float
    {
        if ($existing !== null) {
            return $existing;
        }

        if (! empty($timeIn) && ! empty($timeOut)) {
            $tIn  = strtotime($timeIn);
            $tOut = strtotime($timeOut);
            if ($tOut > $tIn) {
                return round(($tOut - $tIn) / 3600, 2);
            }
        }

        return null;
    }
}