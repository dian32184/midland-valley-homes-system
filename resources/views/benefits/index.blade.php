<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Benefits</title>
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
        .stats{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:var(--gap);margin-bottom:12px}
        .card{border-radius:14px;padding:10px;color:#fff}
        .c1{background:linear-gradient(135deg,#73d9ef,#5aa8f2)}
        .c2{background:linear-gradient(135deg,#9cb5ff,#6d90f6)}
        .c3{background:linear-gradient(135deg,#f5add1,#ea7fbe)}
        .c4{background:linear-gradient(135deg,#d2bcff,#a88ef0)}
        table{width:100%;border-collapse:collapse;font-size:13px}
        th,td{padding:10px 8px;text-align:left;border-bottom:1px solid #e2e8f0;color:#334155;vertical-align:middle}
        th{font-size:12px;text-transform:uppercase;color:#64748b}
        .pill{font-size:11px;padding:4px 8px;border-radius:999px;background:#eef2ff;color:#4338ca;border:1px solid #c7d2fe}
        .btn{border:1px solid #c7d2fe;background:#fff;color:#3730a3;border-radius:10px;padding:8px 12px;text-decoration:none;font-size:12px;font-weight:600;cursor:pointer}
        .btn-primary{background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff;border-color:transparent}
        .flash{margin-bottom:12px;padding:10px 12px;border-radius:10px;font-size:13px;background:#ecfdf5;border:1px solid #a7f3d0;color:#065f46}
        .error-box{margin-bottom:12px;padding:10px 12px;border-radius:10px;font-size:13px;background:#fef2f2;border:1px solid #fecaca;color:#991b1b}
        .status-form{display:flex;align-items:center;gap:8px}
        .status-form select{border:1px solid #cbd5e1;border-radius:10px;padding:6px 8px;font-size:12px}
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
@php
    $userRole = strtolower((string) (auth()->user()->role ?? ''));
    $isAdmin = $userRole === 'admin';
    $sidebarModules = $isAdmin
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
                <a href="{{ route($item['route']) }}" class="{{ request()->routeIs($item['route']) ? 'active' : '' }}">{{ $item['label'] }}</a>
            @endforeach
        </nav>
    </aside>
    <main class="main">
        <header class="header">
            <div class="h-title">Benefits Module</div>
            <button type="button" class="btn btn-primary" id="openBenefitModal">+ Add Benefit</button>
        </header>
        <section class="content">
            @if(session('status'))
                <div class="flash">{{ session('status') }}</div>
            @endif
            @if($errors->any())
                <div class="error-box">{{ $errors->first() }}</div>
            @endif

            <div class="stats">
                <div class="card c1"><strong>Total Benefits</strong><div>{{ $benefits->total() }}</div></div>
                <div class="card c2"><strong>Pending</strong><div>{{ \App\Models\Benefit::where('status', 'pending')->count() }}</div></div>
                <div class="card c3"><strong>Active</strong><div>{{ \App\Models\Benefit::where('status', 'active')->count() }}</div></div>
                <div class="card c4"><strong>Inactive</strong><div>{{ \App\Models\Benefit::where('status', 'inactive')->count() }}</div></div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Benefit Type</th>
                        <th>Membership #</th>
                        <th>Start Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($benefits as $benefit)
                        <tr>
                            <td>{{ $benefit->employee->first_name }} {{ $benefit->employee->last_name }}</td>
                            <td>{{ $benefit->benefit_type }}</td>
                            <td>{{ $benefit->membership_number ?: '-' }}</td>
                            <td>{{ $benefit->start_date ?: '-' }}</td>
                            <td><span class="pill">{{ $benefit->status }}</span></td>
                            <td>
                                @if($isAdmin)
                                    <form method="POST" action="{{ route('benefits.update', $benefit) }}" class="status-form">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" aria-label="Benefit status">
                                            <option value="active" @selected($benefit->status === 'active')>Active</option>
                                            <option value="inactive" @selected($benefit->status === 'inactive')>Inactive</option>
                                        </select>
                                        <button type="submit" class="btn">Save</button>
                                    </form>
                                @else
                                    <span style="color:#64748b;font-size:12px;">Admin only</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6">No benefit records yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div style="margin-top:12px;">{{ $benefits->links() }}</div>
        </section>
    </main>
</div>

<div id="benefitModal" class="modal-backdrop">
    <div class="modal-card">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
            <strong>Add Benefit</strong>
            <button type="button" id="closeBenefitModal" class="btn">Close</button>
        </div>
        <form method="POST" action="{{ route('benefits.store') }}" class="form-grid">
            @csrf
            <select name="employee_id" required>
                @foreach(\App\Models\Employee::orderBy('last_name')->get() as $employee)
                    <option value="{{ $employee->id }}">{{ $employee->first_name }} {{ $employee->last_name }}</option>
                @endforeach
            </select>
            <select name="benefit_type">
                <option value="sss">sss</option>
                <option value="pagibig">pagibig</option>
                <option value="philhealth">philhealth</option>
                <option value="other">other</option>
            </select>
            <input name="membership_number" placeholder="Membership Number">
            <input name="start_date" type="date">
            <input name="eligibility_date" type="date">
            <select name="status">
                <option value="pending">pending</option>
                <option value="active">active</option>
                <option value="inactive">inactive</option>
            </select>
            <textarea name="notes" class="span-2" placeholder="Notes"></textarea>
            <div class="form-actions">
                <button type="button" id="cancelBenefitModal" class="btn">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>
<script>
const bModal = document.getElementById('benefitModal');
document.getElementById('openBenefitModal').onclick = () => bModal.classList.add('show');
document.getElementById('closeBenefitModal').onclick = () => bModal.classList.remove('show');
document.getElementById('cancelBenefitModal').onclick = () => bModal.classList.remove('show');
bModal.addEventListener('click', (e) => { if (e.target === bModal) bModal.classList.remove('show'); });
</script>
</body>
</html>
<!DOCTYPE html><html lang="{{ str_replace('_', '-', app()->getLocale()) }}"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="csrf-token" content="{{ csrf_token() }}"><title>Benefits</title>@vite(['resources/css/app.css', 'resources/js/app.js'])<style>:root{--gap:12px;--radius:16px;--sidebar:220px}*{box-sizing:border-box}body{margin:0;overflow:hidden;font-family:Figtree,Arial,sans-serif;background:linear-gradient(145deg,#f2f3ff 0%,#f9fbff 100%)}.shell{height:100vh;display:flex}.sidebar{width:var(--sidebar);flex:0 0 var(--sidebar);background:linear-gradient(165deg,#eef2ff 0%,#e6ecff 45%,#eaf5ff 100%);border-right:1px solid #e6ebf7;padding:10px;display:flex;flex-direction:column;gap:10px}.brand{border-radius:var(--radius);background:#ffffffd9;border:1px solid #e2e8f0;padding:10px;text-align:center}.brand img{height:48px;width:auto;border-radius:8px;margin-left:50px}.brand h1{margin:8px 0 0;font-size:14px;font-weight:700;color:#1e293b}.menu{display:flex;flex-direction:column;gap:6px}.menu a{padding:8px 10px;border-radius:10px;text-decoration:none;font-size:12px;font-weight:600;color:#475569;border:1px solid #e6eaf5;background:rgba(255,255,255,.7)}.menu a.active{background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff;border-color:transparent}.main{flex:1;display:flex;flex-direction:column;min-width:0;padding:10px;gap:10px;background:linear-gradient(165deg,#f7f9ff 0%,#f1f5ff 50%,#edf4ff 100%)}.header{height:56px;display:flex;align-items:center;justify-content:space-between;padding:0 14px;border:1px solid #dfe6f5;border-radius:14px;background:linear-gradient(135deg,rgba(255,255,255,.9),rgba(240,246,255,.86))}.h-title{font-size:18px;font-weight:700;color:#0f172a}.content{flex:1;min-height:0;overflow:auto;border-radius:var(--radius);border:1px solid #e2e8f0;background:linear-gradient(160deg,rgba(255,255,255,.94),rgba(244,249,255,.9));box-shadow:0 8px 18px rgba(15,23,42,.06);padding:14px}.stats{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:var(--gap);margin-bottom:12px}.card{border-radius:14px;padding:10px;color:#fff}.c1{background:linear-gradient(135deg,#73d9ef,#5aa8f2)}.c2{background:linear-gradient(135deg,#9cb5ff,#6d90f6)}.c3{background:linear-gradient(135deg,#f5add1,#ea7fbe)}.c4{background:linear-gradient(135deg,#d2bcff,#a88ef0)}table{width:100%;border-collapse:collapse;font-size:13px}th,td{padding:10px 8px;text-align:left;border-bottom:1px solid #e2e8f0;color:#334155}th{font-size:12px;text-transform:uppercase;color:#64748b}.pill{font-size:11px;padding:4px 8px;border-radius:999px;background:#eef2ff;color:#4338ca;border:1px solid #c7d2fe}.btn{border:1px solid #c7d2fe;background:#fff;color:#3730a3;border-radius:10px;padding:8px 12px;text-decoration:none;font-size:12px;font-weight:600}.btn-primary{background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff;border-color:transparent}.modal-backdrop{position:fixed;inset:0;background:rgba(15,23,42,.45);display:none;align-items:center;justify-content:center;z-index:50}.modal-backdrop.show{display:flex}.modal-card{width:min(760px,92vw);background:#fff;border:1px solid #dbe3f1;border-radius:16px;padding:14px;max-height:88vh;overflow:auto}.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px}.form-grid input,.form-grid select,.form-grid textarea{border:1px solid #cbd5e1;border-radius:10px;padding:8px}.span-2{grid-column:span 2}.form-actions{grid-column:span 2;display:flex;justify-content:flex-end;gap:8px}</style></head><body>
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
<div class="shell"><aside class="sidebar"><div class="brand"><img src="{{ asset('images/midland.jpg') }}" alt="Midland Valley Homes Logo"><h1>Midland Valley Homes</h1></div><nav class="menu">@foreach($sidebarModules as $item)<a href="{{ route($item['route']) }}" class="{{ request()->routeIs($item['route']) ? 'active' : '' }}">{{ $item['label'] }}</a>@endforeach</nav></aside><main class="main"><header class="header"><div class="h-title">Benefits Module</div><button type="button" class="btn btn-primary" id="openBenefitModal">+ Add Benefit</button></header><section class="content">
<div class="stats"><div class="card c1"><strong>Total Benefits</strong><div>{{ $benefits->total() }}</div></div><div class="card c2"><strong>Pending</strong><div>{{ \App\Models\Benefit::where('status','pending')->count() }}</div></div><div class="card c3"><strong>Active</strong><div>{{ \App\Models\Benefit::where('status','active')->count() }}</div></div><div class="card c4"><strong>Inactive</strong><div>{{ \App\Models\Benefit::where('status','inactive')->count() }}</div></div></div>
<table><thead><tr><th>Employee</th><th>Benefit Type</th><th>Membership #</th><th>Start Date</th><th>Status</th></tr></thead><tbody>@forelse($benefits as $benefit)<tr><td>{{ $benefit->employee->first_name }} {{ $benefit->employee->last_name }}</td><td>{{ $benefit->benefit_type }}</td><td>{{ $benefit->membership_number ?: '-' }}</td><td>{{ $benefit->start_date ?: '-' }}</td><td><span class="pill">{{ $benefit->status }}</span></td></tr>@empty<tr><td colspan="5">No benefit records yet.</td></tr>@endforelse</tbody></table>
<div style="margin-top:12px;">{{ $benefits->links() }}</div></section></main></div>
<div id="benefitModal" class="modal-backdrop"><div class="modal-card"><div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;"><strong>Add Benefit</strong><button type="button" id="closeBenefitModal" class="btn">Close</button></div><form method="POST" action="{{ route('benefits.store') }}" class="form-grid">@csrf<select name="employee_id" required>@foreach(\App\Models\Employee::orderBy('last_name')->get() as $employee)<option value="{{ $employee->id }}">{{ $employee->first_name }} {{ $employee->last_name }}</option>@endforeach</select><select name="benefit_type"><option value="sss">sss</option><option value="pagibig">pagibig</option><option value="philhealth">philhealth</option><option value="other">other</option></select><input name="membership_number" placeholder="Membership Number"><input name="start_date" type="date"><input name="eligibility_date" type="date"><select name="status"><option value="pending">pending</option><option value="active">active</option><option value="inactive">inactive</option></select><textarea name="notes" class="span-2" placeholder="Notes"></textarea><div class="form-actions"><button type="button" id="cancelBenefitModal" class="btn">Cancel</button><button type="submit" class="btn btn-primary">Save</button></div></form></div></div>
<script>const bModal=document.getElementById('benefitModal');document.getElementById('openBenefitModal').onclick=()=>bModal.classList.add('show');document.getElementById('closeBenefitModal').onclick=()=>bModal.classList.remove('show');document.getElementById('cancelBenefitModal').onclick=()=>bModal.classList.remove('show');bModal.addEventListener('click',e=>{if(e.target===bModal)bModal.classList.remove('show');});</script>
</body></html>
