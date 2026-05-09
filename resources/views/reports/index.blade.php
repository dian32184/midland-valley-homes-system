<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reports</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --gap: 12px; --radius: 16px; --sidebar: 220px; }
        * { box-sizing: border-box; } body { margin: 0; overflow: hidden; font-family: Figtree, Arial, sans-serif; background: linear-gradient(145deg, #f2f3ff 0%, #f9fbff 100%); }
        .shell { height: 100vh; display: flex; } .sidebar { width: var(--sidebar); flex: 0 0 var(--sidebar); background: linear-gradient(165deg, #eef2ff 0%, #e6ecff 45%, #eaf5ff 100%); border-right: 1px solid #e6ebf7; padding: 10px; display: flex; flex-direction: column; gap: 10px; }
        .brand { border-radius: var(--radius); background: #ffffffd9; border: 1px solid #e2e8f0; padding: 10px; text-align: center; } .brand img { height: 48px; width: auto; border-radius: 8px; margin-left: 50px; } .brand h1 { margin: 8px 0 0; font-size: 14px; font-weight: 700; color: #1e293b; }
        .menu { display: flex; flex-direction: column; gap: 6px; } .menu a { padding: 8px 10px; border-radius: 10px; text-decoration: none; font-size: 12px; font-weight: 600; color: #475569; border: 1px solid #e6eaf5; background: rgba(255, 255, 255, 0.7); } .menu a.active { background: linear-gradient(135deg, #6366f1, #4f46e5); color: #fff; border-color: transparent; }
        .main { flex: 1; display: flex; flex-direction: column; min-width: 0; padding: 10px; gap: 10px; background: linear-gradient(165deg, #f7f9ff 0%, #f1f5ff 50%, #edf4ff 100%); }
        .header { height: 56px; display: flex; align-items: center; justify-content: space-between; padding: 0 14px; border: 1px solid #dfe6f5; border-radius: 14px; background: linear-gradient(135deg, rgba(255,255,255,0.9), rgba(240,246,255,0.86)); }
        .h-title { font-size: 18px; font-weight: 700; color: #0f172a; } .content { flex: 1; min-height: 0; overflow: auto; border-radius: var(--radius); border: 1px solid #e2e8f0; background: linear-gradient(160deg, rgba(255,255,255,0.94), rgba(244,249,255,0.9)); box-shadow: 0 8px 18px rgba(15, 23, 42, 0.06); padding: 14px; }
        .stats { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: var(--gap); margin-bottom: 12px; } .card { border-radius: 14px; padding: 10px; color: #fff; }
        .c1 { background: linear-gradient(135deg, #73d9ef, #5aa8f2); } .c2 { background: linear-gradient(135deg, #9cb5ff, #6d90f6); } .c3 { background: linear-gradient(135deg, #f5add1, #ea7fbe); } .c4 { background: linear-gradient(135deg, #d2bcff, #a88ef0); }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; } .panel { border-radius: 14px; border: 1px solid #e2e8f0; background: #fff; padding: 12px; }
        .panel h3 { margin: 0 0 8px; font-size: 14px; color: #0f172a; } .row { display: flex; justify-content: space-between; font-size: 13px; padding: 6px 0; border-bottom: 1px dashed #e2e8f0; color: #334155; }
    </style>
</head>
<body>
@php
    $userRole = strtolower((string) (auth()->user()->role ?? ''));
    $sidebarModules = match (true) {
        $userRole === 'documentation' => [
            ['label' => 'Dashboard', 'route' => 'documentationdashboard'],
            ['label' => 'Documents', 'route' => 'documents.index'],
            ['label' => 'Title Transfers', 'route' => 'title-transfers.index'],
            ['label' => 'Customers', 'route' => 'customers.index'],
            ['label' => 'Properties', 'route' => 'properties.index'],
            ['label' => 'Reports', 'route' => 'reports.index'],
        ],
        $userRole === 'admin' => [
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
        ],
        default => [
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
        ],
    };
@endphp
<div class="shell"><aside class="sidebar"><div class="brand"><img src="{{ asset('images/midland.jpg') }}" alt="Midland Valley Homes Logo"><h1>Midland Valley Homes</h1></div><nav class="menu">@foreach ($sidebarModules as $item)<a href="{{ route($item['route']) }}" class="{{ request()->routeIs($item['route']) ? 'active' : '' }}">{{ $item['label'] }}</a>@endforeach</nav></aside>
<main class="main"><header class="header"><div class="h-title">Reports Module</div></header>
<section class="content">
<div class="stats"><div class="card c1"><strong>Total Collections</strong><div>PHP {{ number_format($data['total_collections'], 2) }}</div></div><div class="card c2"><strong>Property Buckets</strong><div>{{ count($data['property_status']) }}</div></div><div class="card c3"><strong>Reservation Buckets</strong><div>{{ count($data['reservation_status']) }}</div></div><div class="card c4"><strong>Document Buckets</strong><div>{{ count($data['document_status']) }}</div></div></div>
<div class="grid">
    <div class="panel"><h3>Property Status</h3>@foreach ($data['property_status'] as $status => $count)<div class="row"><span>{{ $status }}</span><strong>{{ $count }}</strong></div>@endforeach</div>
    <div class="panel"><h3>Reservation Status</h3>@foreach ($data['reservation_status'] as $status => $count)<div class="row"><span>{{ $status }}</span><strong>{{ $count }}</strong></div>@endforeach</div>
    <div class="panel"><h3>Document Status</h3>@foreach ($data['document_status'] as $status => $count)<div class="row"><span>{{ $status }}</span><strong>{{ $count }}</strong></div>@endforeach</div>
    <div class="panel"><h3>Monthly Collections</h3>@forelse ($data['monthly_collections'] as $row)<div class="row"><span>{{ $row->month }}</span><strong>PHP {{ number_format((float) $row->total, 2) }}</strong></div>@empty<div class="row"><span>No payment data with dates yet.</span><strong>-</strong></div>@endforelse</div>
</div>
</section></main></div>
</body>
</html>
