<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>User Roles</title>
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
        .c5{background:linear-gradient(135deg,#818cf8,#6366f1)}
        table{width:100%;border-collapse:collapse;font-size:13px}
        th,td{padding:10px 8px;text-align:left;border-bottom:1px solid #e2e8f0;color:#334155;vertical-align:middle}
        th{font-size:12px;text-transform:uppercase;color:#64748b}
        .pill{font-size:11px;padding:4px 8px;border-radius:999px;background:#eef2ff;color:#4338ca;border:1px solid #c7d2fe}
        .btn{border:1px solid #c7d2fe;background:#fff;color:#3730a3;border-radius:10px;padding:8px 12px;text-decoration:none;font-size:12px;font-weight:600;cursor:pointer}
        .btn-primary{background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff;border-color:transparent}
        .btn-danger{background:#fff1f2;border-color:#fecdd3;color:#b91c1c}
        select.role-select{min-width:140px;border:1px solid #cbd5e1;border-radius:10px;padding:6px 8px;font-size:12px}
        .flash{margin-bottom:12px;padding:10px 12px;border-radius:10px;font-size:13px;background:#ecfdf5;border:1px solid #a7f3d0;color:#065f46}
        .error-box{margin-bottom:12px;padding:10px 12px;border-radius:10px;font-size:13px;background:#fef2f2;border:1px solid #fecaca;color:#991b1b}
        .muted{color:#64748b;font-size:12px}
        @media (max-width:1100px){.stats{grid-template-columns:repeat(2,minmax(0,1fr))}}
        @media (max-width:720px){.stats{grid-template-columns:repeat(2,minmax(0,1fr))}}
    </style>
</head>
<body>
@php
    $sidebarModules = [
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
    ];
    $roleLabels = [
        'admin' => 'Admin',
        'manager' => 'Manager',
        'marketing' => 'Marketing',
        'documentation' => 'Documentation',
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
            <div class="h-title">User Roles</div>
        </header>
        <section class="content">
            @if(session('status'))
                <div class="flash">{{ session('status') }}</div>
            @endif
            @if($errors->any())
                <div class="error-box">{{ $errors->first() }}</div>
            @endif

            <div class="stats">
                @foreach($roleCounts as $roleKey => $count)
                    <div class="card c{{ ($loop->index % 4) + 1 }}">
                        <strong>{{ $roleLabels[$roleKey] ?? ucfirst($roleKey) }}</strong>
                        <div style="margin-top:6px;font-size:18px;font-weight:700">{{ $count }}</div>
                    </div>
                @endforeach
            </div>

            <p class="muted" style="margin:0 0 12px">Assign system roles to login accounts. Changes apply on next request.</p>

            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Current role</th>
                        <th>Change role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                        <tr>
                            <td>{{ $u->name }}</td>
                            <td>{{ $u->email }}</td>
                            <td><span class="pill">{{ $roleLabels[$u->role] ?? $u->role }}</span></td>
                            <td>
                                <form method="POST" action="{{ route('user-roles.update', $u) }}" style="display:flex;align-items:center;gap:8px;margin:0">
                                    @csrf
                                    @method('PATCH')
                                    <select name="role" class="role-select" aria-label="Role for {{ $u->name }}">
                                        @foreach(array_keys($roleLabels) as $r)
                                            <option value="{{ $r }}" @selected($u->role === $r)>{{ $roleLabels[$r] }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </form>
                            </td>
                            <td>
                                <form method="POST" action="{{ route('user-roles.destroy', $u) }}" style="margin:0" onsubmit="return confirm('Delete this user account?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="muted">No users found.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div style="margin-top:12px">{{ $users->links() }}</div>
        </section>
    </main>
</div>
</body>
</html>
