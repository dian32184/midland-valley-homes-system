<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Midland Valley Homes</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    
    <!-- Initialize Theme -->
    <script>
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', savedTheme);
    </script>
    
    @stack('styles')
</head>
<body>
@php
    $user = auth()->user();
    $userRole = strtolower((string) ($user->role ?? ''));
    $userName = $user ? ($user->name ?? 'User') : 'Guest';
    $userEmail = $user ? ($user->email ?? '') : '';
    $userInitial = strtoupper(substr($userName, 0, 1));
    
    // Sidebar logic extracted from previous inline code
    $sidebarModules = match (true) {
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
        $userRole === 'marketing' => [
            ['label' => 'Dashboard', 'route' => 'marketingdashboard'],
            ['label' => 'Customers', 'route' => 'customers.index'],
            ['label' => 'Properties', 'route' => 'properties.index'],
            ['label' => 'Reservations', 'route' => 'reservations.index'],
            ['label' => 'Payments', 'route' => 'payments.index'],
        ],
        default => [
            ['label' => 'Dashboard', 'route' => 'managerdashboard'],
            ['label' => 'Customers', 'route' => 'customers.index'],
            ['label' => 'Properties', 'route' => 'properties.index'],
            ['label' => 'Payments', 'route' => 'payments.index'],
            ['label' => 'Attendance', 'route' => 'attendance-records.index'],
            ['label' => 'Payroll', 'route' => 'payrolls.index'],
            ['label' => 'Benefits', 'route' => 'benefits.index'],
            ['label' => 'Reports', 'route' => 'reports.index'],
        ],
    };
@endphp

<div class="shell">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="brand">
            <img src="{{ asset('images/midland.jpg') }}" alt="Midland Valley Homes Logo">
            <h1>Midland Valley Homes</h1>
        </div>
        <nav class="menu">
            @foreach ($sidebarModules as $item)
                @php
                    $isActive = request()->routeIs($item['route']);
                    // Also check wildcard if it's the index page (e.g. customers.*)
                    if (!$isActive && str_ends_with($item['route'], '.index')) {
                        $baseRoute = str_replace('.index', '', $item['route']);
                        $isActive = request()->routeIs($baseRoute . '.*');
                    }
                @endphp
                <a href="{{ route($item['route']) }}" class="{{ $isActive ? 'active' : '' }}">{{ $item['label'] }}</a>
            @endforeach
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main">
        <!-- New Sleek Header -->
        <header class="header">
            <div class="h-brand">
                <span>101 Repair Service</span> <!-- Example brand name from screenshot, you can change this! -->
                <div class="h-subtitle">Welcome, {{ $userName }} &bull; {{ now()->format('l, M j, Y') }}</div>
            </div>
            
            <div class="header-actions">
                <!-- Font Size Toggle (Placeholder) -->
                <button type="button" class="header-icon-btn" title="Adjust Font Size">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M1 12h2l1.5-4h5l1.5 4h2L9 2H7l-6 10zm4.3-6l2-5.5h1.4l2 5.5H5.3z"/></svg>
                </button>
                
                <!-- Dark Mode Toggle -->
                <button type="button" class="header-icon-btn" id="themeToggle" title="Toggle Dark Mode">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z"/></svg>
                </button>
                
                <!-- Notifications -->
                <button type="button" class="header-icon-btn" title="Notifications">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zm.995-14.901a1 1 0 1 0-1.99 0A5.002 5.002 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901z"/></svg>
                </button>

                <!-- Profile Dropdown -->
                <div class="user-profile" id="profileDropdownBtn">
                    <div class="user-details">
                        <span class="user-name">{{ $userName }}</span>
                        <span class="user-email">{{ $userEmail }}</span>
                    </div>
                    <div class="user-avatar">{{ $userInitial }}</div>
                    
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/></svg>

                    <!-- Dropdown Menu -->
                    <div class="dropdown-menu" id="profileMenu">
                        <a href="{{ route('profile.edit') }}" class="dropdown-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/></svg>
                            View Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                            @csrf
                            <button type="submit" class="dropdown-item" style="width:100%; border:none; background:none; text-align:left; cursor:pointer;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/><path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/></svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Specific Content Wrapper -->
        @yield('content')
        
    </main>
</div>

<!-- Global Scripts -->
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
        profileMenu.classList.toggle('show');
        e.stopPropagation();
    });
    document.addEventListener('click', () => {
        profileMenu.classList.remove('show');
    });
</script>

@stack('scripts')
</body>
</html>
