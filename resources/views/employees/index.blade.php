@php
    $title = 'Employees';
    $headerTitle = 'Employees';
@endphp

<x-manager-shell :title="$title" :header-title="$headerTitle">
    @if (session('status'))
        <div class="flash flash-success">{{ session('status') }}</div>
    @endif

    <div class="panel">
        <div class="panel-head">
            <div>
                <div class="panel-title">Employees Dashboard</div>
                <div class="muted" style="font-size:12px; margin-top:2px;">Manage workforce profiles</div>
            </div>
            <a href="{{ route('employees.create') }}" class="btn btn-primary">Add Employee</a>
        </div>

        <div style="display:grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 10px;">
            <div class="panel" style="padding:10px;"><div class="label">Total</div><div style="font-size:18px; font-weight:800; margin-top:4px;">{{ $stats['total'] }}</div></div>
            <div class="panel" style="padding:10px;"><div class="label">Active</div><div style="font-size:18px; font-weight:800; margin-top:4px;">{{ $stats['active'] }}</div></div>
            <div class="panel" style="padding:10px;"><div class="label">Inactive</div><div style="font-size:18px; font-weight:800; margin-top:4px;">{{ $stats['inactive'] }}</div></div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">Employees List</div>
            <div class="chip">Search + filter</div>
        </div>

        <form method="GET" action="{{ route('employees.index') }}" style="display:grid; grid-template-columns: 1fr 220px auto; gap: 10px; align-items:end; margin-bottom: 10px;">
            <div class="field">
                <div class="label">Search</div>
                <input class="input" name="q" value="{{ $search }}" placeholder="Name, email, phone, position" />
            </div>
            <div class="field">
                <div class="label">Active</div>
                <select class="select" name="active">
                    <option value="" @selected(($active ?? '') === '')>All</option>
                    <option value="1" @selected($active === '1')>Active</option>
                    <option value="0" @selected($active === '0')>Inactive</option>
                </select>
            </div>
            <div style="display:flex; gap: 8px; justify-content:flex-end;">
                <button class="btn btn-primary" type="submit">Filter</button>
                <a class="btn" href="{{ route('employees.index') }}">Reset</a>
            </div>
        </form>

        <div style="overflow:auto; border-radius: 12px; border: 1px solid #e2e8f0; background: #fff;">
            <table>
                <thead>
                <tr>
                    <th style="min-width:240px;">Name</th>
                    <th style="min-width:220px;">Email</th>
                    <th style="min-width:160px;">Phone</th>
                    <th style="min-width:180px;">Position</th>
                    <th style="min-width:160px;">Employment</th>
                    <th style="min-width:120px;">Active</th>
                    <th style="min-width:220px; text-align:right;">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($employees as $employee)
                    @php
                        $name = "{$employee->last_name}, {$employee->first_name}" . ($employee->middle_name ? " {$employee->middle_name}" : '');
                        $badge = $employee->is_active ? 'badge badge-green' : 'badge badge-red';
                    @endphp
                    <tr>
                        <td>
                            <a href="{{ route('employees.show', $employee) }}" style="font-weight:800; color:#0f172a; text-decoration:none;">
                                {{ $name }}
                            </a>
                        </td>
                        <td class="muted">{{ $employee->email ?? '—' }}</td>
                        <td class="muted">{{ $employee->phone ?? '—' }}</td>
                        <td class="muted">{{ $employee->position ?? '—' }}</td>
                        <td class="muted">{{ $employee->employment_type }} / {{ $employee->salary_type }}</td>
                        <td><span class="{{ $badge }}">{{ $employee->is_active ? 'Yes' : 'No' }}</span></td>
                        <td style="text-align:right;">
                            <div style="display:inline-flex; gap: 8px; align-items:center;">
                                <a class="btn" href="{{ route('employees.edit', $employee) }}">Edit</a>
                                <form method="POST" action="{{ route('employees.destroy', $employee) }}" onsubmit="return confirm('Delete this employee?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" type="submit">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="muted" style="padding: 18px; text-align:center;">No employees found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 10px;">
            {{ $employees->links() }}
        </div>
    </div>
</x-manager-shell>

