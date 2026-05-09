<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Property Management</title>
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
        .main { flex: 1; display: flex; flex-direction: column; min-width: 0; padding: 10px; gap: 10px; background: linear-gradient(165deg, #f7f9ff 0%, #f1f5ff 50%, #edf4ff 100%); }
        .header { height: 56px; display: flex; align-items: center; justify-content: space-between; padding: 0 14px; border: 1px solid #dfe6f5; border-radius: 14px; background: linear-gradient(135deg, rgba(255,255,255,0.9), rgba(240,246,255,0.86)); }
        .h-title { font-size: 18px; font-weight: 700; color: #0f172a; }
        .top-actions { display: flex; gap: 8px; align-items: center; }
        .btn { border: 1px solid #c7d2fe; background: #fff; color: #3730a3; border-radius: 10px; padding: 8px 12px; text-decoration: none; font-size: 12px; font-weight: 600; cursor: pointer; }
        .btn-primary { background: linear-gradient(135deg, #6366f1, #4f46e5); color: #fff; border-color: transparent; box-shadow: 0 4px 14px rgba(79, 70, 229, 0.32); }
        .content { flex: 1; min-height: 0; overflow: auto; border-radius: var(--radius); border: 1px solid #e2e8f0; background: linear-gradient(160deg, rgba(255,255,255,0.94), rgba(244,249,255,0.9)); box-shadow: 0 8px 18px rgba(15, 23, 42, 0.06); padding: 14px; }
        .stats { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: var(--gap); margin-bottom: 12px; }
        .card { border-radius: 14px; padding: 10px; color: #fff; }
        .c1 { background: linear-gradient(135deg, #73d9ef, #5aa8f2); }
        .c2 { background: linear-gradient(135deg, #9cb5ff, #6d90f6); }
        .c3 { background: linear-gradient(135deg, #f5add1, #ea7fbe); }
        .c4 { background: linear-gradient(135deg, #d2bcff, #a88ef0); }
        .flash { margin-bottom: 12px; padding: 10px 12px; border-radius: 10px; font-size: 13px; background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        th, td { padding: 10px 8px; text-align: left; border-bottom: 1px solid #e2e8f0; color: #334155; vertical-align: top; }
        th { font-size: 12px; text-transform: uppercase; color: #64748b; }
        .pill { font-size: 11px; padding: 4px 8px; border-radius: 999px; background: #eef2ff; color: #4338ca; border: 1px solid #c7d2fe; display: inline-block; }
        .pill-corner { background: #fef3c7; color: #92400e; border-color: #fcd34d; }
        .pill-available { background: #ecfdf5; color: #065f46; border-color: #a7f3d0; }
        .pill-reserved  { background: #fff7ed; color: #9a3412; border-color: #fed7aa; }
        .pill-sold      { background: #fef2f2; color: #991b1b; border-color: #fecaca; }
        .pill-construction { background: #f0f9ff; color: #075985; border-color: #bae6fd; }
        .muted { color: #64748b; font-size: 12px; }
        .row-actions { display: flex; gap: 8px; }

        /* ── Icon buttons ── */
        .icon-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            background: #fff;
            cursor: pointer;
            transition: background 0.15s, border-color 0.15s, color 0.15s;
        }
        .edit-icon   { color: #4338ca; }
        .edit-icon:hover  { background: #eef2ff; border-color: #c7d2fe; }
        .delete-icon { color: #be123c; }
        .delete-icon:hover { background: #fff1f2; border-color: #fecaca; }

        /* ── Modals ── */
        .modal-backdrop { position: fixed; inset: 0; background: rgba(15, 23, 42, 0.5); backdrop-filter: blur(6px); display: none; align-items: center; justify-content: center; z-index: 50; padding: 16px; }
        .modal-backdrop.show { display: flex; }
        .modal-card { width: min(700px, 96vw); max-height: min(90vh, 860px); overflow: hidden; display: flex; flex-direction: column; border-radius: 20px; border: 1px solid rgba(226, 232, 240, 0.95); background: linear-gradient(165deg, #ffffff 0%, #f8fafc 42%, #f1f5f9 100%); box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.28), 0 0 0 1px rgba(255, 255, 255, 0.65) inset; }
        .modal-head { flex-shrink: 0; display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 18px 22px; border-bottom: 1px solid #e2e8f0; background: linear-gradient(135deg, rgba(99, 102, 241, 0.12) 0%, rgba(139, 92, 246, 0.06) 55%, rgba(248, 250, 252, 0.9) 100%); }
        .modal-title-wrap { display: flex; flex-direction: column; gap: 4px; }
        .modal-title { font-size: 19px; font-weight: 800; letter-spacing: -0.03em; color: #0f172a; margin: 0; line-height: 1.2; }
        .modal-subtitle { font-size: 12px; font-weight: 500; color: #64748b; margin: 0; }
        .modal-head-actions { display: flex; align-items: center; gap: 8px; }
        .close-btn { border: 1px solid #e2e8f0; background: rgba(255, 255, 255, 0.85); border-radius: 11px; padding: 8px 14px; cursor: pointer; font-weight: 600; font-size: 13px; color: #475569; }
        .close-btn:hover { background: #fff; border-color: #cbd5e1; color: #0f172a; }
        .modal-body { overflow: auto; flex: 1; min-height: 0; padding: 18px 22px 22px; -webkit-overflow-scrolling: touch; }

        /* ── Form ── */
        .form-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px 14px; }
        .form-grid input, .form-grid select, .form-grid textarea { width: 100%; border: 1px solid #cbd5e1; border-radius: 11px; padding: 10px 12px; font-size: 13px; background: #fff; transition: border-color 0.15s ease, box-shadow 0.15s ease; }
        .form-grid input:hover, .form-grid select:hover, .form-grid textarea:hover { border-color: #94a3b8; }
        .form-grid input:focus, .form-grid select:focus, .form-grid textarea:focus { outline: none; border-color: #818cf8; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.18); }
        .form-grid input[readonly] { background: #f8fafc; color: #64748b; cursor: not-allowed; }
        .form-grid select { appearance: none; -webkit-appearance: none; padding-right: 38px; background-color: #fff; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='none' stroke='%2364748b' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round' d='M2 4l4 4 4-4'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: calc(100% - 12px) 50%; background-size: 12px 12px; }
        .field label { display: block; font-size: 11px; font-weight: 700; margin-bottom: 5px; color: #475569; letter-spacing: 0.01em; }
        .field.hidden { display: none !important; }
        .span-2 { grid-column: span 2; }
        .section-title { grid-column: span 2; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: #6366f1; margin-top: 10px; padding-top: 14px; border-top: 1px solid #e2e8f0; }
        .section-title:first-of-type { margin-top: 0; padding-top: 0; border-top: none; }
        .form-actions { display: flex; gap: 8px; justify-content: flex-end; margin-top: 4px; padding-top: 18px; border-top: 1px solid #e2e8f0; }
    </style>
</head>
<body>
@php
    $total     = $properties->total();
    $available = \App\Models\Property::where('status', 'available')->count();
    $reserved  = \App\Models\Property::where('status', 'reserved')->count();
    $sold      = \App\Models\Property::where('status', 'sold')->count();
    $userRole  = strtolower((string) (auth()->user()->role ?? ''));

    $streets = \App\Models\Property::STREETS;

    $statusPills = [
        'available'          => 'pill-available',
        'reserved'           => 'pill-reserved',
        'sold'               => 'pill-sold',
        'under_construction' => 'pill-construction',
        'turned_over'        => 'pill',
    ];
    $statusLabels = [
        'available'          => 'Available',
        'reserved'           => 'Reserved',
        'sold'               => 'Sold',
        'under_construction' => 'Under Construction',
        'turned_over'        => 'Turned Over',
    ];

    $sidebarModules = match (true) {
        $userRole === 'documentation' => [
            ['label' => 'Dashboard',       'route' => 'documentationdashboard'],
            ['label' => 'Documents',       'route' => 'documents.index'],
            ['label' => 'Title Transfers', 'route' => 'title-transfers.index'],
            ['label' => 'Customers',       'route' => 'customers.index'],
            ['label' => 'Properties',      'route' => 'properties.index'],
            ['label' => 'Reports',         'route' => 'reports.index'],
        ],
        $userRole === 'admin' => [
            ['label' => 'Dashboard',   'route' => 'admindashboard'],
            ['label' => 'Customers',   'route' => 'customers.index'],
            ['label' => 'Properties',  'route' => 'properties.index'],
            ['label' => 'Payments',    'route' => 'payments.index'],
            ['label' => 'Employees',   'route' => 'employees.index'],
            ['label' => 'Payroll',     'route' => 'payrolls.index'],
            ['label' => 'Benefits',    'route' => 'benefits.index'],
            ['label' => 'User Roles',  'route' => 'user-roles.index'],
            ['label' => 'Audit Logs',  'route' => 'audit-logs.index'],
            ['label' => 'Reports',     'route' => 'reports.index'],
        ],
        $userRole === 'marketing' => [
            ['label' => 'Dashboard',    'route' => 'marketingdashboard'],
            ['label' => 'Customers',    'route' => 'customers.index'],
            ['label' => 'Properties',   'route' => 'properties.index'],
            ['label' => 'Reservations', 'route' => 'reservations.index'],
            ['label' => 'Payments',     'route' => 'payments.index'],
        ],
        $userRole === 'manager' => [
            ['label' => 'Dashboard',       'route' => 'managerdashboard'],
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
        default => [
            ['label' => 'Dashboard',    'route' => 'marketingdashboard'],
            ['label' => 'Customers',    'route' => 'customers.index'],
            ['label' => 'Properties',   'route' => 'properties.index'],
            ['label' => 'Reservations', 'route' => 'reservations.index'],
            ['label' => 'Payments',     'route' => 'payments.index'],
        ],
    };
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
            <div class="h-title">Property Module</div>
            <div class="top-actions">
                <button type="button" class="btn btn-primary" id="openCreatePropertyModal">+ Add Property</button>
            </div>
        </header>

        <section class="content">
            @if(session('success'))
                <div class="flash">{{ session('success') }}</div>
            @endif

            <div class="stats">
                <div class="card c1"><strong>Total Properties</strong><div>{{ $total }}</div></div>
                <div class="card c2"><strong>Available</strong><div>{{ $available }}</div></div>
                <div class="card c3"><strong>Reserved</strong><div>{{ $reserved }}</div></div>
                <div class="card c4"><strong>Sold</strong><div>{{ $sold }}</div></div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Block / Lot</th>
                        <th>Location</th>
                        <th>Model / Lot Type</th>
                        <th>Lot / Floor Area</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($properties as $property)
                        <tr>
                            <td>
                                <strong>Blk {{ $property->block_number }}, Lot {{ $property->lot_number }}</strong>
                            </td>
                            <td>
                                {{ $property->street_name ?: '—' }}<br>
                                <span class="muted">{{ $property->subdivision ?? 'Midland Valley Homes' }}</span>
                            </td>
                            <td>
                                <span class="pill">{{ ucfirst($property->house_model ?? 'diamond') }}</span>
                                @if($property->lot_type === 'corner_lot')
                                    <span class="pill pill-corner">Corner Lot</span>
                                @endif
                            </td>
                            <td>
                                {{ $property->lot_size ?: '—' }}<br>
                                <span class="muted">{{ $property->floor_area ?: '—' }}</span>
                            </td>
                            <td>PHP {{ number_format((float) $property->price, 2) }}</td>
                            <td>
                                <span class="pill {{ $statusPills[$property->status] ?? 'pill' }}">
                                    {{ $statusLabels[$property->status] ?? ucfirst($property->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="row-actions">
                                    {{-- EDIT --}}
                                    <button
                                        type="button"
                                        class="icon-btn edit-icon editPropertyBtn"
                                        aria-label="Edit property"
                                        data-id="{{ $property->id }}"
                                        data-street_name="{{ $property->street_name }}"
                                        data-subdivision="{{ $property->subdivision }}"
                                        data-barangay="{{ $property->barangay }}"
                                        data-city="{{ $property->city }}"
                                        data-province="{{ $property->province }}"
                                        data-zip_code="{{ $property->zip_code }}"
                                        data-block_number="{{ $property->block_number }}"
                                        data-lot_number="{{ $property->lot_number }}"
                                        data-house_model="{{ $property->house_model }}"
                                        data-lot_type="{{ $property->lot_type }}"
                                        data-house_type="{{ $property->house_type }}"
                                        data-price="{{ $property->price }}"
                                        data-lot_size="{{ $property->lot_size }}"
                                        data-floor_area="{{ $property->floor_area }}"
                                        data-status="{{ $property->status }}"
                                        data-available_at="{{ optional($property->available_at)->format('Y-m-d') }}"
                                        data-description="{{ $property->description }}"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4Z"/></svg>
                                    </button>

                                    {{-- DELETE — triggers confirmation modal --}}
                                    <button
                                        type="button"
                                        class="icon-btn delete-icon deletePropertyBtn"
                                        aria-label="Delete property"
                                        data-id="{{ $property->id }}"
                                        data-label="Blk {{ $property->block_number }}, Lot {{ $property->lot_number }} — {{ $property->street_name ?: 'Midland Valley Homes' }}"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7">No property records yet.</td></tr>
                    @endforelse
                </tbody>
            </table>

            <div style="margin-top:12px;">{{ $properties->links() }}</div>
        </section>
    </main>
</div>

{{-- ══════════════════════════════════════════════════════════
     CREATE MODAL
══════════════════════════════════════════════════════════ --}}
<div class="modal-backdrop" id="createPropertyModal" aria-hidden="true">
    <div class="modal-card" role="dialog" aria-labelledby="createPropertyTitle">
        <div class="modal-head">
            <div class="modal-title-wrap">
                <h2 class="modal-title" id="createPropertyTitle">Add property</h2>
                <p class="modal-subtitle">Location, model, lot type &amp; pricing</p>
            </div>
            <div class="modal-head-actions">
                <button type="button" class="close-btn" id="closeCreatePropertyModal">Close</button>
            </div>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('properties.store') }}" class="form-grid" id="createPropertyForm">
                @csrf

                <div class="section-title">Location</div>
                <div class="field">
                    <label>Street name</label>
                    <select name="street_name" id="createStreetName" required>
                        <option value="">Select street</option>
                        @foreach($streets as $street)
                            <option value="{{ $street }}" @selected(old('street_name') === $street)>{{ $street }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Subdivision</label>
                    <input name="subdivision" id="createSubdivision" value="Midland Valley Homes" readonly>
                </div>
                <div class="field">
                    <label>Barangay</label>
                    <input name="barangay" id="createBarangay" value="{{ old('barangay', 'Purok 4, Brgy. Kalasungay') }}" readonly>
                </div>
                <div class="field">
                    <label>City / Municipality</label>
                    <input name="city" id="createCity" value="{{ old('city', 'Malaybalay City') }}" readonly>
                </div>
                <div class="field">
                    <label>Province</label>
                    <input name="province" id="createProvince" value="{{ old('province', 'Bukidnon') }}" readonly>
                </div>
                <div class="field">
                    <label>ZIP code</label>
                    <input name="zip_code" id="createZipCode" value="{{ old('zip_code', '8700') }}" readonly>
                </div>

                <div class="section-title">Unit Details</div>
                <div class="field">
                    <label>House model</label>
                    <select name="house_model" id="createHouseModel" required>
                        <option value="ruby"    @selected(old('house_model') === 'ruby')>Ruby</option>
                        <option value="diamond" @selected(old('house_model', 'diamond') === 'diamond')>Diamond</option>
                        <option value="custom"  @selected(old('house_model') === 'custom')>Custom</option>
                    </select>
                </div>
                <div class="field">
                    <label>Lot type</label>
                    <select name="lot_type" id="createLotType" required>
                        <option value="regular"    @selected(old('lot_type', 'regular') === 'regular')>Regular</option>
                        <option value="corner_lot" @selected(old('lot_type') === 'corner_lot')>Corner Lot</option>
                    </select>
                </div>
                <div class="field">
                    <label>Block number</label>
                    <input name="block_number" id="createBlockNumber" value="{{ old('block_number') }}" placeholder="e.g. 1" required>
                </div>
                <div class="field">
                    <label>Lot number</label>
                    <input name="lot_number" id="createLotNumber" value="{{ old('lot_number') }}" placeholder="e.g. 12" required>
                </div>

                <div class="section-title">Specifications &amp; Pricing</div>
                <div class="field">
                    <label>Lot size</label>
                    <input name="lot_size" id="createLotSize" value="{{ old('lot_size', '120 sqm') }}" placeholder="e.g. 120 sqm">
                </div>
                <div class="field">
                    <label>Floor area</label>
                    <input name="floor_area" id="createFloorArea" value="{{ old('floor_area', '35 sqm') }}" placeholder="e.g. 35 sqm">
                </div>
                <div class="field">
                    <label>Price (PHP)</label>
                    <input name="price" id="createPrice" type="number" step="0.01" value="{{ old('price', 1500000) }}" placeholder="0.00" required>
                </div>
                <div class="field">
                    <label>Status</label>
                    <select name="status" required>
                        @foreach(['available' => 'Available', 'reserved' => 'Reserved', 'sold' => 'Sold', 'under_construction' => 'Under Construction', 'turned_over' => 'Turned Over'] as $val => $label)
                            <option value="{{ $val }}" @selected(old('status', 'available') === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Available at</label>
                    <input name="available_at" type="date" value="{{ old('available_at') }}">
                </div>
                <div class="field span-2">
                    <label>Description</label>
                    <textarea name="description" rows="2" placeholder="General property description…">{{ old('description') }}</textarea>
                </div>

                <div class="span-2 form-actions">
                    <button type="button" class="btn" id="cancelCreatePropertyModal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save property</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     EDIT MODAL
══════════════════════════════════════════════════════════ --}}
<div class="modal-backdrop" id="editPropertyModal" aria-hidden="true">
    <div class="modal-card" role="dialog" aria-labelledby="editPropertyTitle">
        <div class="modal-head">
            <div class="modal-title-wrap">
                <h2 class="modal-title" id="editPropertyTitle">Edit property</h2>
                <p class="modal-subtitle">Update location, model, lot type &amp; pricing</p>
            </div>
            <div class="modal-head-actions">
                <button type="button" class="close-btn" id="closeEditPropertyModal">Close</button>
            </div>
        </div>
        <div class="modal-body">
            <form method="POST" id="editPropertyForm" class="form-grid">
                @csrf
                @method('PUT')

                <div class="section-title">Location</div>
                <div class="field">
                    <label>Street name</label>
                    <select name="street_name" id="editStreetName" required>
                        <option value="">Select street</option>
                        @foreach($streets as $street)
                            <option value="{{ $street }}">{{ $street }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Subdivision</label>
                    <input name="subdivision" id="editSubdivision" value="Midland Valley Homes" readonly>
                </div>
                <div class="field">
                    <label>Barangay</label>
                    <input name="barangay" id="editBarangay" placeholder="e.g. Brgy. San Antonio">
                </div>
                <div class="field">
                    <label>City / Municipality</label>
                    <input name="city" id="editCity" placeholder="e.g. General Trias">
                </div>
                <div class="field">
                    <label>Province</label>
                    <input name="province" id="editProvince" placeholder="e.g. Cavite">
                </div>
                <div class="field">
                    <label>ZIP code</label>
                    <input name="zip_code" id="editZipCode" placeholder="e.g. 4107">
                </div>

                <div class="section-title">Unit Details</div>
                <div class="field">
                    <label>House model</label>
                    <select name="house_model" id="editHouseModel" required>
                        <option value="ruby">Ruby</option>
                        <option value="diamond">Diamond</option>
                        <option value="custom">Custom</option>
                    </select>
                </div>
                <div class="field">
                    <label>Lot type</label>
                    <select name="lot_type" id="editLotType" required>
                        <option value="regular">Regular</option>
                        <option value="corner_lot">Corner Lot</option>
                    </select>
                </div>
                <div class="field">
                    <label>Block number</label>
                    <input name="block_number" id="editBlockNumber" placeholder="e.g. 1" required>
                </div>
                <div class="field">
                    <label>Lot number</label>
                    <input name="lot_number" id="editLotNumber" placeholder="e.g. 12" required>
                </div>

                <div class="section-title">Specifications &amp; Pricing</div>
                <div class="field">
                    <label>Lot size</label>
                    <input name="lot_size" id="editLotSize" placeholder="e.g. 120 sqm">
                </div>
                <div class="field">
                    <label>Floor area</label>
                    <input name="floor_area" id="editFloorArea" placeholder="e.g. 35 sqm">
                </div>
                <div class="field">
                    <label>Price (PHP)</label>
                    <input name="price" id="editPrice" type="number" step="0.01" placeholder="0.00" required>
                </div>
                <div class="field">
                    <label>Status</label>
                    <select name="status" id="editStatus" required>
                        @foreach(['available' => 'Available', 'reserved' => 'Reserved', 'sold' => 'Sold', 'under_construction' => 'Under Construction', 'turned_over' => 'Turned Over'] as $val => $label)
                            <option value="{{ $val }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Available at</label>
                    <input name="available_at" id="editAvailableAt" type="date">
                </div>
                <div class="field span-2">
                    <label>Description</label>
                    <textarea name="description" id="editDescription" rows="2" placeholder="General property description…"></textarea>
                </div>

                <div class="span-2 form-actions">
                    <button type="button" class="btn" id="cancelEditPropertyModal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update property</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     DELETE CONFIRMATION MODAL
══════════════════════════════════════════════════════════ --}}
<div class="modal-backdrop" id="deletePropertyModal" aria-hidden="true">
    <div style="
        width: min(420px, 92vw);
        border-radius: 20px;
        border: 1px solid rgba(226, 232, 240, 0.95);
        background: linear-gradient(165deg, #ffffff 0%, #f8fafc 42%, #f1f5f9 100%);
        box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.28);
        overflow: hidden;
    ">
        {{-- Header --}}
        <div style="
            padding: 20px 22px 16px;
            background: linear-gradient(135deg, rgba(254,226,226,0.6) 0%, rgba(255,241,242,0.4) 55%, rgba(248,250,252,0.9) 100%);
            border-bottom: 1px solid #fee2e2;
            display: flex;
            align-items: center;
            gap: 12px;
        ">
            <div style="
                width: 40px; height: 40px; border-radius: 10px;
                background: #fef2f2; border: 1px solid #fecaca;
                display: flex; align-items: center; justify-content: center;
                flex-shrink: 0;
            ">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#be123c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
            </div>
            <div>
                <div style="font-size: 16px; font-weight: 800; color: #0f172a; letter-spacing: -0.02em;">Delete property?</div>
                <div style="font-size: 12px; color: #64748b; margin-top: 2px;">This action cannot be undone</div>
            </div>
        </div>
        {{-- Body --}}
        <div style="padding: 18px 22px;">
            <p style="margin: 0 0 6px; font-size: 13px; color: #475569;">You are about to permanently delete:</p>
            <div style="
                background: #fef2f2; border: 1px solid #fecaca; border-radius: 10px;
                padding: 10px 14px; font-size: 14px; font-weight: 700; color: #be123c;
            " id="deletePropertyLabel">—</div>
            <p style="margin: 10px 0 0; font-size: 12px; color: #94a3b8;">
                All associated records including reservations, payments, and linked customers will be affected.
            </p>
        </div>
        {{-- Footer --}}
        <div style="
            padding: 14px 22px 20px;
            display: flex; gap: 8px; justify-content: flex-end;
            border-top: 1px solid #f1f5f9;
        ">
            <button type="button" class="btn" id="cancelDeletePropertyModal" style="border-radius:11px; padding:10px 18px; font-size:13px;">
                Cancel
            </button>
            <form method="POST" id="deletePropertyForm">
                @csrf
                @method('DELETE')
                <button type="submit" style="
                    border: none; border-radius: 11px; padding: 10px 18px;
                    font-size: 13px; font-weight: 600; cursor: pointer;
                    background: linear-gradient(135deg, #e11d48, #be123c);
                    color: #fff;
                    box-shadow: 0 4px 14px rgba(190, 18, 60, 0.35);
                ">Yes, delete</button>
            </form>
        </div>
    </div>
</div>

<script>
    // ── Constants ─────────────────────────────────────────────────────────────
    const PRICE_REGULAR    = 1500000;
    const PRICE_CORNER_LOT = 1650000;
    const LOT_SIZE_STD     = '120 sqm';
    const FLOOR_AREA_STD   = '35 sqm';

    function syncModelFields(modelSel, lotTypeSel, lotSizeInput, floorAreaInput, priceInput) {
        const model = modelSel.value;
        const lotType = lotTypeSel.value;
        if (model !== 'custom') {
            lotSizeInput.value   = LOT_SIZE_STD;
            floorAreaInput.value = FLOOR_AREA_STD;
            priceInput.value     = lotType === 'corner_lot' ? PRICE_CORNER_LOT : PRICE_REGULAR;
        }
    }

    // ── CREATE modal ──────────────────────────────────────────────────────────
    const createModal     = document.getElementById('createPropertyModal');
    const createForm      = document.getElementById('createPropertyForm');
    const createModel     = document.getElementById('createHouseModel');
    const createLotType   = document.getElementById('createLotType');
    const createLotSize   = document.getElementById('createLotSize');
    const createFloorArea = document.getElementById('createFloorArea');
    const createPrice     = document.getElementById('createPrice');

    function syncCreate() { syncModelFields(createModel, createLotType, createLotSize, createFloorArea, createPrice); }

    const openCreateModal = () => { createModal.classList.add('show'); syncCreate(); };
    const closeCreateModal = () => {
        createModal.classList.remove('show');
        createForm.reset();
        document.getElementById('createSubdivision').value = 'Midland Valley Homes';
        syncCreate();
    };

    document.getElementById('openCreatePropertyModal').addEventListener('click', openCreateModal);
    document.getElementById('closeCreatePropertyModal').addEventListener('click', closeCreateModal);
    document.getElementById('cancelCreatePropertyModal').addEventListener('click', closeCreateModal);
    createModal.addEventListener('click', e => { if (e.target === createModal) closeCreateModal(); });
    createModel.addEventListener('change', syncCreate);
    createLotType.addEventListener('change', syncCreate);
    syncCreate();

    // ── EDIT modal ────────────────────────────────────────────────────────────
    const editModal     = document.getElementById('editPropertyModal');
    const editForm      = document.getElementById('editPropertyForm');
    const editModel     = document.getElementById('editHouseModel');
    const editLotType   = document.getElementById('editLotType');
    const editLotSize   = document.getElementById('editLotSize');
    const editFloorArea = document.getElementById('editFloorArea');
    const editPrice     = document.getElementById('editPrice');

    function syncEdit() { syncModelFields(editModel, editLotType, editLotSize, editFloorArea, editPrice); }

    const openEditModal  = () => editModal.classList.add('show');
    const closeEditModal = () => editModal.classList.remove('show');

    document.getElementById('closeEditPropertyModal').addEventListener('click', closeEditModal);
    document.getElementById('cancelEditPropertyModal').addEventListener('click', closeEditModal);
    editModal.addEventListener('click', e => { if (e.target === editModal) closeEditModal(); });
    editModel.addEventListener('change', syncEdit);
    editLotType.addEventListener('change', syncEdit);

    document.querySelectorAll('.editPropertyBtn').forEach(btn => {
        btn.addEventListener('click', () => {
            const d = btn.dataset;
            editForm.action = `/properties/${d.id}`;

            document.getElementById('editStreetName').value  = d.street_name  || '';
            document.getElementById('editSubdivision').value = d.subdivision  || 'Midland Valley Homes';
            document.getElementById('editBarangay').value    = d.barangay     || '';
            document.getElementById('editCity').value        = d.city         || '';
            document.getElementById('editProvince').value    = d.province     || '';
            document.getElementById('editZipCode').value     = d.zip_code     || '';
            document.getElementById('editBlockNumber').value = d.block_number || '';
            document.getElementById('editLotNumber').value   = d.lot_number   || '';
            editModel.value                                   = d.house_model  || 'diamond';
            editLotType.value                                 = d.lot_type     || 'regular';
            document.getElementById('editStatus').value      = d.status       || 'available';
            document.getElementById('editAvailableAt').value = d.available_at || '';
            document.getElementById('editDescription').value = d.description  || '';

            editLotSize.value   = d.lot_size   || '';
            editFloorArea.value = d.floor_area || '';
            editPrice.value     = d.price      || '';
            syncEdit();

            openEditModal();
        });
    });

    // ── DELETE confirmation modal ─────────────────────────────────────────────
    const deleteModal    = document.getElementById('deletePropertyModal');
    const deleteForm     = document.getElementById('deletePropertyForm');
    const deleteLabelEl  = document.getElementById('deletePropertyLabel');

    const openDeleteModal  = () => deleteModal.classList.add('show');
    const closeDeleteModal = () => deleteModal.classList.remove('show');

    document.querySelectorAll('.deletePropertyBtn').forEach(btn => {
        btn.addEventListener('click', () => {
            deleteForm.action       = `/properties/${btn.dataset.id}`;
            deleteLabelEl.textContent = btn.dataset.label;
            openDeleteModal();
        });
    });

    document.getElementById('cancelDeletePropertyModal').addEventListener('click', closeDeleteModal);
    deleteModal.addEventListener('click', e => { if (e.target === deleteModal) closeDeleteModal(); });
</script>
</body>
</html>