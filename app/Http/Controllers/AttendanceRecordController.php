<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AttendanceRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = AttendanceRecord::query()->with('employee');

        $search = trim((string) request('q', ''));
        if ($search !== '') {
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $status = request('status');
        if (is_string($status) && $status !== '' && $status !== 'all') {
            $query->where('status', $status);
        }

        $date = request('date');
        if (is_string($date) && $date !== '') {
            $query->whereDate('attendance_date', $date);
        }

        $records = $query
            ->orderByDesc('attendance_date')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => AttendanceRecord::count(),
            'present' => AttendanceRecord::where('status', 'present')->count(),
            'absent' => AttendanceRecord::where('status', 'absent')->count(),
            'late' => AttendanceRecord::where('status', 'late')->count(),
            'today' => AttendanceRecord::whereDate('attendance_date', now()->toDateString())->count(),
        ];

        return view('attendance-records.index', compact('records', 'search', 'status', 'date', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::query()
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        return view('attendance-records.create', [
            'record' => new AttendanceRecord(),
            'employees' => $employees,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate($this->rules());

        $record = AttendanceRecord::create($data);

        return redirect()
            ->route('attendance-records.show', $record)
            ->with('status', 'Attendance record created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AttendanceRecord $attendance_record)
    {
        $attendance_record->load('employee');

        return view('attendance-records.show', [
            'record' => $attendance_record,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AttendanceRecord $attendance_record)
    {
        $employees = Employee::query()
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        return view('attendance-records.edit', [
            'record' => $attendance_record,
            'employees' => $employees,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AttendanceRecord $attendance_record)
    {
        $data = $request->validate($this->rules($attendance_record));

        $attendance_record->update($data);

        return redirect()
            ->route('attendance-records.show', $attendance_record)
            ->with('status', 'Attendance record updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AttendanceRecord $attendance_record)
    {
        $attendance_record->delete();

        return redirect()
            ->route('attendance-records.index')
            ->with('status', 'Attendance record deleted.');
    }

    private function rules(?AttendanceRecord $record = null): array
    {
        $statuses = ['present', 'absent', 'late'];

        return [
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
            'attendance_date' => [
                'required',
                'date',
                Rule::unique('attendance_records', 'attendance_date')
                    ->where(fn ($q) => $q->where('employee_id', request('employee_id')))
                    ->ignore($record?->id),
            ],
            'time_in' => ['nullable', 'date_format:H:i'],
            'time_out' => ['nullable', 'date_format:H:i'],
            'status' => ['required', Rule::in($statuses)],
            'hours_worked' => ['nullable', 'numeric', 'min:0', 'max:24'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
