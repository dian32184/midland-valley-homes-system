<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Payroll</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root{--gap:12px;--radius:16px;--sidebar:220px}
        *{box-sizing:border-box}
        body{margin:0;overflow:hidden;font-family:Figtree,Arial,sans-serif;background:linear-gradient(145deg,#f2f3ff 0%,#f9fbff 100%)}
        .shell{height:100vh;display:flex}
        .sidebar{width:var(--sidebar);flex:0 0 var(--sidebar);background:linear-gradient(165deg,#eef2ff 0%,#e6ecff 45%,#eaf5ff 100%);border-right:1px solid #e6ebf7;padding:10px;display:flex;flex-direction:column;gap:10px}
        .brand{border-radius:var(--radius);background:#ffffffd9;border:1px solid #e2e8f0;padding:10px;text-align:center}
        .brand img{height:48px;width:auto;border-radius:8px;margin-left:50px}
        .brand h1{margin:8px 0 0;font-size:14px;font-weight:700;color:#1e293b}
        .menu{display:flex;flex-direction:column;gap:6px}
        .menu a{padding:8px 10px;border-radius:10px;text-decoration:none;font-size:12px;font-weight:600;color:#475569;border:1px solid #e6eaf5;background:rgba(255,255,255,.7)}
        .menu a.active{background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff;border-color:transparent}
        .main{flex:1;display:flex;flex-direction:column;min-width:0;padding:10px;gap:10px;background:linear-gradient(165deg,#f7f9ff 0%,#f1f5ff 50%,#edf4ff 100%)}
        .header{height:56px;display:flex;align-items:center;justify-content:space-between;padding:0 14px;border:1px solid #dfe6f5;border-radius:14px;background:linear-gradient(135deg,rgba(255,255,255,.9),rgba(240,246,255,.86))}
        .h-title{font-size:18px;font-weight:700;color:#0f172a}
        .content{flex:1;min-height:0;overflow:auto;border-radius:var(--radius);border:1px solid #e2e8f0;background:linear-gradient(160deg,rgba(255,255,255,.94),rgba(244,249,255,.9));box-shadow:0 8px 18px rgba(15,23,42,.06);padding:14px}
        .filter-row{display:grid;grid-template-columns:140px 1fr 1fr auto;gap:8px;margin-bottom:12px}
        .filter-row input,.filter-row select{border:1px solid #cbd5e1;border-radius:10px;padding:8px}
        .btn{border:1px solid #c7d2fe;background:#fff;color:#3730a3;border-radius:10px;padding:8px 12px;text-decoration:none;font-size:12px;font-weight:600;cursor:pointer}
        .btn-primary{background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff;border-color:transparent}
        .stats{display:grid;grid-template-columns:repeat(5,minmax(0,1fr));gap:var(--gap);margin-bottom:12px}
        .card{border-radius:14px;padding:10px;color:#fff}
        .c1{background:linear-gradient(135deg,#73d9ef,#5aa8f2)}
        .c2{background:linear-gradient(135deg,#9cb5ff,#6d90f6)}
        .c3{background:linear-gradient(135deg,#f5add1,#ea7fbe)}
        .c4{background:linear-gradient(135deg,#d2bcff,#a88ef0)}
        .c5{background:linear-gradient(135deg,#818cf8,#6366f1)}
        table{width:100%;border-collapse:collapse;font-size:13px}
        th,td{padding:10px 8px;text-align:left;border-bottom:1px solid #e2e8f0;color:#334155;vertical-align:top}
        th{font-size:12px;text-transform:uppercase;color:#64748b}
        .pill{font-size:11px;padding:4px 8px;border-radius:999px;background:#eef2ff;color:#4338ca;border:1px solid #c7d2fe}
        .muted{color:#64748b}
        .modal-backdrop{position:fixed;inset:0;background:rgba(15,23,42,.45);display:none;align-items:center;justify-content:center;z-index:50}
        .modal-backdrop.show{display:flex}
        .modal-card{width:min(760px,92vw);background:#fff;border:1px solid #dbe3f1;border-radius:16px;padding:14px;max-height:88vh;overflow:auto}
        .flash{margin-bottom:12px;padding:10px 12px;border-radius:10px;font-size:13px;background:#ecfdf5;border:1px solid #a7f3d0;color:#065f46}
        .error-box{margin-bottom:12px;padding:10px 12px;border-radius:10px;font-size:13px;background:#fef2f2;border:1px solid #fecaca;color:#991b1b}
        .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px}
        .form-grid input,.form-grid select,.form-grid textarea{border:1px solid #cbd5e1;border-radius:10px;padding:8px}
        .span-2{grid-column:span 2}
        .form-actions{grid-column:span 2;display:flex;justify-content:flex-end;gap:8px}
        .auto-note{grid-column:span 2;border:1px dashed #c7d2fe;background:#eef2ff;border-radius:10px;padding:10px;font-size:12px;color:#3730a3}

        @media (max-width: 980px){
            :root{--sidebar:0px}
            body{overflow:auto}
            .shell{height:auto;min-height:100vh}
            .sidebar{display:none}
            .stats{grid-template-columns:repeat(2,minmax(0,1fr))}
        }
        @media (max-width: 640px){
            .filter-row{grid-template-columns:1fr;gap:10px}
            .form-grid{grid-template-columns:1fr}
            .span-2{grid-column:auto}
        }
    </style>
</head>
<body>
@php
    $userRole = strtolower((string) (auth()->user()->role ?? ''));
    $sidebarModules = $userRole === 'admin'
        ? [
            ['label' => 'Dashboard', 'route' => 'admindashboard'],
            ['label' => 'Customers', 'route' => 'customers.index'],
            ['label' => 'Properties', 'route' => 'properties.index'],
            ['label' => 'Payments', 'route' => 'payments.index'],
            ['label' => 'Employees', 'route' => 'employees.index'],
            ['label' => 'Payroll', 'route' => 'payrolls.index'],
            ['label' => 'Benefits', 'route' => 'benefits.index'],
            ['label' => 'User Roles', 'route' => 'user-roles.index'],
            ['label' => 'Audit Logs', 'route' => 'audit-logs.index'],
            ['label' => 'Reports', 'route' => 'reports.index'],
        ]
        : [
            ['label' => 'Dashboard', 'route' => 'managerdashboard'],
            ['label' => 'Customers', 'route' => 'customers.index'],
            ['label' => 'Properties', 'route' => 'properties.index'],
            ['label' => 'Payments', 'route' => 'payments.index'],
            ['label' => 'Documents', 'route' => 'documents.index'],
            ['label' => 'Title Transfers', 'route' => 'title-transfers.index'],
            ['label' => 'Construction', 'route' => 'construction-projects.index'],
            ['label' => 'Employees', 'route' => 'employees.index'],
            ['label' => 'Attendance', 'route' => 'attendance-records.index'],
            ['label' => 'Payroll', 'route' => 'payrolls.index'],
            ['label' => 'Benefits', 'route' => 'benefits.index'],
            ['label' => 'Reports', 'route' => 'reports.index'],
        ];
@endphp

<div class="shell">
    <aside class="sidebar">
        <div class="brand">
            <img src="{{ asset('images/midland.jpg') }}" alt="Midland Valley Homes Logo">
            <h1>Midland Valley Homes</h1>
        </div>
        <nav class="menu">
            @foreach($sidebarModules as $item)
                <a href="{{ route($item['route']) }}" class="{{ request()->routeIs($item['route']) ? 'active' : '' }}">
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>
    </aside>

    <main class="main">
        <header class="header">
            <div class="h-title">Payroll Module</div>
            @if(auth()->user()->role === 'admin')
                <button type="button" class="btn btn-primary" id="openPayrollModal">+ Add payroll</button>
            @endif
        </header>

        <section class="content">
            @if(session('status'))
                <div class="flash">{{ session('status') }}</div>
            @endif
            @if($errors->any())
                <div class="error-box">{{ $errors->first() }}</div>
            @endif

            <form method="GET" class="filter-row">
                <select name="payroll_type">
                    <option value="weekly" @selected($payrollType === 'weekly')>weekly</option>
                    <option value="monthly" @selected($payrollType === 'monthly')>monthly</option>
                </select>
                <input type="month" name="month" value="{{ $selectedMonth }}">
                <select name="position">
                    <option value="">All Positions</option>
                    @foreach($positions as $position)
                        <option value="{{ $position }}" @selected($selectedPosition === $position)>{{ $position }}</option>
                    @endforeach
                </select>
                <button class="btn">Apply Filter</button>
            </form>

            <div class="stats">
                <div class="card c1"><strong>Total Gross</strong><div>PHP {{ number_format($summary['gross'], 2) }}</div></div>
                <div class="card c2"><strong>Total Deductions</strong><div>PHP {{ number_format($summary['deductions'], 2) }}</div></div>
                <div class="card c3"><strong>Total Net</strong><div>PHP {{ number_format($summary['net'], 2) }}</div></div>
                <div class="card c4"><strong>Pending</strong><div>{{ $summary['pending'] }}</div></div>
                <div class="card c5"><strong>Approved</strong><div>{{ $summary['approved'] }}</div></div>
            </div>

            <table>
                <thead>
                <tr>
                    <th>Employee</th>
                    <th>Position</th>
                    <th>Period</th>
                    <th>Gross</th>
                    <th>Deductions</th>
                    <th>Net</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($payrolls as $payroll)
                    <tr>
                        <td>{{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}</td>
                        <td>{{ $payroll->employee->position ?? '-' }}</td>
                        <td class="muted">
                            {{ \Carbon\Carbon::parse($payroll->period_start_date)->format('M d') }}
                            -
                            {{ \Carbon\Carbon::parse($payroll->period_end_date)->format('M d, Y') }}
                        </td>
                        <td>PHP {{ number_format((float) $payroll->gross_amount, 2) }}</td>
                        <td>PHP {{ number_format((float) $payroll->deductions, 2) }}</td>
                        <td>PHP {{ number_format((float) $payroll->net_amount, 2) }}</td>
                        <td>
                            <span class="pill">
                                @if($payroll->status === 'pending')
                                    pending (awaiting manager)
                                @elseif($payroll->status === 'approved')
                                    approved / released
                                @else
                                    {{ $payroll->status }}
                                @endif
                            </span>
                        </td>
                        <td>
                            @if(auth()->user()->role === 'manager' && $payroll->status === 'pending')
                                <form method="POST" action="{{ route('payrolls.update', $payroll) }}" style="margin:0" onsubmit="return confirm('Approve this payroll and mark it as released to the employee?');">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" class="btn btn-primary">Approve &amp; release</button>
                                </form>
                            @else
                                <span class="muted">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="muted">No payroll records for selected filters.</td></tr>
                @endforelse
                </tbody>
            </table>

            <div style="margin-top:12px;">
                {{ $payrolls->links() }}
            </div>
        </section>
    </main>
</div>

<div id="payrollModal" class="modal-backdrop">
    <div class="modal-card">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
            <strong>Add payroll (admin)</strong>
            <button type="button" id="closePayrollModal" class="btn">Close</button>
        </div>
        <p class="muted" style="margin:0 0 10px;font-size:12px;">New entries are saved as <strong>pending</strong> until a manager approves them.</p>

        <form method="POST" action="{{ route('payrolls.store') }}" class="form-grid">
            @csrf

            <select name="employee_id" id="employeeIdField" required>
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}" data-position="{{ $employee->position ?? 'N/A' }}">
                        {{ $employee->first_name }} {{ $employee->last_name }}
                    </option>
                @endforeach
            </select>
            <input id="employeePositionField" type="text" value="" placeholder="Employee position" readonly>

            <select name="payroll_type" id="payrollTypeField">
                <option value="weekly">weekly</option>
                <option value="monthly">monthly</option>
            </select>
            <input id="payrollMonthField" type="text" placeholder="Payroll month" readonly>

            <input name="period_start_date" id="periodStartField" type="date" required>
            <input name="period_end_date" id="periodEndField" type="date" required>

            <div class="auto-note span-2">
                Gross, deductions, and net pay are automatically computed based on employee salary/attendance and tenure.
                Employees with 2+ years tenure use SSS + PhilHealth + Pag-IBIG deductions; below 2 years uses standard-minus.
            </div>

            <textarea name="notes" class="span-2" placeholder="Notes"></textarea>

            <div class="form-actions">
                <button type="button" id="cancelPayrollModal" class="btn">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
const pModal = document.getElementById('payrollModal');
const payrollTypeField = document.getElementById('payrollTypeField');
const periodStartField = document.getElementById('periodStartField');
const periodEndField = document.getElementById('periodEndField');
const payrollMonthField = document.getElementById('payrollMonthField');
const employeeIdField = document.getElementById('employeeIdField');
const employeePositionField = document.getElementById('employeePositionField');
function toInputDate(dateObj) {
    const year = dateObj.getFullYear();
    const month = String(dateObj.getMonth() + 1).padStart(2, '0');
    const day = String(dateObj.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

function setPayrollMonthFromDate(dateObj) {
    payrollMonthField.value = dateObj.toLocaleString('en-US', { month: 'long', year: 'numeric' });
}

function applyAutoPeriod(force = false) {
    if (!force && periodStartField.value && periodEndField.value) {
        return;
    }

    const now = new Date();

    if (payrollTypeField.value === 'monthly') {
        const start = new Date(now.getFullYear(), now.getMonth(), 1);
        const end = new Date(now.getFullYear(), now.getMonth() + 1, 0);
        periodStartField.value = toInputDate(start);
        periodEndField.value = toInputDate(end);
        setPayrollMonthFromDate(start);
        return;
    }

    const weekday = now.getDay();
    const diffToMonday = weekday === 0 ? -6 : 1 - weekday;
    const monday = new Date(now);
    monday.setDate(now.getDate() + diffToMonday);
    const sunday = new Date(monday);
    sunday.setDate(monday.getDate() + 6);

    periodStartField.value = toInputDate(monday);
    periodEndField.value = toInputDate(sunday);
    setPayrollMonthFromDate(monday);
}

function syncEmployeePosition() {
    const selected = employeeIdField.options[employeeIdField.selectedIndex];
    employeePositionField.value = selected?.dataset.position || 'N/A';
}

function openPayrollModal() {
    pModal.classList.add('show');
    syncEmployeePosition();
    applyAutoPeriod(true);
}

document.getElementById('closePayrollModal').onclick = () => pModal.classList.remove('show');
document.getElementById('cancelPayrollModal').onclick = () => pModal.classList.remove('show');
pModal.addEventListener('click', e => {
    if (e.target === pModal) pModal.classList.remove('show');
});
const openBtn = document.getElementById('openPayrollModal');
if (openBtn) {
    openBtn.onclick = openPayrollModal;
}
employeeIdField.addEventListener('change', syncEmployeePosition);
payrollTypeField.addEventListener('change', () => applyAutoPeriod(true));

periodStartField.addEventListener('change', () => {
    if (!periodStartField.value) return;
    const selectedDate = new Date(periodStartField.value + 'T00:00:00');
    setPayrollMonthFromDate(selectedDate);
});

syncEmployeePosition();
applyAutoPeriod(false);
</script>
</body>
</html>
