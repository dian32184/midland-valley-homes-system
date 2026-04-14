<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Marketing Dashboard</title>
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
        .donut { width: 140px; height: 140px; border-radius: 999px; margin: 0 auto 8px; background: conic-gradient(#8b5cf6 0 40%, #22d3ee 40% 70%, #60a5fa 70% 85%, #f472b6 85% 100%); display: grid; place-items: center; }
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
    $marketingStats = [
        'total_leads' => \App\Models\Customer::count(),
        'pending_leads' => \App\Models\Customer::where('status', 'pending')->count(),
        'approved_leads' => \App\Models\Customer::where('status', 'approved')->count(),
        'available_properties' => \App\Models\Property::where('status', 'available')->count(),
        'active_reservations' => \App\Models\Reservation::where('status', 'active')->count(),
        'total_reservations' => \App\Models\Reservation::count(),
        'payment_followups' => \App\Models\Payment::count(),
    ];
    $sidebarModules = [
        ['label' => 'Dashboard', 'route' => 'marketingdashboard'],
        ['label' => 'Customers', 'route' => 'customers.index'],
        ['label' => 'Properties', 'route' => 'properties.index'],
        ['label' => 'Reservations', 'route' => 'reservations.index'],
        ['label' => 'Payments', 'route' => 'payments.index'],
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
            <div><div class="h-title">Marketing Dashboard</div></div>
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
                <div class="card c-teal"><div class="k-label">Leads</div><div class="k-number">{{ $marketingStats['total_leads'] }}</div><div class="k-sub">Pending {{ $marketingStats['pending_leads'] }} | Approved {{ $marketingStats['approved_leads'] }}</div></div>
                <div class="card c-blue"><div class="k-label">Available Units</div><div class="k-number">{{ $marketingStats['available_properties'] }}</div><div class="k-sub">Property options for clients</div></div>
                <div class="card c-pink"><div class="k-label">Reservations</div><div class="k-number">{{ $marketingStats['total_reservations'] }}</div><div class="k-sub">Active {{ $marketingStats['active_reservations'] }}</div></div>
                <div class="card c-purple"><div class="k-label">Follow-ups</div><div class="k-number">{{ $marketingStats['payment_followups'] }}</div><div class="k-sub">Payment/processing follow-up list</div></div>
            </div>
            <div class="middle">
                <div class="panel">
                    <div class="panel-head"><div class="panel-title">Lead Conversion Trend</div><div class="chip">Last 6 months</div></div>
                    <div class="line-wrap">
                        <svg viewBox="0 0 600 220" preserveAspectRatio="none">
                            <defs><linearGradient id="mkFillLine" x1="0" x2="0" y1="0" y2="1"><stop offset="0%" stop-color="#93c5fd" stop-opacity="0.55"></stop><stop offset="100%" stop-color="#93c5fd" stop-opacity="0"></stop></linearGradient></defs>
                            <path d="M0,192 C52,176 90,168 140,136 C182,109 230,150 276,116 C322,84 362,97 420,84 C468,72 525,86 600,58 L600,220 L0,220 Z" fill="url(#mkFillLine)"></path>
                            <path d="M0,192 C52,176 90,168 140,136 C182,109 230,150 276,116 C322,84 362,97 420,84 C468,72 525,86 600,58" fill="none" stroke="#60a5fa" stroke-width="4"></path>
                        </svg>
                    </div>
                </div>
                <div class="panel">
                    <div class="panel-head"><div class="panel-title">Lead Source Split</div><div class="chip">This month</div></div>
                    <div class="donut"></div>
                    <div class="legend">
                        <div><span>Facebook</span><strong>40%</strong></div>
                        <div><span>Walk-in</span><strong>30%</strong></div>
                        <div><span>Referrals</span><strong>15%</strong></div>
                        <div><span>Office-to-office</span><strong>15%</strong></div>
                    </div>
                </div>
            </div>
            <div class="bottom">
                <div class="panel">
                    <div class="panel-title">Marketing Goals</div>
                    <div class="goal"><div class="goal-row"><span>Lead to Approval Rate</span><strong>62%</strong></div><div class="track"><div class="fill" style="width:62%"></div></div></div>
                    <div class="goal"><div class="goal-row"><span>Reservation Conversion</span><strong>47%</strong></div><div class="track"><div class="fill" style="width:47%"></div></div></div>
                    <div class="goal"><div class="goal-row"><span>Response Time Target</span><strong>73%</strong></div><div class="track"><div class="fill" style="width:73%"></div></div></div>
                </div>
                <div class="panel">
                    <div class="panel-title">Quick Summary / Notes</div>
                    <div class="notes">
                        <div class="note">Most leads this month come from Facebook campaigns.</div>
                        <div class="note">Walk-in inquiries improved compared to last month.</div>
                        <div class="note">Focus on converting pending leads to reservations.</div>
                        <div class="note">Coordinate with payments for follow-up reminders.</div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>
</body>
</html>
