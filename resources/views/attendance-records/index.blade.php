@php
    $title = 'Attendance';
    $headerTitle = 'Attendance';
@endphp

<x-manager-shell :title="$title" :header-title="$headerTitle">
    @if (session('status'))
        <div class="flash flash-success">{{ session('status') }}</div>
    @endif

    <div class="panel">
        <div class="panel-head">
            <div>
                <div class="panel-title">Attendance Dashboard</div>
                <div class="muted" style="font-size:12px; margin-top:2px;">Daily records per employee</div>
            </div>
            <a href="{{ route('attendance-records.create') }}" class="btn btn-primary">Add Attendance</a>
        </div>

        <div style="display:grid; grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 10px;">
            <div class="panel" style="padding:10px;"><div class="label">Total</div><div style="font-size:18px; font-weight:800; margin-top:4px;">{{ $stats['total'] }}</div></div>
            <div class="panel" style="padding:10px;"><div class="label">Today</div><div style="font-size:18px; font-weight:800; margin-top:4px;">{{ $stats['today'] }}</div></div>
            <div class="panel" style="padding:10px;"><div class="label">Present</div><div style="font-size:18px; font-weight:800; margin-top:4px;">{{ $stats['present'] }}</div></div>
            <div class="panel" style="padding:10px;"><div class="label">Late</div><div style="font-size:18px; font-weight:800; margin-top:4px;">{{ $stats['late'] }}</div></div>
            <div class="panel" style="padding:10px;"><div class="label">Absent</div><div style="font-size:18px; font-weight:800; margin-top:4px;">{{ $stats['absent'] }}</div></div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">Attendance List</div>
            <div class="chip">Search + filter</div>
        </div>

        <form method="GET" action="{{ route('attendance-records.index') }}" style="display:grid; grid-template-columns: 1fr 180px 220px auto; gap: 10px; align-items:end; margin-bottom: 10px;">
            <div class="field">
                <div class="label">Search</div>
                <input class="input" name="q" value="{{ $search }}" placeholder="Employee name/email" />
            </div>
            <div class="field">
                <div class="label">Date</div>
                <input class="input" name="date" type="date" value="{{ $date }}" />
            </div>
            <div class="field">
                <div class="label">Status</div>
                <select class="select" name="status">
                    <option value="all" @selected(($status ?? 'all') === 'all')>All</option>
                    <option value="present" @selected($status === 'present')>Present</option>
                    <option value="late" @selected($status === 'late')>Late</option>
                    <option value="absent" @selected($status === 'absent')>Absent</option>
                </select>
            </div>
            <div style="display:flex; gap: 8px; justify-content:flex-end;">
                <button class="btn btn-primary" type="submit">Filter</button>
                <a class="btn" href="{{ route('attendance-records.index') }}">Reset</a>
            </div>
        </form>

        <div style="overflow:auto; border-radius: 12px; border: 1px solid #e2e8f0; background: #fff;">
            <table>
                <thead>
                <tr>
                    <th style="min-width:140px;">Date</th>
                    <th style="min-width:240px;">Employee</th>
                    <th style="min-width:140px;">Status</th>
                    <th style="min-width:140px;">Time in</th>
                    <th style="min-width:140px;">Time out</th>
                    <th style="min-width:140px;">Hours</th>
                    <th style="min-width:220px; text-align:right;">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($records as $record)
                    @php
                        $name = $record->employee ? "{$record->employee->last_name}, {$record->employee->first_name}" : '—';
                        $badge = match($record->status) {
                            'present' => 'badge badge-green',
                            'late' => 'badge badge-yellow',
                            'absent' => 'badge badge-red',
                            default => 'badge badge-gray',
                        };
                    @endphp
                    <tr>
                        <td class="muted">{{ $record->attendance_date }}</td>
                        <td>
                            <a href="{{ route('attendance-records.show', $record) }}" style="font-weight:800; color:#0f172a; text-decoration:none;">
                                {{ $name }}
                            </a>
                        </td>
                        <td><span class="{{ $badge }}">{{ $record->status }}</span></td>
                        <td class="muted">{{ $record->time_in ?? '—' }}</td>
                        <td class="muted">{{ $record->time_out ?? '—' }}</td>
                        <td><strong>{{ $record->hours_worked !== null ? number_format((float) $record->hours_worked, 2) : '—' }}</strong></td>
                        <td style="text-align:right;">
                            <div style="display:inline-flex; gap: 8px; align-items:center;">
                                <a class="btn" href="{{ route('attendance-records.edit', $record) }}">Edit</a>
                                <form method="POST" action="{{ route('attendance-records.destroy', $record) }}" onsubmit="return confirm('Delete this attendance record?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" type="submit">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="muted" style="padding: 18px; text-align:center;">No attendance records found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 10px;">
            {{ $records->links() }}
        </div>
    </div>
</x-manager-shell>

