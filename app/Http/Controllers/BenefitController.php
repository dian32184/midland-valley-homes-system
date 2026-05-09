<?php

namespace App\Http\Controllers;

use App\Models\Benefit;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BenefitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $benefits = Benefit::with('employee')->orderByDesc('created_at')->paginate(12);

        return view('benefits.index', compact('benefits'));
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
            'benefit_type' => ['required', Rule::in(['sss', 'pagibig', 'philhealth', 'other'])],
            'membership_number' => ['nullable', 'string', 'max:255'],
            'start_date' => ['nullable', 'date'],
            'eligibility_date' => ['nullable', 'date'],
            'status' => ['required', Rule::in(['pending', 'active', 'inactive'])],
            'notes' => ['nullable', 'string'],
        ]);

        $employee = Employee::findOrFail($validated['employee_id']);
        if ($employee->hire_date) {
            $autoEligibilityDate = date('Y-m-d', strtotime($employee->hire_date.' +2 years'));
            $validated['eligibility_date'] = $validated['eligibility_date'] ?? $autoEligibilityDate;

            if ($validated['status'] === 'active' && $validated['eligibility_date'] > date('Y-m-d')) {
                return redirect()
                    ->route('benefits.index')
                    ->withErrors(['status' => 'Employee is not yet eligible for active benefits (minimum 2 years).'])
                    ->withInput();
            }
        }

        Benefit::create($validated);

        return redirect()
            ->route('benefits.index')
            ->with('status', 'Benefit record added successfully.');
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
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        if (strtolower((string) ($request->user()?->role ?? '')) !== 'admin') {
            return redirect()
                ->route('benefits.index')
                ->withErrors(['status' => 'Only admin can update benefit status.']);
        }

        $benefit = Benefit::with('employee')->findOrFail($id);
        $employee = $benefit->employee;

        if (
            $validated['status'] === 'active'
            && $employee
            && $employee->hire_date
            && now()->lt(Carbon::parse($employee->hire_date)->addYears(2))
        ) {
            return redirect()
                ->route('benefits.index')
                ->withErrors(['status' => 'Employee is not yet eligible for active benefits (minimum 2 years).']);
        }

        $benefit->update(['status' => $validated['status']]);

        return redirect()
            ->route('benefits.index')
            ->with('status', 'Benefit status updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
