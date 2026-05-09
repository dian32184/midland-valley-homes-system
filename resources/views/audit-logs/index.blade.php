<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Audit Logs</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --gap: 12px; --radius: 16px; --sidebar: 220px; }
        * { box-sizing: border-box; }
        body { margin: 0; overflow: hidden; font-family: Figtree, Arial, sans-serif; background: linear-gradient(145deg, #f2f3ff 0%, #f9fbff 100%); }
        .shell { height: 100vh; display: flex; }
        .sidebar { width: var(--sidebar); flex: 0 0 var(--sidebar); background: linear-gradient(165deg, #eef2ff 0%, #e6ecff 45%, #eaf5ff 100%); border-right: 1px solid #e6ebf7; padding: 10px; display: flex; flex-direction: column; gap: 10px; }
        .brand { border-radius: var(--radius); background: #ffffffd9; border: 1px solid #e2e8f0; padding: 10px; text-align: center; }
        .brand img { height: 48px; width: auto; border-radius: 8px; margin-left: 50px; }
        .brand h1 { margin: 8px 0 0; font-size: 14px; font-weight: 700; color: #1e293b; }
        .menu { display: flex; flex-direction: column; gap: 6px; }
        .menu a { padding: 8px 10px; border-radius: 10px; text-decoration: none; font-size: 12px; font-weight: 600; color: #475569; border: 1px solid #e6eaf5; background: rgba(255, 255, 255, 0.7); }
        .menu a.active { background: linear-gradient(135deg, #6366f1, #4f46e5); color: #fff; border-color: transparent; }
        .main { flex: 1; display: flex; flex-direction: column; min-width: 0; padding: 10px; gap: 10px; }
        .header { height: 56px; display: flex; align-items: center; justify-content: space-between; padding: 0 14px; border: 1px solid #dfe6f5; border-radius: 14px; background: linear-gradient(135deg, rgba(255,255,255,0.9), rgba(240,246,255,0.86)); }
        .h-title { font-size: 18px; font-weight: 700; color: #0f172a; }
        .content { flex: 1; min-height: 0; overflow: auto; border-radius: var(--radius); border: 1px solid #e2e8f0; background: #fff; padding: 14px; }
        .filters { display: grid; grid-template-columns: 1fr auto; gap: 8px; margin-bottom: 12px; }
        .filters input { border: 1px solid #cbd5e1; border-radius: 10px; padding: 8px 10px; font-size: 13px; }
        .filters button, .filters a { border-radius: 10px; padding: 8px 10px; font-size: 13px; text-decoration: none; border: 1px solid #cbd5e1; background: #f8fafc; color: #1e293b; display: inline-flex; align-items: center; }
        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        th, td { border-bottom: 1px solid #e2e8f0; padding: 8px; text-align: left; vertical-align: top; }
        th { background: #f8fafc; color: #0f172a; font-weight: 700; position: sticky; top: 0; }
        .muted { color: #64748b; }
        .payload { max-width: 260px; white-space: pre-wrap; word-break: break-word; color: #334155; }
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
@endphp
<div class="shell">
    <aside class="sidebar">
        <div class="brand">
            <img src="{{ asset('images/midland.jpg') }}" alt="Midland Valley Homes Logo">
            <h1>Midland Valley Homes</h1>
        </div>
        <nav class="menu">
            @foreach ($sidebarModules as $item)
                <a href="{{ route($item['route']) }}" class="{{ request()->routeIs($item['route']) ? 'active' : '' }}">{{ $item['label'] }}</a>
            @endforeach
        </nav>
    </aside>
    <main class="main">
        <header class="header">
            <div class="h-title">Audit Logs</div>
            <div class="muted">{{ $logs->total() }} records</div>
        </header>
        <section class="content">
            @if (session('warning') || isset($warning))
                <div style="margin-bottom:12px; border:1px solid #facc15; background:#fffbeb; color:#854d0e; border-radius:10px; padding:10px 12px; font-size:13px;">
                    {{ session('warning') ?? $warning }}
                </div>
            @endif

            <form method="GET" action="{{ route('audit-logs.index') }}" class="filters">
                <input type="text" name="search" placeholder="Search user or action..." value="{{ request('search') }}">
                <div style="display:flex; gap:8px;">
                    <button type="submit">Filter</button>
                    <a href="{{ route('audit-logs.index') }}">Reset</a>
                </div>
            </form>

            <table>
                <thead>
                    <tr>
                        <th>Date/Time</th>
                        <th>User</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                        @php
                            $method = strtoupper((string) $log->http_method);
                            $route = strtolower((string) $log->route_name);
                            $subject = trim(str_replace(['-', '.'], ' ', explode('.', $route)[0] ?? 'record'));
                            $verb = match (true) {
                                str_contains($route, '.store') => 'added',
                                str_contains($route, '.update') => 'updated',
                                str_contains($route, '.destroy') => 'deleted',
                                str_contains($route, '.bulk-store') => 'recorded',
                                default => match ($method) {
                                    'POST' => 'added',
                                    'PATCH', 'PUT' => 'updated',
                                    'DELETE' => 'deleted',
                                    default => 'changed',
                                },
                            };
                            $readableAction = ucfirst(trim($verb.' '.$subject));
                        @endphp
                        <tr>
                            <td>
                                {{ $log->created_at?->format('Y-m-d H:i:s') }}
                            </td>
                            <td>
                                {{ $log->user?->name ?? 'Unknown' }}
                            </td>
                            <td>{{ $readableAction }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="muted">No audit logs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div style="margin-top: 12px;">
                {{ $logs->links() }}
            </div>
        </section>
    </main>
</div>
</body>
</html>
