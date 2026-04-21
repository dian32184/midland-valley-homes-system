<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'Midland Valley Homes') }}</title>
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
        .h-sub { font-size: 12px; color: #64748b; margin-top: 2px; }
        .top-date {
            font-size: 12px;
            color: #475569;
            border: 1px solid #dbe3f1;
            background: #fff;
            border-radius: 10px;
            padding: 6px 10px;
            margin-right: 8px;
        }
        .logout-icon {
            border: 1px solid #dbe3f1;
            background: #fff;
            border-radius: 10px;
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #334155;
            transition: all 0.2s ease;
        }
        .logout-icon:hover { background: #f8fafc; color: #1e293b; }

        .content-scroll {
            flex: 1;
            min-height: 0;
            overflow: auto;
            border-radius: var(--radius);
        }

        .panel {
            border-radius: var(--radius);
            border: 1px solid #e2e8f0;
            background: linear-gradient(160deg, rgba(255, 255, 255, 0.94), rgba(244, 249, 255, 0.9));
            box-shadow: 0 8px 18px rgba(15, 23, 42, 0.06);
            padding: 12px;
            overflow: hidden;
        }
        .panel-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; gap: 10px; }
        .panel-title { font-size: 14px; font-weight: 700; color: #0f172a; }
        .chip { font-size: 11px; color: #64748b; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 4px 8px; white-space: nowrap; }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            height: 34px;
            padding: 0 12px;
            border-radius: 10px;
            border: 1px solid #dbe3f1;
            background: #fff;
            font-size: 12px;
            font-weight: 700;
            color: #334155;
            text-decoration: none;
            transition: all 0.2s ease;
            white-space: nowrap;
        }
        .btn:hover { background: #f8fafc; color: #0f172a; }
        .btn-primary { border-color: transparent; color: #fff; background: linear-gradient(135deg, #6366f1, #4f46e5); box-shadow: 0 8px 14px rgba(79, 70, 229, 0.18); }
        .btn-primary:hover { filter: brightness(1.03); }
        .btn-danger { border-color: transparent; color: #fff; background: linear-gradient(135deg, #ef4444, #dc2626); }

        .flash { border-radius: 12px; padding: 10px 12px; font-size: 12px; border: 1px solid; }
        .flash-success { background: #ecfdf5; border-color: #bbf7d0; color: #065f46; }
        .flash-error { background: #fef2f2; border-color: #fecaca; color: #7f1d1d; }

        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px 10px; font-size: 12px; }
        thead th { text-align: left; color: #64748b; font-weight: 800; font-size: 11px; text-transform: uppercase; letter-spacing: 0.04em; border-bottom: 1px solid #e2e8f0; }
        tbody td { border-bottom: 1px solid #eef2f7; color: #0f172a; }
        tbody tr:hover td { background: rgba(248, 250, 252, 0.65); }
        .muted { color: #64748b; }
        .badge { display: inline-flex; align-items: center; border-radius: 999px; padding: 3px 8px; font-size: 11px; font-weight: 800; border: 1px solid; }
        .badge-gray { background: #f1f5f9; color: #334155; border-color: #e2e8f0; }
        .badge-yellow { background: #fef9c3; color: #713f12; border-color: #fde68a; }
        .badge-green { background: #dcfce7; color: #14532d; border-color: #bbf7d0; }
        .badge-red { background: #fee2e2; color: #7f1d1d; border-color: #fecaca; }

        .field { display: grid; gap: 6px; }
        .label { font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.04em; }
        .input, .select, .textarea {
            border: 1px solid #dbe3f1;
            background: #fff;
            border-radius: 10px;
            padding: 8px 10px;
            font-size: 12px;
            color: #0f172a;
            outline: none;
        }
        .textarea { resize: vertical; }
        .error { color: #b91c1c; font-size: 12px; }
    </style>
</head>
<body>
@php
    $sidebarModules = $sidebarModules ?? [
        ['label' => 'Dashboard', 'route' => 'managerdashboard'],
        ['label' => 'Customers', 'route' => 'customers.index'],
        ['label' => 'Properties', 'route' => 'properties.index'],
        ['label' => 'Reservations', 'route' => 'reservations.index'],
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
            <x-application-logo class="block h-12 w-auto mx-auto text-indigo-700" />
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
            <div>
                <div class="h-title">{{ $headerTitle ?? ($title ?? 'Dashboard') }}</div>
                @if (!empty($headerSubtitle))
                    <div class="h-sub">{{ $headerSubtitle }}</div>
                @endif
            </div>
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

        <div class="content-scroll">
            <div style="display:grid; gap: var(--gap); padding: 0;">
                {{ $slot }}
            </div>
        </div>
    </main>
</div>
</body>
</html>

