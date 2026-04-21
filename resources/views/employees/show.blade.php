@php
    $title = 'Employee';
    $headerTitle = 'Employees';
    $headerSubtitle = "{$employee->last_name}, {$employee->first_name}";
    $badge = $employee->is_active ? 'badge badge-green' : 'badge badge-red';
@endphp

<x-manager-shell :title="$title" :header-title="$headerTitle" :header-subtitle="$headerSubtitle">
    @if (session('status'))
        <div class="flash flash-success">{{ session('status') }}</div>
    @endif

    <div class="panel">
        <div class="panel-head">
            <div style="display:flex; gap: 10px; align-items:center;">
                <div class="panel-title">Employee Profile</div>
                <span class="{{ $badge }}">{{ $employee->is_active ? 'Active' : 'Inactive' }}</span>
            </div>
            <div style="display:flex; gap: 8px;">
                <a class="btn btn-primary" href="{{ route('employees.edit', $employee) }}">Edit</a>
                <a class="btn" href="{{ route('employees.index') }}">Back</a>
            </div>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 10px;">
            <div class="panel" style="padding:10px;">
                <div class="label">Contact</div>
                <div style="display:grid; gap: 6px; margin-top: 8px;">
                    <div><span class="muted">Email:</span> <strong>{{ $employee->email ?? '—' }}</strong></div>
                    <div><span class="muted">Phone:</span> <strong>{{ $employee->phone ?? '—' }}</strong></div>
                    <div><span class="muted">Position:</span> <strong>{{ $employee->position ?? '—' }}</strong></div>
                </div>
            </div>
            <div class="panel" style="padding:10px;">
                <div class="label">Employment</div>
                <div style="display:grid; gap: 6px; margin-top: 8px;">
                    <div><span class="muted">Type:</span> <strong>{{ $employee->employment_type }}</strong></div>
                    <div><span class="muted">Salary type:</span> <strong>{{ $employee->salary_type }}</strong></div>
                    <div><span class="muted">Hire date:</span> <strong>{{ $employee->hire_date ?? '—' }}</strong></div>
                </div>
            </div>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 10px;">
            <div class="panel" style="padding:10px;">
                <div class="label">Daily rate</div>
                <div style="margin-top: 8px;"><strong>{{ $employee->daily_rate !== null ? number_format((float) $employee->daily_rate, 2) : '—' }}</strong></div>
            </div>
            <div class="panel" style="padding:10px;">
                <div class="label">Monthly salary</div>
                <div style="margin-top: 8px;"><strong>{{ $employee->monthly_salary !== null ? number_format((float) $employee->monthly_salary, 2) : '—' }}</strong></div>
            </div>
        </div>

        <div class="panel" style="margin-top: 10px; padding:10px;">
            <div class="label">Notes</div>
            <div style="margin-top: 8px; white-space: pre-wrap;">{{ $employee->notes ?? '—' }}</div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">Danger zone</div>
            <div class="chip">Delete employee</div>
        </div>
        <form method="POST" action="{{ route('employees.destroy', $employee) }}" onsubmit="return confirm('Delete this employee?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete employee</button>
        </form>
    </div>
</x-manager-shell>

