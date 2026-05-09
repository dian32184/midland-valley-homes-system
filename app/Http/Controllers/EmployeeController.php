<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = Employee::orderByDesc('created_at')->paginate(12);

        return view('employees.index', compact('employees'));
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
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', 'unique:employees,email'],
            'phone' => ['nullable', 'string', 'max:50'],
            'position' => ['nullable', 'string', 'max:255'],
            'employment_type' => ['required', 'in:office,onsite'],
            'salary_type' => ['required', 'in:weekly,monthly'],
            'daily_rate' => ['nullable', 'numeric', 'min:0'],
            'monthly_salary' => ['nullable', 'numeric', 'min:0'],
            'hire_date' => ['nullable', 'date'],
            'is_active' => ['required', 'boolean'],
            'notes' => ['nullable', 'string'],
        ]);

        $data['daily_rate'] = $data['salary_type'] === 'weekly' ? ($data['daily_rate'] ?? null) : null;
        $data['monthly_salary'] = $data['salary_type'] === 'monthly' ? ($data['monthly_salary'] ?? null) : null;

        Employee::create($data);

        return redirect()
            ->route('employees.index')
            ->with('status', 'Employee added successfully.');
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
    public function update(Request $request, Employee $employee)
{
    if ($request->boolean('toggle_status')) {
        $employee->update(['is_active' => !$employee->is_active]);
        return back()->with('success', 'Employee status updated.');
    }


}
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
