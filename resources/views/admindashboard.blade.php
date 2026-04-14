<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --gap: 12px; --radius: 16px; --sidebar: 220px; --header: 60px; }
        * { box-sizing: border-box; }
        body { margin: 0; overflow: hidden; font-family: Figtree, Arial, sans-serif; background: linear-gradient(145deg, #f2f3ff 0%, #f9fbff 100%); }
        .shell { height: 100vh; display: flex; }
        .sidebar {
            width: var(--sidebar);
            flex: 0 0 var(--sidebar);
            background:
                radial-gradient(circle at 18% 12%, rgba(99, 102, 241, 0.18), transparent 34%),
                radial-gradient(circle at 88% 86%, rgba(56, 189, 248, 0.12), transparent 38%),
                linear-gradient(165deg, #eef2ff 0%, #e6ecff 45%, #eaf5ff 100%);
            border-right: 1px solid #e6ebf7;
            padding: 10px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .brand { border-radius: var(--radius); background: #ffffffd9; border: 1px solid #e2e8f0; padding: 10px; text-align: center; }
        .brand img { height: 48px; width: auto; border-radius: 8px; margin-left: 50px; }
        .brand h1 { margin: 8px 0 0; font-size: 14px; font-weight: 700; color: #1e293b; }
        .menu { display: flex; flex-direction: column; gap: 6px; overflow: hidden; }
        .menu a {
            padding: 8px 10px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            color: #475569;
            border: 1px solid #e6eaf5;
            background: rgba(255, 255, 255, 0.7);
            line-height: 1.2;
        }
        .menu a:hover { background: #eef2ff; color: #3730a3; border-color: #c7d2fe; }
        .menu a.active { background: linear-gradient(135deg, #6366f1, #4f46e5); color: #fff; border-color: transparent; box-shadow: 0 8px 14px rgba(79, 70, 229, 0.28); }
        .main {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            padding: 10px;
            gap: 10px;
            background:
                radial-gradient(circle at 10% 10%, rgba(99, 102, 241, 0.09), transparent 28%),
                radial-gradient(circle at 90% 90%, rgba(56, 189, 248, 0.08), transparent 28%),
                linear-gradient(165deg, #f7f9ff 0%, #f1f5ff 50%, #edf4ff 100%);
        }
        .header {
            height: 56px;
            min-height: 56px;
            max-height: 56px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 14px;
            border: 1px solid #dfe6f5;
            border-radius: 14px;
            background: linear-gradient(135deg, rgba(255,255,255,0.9), rgba(240,246,255,0.86));
            box-shadow: 0 8px 18px rgba(15, 23, 42, 0.06);
        }
        .h-title { font-size: 18px; font-weight: 700; color: #0f172a; line-height: 1.1; }
        .top-date { font-size: 12px; color: #475569; border: 1px solid #dbe3f1; background: #fff; border-radius: 10px; padding: 6px 10px; margin-right: 8px; }
        .logout-icon { border: 1px solid #dbe3f1; background: #fff; border-radius: 10px; width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; color: #334155; }
        .content { flex: 1; min-height: 0; display: grid; grid-template-rows: 90px 1fr 1fr; gap: var(--gap); overflow: hidden; }
        .summary { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: var(--gap); }
        .card { border-radius: var(--radius); box-shadow: 0 8px 18px rgba(15, 23, 42, 0.08); padding: 12px; color: #fff; height: 90px; overflow: hidden; }
        .c-teal { background: linear-gradient(135deg, #73d9ef, #5aa8f2); }
        .c-blue { background: linear-gradient(135deg, #9cb5ff, #6d90f6); }
        .c-pink { background: linear-gradient(135deg, #f5add1, #ea7fbe); }
        .c-purple { background: linear-gradient(135deg, #d2bcff, #a88ef0); }
        .k-label { font-size: 12px; font-weight: 700; text-transform: uppercase; opacity: 0.95; }
        .k-number { font-size: 16px; font-weight: 700; margin-top: 4px; }
        .k-sub { font-size: 12px; margin-top: 4px; opacity: 0.95; }
        .middle { display: grid; grid-template-columns: 65fr 35fr; gap: var(--gap); }
        .panel { border-radius: var(--radius); border: 1px solid #e2e8f0; background: linear-gradient(160deg, rgba(255,255,255,0.94), rgba(244,249,255,0.9)); box-shadow: 0 8px 18px rgba(15, 23, 42, 0.06); padding: 10px; overflow: hidden; }
        .panel-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; }
        .panel-title { font-size: 14px; font-weight: 700; color: #0f172a; }
        .chip { font-size: 11px; color: #64748b; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 4px 8px; }
        .line-wrap { position: relative; height: 170px; border-radius: 12px; background: linear-gradient(180deg, #f8fbff, #fff); overflow: hidden; }
        .line-wrap svg { width: 100%; height: 100%; position: absolute; inset: 0; }
        .donut { width: 140px; height: 140px; border-radius: 999px; margin: 0 auto 8px; background: conic-gradient(#8b5cf6 0 30%, #22d3ee 30% 58%, #60a5fa 58% 80%, #f472b6 80% 100%); display: grid; place-items: center; }
        .donut::after { content: ""; width: 82px; height: 82px; border-radius: 999px; background: #fff; border: 1px solid #e2e8f0; }
        .legend { font-size: 12px; color: #475569; display: grid; gap: 3px; }
        .legend div { display: flex; justify-content: space-between; }
        .bottom { display: grid; grid-template-columns: 1fr 1fr; gap: var(--gap); }
        .goal { margin-top: 8px; }
        .goal-row { display: flex; justify-content: space-between; font-size: 12px; color: #475569; margin-bottom: 5px; }
        .track { height: 6px; background: #e5e7eb; border-radius: 999px; overflow: hidden; }
        .fill { height: 100%; background: linear-gradient(90deg, #60a5fa, #6366f1); }
        .notes { margin-top: 10px; font-size: 12px; color: #475569; display: grid; gap: 8px; }
        .note { border-radius: 10px; background: #f8fafc; border: 1px solid #e2e8f0; padding: 8px; }
    </style>
</head>
<body>
@php
    $adminStats = [
        'total_customers' => \App\Models\Customer::count(),
        'total_properties' => \App\Models\Property::count(),
        'pending_documents' => \App\Models\Document::where('status', 'pending')->count(),
        'total_collections' => \App\Models\Payment::sum('amount'),
        'employee_count' => \App\Models\Employee::count(),
        'pending_payrolls' => \App\Models\Payroll::where('status', 'pending')->count(),
    ];
    $sidebarModules = [
        ['label' => 'Dashboard', 'route' => 'admindashboard'],
        ['label' => 'Customers', 'route' => 'customers.index'],
        ['label' => 'Properties', 'route' => 'properties.index'],
        ['label' => 'Reservations', 'route' => 'reservations.index'],
        ['label' => 'Payments', 'route' => 'payments.index'],
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
            @foreach ($sidebarModules as $item)
                <a href="{{ route($item['route']) }}" class="{{ request()->routeIs($item['route']) ? 'active' : '' }}">{{ $item['label'] }}</a>
            @endforeach
        </nav>
    </aside>
    <main class="main">
        <header class="header">
            <div><div class="h-title">Admin Dashboard</div></div>
            <div style="display:flex; align-items:center;">
                <div class="top-date">{{ now()->format('M d, Y') }}</div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-icon" title="Log out" aria-label="Log out">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 4.75A1.75 1.75 0 014.75 3h6.5A1.75 1.75 0 0113 4.75v2.5a.75.75 0 01-1.5 0v-2.5a.25.25 0 00-.25-.25h-6.5a.25.25 0 00-.25.25v10.5c0 .138.112.25.25.25h6.5a.25.25 0 00.25-.25v-2.5a.75.75 0 011.5 0v2.5A1.75 1.75 0 0111.25 17h-6.5A1.75 1.75 0 013 15.25V4.75z" clip-rule="evenodd" />
                            <path fill-rule="evenodd" d="M15.78 6.47a.75.75 0 010 1.06L13.31 10l2.47 2.47a.75.75 0 11-1.06 1.06l-3-3a.75.75 0 010-1.06l3-3a.75.75 0 011.06 0z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </form>
            </div>
        </header>
        <section class="content">
            <div class="summary">
                <div class="card c-teal"><div class="k-label">Customers</div><div class="k-number">{{ $adminStats['total_customers'] }}</div><div class="k-sub">Managed customer records</div></div>
                <div class="card c-blue"><div class="k-label">Properties</div><div class="k-number">{{ $adminStats['total_properties'] }}</div><div class="k-sub">Inventory maintained</div></div>
                <div class="card c-pink"><div class="k-label">Collections</div><div class="k-number">PHP {{ number_format((float) $adminStats['total_collections'], 2) }}</div><div class="k-sub">Total recorded payments</div></div>
                <div class="card c-purple"><div class="k-label">Payroll Queue</div><div class="k-number">{{ $adminStats['pending_payrolls'] }}</div><div class="k-sub">Pending admin processing</div></div>
            </div>
            <div class="middle">
                <div class="panel">
                    <div class="panel-head"><div class="panel-title">Admin Workload Trend</div><div class="chip">Last 6 months</div></div>
                    <div class="line-wrap">
                        <svg viewBox="0 0 600 220" preserveAspectRatio="none">
                            <defs><linearGradient id="adFillLine" x1="0" x2="0" y1="0" y2="1"><stop offset="0%" stop-color="#93c5fd" stop-opacity="0.55"></stop><stop offset="100%" stop-color="#93c5fd" stop-opacity="0"></stop></linearGradient></defs>
                            <path d="M0,194 C70,165 100,172 150,145 C195,122 250,160 304,126 C350,95 390,112 450,96 C500,82 542,92 600,70 L600,220 L0,220 Z" fill="url(#adFillLine)"></path>
                            <path d="M0,194 C70,165 100,172 150,145 C195,122 250,160 304,126 C350,95 390,112 450,96 C500,82 542,92 600,70" fill="none" stroke="#60a5fa" stroke-width="4"></path>
                        </svg>
                    </div>
                </div>
                <div class="panel">
                    <div class="panel-head"><div class="panel-title">Task Split</div><div class="chip">This month</div></div>
                    <div class="donut"></div>
                    <div class="legend">
                        <div><span>Customer Encoding</span><strong>30%</strong></div>
                        <div><span>Payments</span><strong>28%</strong></div>
                        <div><span>Payroll</span><strong>22%</strong></div>
                        <div><span>Benefits</span><strong>20%</strong></div>
                    </div>
                </div>
            </div>
            <div class="bottom">
                <div class="panel">
                    <div class="panel-title">Admin Goals</div>
                    <div class="goal"><div class="goal-row"><span>Payroll Completion</span><strong>71%</strong></div><div class="track"><div class="fill" style="width:71%"></div></div></div>
                    <div class="goal"><div class="goal-row"><span>Data Accuracy Checks</span><strong>66%</strong></div><div class="track"><div class="fill" style="width:66%"></div></div></div>
                    <div class="goal"><div class="goal-row"><span>Benefits Encoding</span><strong>52%</strong></div><div class="track"><div class="fill" style="width:52%"></div></div></div>
                </div>
                <div class="panel">
                    <div class="panel-title">Quick Summary / Notes</div>
                    <div class="notes">
                        <div class="note">Pending documents: {{ $adminStats['pending_documents'] }}.</div>
                        <div class="note">Total employees managed: {{ $adminStats['employee_count'] }}.</div>
                        <div class="note">Payroll queue requires validation this week.</div>
                        <div class="note">Collections are synchronized with payment records.</div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>
</body>
</html>
