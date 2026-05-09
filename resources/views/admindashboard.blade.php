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
        html[data-theme="dark"] {
            --bg-app: linear-gradient(145deg, #0f172a 0%, #020617 100%);
            --bg-sidebar: linear-gradient(165deg, #1e293b 0%, #0f172a 100%);
            --bg-header: #1e293b;
            --bg-panel: #1e293b;
            --bg-card: #1e293b;
            --border-color: #334155;
            --text-primary: #f8fafc;
            --text-secondary: #cbd5e1;
            --menu-bg: rgba(30, 41, 59, 0.7);
            --menu-hover: #334155;
        }
        html[data-theme="light"] {
            --bg-app: linear-gradient(145deg, #f2f3ff 0%, #f9fbff 100%);
            --bg-sidebar: linear-gradient(165deg, #eef2ff 0%, #e6ecff 45%, #eaf5ff 100%);
            --bg-header: linear-gradient(135deg, rgba(255,255,255,0.9), rgba(240,246,255,0.86));
            --bg-panel: linear-gradient(160deg, rgba(255,255,255,0.94), rgba(244,249,255,0.9));
            --bg-card: #ffffff;
            --border-color: #e2e8f0;
            --text-primary: #1e293b;
            --text-secondary: #475569;
            --menu-bg: rgba(255, 255, 255, 0.7);
            --menu-hover: #eef2ff;
        }
        * { box-sizing: border-box; }
        body { margin: 0; overflow: hidden; font-family: Figtree, Arial, sans-serif; background: var(--bg-app); color: var(--text-primary); transition: background 0.3s, color 0.3s; }
        .shell { height: 100vh; display: flex; }
        .sidebar {
            width: var(--sidebar);
            flex: 0 0 var(--sidebar);
            background: var(--bg-sidebar);
            border-right: 1px solid var(--border-color);
            padding: 10px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .brand { border-radius: var(--radius); background: var(--bg-card); border: 1px solid var(--border-color); padding: 10px; text-align: center; }
        .brand img { height: 48px; width: auto; border-radius: 8px; margin-left: 50px; }
        .brand h1 { margin: 8px 0 0; font-size: 14px; font-weight: 700; color: var(--text-primary); }
        .menu { display: flex; flex-direction: column; gap: 6px; overflow: hidden; }
        .menu a {
            padding: 8px 10px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-secondary);
            border: 1px solid var(--border-color);
            background: var(--menu-bg);
            line-height: 1.2;
        }
        .menu a:hover { background: var(--menu-hover); color: var(--text-primary); }
        .menu a.active { background: linear-gradient(135deg, #6366f1, #4f46e5); color: #fff; border-color: transparent; box-shadow: 0 8px 14px rgba(79, 70, 229, 0.28); }
        .main {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            padding: 10px;
            gap: 10px;
        }
        .header {
            height: 56px;
            min-height: 56px;
            max-height: 56px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            border: 1px solid var(--border-color);
            border-radius: 14px;
            background: var(--bg-header);
            box-shadow: 0 8px 18px rgba(15, 23, 42, 0.06);
            transition: background 0.3s;
        }
        .h-title { font-size: 18px; font-weight: 700; color: var(--text-primary); line-height: 1.1; }
        .top-date { font-size: 12px; color: var(--text-secondary); border: 1px solid var(--border-color); background: var(--bg-card); border-radius: 10px; padding: 6px 10px; margin-right: 8px; }
        .dropdown-item:hover { background: var(--menu-hover); }
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
        .panel { border-radius: var(--radius); border: 1px solid var(--border-color); background: var(--bg-panel); box-shadow: 0 8px 18px rgba(15, 23, 42, 0.06); padding: 10px; overflow: hidden; transition: background 0.3s; }
        .panel-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; }
        .panel-title { font-size: 14px; font-weight: 700; color: var(--text-primary); }
        .chip { font-size: 11px; color: #64748b; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 4px 8px; }
        .line-wrap { position: relative; height: 170px; border-radius: 12px; background: linear-gradient(180deg, #f8fbff, #fff); overflow: hidden; }
        .line-wrap svg { width: 100%; height: 100%; position: absolute; inset: 0; }
        .donut { width: 140px; height: 140px; border-radius: 999px; margin: 0 auto 8px; background: conic-gradient(#8b5cf6 0 30%, #22d3ee 30% 58%, #60a5fa 58% 80%, #f472b6 80% 100%); display: grid; place-items: center; }
        .donut::after { content: ""; width: 82px; height: 82px; border-radius: 999px; background: #fff; border: 1px solid #e2e8f0; }
        .legend { font-size: 12px; color: #475569; display: grid; gap: 3px; }
        .legend div { display: flex; justify-content: space-between; }
        .bottom { display: grid; grid-template-columns: 1fr 1fr; gap: var(--gap); }
        .goal { margin-top: 8px; }
        .goal-row { display: flex; justify-content: space-between; font-size: 12px; color: var(--text-secondary); margin-bottom: 5px; }
        .track { height: 6px; background: var(--border-color); border-radius: 999px; overflow: hidden; }
        .fill { height: 100%; background: linear-gradient(90deg, #60a5fa, #6366f1); }
        .notes { margin-top: 10px; font-size: 12px; color: var(--text-secondary); display: grid; gap: 8px; }
        .note { border-radius: 10px; background: var(--bg-card); border: 1px solid var(--border-color); padding: 8px; }
    </style>
    <script>
        // Init theme
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', savedTheme);
    </script>
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
            <div class="h-brand">
                <div class="h-title">Admin Dashboard</div>
                <div class="h-subtitle" style="font-size: 11px; color: var(--text-secondary); margin-top: 2px;">Welcome, {{ auth()->user()->name ?? 'Admin' }}</div>
            </div>
            
            <div class="header-actions" style="display:flex; align-items:center; gap:16px;">
                <div class="top-date">{{ now()->format('l, M j, Y') }}</div>
                
                <!-- Dark Mode Toggle -->
                <button type="button" class="header-icon-btn" id="themeToggle" title="Toggle Dark Mode" style="border:1px solid var(--border-color); background:var(--bg-card); border-radius:10px; width:34px; height:34px; display:inline-flex; align-items:center; justify-content:center; color:var(--text-secondary); cursor:pointer;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z"/></svg>
                </button>

                <!-- Profile Dropdown -->
                <div class="user-profile" id="profileDropdownBtn" style="position:relative; display:flex; align-items:center; gap:8px; cursor:pointer; background:var(--bg-card); border:1px solid var(--border-color); padding:4px 8px; border-radius:12px;">
                    @php
                        $userName = auth()->user()->name ?? 'Admin';
                        $userInitial = strtoupper(substr($userName, 0, 1));
                    @endphp
                    <div class="user-avatar" style="width:26px; height:26px; border-radius:50%; background:linear-gradient(135deg, #6366f1, #4f46e5); color:#fff; display:flex; align-items:center; justify-content:center; font-weight:bold; font-size:12px;">
                        {{ $userInitial }}
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/></svg>

                    <!-- Dropdown Menu -->
                    <div class="dropdown-menu" id="profileMenu" style="position:absolute; top:45px; right:0; width:160px; background:var(--bg-card); border:1px solid var(--border-color); border-radius:12px; box-shadow:0 10px 25px -5px rgba(15,23,42,0.1); padding:8px 0; display:none; flex-direction:column; z-index:100;">
                        <a href="{{ route('profile.edit') }}" class="dropdown-item" style="padding:8px 16px; color:var(--text-primary); text-decoration:none; font-size:13px; display:flex; align-items:center; gap:8px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/></svg>
                            Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                            @csrf
                            <button type="submit" class="dropdown-item" style="width:100%; padding:8px 16px; color:#be123c; border:none; background:none; text-align:left; font-size:13px; display:flex; align-items:center; gap:8px; cursor:pointer;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/><path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/></svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>
@endphp
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
<script>
    // Theme Toggle Logic
    const themeToggleBtn = document.getElementById('themeToggle');
    themeToggleBtn.addEventListener('click', () => {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        const newTheme = currentTheme === 'light' ? 'dark' : 'light';
        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
    });

    // Profile Dropdown Logic
    const profileBtn = document.getElementById('profileDropdownBtn');
    const profileMenu = document.getElementById('profileMenu');
    profileBtn.addEventListener('click', (e) => {
        profileMenu.style.display = profileMenu.style.display === 'flex' ? 'none' : 'flex';
        e.stopPropagation();
    });
    document.addEventListener('click', () => {
        profileMenu.style.display = 'none';
    });
</script>
</body>
</html>
