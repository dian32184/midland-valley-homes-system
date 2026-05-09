<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Profile — Midland Valley Homes</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --gap: 12px; --radius: 16px; --sidebar: 220px; }
        html[data-theme="dark"] {
            --bg-app:     linear-gradient(145deg, #0f172a 0%, #020617 100%);
            --bg-sidebar: linear-gradient(165deg, #1e293b 0%, #0f172a 100%);
            --bg-header:  #1e293b;
            --bg-panel:   #1e293b;
            --bg-card:    #1e293b;
            --bg-input:   #0f172a;
            --border-color:   #334155;
            --border-focus:   #6366f1;
            --text-primary:   #f8fafc;
            --text-secondary: #94a3b8;
            --text-muted:     #64748b;
            --menu-bg:   rgba(30,41,59,0.7);
            --menu-hover:#334155;
            --danger-bg: rgba(244,63,94,0.12);
            --danger-text:#fda4af;
        }
        html[data-theme="light"] {
            --bg-app:     linear-gradient(145deg, #f2f3ff 0%, #f9fbff 100%);
            --bg-sidebar: linear-gradient(165deg, #eef2ff 0%, #e6ecff 45%, #eaf5ff 100%);
            --bg-header:  linear-gradient(135deg, rgba(255,255,255,0.9), rgba(240,246,255,0.86));
            --bg-panel:   linear-gradient(160deg, rgba(255,255,255,0.94), rgba(244,249,255,0.9));
            --bg-card:    #ffffff;
            --bg-input:   #f8fafc;
            --border-color:   #e2e8f0;
            --border-focus:   #6366f1;
            --text-primary:   #1e293b;
            --text-secondary: #475569;
            --text-muted:     #94a3b8;
            --menu-bg:   rgba(255,255,255,0.7);
            --menu-hover:#eef2ff;
            --danger-bg: #fff1f2;
            --danger-text:#be123c;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0; overflow: hidden;
            font-family: Figtree, Arial, sans-serif;
            background: var(--bg-app); color: var(--text-primary);
            transition: background 0.3s, color 0.3s;
        }
        .shell { height: 100vh; display: flex; }

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar); flex: 0 0 var(--sidebar);
            background: var(--bg-sidebar);
            border-right: 1px solid var(--border-color);
            padding: 10px; display: flex; flex-direction: column; gap: 10px;
            transition: background 0.3s, border-color 0.3s;
        }
        .brand {
            border-radius: var(--radius); background: var(--bg-card);
            border: 1px solid var(--border-color); padding: 10px; text-align: center;
            transition: background 0.3s, border-color 0.3s;
        }
        .brand img { height: 48px; width: auto; border-radius: 8px; margin-left: 50px; }
        .brand h1 { margin: 8px 0 0; font-size: 14px; font-weight: 700; color: var(--text-primary); }
        .menu { display: flex; flex-direction: column; gap: 6px; }
        .menu a {
            padding: 8px 10px; border-radius: 10px; text-decoration: none;
            font-size: 12px; font-weight: 600; color: var(--text-secondary);
            border: 1px solid var(--border-color); background: var(--menu-bg); line-height: 1.2;
            transition: background 0.2s, color 0.2s;
        }
        .menu a:hover { background: var(--menu-hover); color: var(--text-primary); }
        .menu a.active {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: #fff; border-color: transparent;
            box-shadow: 0 8px 14px rgba(79,70,229,0.28);
        }

        /* ── Main ── */
        .main {
            flex: 1; display: flex; flex-direction: column;
            min-width: 0; padding: 10px; gap: 10px; overflow: hidden;
        }

        /* ── Header ── */
        .header {
            height: 56px; min-height: 56px; max-height: 56px;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 20px;
            border: 1px solid var(--border-color); border-radius: 14px;
            background: var(--bg-header);
            box-shadow: 0 8px 18px rgba(15,23,42,0.06);
            transition: background 0.3s, border-color 0.3s;
        }
        .h-title    { font-size: 18px; font-weight: 700; color: var(--text-primary); line-height: 1.1; }
        .h-subtitle { font-size: 11px; color: var(--text-secondary); margin-top: 2px; }
        .header-right { display: flex; align-items: center; gap: 10px; }
        .date-pill {
            font-size: 12px; color: var(--text-secondary);
            border: 1px solid var(--border-color); background: var(--bg-card);
            border-radius: 10px; padding: 5px 12px;
            transition: background 0.3s, border-color 0.3s;
        }
        .icon-btn {
            border: 1px solid var(--border-color); background: var(--bg-card);
            border-radius: 10px; width: 34px; height: 34px;
            display: inline-flex; align-items: center; justify-content: center;
            color: var(--text-secondary); cursor: pointer;
            transition: background 0.2s, border-color 0.2s;
        }
        .icon-btn:hover { background: var(--menu-hover); color: var(--text-primary); }

        /* Profile dropdown */
        .profile-btn {
            position: relative; display: flex; align-items: center; gap: 8px;
            background: var(--bg-card); border: 1px solid var(--border-color);
            border-radius: 12px; padding: 4px 10px 4px 4px;
            cursor: pointer; user-select: none;
            transition: background 0.2s, border-color 0.2s, box-shadow 0.2s;
        }
        .profile-btn:hover     { border-color: #a5b4fc; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
        .profile-btn.open      { border-color: #818cf8; box-shadow: 0 0 0 3px rgba(99,102,241,0.15); }
        .p-avatar {
            width: 28px; height: 28px; border-radius: 50%;
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: #fff; display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 12px; flex-shrink: 0;
            box-shadow: 0 2px 6px rgba(99,102,241,0.35);
        }
        .p-name {
            font-size: 12px; font-weight: 600; color: var(--text-primary);
            max-width: 90px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
        }
        .p-chevron { color: var(--text-secondary); display: flex; align-items: center; transition: transform 0.22s ease, color 0.2s; }
        .profile-btn.open .p-chevron { transform: rotate(180deg); color: #6366f1; }
        .p-dropdown {
            position: absolute; top: calc(100% + 8px); right: 0; width: 210px;
            background: var(--bg-card); border: 1px solid var(--border-color);
            border-radius: 14px; box-shadow: 0 10px 30px -6px rgba(15,23,42,0.14);
            padding: 6px; opacity: 0; transform: translateY(-6px) scale(0.97);
            pointer-events: none; transition: opacity 0.18s ease, transform 0.18s ease; z-index: 200;
        }
        .p-dropdown.open { opacity: 1; transform: translateY(0) scale(1); pointer-events: auto; }
        .pd-header {
            display: flex; align-items: center; gap: 10px; padding: 10px;
            border-bottom: 1px solid var(--border-color); margin-bottom: 5px;
        }
        .pd-avatar {
            width: 36px; height: 36px; border-radius: 50%;
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: #fff; display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 14px; flex-shrink: 0;
            box-shadow: 0 3px 10px rgba(99,102,241,0.35);
        }
        .pd-name { font-size: 13px; font-weight: 700; color: var(--text-primary); line-height: 1.2; }
        .pd-role { font-size: 11px; color: var(--text-secondary); margin-top: 2px; }
        .pd-item {
            display: flex; align-items: center; gap: 9px; padding: 8px 10px; border-radius: 9px;
            font-size: 12px; font-weight: 600; color: var(--text-primary); text-decoration: none;
            cursor: pointer; transition: background 0.15s, color 0.15s;
            border: none; background: none; width: 100%; text-align: left;
        }
        .pd-item:hover { background: var(--menu-hover); color: #4f46e5; }
        .pd-item.active-page { background: var(--menu-hover); color: #4f46e5; }
        .pd-item svg { flex-shrink: 0; color: var(--text-secondary); transition: color 0.15s; }
        .pd-item:hover svg, .pd-item.active-page svg { color: #6366f1; }
        .pd-divider { height: 1px; background: var(--border-color); margin: 5px 0; }
        .pd-item.danger { color: var(--danger-text); }
        .pd-item.danger:hover { background: var(--danger-bg); }
        .pd-item.danger svg { color: #f43f5e; }

        /* ── Scrollable content area ── */
        .content-scroll {
            flex: 1; min-height: 0; overflow-y: auto; padding-right: 2px;
        }
        .content-scroll::-webkit-scrollbar { width: 5px; }
        .content-scroll::-webkit-scrollbar-track { background: transparent; }
        .content-scroll::-webkit-scrollbar-thumb { background: var(--border-color); border-radius: 99px; }

        /* ── Panel base ── */
        .panel {
            border-radius: var(--radius); border: 1px solid var(--border-color);
            background: var(--bg-panel); box-shadow: 0 8px 18px rgba(15,23,42,0.06);
            padding: 20px; transition: background 0.3s, border-color 0.3s;
        }
        .panel-title {
            font-size: 13px; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.06em; color: var(--text-muted); margin: 0 0 16px;
        }

        /* ── Avatar section ── */
        .avatar-ring {
            width: 96px; height: 96px; border-radius: 50%;
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: #fff; display: flex; align-items: center; justify-content: center;
            font-size: 36px; font-weight: 700; margin: 0 auto 14px;
            box-shadow: 0 8px 24px rgba(99,102,241,0.38);
            position: relative;
        }
        .avatar-ring .edit-overlay {
            position: absolute; inset: 0; border-radius: 50%;
            background: rgba(0,0,0,0.35); opacity: 0;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: opacity 0.2s;
        }
        .avatar-ring:hover .edit-overlay { opacity: 1; }
        .avatar-ring .edit-overlay svg { color: #fff; }
        .avatar-name { font-size: 16px; font-weight: 700; color: var(--text-primary); }
        .avatar-role {
            display: inline-block; margin-top: 6px;
            font-size: 11px; font-weight: 700; letter-spacing: 0.07em; text-transform: uppercase;
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: #fff; border-radius: 999px; padding: 3px 12px;
        }
        .avatar-meta { margin-top: 14px; display: flex; flex-direction: column; align-items: center; gap: 8px; }
        .avatar-meta-row {
            display: flex; align-items: center; gap: 8px;
            font-size: 12px; color: var(--text-secondary);
        }
        .avatar-meta-row svg { flex-shrink: 0; color: var(--text-muted); }

        /* ── Form ── */
        .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .form-group { display: flex; flex-direction: column; gap: 5px; margin-bottom: 14px; }
        .form-group:last-child { margin-bottom: 0; }
        label { font-size: 12px; font-weight: 600; color: var(--text-secondary); }
        .form-input {
            width: 100%; padding: 9px 12px;
            background: var(--bg-input); border: 1px solid var(--border-color);
            border-radius: 10px; font-size: 13px; font-weight: 500;
            color: var(--text-primary); font-family: inherit;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.3s;
            outline: none;
        }
        .form-input:focus {
            border-color: var(--border-focus);
            box-shadow: 0 0 0 3px rgba(99,102,241,0.15);
            background: var(--bg-card);
        }
        .form-input::placeholder { color: var(--text-muted); }
        .form-input:disabled { opacity: 0.5; cursor: not-allowed; }

        /* ── Password strength ── */
        .strength-bar { display: flex; gap: 4px; margin-top: 6px; }
        .strength-seg {
            height: 4px; flex: 1; border-radius: 999px;
            background: var(--border-color); transition: background 0.3s;
        }
        .strength-label { font-size: 11px; color: var(--text-muted); margin-top: 4px; }

        /* ── Buttons ── */
        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 9px 18px; border-radius: 10px;
            font-size: 13px; font-weight: 600; font-family: inherit;
            cursor: pointer; border: none; transition: all 0.2s;
        }
        .btn-primary {
            background: linear-gradient(135deg, #6366f1, #4f46e5); color: #fff;
            box-shadow: 0 4px 12px rgba(99,102,241,0.3);
        }
        .btn-primary:hover { box-shadow: 0 6px 18px rgba(99,102,241,0.45); transform: translateY(-1px); }
        .btn-ghost {
            background: var(--bg-input); color: var(--text-secondary);
            border: 1px solid var(--border-color);
        }
        .btn-ghost:hover { background: var(--menu-hover); color: var(--text-primary); }
        .btn-danger {
            background: var(--danger-bg); color: var(--danger-text);
            border: 1px solid transparent;
        }
        .btn-danger:hover { filter: brightness(0.95); }
        .btn-row { display: flex; align-items: center; gap: 8px; margin-top: 18px; }

        /* ── Alert / success banner ── */
        .alert {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 14px; border-radius: 10px;
            font-size: 12px; font-weight: 600; margin-bottom: 14px;
        }
        .alert-success { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
        .alert-error   { background: #fff1f2; color: #9f1239; border: 1px solid #fecdd3; }
        [data-theme="dark"] .alert-success { background: rgba(16,185,129,0.12); color: #6ee7b7; border-color: rgba(16,185,129,0.25); }
        [data-theme="dark"] .alert-error   { background: rgba(244,63,94,0.12);  color: #fda4af; border-color: rgba(244,63,94,0.25); }

        /* ── Danger zone ── */
        .danger-zone {
            border: 1px solid #fecdd3; border-radius: var(--radius);
            padding: 16px 20px;
            background: #fff1f2;
            transition: background 0.3s, border-color 0.3s;
            width: 100%; max-width: 640px;
        }
        [data-theme="dark"] .danger-zone { background: rgba(244,63,94,0.07); border-color: rgba(244,63,94,0.25); }
        .danger-zone-title { font-size: 13px; font-weight: 700; color: #be123c; margin: 0 0 4px; }
        [data-theme="dark"] .danger-zone-title { color: #fda4af; }
        .danger-zone-desc  { font-size: 12px; color: #9f1239; margin: 0 0 12px; }
        [data-theme="dark"] .danger-zone-desc  { color: #fda4af; opacity: 0.75; }

        /* ── Section divider inside panel ── */
        .inner-divider {
            height: 1px; background: var(--border-color); margin: 20px 0;
        }
    </style>
    <script>
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', savedTheme);
    </script>
</head>
<body>

@php
    $user = auth()->user();
    $userName    = $user->name     ?? 'User';
    $userEmail   = $user->email    ?? '';
    $userInitial = strtoupper(substr($userName, 0, 1));
    $userRole    = ucfirst($user->role ?? 'User');

    $role = $user->role ?? 'admin';
    $dashRoute = match($role) {
        'manager'       => 'managerdashboard',
        'documentation' => 'documentationdashboard',
        'marketing'     => 'marketingdashboard',
        default         => 'admindashboard',
    };
    $sidebarModules = match($role) {
        'manager' => [
            ['label' => 'Dashboard',      'route' => 'managerdashboard'],
            ['label' => 'Customers',       'route' => 'customers.index'],
            ['label' => 'Properties',      'route' => 'properties.index'],
            ['label' => 'Payments',        'route' => 'payments.index'],
            ['label' => 'Documents',       'route' => 'documents.index'],
            ['label' => 'Title Transfers', 'route' => 'title-transfers.index'],
            ['label' => 'Construction',    'route' => 'construction-projects.index'],
            ['label' => 'Employees',       'route' => 'employees.index'],
            ['label' => 'Attendance',      'route' => 'attendance-records.index'],
            ['label' => 'Payroll',         'route' => 'payrolls.index'],
            ['label' => 'Benefits',        'route' => 'benefits.index'],
            ['label' => 'Reports',         'route' => 'reports.index'],
        ],
        'documentation' => [
            ['label' => 'Dashboard',      'route' => 'documentationdashboard'],
            ['label' => 'Documents',      'route' => 'documents.index'],
            ['label' => 'Title Transfers','route' => 'title-transfers.index'],
            ['label' => 'Customers',      'route' => 'customers.index'],
            ['label' => 'Properties',     'route' => 'properties.index'],
            ['label' => 'Reports',        'route' => 'reports.index'],
        ],
        default => [
            ['label' => 'Dashboard',  'route' => 'admindashboard'],
            ['label' => 'Customers',  'route' => 'customers.index'],
            ['label' => 'Properties', 'route' => 'properties.index'],
            ['label' => 'Payments',   'route' => 'payments.index'],
            ['label' => 'Employees',  'route' => 'employees.index'],
            ['label' => 'Payroll',    'route' => 'payrolls.index'],
            ['label' => 'Benefits',   'route' => 'benefits.index'],
            ['label' => 'User Roles', 'route' => 'user-roles.index'],
            ['label' => 'Audit Logs', 'route' => 'audit-logs.index'],
            ['label' => 'Reports',    'route' => 'reports.index'],
        ],
    };
@endphp

<div class="shell">
    {{-- ── Sidebar ── --}}
    <aside class="sidebar">
        <div class="brand">
            <img src="{{ asset('images/midland.jpg') }}" alt="Midland Valley Homes Logo">
            <h1>Midland Valley Homes</h1>
        </div>
        <nav class="menu">
            @foreach ($sidebarModules as $item)
                <a href="{{ route($item['route']) }}" class="{{ request()->routeIs($item['route']) ? 'active' : '' }}">
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>
    </aside>

    {{-- ── Main ── --}}
    <main class="main">

        {{-- ── Header ── --}}
        <header class="header">
            <div>
                <div class="h-title">My Profile</div>
                <div class="h-subtitle">Manage your account information</div>
            </div>
            <div class="header-right">
                <div class="date-pill">{{ now()->format('l, M j, Y') }}</div>

                <button type="button" class="icon-btn" id="themeToggle" title="Toggle Dark Mode">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z"/>
                    </svg>
                </button>

                <div class="profile-btn" id="profileBtn">
                    <div class="p-avatar">{{ $userInitial }}</div>
                    <span class="p-name">{{ $userName }}</span>
                    <span class="p-chevron">
                        <svg width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>
                        </svg>
                    </span>
                    <div class="p-dropdown" id="profileMenu">
                        <div class="pd-header">
                            <div class="pd-avatar">{{ $userInitial }}</div>
                            <div>
                                <div class="pd-name">{{ $userName }}</div>
                                <div class="pd-role">{{ $userRole }}</div>
                            </div>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="pd-item active-page">
                            <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/></svg>
                            My Profile
                        </a>
                        <a href="{{ route($dashRoute) }}" class="pd-item">
                            <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M8 3.293l6 6V13.5a.5.5 0 0 1-.5.5h-4v-3H6.5v3h-4a.5.5 0 0 1-.5-.5V9.293l6-6zm-6.707 6L8 2.586l6.707 6.707A1 1 0 0 0 16 8.586V10l-8-8-8 8v-1.414a1 1 0 0 0 1.293.707z"/></svg>
                            Dashboard
                        </a>
                        <div class="pd-divider"></div>
                        <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                            @csrf
                            <button type="submit" class="pd-item danger">
                                <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/><path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/></svg>
                                Log out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        {{-- ── Scrollable body ── --}}
        <div class="content-scroll">

            {{-- Flash messages --}}
            @if (session('status') === 'profile-updated')
                <div class="alert alert-success" style="max-width:640px; margin: 0 auto var(--gap);">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/></svg>
                    Profile updated successfully.
                </div>
            @endif
            @if (session('status') === 'password-updated')
                <div class="alert alert-success" style="max-width:640px; margin: 0 auto var(--gap);">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/></svg>
                    Password changed successfully.
                </div>
            @endif

            {{-- ── Centered single-column layout ── --}}
            <div style="display:flex; flex-direction:column; align-items:center; gap:var(--gap); padding-bottom: 20px;">

                {{-- ── PANEL 1: Avatar + Personal Information (merged) ── --}}
                <div class="panel" style="width:100%; max-width:640px;">

                    {{-- Avatar section --}}
                    <div style="text-align:center; padding-bottom:20px; border-bottom:1px solid var(--border-color); margin-bottom:20px;">
                        <div class="avatar-ring">
                            {{ $userInitial }}
                            <div class="edit-overlay" title="Change photo">
                                <svg width="22" height="22" fill="currentColor" viewBox="0 0 16 16"><path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/><path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/></svg>
                            </div>
                        </div>
                        <div class="avatar-name">{{ $userName }}</div>
                        <div class="avatar-role">{{ $userRole }}</div>
                        <div class="avatar-meta">
                            <div class="avatar-meta-row">
                                <svg width="13" height="13" fill="currentColor" viewBox="0 0 16 16"><path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555zM0 4.697v7.104l5.803-3.558L0 4.697zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757zm3.436-.586L16 11.801V4.697l-5.803 3.546z"/></svg>
                                {{ $userEmail ?: 'No email set' }}
                            </div>
                            <div class="avatar-meta-row">
                                <svg width="13" height="13" fill="currentColor" viewBox="0 0 16 16"><path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/></svg>
                                Member since {{ $user->created_at?->format('M Y') ?? 'N/A' }}
                            </div>
                        </div>
                    </div>

                    {{-- Personal Information form --}}
                    <p class="panel-title">Personal Information</p>

                    @if ($errors->any() && !$errors->has('current_password'))
                        <div class="alert alert-error">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/></svg>
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf @method('PATCH')

                        <div class="form-grid-2">
                            <div class="form-group">
                                <label for="name">Full Name</label>
                                <input id="name" name="name" type="text" class="form-input"
                                       value="{{ old('name', $user->name) }}" required autocomplete="name"
                                       placeholder="Your full name">
                                @error('name')<span style="font-size:11px; color:#f43f5e; margin-top:3px;">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input id="email" name="email" type="email" class="form-input"
                                       value="{{ old('email', $user->email) }}" required autocomplete="email"
                                       placeholder="you@example.com">
                                @error('email')<span style="font-size:11px; color:#f43f5e; margin-top:3px;">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="role_display">Role</label>
                            <input id="role_display" type="text" class="form-input"
                                   value="{{ $userRole }}" disabled>
                        </div>

                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                            <div class="alert alert-error" style="margin-bottom: 12px;">
                                <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/></svg>
                                Email unverified.
                                <a href="{{ route('verification.send') }}" style="color:inherit; text-decoration:underline; margin-left:4px;">Resend verification</a>
                            </div>
                        @endif

                        <div class="btn-row">
                            <button type="submit" class="btn btn-primary">
                                <svg width="13" height="13" fill="currentColor" viewBox="0 0 16 16"><path d="M2 1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H9.5a1 1 0 0 0-1 1v7.293l2.646-2.647a.5.5 0 0 1 .708.708l-3.5 3.5a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L7.5 9.293V2a2 2 0 0 1 2-2H14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h2.5a.5.5 0 0 1 0 1H2z"/></svg>
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>

                {{-- ── PANEL 2: Change Password ── --}}
                <div class="panel" style="width:100%; max-width:640px;">
                    <p class="panel-title">Change Password</p>

                    @if ($errors->has('current_password') || $errors->has('password'))
                        <div class="alert alert-error">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/></svg>
                            {{ $errors->first('current_password') ?: $errors->first('password') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf @method('PUT')

                        <div class="form-grid-2">
                            <div class="form-group" style="grid-column: 1 / -1;">
                                <label for="current_password">Current Password</label>
                                <input id="current_password" name="current_password" type="password"
                                       class="form-input" autocomplete="current-password"
                                       placeholder="Enter current password">
                            </div>
                            <div class="form-group">
                                <label for="password">New Password</label>
                                <input id="password" name="password" type="password"
                                       class="form-input" autocomplete="new-password"
                                       placeholder="Min. 8 characters">
                                <div class="strength-bar" id="strengthBar">
                                    <div class="strength-seg" id="s1"></div>
                                    <div class="strength-seg" id="s2"></div>
                                    <div class="strength-seg" id="s3"></div>
                                    <div class="strength-seg" id="s4"></div>
                                </div>
                                <div class="strength-label" id="strengthLabel"></div>
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation">Confirm New Password</label>
                                <input id="password_confirmation" name="password_confirmation" type="password"
                                       class="form-input" autocomplete="new-password"
                                       placeholder="Repeat new password">
                            </div>
                        </div>

                        <div class="btn-row">
                            <button type="submit" class="btn btn-primary">
                                <svg width="13" height="13" fill="currentColor" viewBox="0 0 16 16"><path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/></svg>
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>

                {{-- ── Danger Zone ── --}}
                <div class="danger-zone">
                    <p class="danger-zone-title">Delete Account</p>
                    <p class="danger-zone-desc">Once your account is deleted, all data will be permanently removed and cannot be recovered.</p>
                    <button type="button" class="btn btn-danger" id="deleteBtn">
                        <svg width="13" height="13" fill="currentColor" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/><path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/></svg>
                        Delete My Account
                    </button>

                    <div id="deleteModal" style="display:none; margin-top:14px; padding:14px; background:var(--bg-card); border:1px solid var(--border-color); border-radius:12px;">
                        <p style="font-size:13px; font-weight:600; color:var(--text-primary); margin:0 0 10px;">Type your password to confirm deletion:</p>
                        <form method="POST" action="{{ route('profile.destroy') }}">
                            @csrf @method('DELETE')
                            <div class="form-group" style="margin-bottom:10px;">
                                <input name="password" type="password" class="form-input" placeholder="Your current password" required>
                            </div>
                            <div style="display:flex; gap:8px;">
                                <button type="submit" class="btn btn-danger">Confirm Delete</button>
                                <button type="button" class="btn btn-ghost" id="cancelDelete">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>{{-- end centered column --}}
        </div>{{-- end content-scroll --}}
    </main>
</div>

<script>
    /* Theme toggle */
    document.getElementById('themeToggle').addEventListener('click', () => {
        const t = document.documentElement.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
        document.documentElement.setAttribute('data-theme', t);
        localStorage.setItem('theme', t);
    });

    /* Profile dropdown */
    const profileBtn  = document.getElementById('profileBtn');
    const profileMenu = document.getElementById('profileMenu');
    profileBtn.addEventListener('click', e => {
        e.stopPropagation();
        const open = profileMenu.classList.toggle('open');
        profileBtn.classList.toggle('open', open);
    });
    document.addEventListener('click', () => {
        profileMenu.classList.remove('open');
        profileBtn.classList.remove('open');
    });

    /* Password strength meter */
    const pwdInput      = document.getElementById('password');
    const segs          = [document.getElementById('s1'), document.getElementById('s2'), document.getElementById('s3'), document.getElementById('s4')];
    const strengthLabel = document.getElementById('strengthLabel');
    const levels        = [
        { color: '#f43f5e', label: 'Weak' },
        { color: '#fb923c', label: 'Fair' },
        { color: '#facc15', label: 'Good' },
        { color: '#22c55e', label: 'Strong' },
    ];
    function scorePassword(p) {
        let s = 0;
        if (p.length >= 8)  s++;
        if (/[A-Z]/.test(p)) s++;
        if (/[0-9]/.test(p)) s++;
        if (/[^A-Za-z0-9]/.test(p)) s++;
        return s;
    }
    if (pwdInput) {
        pwdInput.addEventListener('input', () => {
            const val   = pwdInput.value;
            const score = val.length ? scorePassword(val) : 0;
            segs.forEach((seg, i) => {
                seg.style.background = i < score ? levels[score - 1].color : 'var(--border-color)';
            });
            strengthLabel.textContent = val.length ? levels[score - 1].label : '';
        });
    }

    /* Delete account toggle */
    document.getElementById('deleteBtn').addEventListener('click', () => {
        document.getElementById('deleteModal').style.display = 'block';
    });
    document.getElementById('cancelDelete').addEventListener('click', () => {
        document.getElementById('deleteModal').style.display = 'none';
    });
</script>
</body>
</html>