@php
    $statusOptions = [
        'present' => 'Present',
        'late' => 'Late',
        'absent' => 'Absent',
    ];
@endphp

<div style="display:grid; grid-template-columns: 1fr 1fr; gap: 10px;">
    <div class="field">
        <div class="label">Employee *</div>
        <select class="select" name="employee_id">
            <option value="">Select employee</option>
            @foreach ($employees as $employee)
                <option value="{{ $employee->id }}" @selected((int) old('employee_id', $record->employee_id) === (int) $employee->id)>
                    {{ $employee->last_name }}, {{ $employee->first_name }}{{ $employee->email ? " • {$employee->email}" : '' }}
                </option>
            @endforeach
        </select>
        @error('employee_id') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <div class="label">Attendance date *</div>
        <input class="input" name="attendance_date" type="date" value="{{ old('attendance_date', optional($record->attendance_date)->format('Y-m-d') ?? $record->attendance_date) }}" />
        @error('attendance_date') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <div class="label">Status *</div>
        <select class="select" name="status">
            @foreach ($statusOptions as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $record->status ?? 'present') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('status') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <div class="label">Hours worked</div>
        <input class="input" name="hours_worked" type="number" step="0.25" min="0" max="24" value="{{ old('hours_worked', $record->hours_worked) }}" />
        @error('hours_worked') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <div class="label">Time in</div>
        <input class="input" name="time_in" type="time" value="{{ old('time_in', $record->time_in) }}" />
        @error('time_in') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <div class="label">Time out</div>
        <input class="input" name="time_out" type="time" value="{{ old('time_out', $record->time_out) }}" />
        @error('time_out') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field" style="grid-column: 1 / -1;">
        <div class="label">Notes</div>
        <textarea class="textarea" name="notes" rows="3">{{ old('notes', $record->notes) }}</textarea>
        @error('notes') <div class="error">{{ $message }}</div> @enderror
    </div>
</div>

