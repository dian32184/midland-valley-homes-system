@php
    $title = 'Attendance';
    $headerTitle = 'Attendance';
    $headerSubtitle = "Record #{$record->id}";

    $name = $record->employee ? "{$record->employee->last_name}, {$record->employee->first_name}" : '—';
    $badge = match($record->status) {
        'present' => 'badge badge-green',
        'late' => 'badge badge-yellow',
        'absent' => 'badge badge-red',
        default => 'badge badge-gray',
    };
@endphp

<x-manager-shell :title="$title" :header-title="$headerTitle" :header-subtitle="$headerSubtitle">
    @if (session('status'))
        <div class="flash flash-success">{{ session('status') }}</div>
    @endif

    <div class="panel">
        <div class="panel-head">
            <div style="display:flex; gap: 10px; align-items:center;">
                <div class="panel-title">Attendance Details</div>
                <span class="{{ $badge }}">{{ $record->status }}</span>
            </div>
            <div style="display:flex; gap: 8px;">
                <a class="btn btn-primary" href="{{ route('attendance-records.edit', $record) }}">Edit</a>
                <a class="btn" href="{{ route('attendance-records.index') }}">Back</a>
            </div>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 10px;">
            <div class="panel" style="padding:10px;">
                <div class="label">Employee</div>
                <div style="margin-top: 8px;"><strong>{{ $name }}</strong></div>
                <div class="muted" style="margin-top: 4px;">{{ $record->employee?->email ?? '—' }}</div>
            </div>
            <div class="panel" style="padding:10px;">
                <div class="label">Date</div>
                <div style="margin-top: 8px;"><strong>{{ $record->attendance_date }}</strong></div>
            </div>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; margin-top: 10px;">
            <div class="panel" style="padding:10px;"><div class="label">Time in</div><div style="margin-top: 8px;"><strong>{{ $record->time_in ?? '—' }}</strong></div></div>
            <div class="panel" style="padding:10px;"><div class="label">Time out</div><div style="margin-top: 8px;"><strong>{{ $record->time_out ?? '—' }}</strong></div></div>
            <div class="panel" style="padding:10px;"><div class="label">Hours worked</div><div style="margin-top: 8px;"><strong>{{ $record->hours_worked !== null ? number_format((float) $record->hours_worked, 2) : '—' }}</strong></div></div>
        </div>

        <div class="panel" style="margin-top: 10px; padding:10px;">
            <div class="label">Notes</div>
            <div style="margin-top: 8px; white-space: pre-wrap;">{{ $record->notes ?? '—' }}</div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">Danger zone</div>
            <div class="chip">Delete record</div>
        </div>
        <form method="POST" action="{{ route('attendance-records.destroy', $record) }}" onsubmit="return confirm('Delete this attendance record?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete record</button>
        </form>
    </div>
</x-manager-shell>

