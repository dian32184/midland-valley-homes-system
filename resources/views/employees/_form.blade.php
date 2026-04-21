@php
    $employmentOptions = [
        'office' => 'Office',
        'onsite' => 'On-site',
    ];
    $salaryOptions = [
        'weekly' => 'Weekly',
        'monthly' => 'Monthly',
    ];
@endphp

<div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px;">
    <div class="field">
        <div class="label">First name *</div>
        <input class="input" name="first_name" value="{{ old('first_name', $employee->first_name) }}" />
        @error('first_name') <div class="error">{{ $message }}</div> @enderror
    </div>
    <div class="field">
        <div class="label">Last name *</div>
        <input class="input" name="last_name" value="{{ old('last_name', $employee->last_name) }}" />
        @error('last_name') <div class="error">{{ $message }}</div> @enderror
    </div>
    <div class="field">
        <div class="label">Middle name</div>
        <input class="input" name="middle_name" value="{{ old('middle_name', $employee->middle_name) }}" />
        @error('middle_name') <div class="error">{{ $message }}</div> @enderror
    </div>
    <div class="field">
        <div class="label">Email</div>
        <input class="input" name="email" value="{{ old('email', $employee->email) }}" />
        @error('email') <div class="error">{{ $message }}</div> @enderror
    </div>
    <div class="field">
        <div class="label">Phone</div>
        <input class="input" name="phone" value="{{ old('phone', $employee->phone) }}" />
        @error('phone') <div class="error">{{ $message }}</div> @enderror
    </div>
    <div class="field">
        <div class="label">Position</div>
        <input class="input" name="position" value="{{ old('position', $employee->position) }}" />
        @error('position') <div class="error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="panel" style="margin-top: 10px;">
    <div class="panel-head">
        <div class="panel-title">Employment</div>
        <div class="chip">Type + salary</div>
    </div>
    <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px;">
        <div class="field">
            <div class="label">Employment type *</div>
            <select class="select" name="employment_type">
                @foreach ($employmentOptions as $value => $label)
                    <option value="{{ $value }}" @selected(old('employment_type', $employee->employment_type ?? 'office') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('employment_type') <div class="error">{{ $message }}</div> @enderror
        </div>
        <div class="field">
            <div class="label">Salary type *</div>
            <select class="select" name="salary_type">
                @foreach ($salaryOptions as $value => $label)
                    <option value="{{ $value }}" @selected(old('salary_type', $employee->salary_type ?? 'monthly') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('salary_type') <div class="error">{{ $message }}</div> @enderror
        </div>
        <div class="field">
            <div class="label">Hire date</div>
            <input class="input" name="hire_date" type="date" value="{{ old('hire_date', optional($employee->hire_date)->format('Y-m-d') ?? $employee->hire_date) }}" />
            @error('hire_date') <div class="error">{{ $message }}</div> @enderror
        </div>
        <div class="field">
            <div class="label">Daily rate</div>
            <input class="input" name="daily_rate" type="number" step="0.01" min="0" value="{{ old('daily_rate', $employee->daily_rate) }}" />
            @error('daily_rate') <div class="error">{{ $message }}</div> @enderror
        </div>
        <div class="field">
            <div class="label">Monthly salary</div>
            <input class="input" name="monthly_salary" type="number" step="0.01" min="0" value="{{ old('monthly_salary', $employee->monthly_salary) }}" />
            @error('monthly_salary') <div class="error">{{ $message }}</div> @enderror
        </div>
        <div class="field">
            <div class="label">Active</div>
            <label style="display:flex; align-items:center; gap: 8px; margin-top: 6px;">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', (bool) ($employee->is_active ?? true))) />
                <span class="muted">Employee is active</span>
            </label>
            @error('is_active') <div class="error">{{ $message }}</div> @enderror
        </div>
        <div class="field" style="grid-column: 1 / -1;">
            <div class="label">Notes</div>
            <textarea class="textarea" name="notes" rows="3">{{ old('notes', $employee->notes) }}</textarea>
            @error('notes') <div class="error">{{ $message }}</div> @enderror
        </div>
    </div>
</div>

