@include('payrolls.index_clean')
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Payroll</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<div style="padding:16px;">
    <form method="GET">
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
        <button type="submit">Apply</button>
    </form>
    <table>
        <thead>
            <tr><th>Employee</th><th>Position</th><th>Period</th><th>Gross</th><th>Deductions</th><th>Net</th><th>Status</th></tr>
        </thead>
        <tbody>
            @forelse($payrolls as $payroll)
                <tr>
                    <td>{{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}</td>
                    <td>{{ $payroll->employee->position }}</td>
                    <td>{{ $payroll->period_start_date }} - {{ $payroll->period_end_date }}</td>
                    <td>{{ $payroll->gross_amount }}</td>
                    <td>{{ $payroll->deductions }}</td>
                    <td>{{ $payroll->net_amount }}</td>
                    <td>{{ $payroll->status }}</td>
                </tr>
            @empty
                <tr><td colspan="7">No payroll records.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
</body>
</html>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="csrf-token" content="{{ csrf_token() }}"><title>Payroll</title>@vite(['resources/css/app.css', 'resources/js/app.js'])</head>
<body>
<style>:root{--gap:12px;--radius:16px;--sidebar:220px}*{box-sizing:border-box}body{margin:0;overflow:hidden;font-family:Figtree,Arial,sans-serif;background:linear-gradient(145deg,#f2f3ff 0%,#f9fbff 100%)}.shell{height:100vh;display:flex}.sidebar{width:var(--sidebar);flex:0 0 var(--sidebar);background:linear-gradient(165deg,#eef2ff 0%,#e6ecff 45%,#eaf5ff 100%);border-right:1px solid #e6ebf7;padding:10px;display:flex;flex-direction:column;gap:10px}.brand{border-radius:var(--radius);background:#ffffffd9;border:1px solid #e2e8f0;padding:10px;text-align:center}.brand img{height:48px;width:auto;border-radius:8px;margin-left:50px}.brand h1{margin:8px 0 0;font-size:14px;font-weight:700;color:#1e293b}.menu{display:flex;flex-direction:column;gap:6px}.menu a{padding:8px 10px;border-radius:10px;text-decoration:none;font-size:12px;font-weight:600;color:#475569;border:1px solid #e6eaf5;background:rgba(255,255,255,.7)}.menu a.active{background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff;border-color:transparent}.main{flex:1;display:flex;flex-direction:column;min-width:0;padding:10px;gap:10px;background:linear-gradient(165deg,#f7f9ff 0%,#f1f5ff 50%,#edf4ff 100%)}.header{height:56px;display:flex;align-items:center;justify-content:space-between;padding:0 14px;border:1px solid #dfe6f5;border-radius:14px;background:linear-gradient(135deg,rgba(255,255,255,.9),rgba(240,246,255,.86))}.h-title{font-size:18px;font-weight:700;color:#0f172a}.content{flex:1;min-height:0;overflow:auto;border-radius:var(--radius);border:1px solid #e2e8f0;background:linear-gradient(160deg,rgba(255,255,255,.94),rgba(244,249,255,.9));box-shadow:0 8px 18px rgba(15,23,42,.06);padding:14px}.filter-row{display:grid;grid-template-columns:140px 1fr 1fr auto;gap:8px;margin-bottom:12px}.filter-row input,.filter-row select{border:1px solid #cbd5e1;border-radius:10px;padding:8px}.btn{border:1px solid #c7d2fe;background:#fff;color:#3730a3;border-radius:10px;padding:8px 12px;text-decoration:none;font-size:12px;font-weight:600;cursor:pointer}.btn-primary{background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff;border-color:transparent}.stats{display:grid;grid-template-columns:repeat(5,minmax(0,1fr));gap:var(--gap);margin-bottom:12px}.card{border-radius:14px;padding:10px;color:#fff}.c1{background:linear-gradient(135deg,#73d9ef,#5aa8f2)}.c2{background:linear-gradient(135deg,#9cb5ff,#6d90f6)}.c3{background:linear-gradient(135deg,#f5add1,#ea7fbe)}.c4{background:linear-gradient(135deg,#d2bcff,#a88ef0)}.c5{background:linear-gradient(135deg,#818cf8,#6366f1)}table{width:100%;border-collapse:collapse;font-size:13px}th,td{padding:10px 8px;text-align:left;border-bottom:1px solid #e2e8f0;color:#334155}th{font-size:12px;text-transform:uppercase;color:#64748b}.pill{font-size:11px;padding:4px 8px;border-radius:999px;background:#eef2ff;color:#4338ca;border:1px solid #c7d2fe}.modal-backdrop{position:fixed;inset:0;background:rgba(15,23,42,.45);display:none;align-items:center;justify-content:center;z-index:50}.modal-backdrop.show{display:flex}.modal-card{width:min(760px,92vw);background:#fff;border:1px solid #dbe3f1;border-radius:16px;padding:14px;max-height:88vh;overflow:auto}.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px}.form-grid input,.form-grid select,.form-grid textarea{border:1px solid #cbd5e1;border-radius:10px;padding:8px}.span-2{grid-column:span 2}.form-actions{grid-column:span 2;display:flex;justify-content:flex-end;gap:8px}</style>
@php($sidebarModules=[['label'=>'Dashboard','route'=>'managerdashboard'],['label'=>'Customers','route'=>'customers.index'],['label'=>'Properties','route'=>'properties.index'],['label'=>'Payments','route'=>'payments.index'],['label'=>'Documents','route'=>'documents.index'],['label'=>'Title Transfers','route'=>'title-transfers.index'],['label'=>'Construction','route'=>'construction-projects.index'],['label'=>'Employees','route'=>'employees.index'],['label'=>'Attendance','route'=>'attendance-records.index'],['label'=>'Payroll','route'=>'payrolls.index'],['label'=>'Benefits','route'=>'benefits.index'],['label'=>'Reports','route'=>'reports.index']])
<div class="shell"><aside class="sidebar"><div class="brand"><img src="{{ asset('images/midland.jpg') }}" alt="Midland Valley Homes Logo"><h1>Midland Valley Homes</h1></div><nav class="menu">@foreach($sidebarModules as $item)<a href="{{ route($item['route']) }}" class="{{ request()->routeIs($item['route']) ? 'active' : '' }}">{{ $item['label'] }}</a>@endforeach</nav></aside>
<main class="main"><header class="header"><div class="h-title">Payroll Module</div><button type="button" class="btn btn-primary" id="openPayrollModal">+ Add Payroll</button></header>
<section class="content">
<form method="GET" class="filter-row"><select name="payroll_type"><option value="weekly" @selected($payrollType==='weekly')>weekly</option><option value="monthly" @selected($payrollType==='monthly')>monthly</option></select><input type="month" name="month" value="{{ $selectedMonth }}"><select name="position"><option value="">All Positions</option>@foreach($positions as $position)<option value="{{ $position }}" @selected($selectedPosition===$position)>{{ $position }}</option>@endforeach</select><button class="btn">Apply Filter</button></form>
<div class="stats"><div class="card c1"><strong>Total Gross</strong><div>PHP {{ number_format($summary['gross'],2) }}</div></div><div class="card c2"><strong>Total Deductions</strong><div>PHP {{ number_format($summary['deductions'],2) }}</div></div><div class="card c3"><strong>Total Net</strong><div>PHP {{ number_format($summary['net'],2) }}</div></div><div class="card c4"><strong>Pending</strong><div>{{ $summary['pending'] }}</div></div><div class="card c5"><strong>Paid</strong><div>{{ $summary['paid'] }}</div></div></div>
<table><thead><tr><th>Employee</th><th>Position</th><th>Period</th><th>Gross</th><th>Deductions</th><th>Net</th><th>Status</th></tr></thead><tbody>@forelse($payrolls as $payroll)<tr><td>{{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}</td><td>{{ $payroll->employee->position }}</td><td>{{ \Carbon\Carbon::parse($payroll->period_start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($payroll->period_end_date)->format('M d, Y') }}</td><td>PHP {{ number_format((float)$payroll->gross_amount,2) }}</td><td>PHP {{ number_format((float)$payroll->deductions,2) }}</td><td>PHP {{ number_format((float)$payroll->net_amount,2) }}</td><td><span class="pill">{{ $payroll->status }}</span></td></tr>@empty<tr><td colspan="7">No payroll records for selected filters.</td></tr>@endforelse</tbody></table>
<div style="margin-top:12px;">{{ $payrolls->links() }}</div></section></main></div>
<div id="payrollModal" class="modal-backdrop"><div class="modal-card"><div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;"><strong>Add Payroll</strong><button type="button" id="closePayrollModal" class="btn">Close</button></div><form method="POST" action="{{ route('payrolls.store') }}" class="form-grid">@csrf<select name="employee_id" required>@foreach($employees as $employee)<option value="{{ $employee->id }}">{{ $employee->first_name }} {{ $employee->last_name }}</option>@endforeach</select><select name="payroll_type"><option value="weekly">weekly</option><option value="monthly">monthly</option></select><input name="period_start_date" type="date" required><input name="period_end_date" type="date" required><input name="gross_amount" type="number" step="0.01" value="0"><input name="deductions" type="number" step="0.01" value="0"><input name="net_amount" type="number" step="0.01" value="0"><select name="status"><option value="pending">pending</option><option value="processed">processed</option><option value="paid">paid</option></select><input name="paid_at" type="date"><textarea name="notes" class="span-2" placeholder="Notes"></textarea><div class="form-actions"><button type="button" id="cancelPayrollModal" class="btn">Cancel</button><button type="submit" class="btn btn-primary">Save</button></div></form></div></div>
<script>const pModal=document.getElementById('payrollModal');document.getElementById('openPayrollModal').onclick=()=>pModal.classList.add('show');document.getElementById('closePayrollModal').onclick=()=>pModal.classList.remove('show');document.getElementById('cancelPayrollModal').onclick=()=>pModal.classList.remove('show');pModal.addEventListener('click',e=>{if(e.target===pModal)pModal.classList.remove('show');});</script>
</body></html>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Payroll</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
@php($sidebarModules=[['label'=>'Dashboard','route'=>'managerdashboard'],['label'=>'Customers','route'=>'customers.index'],['label'=>'Properties','route'=>'properties.index'],['label'=>'Payments','route'=>'payments.index'],['label'=>'Documents','route'=>'documents.index'],['label'=>'Title Transfers','route'=>'title-transfers.index'],['label'=>'Construction','route'=>'construction-projects.index'],['label'=>'Employees','route'=>'employees.index'],['label'=>'Attendance','route'=>'attendance-records.index'],['label'=>'Payroll','route'=>'payrolls.index'],['label'=>'Benefits','route'=>'benefits.index'],['label'=>'Reports','route'=>'reports.index']])
<style>
    :root{--gap:12px;--radius:16px;--sidebar:220px}*{box-sizing:border-box}body{margin:0;overflow:hidden;font-family:Figtree,Arial,sans-serif;background:linear-gradient(145deg,#f2f3ff 0%,#f9fbff 100%)}.shell{height:100vh;display:flex}.sidebar{width:var(--sidebar);flex:0 0 var(--sidebar);background:linear-gradient(165deg,#eef2ff 0%,#e6ecff 45%,#eaf5ff 100%);border-right:1px solid #e6ebf7;padding:10px;display:flex;flex-direction:column;gap:10px}.brand{border-radius:var(--radius);background:#ffffffd9;border:1px solid #e2e8f0;padding:10px;text-align:center}.brand img{height:48px;width:auto;border-radius:8px;margin-left:50px}.brand h1{margin:8px 0 0;font-size:14px;font-weight:700;color:#1e293b}.menu{display:flex;flex-direction:column;gap:6px}.menu a{padding:8px 10px;border-radius:10px;text-decoration:none;font-size:12px;font-weight:600;color:#475569;border:1px solid #e6eaf5;background:rgba(255,255,255,.7)}.menu a.active{background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff;border-color:transparent}.main{flex:1;display:flex;flex-direction:column;min-width:0;padding:10px;gap:10px;background:linear-gradient(165deg,#f7f9ff 0%,#f1f5ff 50%,#edf4ff 100%)}.header{height:56px;display:flex;align-items:center;justify-content:space-between;padding:0 14px;border:1px solid #dfe6f5;border-radius:14px;background:linear-gradient(135deg,rgba(255,255,255,.9),rgba(240,246,255,.86))}.h-title{font-size:18px;font-weight:700;color:#0f172a}.content{flex:1;min-height:0;overflow:auto;border-radius:var(--radius);border:1px solid #e2e8f0;background:linear-gradient(160deg,rgba(255,255,255,.94),rgba(244,249,255,.9));box-shadow:0 8px 18px rgba(15,23,42,.06);padding:14px}.filter-row{display:grid;grid-template-columns:140px 1fr 1fr auto;gap:8px;margin-bottom:12px}.filter-row input,.filter-row select{border:1px solid #cbd5e1;border-radius:10px;padding:8px}.btn{border:1px solid #c7d2fe;background:#fff;color:#3730a3;border-radius:10px;padding:8px 12px;text-decoration:none;font-size:12px;font-weight:600;cursor:pointer}.btn-primary{background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff;border-color:transparent}.stats{display:grid;grid-template-columns:repeat(5,minmax(0,1fr));gap:var(--gap);margin-bottom:12px}.card{border-radius:14px;padding:10px;color:#fff}.c1{background:linear-gradient(135deg,#73d9ef,#5aa8f2)}.c2{background:linear-gradient(135deg,#9cb5ff,#6d90f6)}.c3{background:linear-gradient(135deg,#f5add1,#ea7fbe)}.c4{background:linear-gradient(135deg,#d2bcff,#a88ef0)}.c5{background:linear-gradient(135deg,#818cf8,#6366f1)}table{width:100%;border-collapse:collapse;font-size:13px}th,td{padding:10px 8px;text-align:left;border-bottom:1px solid #e2e8f0;color:#334155}th{font-size:12px;text-transform:uppercase;color:#64748b}.pill{font-size:11px;padding:4px 8px;border-radius:999px;background:#eef2ff;color:#4338ca;border:1px solid #c7d2fe}.modal-backdrop{position:fixed;inset:0;background:rgba(15,23,42,.45);display:none;align-items:center;justify-content:center;z-index:50}.modal-backdrop.show{display:flex}.modal-card{width:min(760px,92vw);background:#fff;border:1px solid #dbe3f1;border-radius:16px;padding:14px;max-height:88vh;overflow:auto}.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px}.form-grid input,.form-grid select,.form-grid textarea{border:1px solid #cbd5e1;border-radius:10px;padding:8px}.span-2{grid-column:span 2}.form-actions{grid-column:span 2;display:flex;justify-content:flex-end;gap:8px}
</style>
<div class="shell">
    <aside class="sidebar"><div class="brand"><img src="{{ asset('images/midland.jpg') }}" alt="Midland Valley Homes Logo"><h1>Midland Valley Homes</h1></div><nav class="menu">@foreach($sidebarModules as $item)<a href="{{ route($item['route']) }}" class="{{ request()->routeIs($item['route']) ? 'active' : '' }}">{{ $item['label'] }}</a>@endforeach</nav></aside>
    <main class="main">
        <header class="header"><div class="h-title">Payroll Module</div><button type="button" class="btn btn-primary" id="openPayrollModal">+ Add Payroll</button></header>
        <section class="content">
            <form method="GET" class="filter-row">
                <select name="payroll_type"><option value="weekly" @selected($payrollType === 'weekly')>weekly</option><option value="monthly" @selected($payrollType === 'monthly')>monthly</option></select>
                <input type="month" name="month" value="{{ $selectedMonth }}">
                <select name="position">
                    <option value="">All Positions</option>
                    @foreach($positions as $position)
                        <option value="{{ $position }}" @selected($selectedPosition === $position)>{{ $position }}</option>
                    @endforeach
                </select>
                <button class="btn">Apply Filter</button>
            </form>
            <div class="stats"><div class="card c1"><strong>Total Gross</strong><div>PHP {{ number_format($summary['gross'], 2) }}</div></div><div class="card c2"><strong>Total Deductions</strong><div>PHP {{ number_format($summary['deductions'], 2) }}</div></div><div class="card c3"><strong>Total Net</strong><div>PHP {{ number_format($summary['net'], 2) }}</div></div><div class="card c4"><strong>Pending</strong><div>{{ $summary['pending'] }}</div></div><div class="card c5"><strong>Paid</strong><div>{{ $summary['paid'] }}</div></div></div>
            <table><thead><tr><th>Employee</th><th>Position</th><th>Period</th><th>Gross</th><th>Deductions</th><th>Net</th><th>Status</th></tr></thead><tbody>@forelse($payrolls as $payroll)<tr><td>{{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}</td><td>{{ $payroll->employee->position }}</td><td>{{ \Carbon\Carbon::parse($payroll->period_start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($payroll->period_end_date)->format('M d, Y') }}</td><td>PHP {{ number_format((float) $payroll->gross_amount, 2) }}</td><td>PHP {{ number_format((float) $payroll->deductions, 2) }}</td><td>PHP {{ number_format((float) $payroll->net_amount, 2) }}</td><td><span class="pill">{{ $payroll->status }}</span></td></tr>@empty<tr><td colspan="7">No payroll records for selected filters.</td></tr>@endforelse</tbody></table>
            <div style="margin-top:12px;">{{ $payrolls->links() }}</div>
        </section>
    </main>
</div>
<div id="payrollModal" class="modal-backdrop"><div class="modal-card"><div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;"><strong>Add Payroll</strong><button type="button" id="closePayrollModal" class="btn">Close</button></div><form method="POST" action="{{ route('payrolls.store') }}" class="form-grid">@csrf<select name="employee_id" required>@foreach($employees as $employee)<option value="{{ $employee->id }}">{{ $employee->first_name }} {{ $employee->last_name }}</option>@endforeach</select><select name="payroll_type"><option value="weekly">weekly</option><option value="monthly">monthly</option></select><input name="period_start_date" type="date" required><input name="period_end_date" type="date" required><input name="gross_amount" type="number" step="0.01" value="0"><input name="deductions" type="number" step="0.01" value="0"><input name="net_amount" type="number" step="0.01" value="0"><select name="status"><option value="pending">pending</option><option value="processed">processed</option><option value="paid">paid</option></select><input name="paid_at" type="date"><textarea name="notes" class="span-2" placeholder="Notes"></textarea><div class="form-actions"><button type="button" id="cancelPayrollModal" class="btn">Cancel</button><button type="submit" class="btn btn-primary">Save</button></div></form></div></div>
<script>const pModal=document.getElementById('payrollModal');document.getElementById('openPayrollModal').onclick=()=>pModal.classList.add('show');document.getElementById('closePayrollModal').onclick=()=>pModal.classList.remove('show');document.getElementById('cancelPayrollModal').onclick=()=>pModal.classList.remove('show');pModal.addEventListener('click',e=>{if(e.target===pModal)pModal.classList.remove('show');});</script>
</body>
</html>
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
        th,td{padding:10px 8px;text-align:left;border-bottom:1px solid #e2e8f0;color:#334155}
        th{font-size:12px;text-transform:uppercase;color:#64748b}
        .pill{font-size:11px;padding:4px 8px;border-radius:999px;background:#eef2ff;color:#4338ca;border:1px solid #c7d2fe}
        .modal-backdrop{position:fixed;inset:0;background:rgba(15,23,42,.45);display:none;align-items:center;justify-content:center;z-index:50}
        .modal-backdrop.show{display:flex}
        .modal-card{width:min(760px,92vw);background:#fff;border:1px solid #dbe3f1;border-radius:16px;padding:14px;max-height:88vh;overflow:auto}
        .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px}
        .form-grid input,.form-grid select,.form-grid textarea{border:1px solid #cbd5e1;border-radius:10px;padding:8px}
        .span-2{grid-column:span 2}
        .form-actions{grid-column:span 2;display:flex;justify-content:flex-end;gap:8px}
    </style>
</head>
<body>
@php($sidebarModules=[['label'=>'Dashboard','route'=>'managerdashboard'],['label'=>'Customers','route'=>'customers.index'],['label'=>'Properties','route'=>'properties.index'],['label'=>'Payments','route'=>'payments.index'],['label'=>'Documents','route'=>'documents.index'],['label'=>'Title Transfers','route'=>'title-transfers.index'],['label'=>'Construction','route'=>'construction-projects.index'],['label'=>'Employees','route'=>'employees.index'],['label'=>'Attendance','route'=>'attendance-records.index'],['label'=>'Payroll','route'=>'payrolls.index'],['label'=>'Benefits','route'=>'benefits.index'],['label'=>'Reports','route'=>'reports.index']])
<div class="shell">
    <aside class="sidebar">
        <div class="brand"><img src="{{ asset('images/midland.jpg') }}" alt="Midland Valley Homes Logo"><h1>Midland Valley Homes</h1></div>
        <nav class="menu">@foreach($sidebarModules as $item)<a href="{{ route($item['route']) }}" class="{{ request()->routeIs($item['route']) ? 'active' : '' }}">{{ $item['label'] }}</a>@endforeach</nav>
    </aside>
    <main class="main">
        <header class="header">
            <div class="h-title">Payroll Module</div>
            <button type="button" class="btn btn-primary" id="openPayrollModal">+ Add Payroll</button>
        </header>
        <section class="content">
            <form method="GET" class="filter-row">
                <select name="payroll_type">
                    <option value="weekly" @selected($payrollType === 'weekly')>weekly</option>
                    <option value="monthly" @selected($payrollType === 'monthly')>monthly</option>
                </select>
                <input type="month" name="month" value="{{ $selectedMonth }}">
                <select name="employee_id">
                    <option value="">All Employees</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" @selected((string) $employeeId === (string) $employee->id)>{{ $employee->first_name }} {{ $employee->last_name }}</option>
                    @endforeach
                </select>
                <button class="btn">Apply Filter</button>
            </form>

            <div class="stats">
                <div class="card c1"><strong>Total Gross</strong><div>PHP {{ number_format($summary['gross'], 2) }}</div></div>
                <div class="card c2"><strong>Total Deductions</strong><div>PHP {{ number_format($summary['deductions'], 2) }}</div></div>
                <div class="card c3"><strong>Total Net</strong><div>PHP {{ number_format($summary['net'], 2) }}</div></div>
                <div class="card c4"><strong>Pending</strong><div>{{ $summary['pending'] }}</div></div>
                <div class="card c5"><strong>Paid</strong><div>{{ $summary['paid'] }}</div></div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Period</th>
                        <th>Gross</th>
                        <th>Deductions</th>
                        <th>Net</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payrolls as $payroll)
                        <tr>
                            <td>{{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}</td>
                            <td>{{ \Carbon\Carbon::parse($payroll->period_start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($payroll->period_end_date)->format('M d, Y') }}</td>
                            <td>PHP {{ number_format((float) $payroll->gross_amount, 2) }}</td>
                            <td>PHP {{ number_format((float) $payroll->deductions, 2) }}</td>
                            <td>PHP {{ number_format((float) $payroll->net_amount, 2) }}</td>
                            <td><span class="pill">{{ $payroll->status }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="6">No payroll records for selected filters.</td></tr>
                    @endforelse
                </tbody>
            </table>

            <div style="margin-top:12px;">{{ $payrolls->links() }}</div>
        </section>
    </main>
</div>

<div id="payrollModal" class="modal-backdrop">
    <div class="modal-card">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;"><strong>Add Payroll</strong><button type="button" id="closePayrollModal" class="btn">Close</button></div>
        <form method="POST" action="{{ route('payrolls.store') }}" class="form-grid">
            @csrf
            <select name="employee_id" required>@foreach($employees as $employee)<option value="{{ $employee->id }}">{{ $employee->first_name }} {{ $employee->last_name }}</option>@endforeach</select>
            <select name="payroll_type"><option value="weekly">weekly</option><option value="monthly">monthly</option></select>
            <input name="period_start_date" type="date" required>
            <input name="period_end_date" type="date" required>
            <input name="gross_amount" type="number" step="0.01" value="0">
            <input name="deductions" type="number" step="0.01" value="0">
            <input name="net_amount" type="number" step="0.01" value="0">
            <select name="status"><option value="pending">pending</option><option value="processed">processed</option><option value="paid">paid</option></select>
            <input name="paid_at" type="date">
            <textarea name="notes" class="span-2" placeholder="Notes"></textarea>
            <div class="form-actions"><button type="button" id="cancelPayrollModal" class="btn">Cancel</button><button type="submit" class="btn btn-primary">Save</button></div>
        </form>
    </div>
</div>

<script>
const pModal=document.getElementById('payrollModal');
document.getElementById('openPayrollModal').onclick=()=>pModal.classList.add('show');
document.getElementById('closePayrollModal').onclick=()=>pModal.classList.remove('show');
document.getElementById('cancelPayrollModal').onclick=()=>pModal.classList.remove('show');
pModal.addEventListener('click',e=>{if(e.target===pModal)pModal.classList.remove('show');});
</script>
</body>
</html>
<!DOCTYPE html><html lang="{{ str_replace('_', '-', app()->getLocale()) }}"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="csrf-token" content="{{ csrf_token() }}"><title>Payroll</title>@vite(['resources/css/app.css', 'resources/js/app.js'])<style>:root{--gap:12px;--radius:16px;--sidebar:220px}*{box-sizing:border-box}body{margin:0;overflow:hidden;font-family:Figtree,Arial,sans-serif;background:linear-gradient(145deg,#f2f3ff 0%,#f9fbff 100%)}.shell{height:100vh;display:flex}.sidebar{width:var(--sidebar);flex:0 0 var(--sidebar);background:linear-gradient(165deg,#eef2ff 0%,#e6ecff 45%,#eaf5ff 100%);border-right:1px solid #e6ebf7;padding:10px;display:flex;flex-direction:column;gap:10px}.brand{border-radius:var(--radius);background:#ffffffd9;border:1px solid #e2e8f0;padding:10px;text-align:center}.brand img{height:48px;width:auto;border-radius:8px;margin-left:50px}.brand h1{margin:8px 0 0;font-size:14px;font-weight:700;color:#1e293b}.menu{display:flex;flex-direction:column;gap:6px}.menu a{padding:8px 10px;border-radius:10px;text-decoration:none;font-size:12px;font-weight:600;color:#475569;border:1px solid #e6eaf5;background:rgba(255,255,255,.7)}.menu a.active{background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff;border-color:transparent}.main{flex:1;display:flex;flex-direction:column;min-width:0;padding:10px;gap:10px;background:linear-gradient(165deg,#f7f9ff 0%,#f1f5ff 50%,#edf4ff 100%)}.header{height:56px;display:flex;align-items:center;justify-content:space-between;padding:0 14px;border:1px solid #dfe6f5;border-radius:14px;background:linear-gradient(135deg,rgba(255,255,255,.9),rgba(240,246,255,.86))}.h-title{font-size:18px;font-weight:700;color:#0f172a}.content{flex:1;min-height:0;overflow:auto;border-radius:var(--radius);border:1px solid #e2e8f0;background:linear-gradient(160deg,rgba(255,255,255,.94),rgba(244,249,255,.9));box-shadow:0 8px 18px rgba(15,23,42,.06);padding:14px}.stats{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:var(--gap);margin-bottom:12px}.card{border-radius:14px;padding:10px;color:#fff}.c1{background:linear-gradient(135deg,#73d9ef,#5aa8f2)}.c2{background:linear-gradient(135deg,#9cb5ff,#6d90f6)}.c3{background:linear-gradient(135deg,#f5add1,#ea7fbe)}.c4{background:linear-gradient(135deg,#d2bcff,#a88ef0)}table{width:100%;border-collapse:collapse;font-size:13px}th,td{padding:10px 8px;text-align:left;border-bottom:1px solid #e2e8f0;color:#334155}th{font-size:12px;text-transform:uppercase;color:#64748b}.pill{font-size:11px;padding:4px 8px;border-radius:999px;background:#eef2ff;color:#4338ca;border:1px solid #c7d2fe}.btn{border:1px solid #c7d2fe;background:#fff;color:#3730a3;border-radius:10px;padding:8px 12px;text-decoration:none;font-size:12px;font-weight:600}.btn-primary{background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff;border-color:transparent}.modal-backdrop{position:fixed;inset:0;background:rgba(15,23,42,.45);display:none;align-items:center;justify-content:center;z-index:50}.modal-backdrop.show{display:flex}.modal-card{width:min(760px,92vw);background:#fff;border:1px solid #dbe3f1;border-radius:16px;padding:14px;max-height:88vh;overflow:auto}.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px}.form-grid input,.form-grid select,.form-grid textarea{border:1px solid #cbd5e1;border-radius:10px;padding:8px}.span-2{grid-column:span 2}.form-actions{grid-column:span 2;display:flex;justify-content:flex-end;gap:8px}</style></head><body>
@php($sidebarModules=[['label'=>'Dashboard','route'=>'managerdashboard'],['label'=>'Customers','route'=>'customers.index'],['label'=>'Properties','route'=>'properties.index'],['label'=>'Payments','route'=>'payments.index'],['label'=>'Documents','route'=>'documents.index'],['label'=>'Title Transfers','route'=>'title-transfers.index'],['label'=>'Construction','route'=>'construction-projects.index'],['label'=>'Employees','route'=>'employees.index'],['label'=>'Attendance','route'=>'attendance-records.index'],['label'=>'Payroll','route'=>'payrolls.index'],['label'=>'Benefits','route'=>'benefits.index'],['label'=>'Reports','route'=>'reports.index']])
<div class="shell"><aside class="sidebar"><div class="brand"><img src="{{ asset('images/midland.jpg') }}" alt="Midland Valley Homes Logo"><h1>Midland Valley Homes</h1></div><nav class="menu">@foreach($sidebarModules as $item)<a href="{{ route($item['route']) }}" class="{{ request()->routeIs($item['route']) ? 'active' : '' }}">{{ $item['label'] }}</a>@endforeach</nav></aside><main class="main"><header class="header"><div class="h-title">Payroll Module</div><button type="button" class="btn btn-primary" id="openPayrollModal">+ Add Payroll</button></header><section class="content">
<div class="stats"><div class="card c1"><strong>Total Payrolls</strong><div>{{ $payrolls->total() }}</div></div><div class="card c2"><strong>Pending</strong><div>{{ \App\Models\Payroll::where('status','pending')->count() }}</div></div><div class="card c3"><strong>Processed</strong><div>{{ \App\Models\Payroll::where('status','processed')->count() }}</div></div><div class="card c4"><strong>Paid</strong><div>{{ \App\Models\Payroll::where('status','paid')->count() }}</div></div></div>
<table><thead><tr><th>Employee</th><th>Period</th><th>Net Amount</th><th>Type</th><th>Status</th></tr></thead><tbody>@forelse($payrolls as $payroll)<tr><td>{{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}</td><td>{{ $payroll->period_start_date }} to {{ $payroll->period_end_date }}</td><td>PHP {{ number_format((float) $payroll->net_amount, 2) }}</td><td>{{ $payroll->payroll_type }}</td><td><span class="pill">{{ $payroll->status }}</span></td></tr>@empty<tr><td colspan="5">No payroll records yet.</td></tr>@endforelse</tbody></table>
<div style="margin-top:12px;">{{ $payrolls->links() }}</div></section></main></div>
<div id="payrollModal" class="modal-backdrop"><div class="modal-card"><div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;"><strong>Add Payroll</strong><button type="button" id="closePayrollModal" class="btn">Close</button></div><form method="POST" action="{{ route('payrolls.store') }}" class="form-grid">@csrf<select name="employee_id" required>@foreach(\App\Models\Employee::orderBy('last_name')->get() as $employee)<option value="{{ $employee->id }}">{{ $employee->first_name }} {{ $employee->last_name }}</option>@endforeach</select><select name="payroll_type"><option value="weekly">weekly</option><option value="monthly">monthly</option></select><input name="period_start_date" type="date" required><input name="period_end_date" type="date" required><input name="gross_amount" type="number" step="0.01" value="0"><input name="deductions" type="number" step="0.01" value="0"><input name="net_amount" type="number" step="0.01" value="0"><select name="status"><option value="pending">pending</option><option value="processed">processed</option><option value="paid">paid</option></select><input name="paid_at" type="date"><textarea name="notes" class="span-2" placeholder="Notes"></textarea><div class="form-actions"><button type="button" id="cancelPayrollModal" class="btn">Cancel</button><button type="submit" class="btn btn-primary">Save</button></div></form></div></div>
<script>const pModal=document.getElementById('payrollModal');document.getElementById('openPayrollModal').onclick=()=>pModal.classList.add('show');document.getElementById('closePayrollModal').onclick=()=>pModal.classList.remove('show');document.getElementById('cancelPayrollModal').onclick=()=>pModal.classList.remove('show');pModal.addEventListener('click',e=>{if(e.target===pModal)pModal.classList.remove('show');});</script>
</body></html>
