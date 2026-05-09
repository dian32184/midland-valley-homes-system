<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\Employee;
use App\Models\Payroll;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PayrollController extends Controller
{
    private const TENURE_THRESHOLD_YEARS = 2;

    private const HOURS_PER_WORKDAY = 8;

    private const SSS_RATE = 0.05;

    private const PHILHEALTH_FIXED = 250.0;

    private const PAGIBIG_FIXED = 400.0;

    private const MONTHLY_TO_WEEKLY_DIVISOR = 4;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $selectedMonth = $request->query('month', now()->format('Y-m'));
        $payrollType = $request->query('payroll_type', 'weekly');
        $selectedPosition = $request->query('position');

        $query = Payroll::with('employee')
            ->where('payroll_type', $payrollType)
            ->whereRaw("DATE_FORMAT(period_end_date, '%Y-%m') = ?", [$selectedMonth]);

        if (! empty($selectedPosition)) {
            $query->whereHas('employee', fn ($employeeQuery) => $employeeQuery->where('position', $selectedPosition));
        }

        $summary = [
            'gross' => (float) (clone $query)->sum('gross_amount'),
            'deductions' => (float) (clone $query)->sum('deductions'),
            'net' => (float) (clone $query)->sum('net_amount'),
            'pending' => (clone $query)->where('status', 'pending')->count(),
            'approved' => (clone $query)->where('status', 'approved')->count(),
        ];

        $payrolls = $query
            ->orderByDesc('period_end_date')
            ->paginate(15)
            ->withQueryString();

        $employees = Employee::orderBy('last_name')->get();
        $positions = Employee::query()
            ->whereNotNull('position')
            ->distinct()
            ->orderBy('position')
            ->pluck('position');

        return view('payrolls.index_clean', compact(
            'payrolls',
            'employees',
            'positions',
            'selectedMonth',
            'payrollType',
            'selectedPosition',
            'summary'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'period_start_date' => ['required', 'date'],
            'period_end_date' => ['required', 'date', 'after_or_equal:period_start_date'],
            'payroll_type' => ['required', 'in:weekly,monthly'],
            'notes' => ['nullable', 'string'],
        ]);

        $validated['status'] = 'pending';
        $validated['paid_at'] = null;
        $validated['approved_at'] = null;

        $employee = Employee::findOrFail($validated['employee_id']);
        $periodStart = Carbon::parse($validated['period_start_date'])->startOfDay();
        $periodEnd = Carbon::parse($validated['period_end_date'])->endOfDay();

        [$grossAmount, $hoursWorked] = $this->computeGrossAmount(
            $employee,
            $validated['payroll_type'],
            $periodStart,
            $periodEnd
        );

        [$deductionAmount, $deductionBreakdown] = $this->computeDeductions($employee, $grossAmount);
        $netAmount = max(0, round($grossAmount - $deductionAmount, 2));

        $validated['gross_amount'] = $grossAmount;
        $validated['deductions'] = $deductionAmount;
        $validated['net_amount'] = $netAmount;

        $computationNotes = sprintf(
            'Auto-computed payroll | Tenure: %.2f years | Hours: %.2f | Gross: %.2f | Deductions: %s | Net: %.2f',
            $this->getEmployeeTenureYears($employee),
            $hoursWorked,
            $grossAmount,
            implode(', ', $deductionBreakdown),
            $netAmount
        );

        $validated['notes'] = trim(($validated['notes'] ?? '')."\n".$computationNotes);

        Payroll::create($validated);

        return redirect()
            ->route('payrolls.index')
            ->with('status', 'Payroll record added successfully.');
    }

    private function computeGrossAmount(Employee $employee, string $payrollType, Carbon $periodStart, Carbon $periodEnd): array
    {
        $attendanceQuery = AttendanceRecord::query()
            ->where('employee_id', $employee->id)
            ->whereBetween('attendance_date', [$periodStart->toDateString(), $periodEnd->toDateString()]);

        $hoursWorked = (float) (clone $attendanceQuery)->sum('hours_worked');
        $presentDays = (clone $attendanceQuery)->whereIn('status', ['present', 'late'])->count();

        $dailyRate = (float) ($employee->daily_rate ?? 0);
        $monthlySalary = (float) ($employee->monthly_salary ?? 0);
        $salaryType = $employee->salary_type ?? 'monthly';

        if ($salaryType === 'weekly' && $dailyRate > 0) {
            if ($hoursWorked > 0) {
                return [round(($dailyRate / self::HOURS_PER_WORKDAY) * $hoursWorked, 2), $hoursWorked];
            }

            return [round($dailyRate * $presentDays, 2), (float) ($presentDays * self::HOURS_PER_WORKDAY)];
        }

        if ($monthlySalary <= 0 && $dailyRate > 0) {
            if ($hoursWorked > 0) {
                return [round(($dailyRate / self::HOURS_PER_WORKDAY) * $hoursWorked, 2), $hoursWorked];
            }

            return [round($dailyRate * $presentDays, 2), (float) ($presentDays * self::HOURS_PER_WORKDAY)];
        }

        if ($payrollType === 'monthly') {
            return [round($monthlySalary, 2), $hoursWorked];
        }

        return [round($monthlySalary / self::MONTHLY_TO_WEEKLY_DIVISOR, 2), $hoursWorked];
    }

    private function computeDeductions(Employee $employee, float $grossAmount): array
    {
        if ($grossAmount <= 0) {
            return [0.0, ['none (gross is zero)']];
        }

        $tenureYears = $this->getEmployeeTenureYears($employee);

        if ($tenureYears >= self::TENURE_THRESHOLD_YEARS) {
            $sss = round($grossAmount * self::SSS_RATE, 2);
            $philhealth = self::PHILHEALTH_FIXED;
            $pagibig = self::PAGIBIG_FIXED;
            $total = round($sss + $philhealth + $pagibig, 2);

            return [$total, [
                "sss={$sss}",
                "philhealth={$philhealth}",
                "pagibig={$pagibig}",
            ]];
        }

        return [0.0, ['none (not yet 2 years tenure)']];
    }

    private function getEmployeeTenureYears(Employee $employee): float
    {
        if (empty($employee->hire_date)) {
            return 0.0;
        }

        return Carbon::parse($employee->hire_date)->floatDiffInYears(now());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payroll $payroll)
    {
        $request->validate([
            'status' => ['required', Rule::in(['approved'])],
        ]);

        if ($payroll->status !== 'pending') {
            return redirect()
                ->route('payrolls.index')
                ->withErrors(['status' => 'Only payroll entries awaiting manager approval can be approved.']);
        }

        $today = now()->toDateString();

        $payroll->update([
            'status' => 'approved',
            'approved_at' => $today,
            'paid_at' => $today,
        ]);

        return redirect()
            ->route('payrolls.index')
            ->with('status', 'Payroll approved and marked as released to the employee.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
