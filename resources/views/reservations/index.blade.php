<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reservation Management</title>
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
        .btn { border: 1px solid #c7d2fe; background: #fff; color: #3730a3; border-radius: 10px; padding: 8px 12px; text-decoration: none; font-size: 12px; font-weight: 600; }
        .btn-primary { background: linear-gradient(135deg, #6366f1, #4f46e5); color: #fff; border-color: transparent; }
        .content { flex: 1; min-height: 0; overflow: auto; border-radius: var(--radius); border: 1px solid #e2e8f0; background: linear-gradient(160deg, rgba(255,255,255,0.94), rgba(244,249,255,0.9)); box-shadow: 0 8px 18px rgba(15, 23, 42, 0.06); padding: 14px; }
        .stats { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: var(--gap); margin-bottom: 12px; }
        .card { border-radius: 14px; padding: 10px; color: #fff; }
        .c1 { background: linear-gradient(135deg, #73d9ef, #5aa8f2); }
        .c2 { background: linear-gradient(135deg, #9cb5ff, #6d90f6); }
        .c3 { background: linear-gradient(135deg, #f5add1, #ea7fbe); }
        .c4 { background: linear-gradient(135deg, #d2bcff, #a88ef0); }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        th, td { padding: 10px 8px; text-align: left; border-bottom: 1px solid #e2e8f0; color: #334155; }
        th { font-size: 12px; text-transform: uppercase; color: #64748b; }
        .pill { font-size: 11px; padding: 4px 8px; border-radius: 999px; background: #eef2ff; color: #4338ca; border: 1px solid #c7d2fe; }
        .row-actions { display: flex; gap: 8px; }
        .link { color: #4338ca; text-decoration: none; font-weight: 600; background: none; border: none; padding: 0; cursor: pointer; }
        .danger { color: #be123c; background: none; border: none; padding: 0; font: inherit; cursor: pointer; font-weight: 600; }
        .modal-backdrop {
    position: fixed; inset: 0;
    background: rgba(15, 23, 42, 0.5);
    backdrop-filter: blur(6px);
    display: none; align-items: center; justify-content: center;
    z-index: 50; padding: 16px;
}
.modal-backdrop.show { display: flex; }
.modal-card {
    width: min(700px, 96vw);
    max-height: min(90vh, 860px);
    overflow: hidden;
    display: flex; flex-direction: column;
    border-radius: 20px;
    border: 1px solid rgba(226, 232, 240, 0.95);
    background: linear-gradient(165deg, #ffffff 0%, #f8fafc 42%, #f1f5f9 100%);
    box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.28), 0 0 0 1px rgba(255, 255, 255, 0.65) inset;
}
.modal-head {
    flex-shrink: 0;
    display: flex; align-items: center; justify-content: space-between; gap: 12px;
    padding: 18px 22px; margin: 0;
    border-bottom: 1px solid #e2e8f0;
    background: linear-gradient(135deg, rgba(99, 102, 241, 0.12) 0%, rgba(139, 92, 246, 0.06) 55%, rgba(248, 250, 252, 0.9) 100%);
}
.modal-title-wrap { display: flex; flex-direction: column; gap: 4px; }
.modal-title { font-size: 19px; font-weight: 800; letter-spacing: -0.03em; color: #0f172a; margin: 0; line-height: 1.2; }
.modal-subtitle { font-size: 12px; font-weight: 500; color: #64748b; margin: 0; }
.modal-head-actions { display: flex; align-items: center; gap: 8px; }
.close-btn {
    border: 1px solid #e2e8f0; background: rgba(255, 255, 255, 0.85);
    border-radius: 11px; padding: 8px 14px; cursor: pointer;
    font-weight: 600; font-size: 13px; color: #475569;
    transition: background 0.15s ease, border-color 0.15s ease;
}
.close-btn:hover { background: #fff; border-color: #cbd5e1; color: #0f172a; }
.modal-body { overflow: auto; flex: 1; min-height: 0; padding: 18px 22px 22px; -webkit-overflow-scrolling: touch; }
.form-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px 14px; }
.form-grid input, .form-grid select, .form-grid textarea {
    width: 100%; border: 1px solid #cbd5e1; border-radius: 11px;
    padding: 10px 12px; font-size: 13px; background: #fff;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
}
.form-grid input:hover, .form-grid select:hover, .form-grid textarea:hover { border-color: #94a3b8; }
.form-grid input:focus, .form-grid select:focus, .form-grid textarea:focus {
    outline: none; border-color: #818cf8;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.18);
}
.form-grid select {
    appearance: none; -webkit-appearance: none; -moz-appearance: none;
    padding-right: 38px;
    background-color: #fff;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='none' stroke='%2364748b' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round' d='M2 4l4 4 4-4'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: calc(100% - 12px) 50%;
    background-size: 12px 12px;
}
.field label { display: block; font-size: 11px; font-weight: 700; margin-bottom: 5px; color: #475569; letter-spacing: 0.01em; }
.span-2 { grid-column: span 2; }
.form-actions { display: flex; gap: 8px; justify-content: flex-end; margin-top: 4px; padding-top: 18px; border-top: 1px solid #e2e8f0; }
    </style>
</head>
<body>
@php
    $total = $reservations->total();
    $active = \App\Models\Reservation::where('status', 'active')->count();
    $completed = \App\Models\Reservation::where('status', 'completed')->count();
    $cancelled = \App\Models\Reservation::where('status', 'cancelled')->count();
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
            <div class="h-title">Reservation Module</div>
            <div class="top-actions">
                <button type="button" class="btn btn-primary" id="openCreateReservationModal">+ Add Reservation</button>
            </div>
        </header>

        <section class="content">
            <div class="stats">
                <div class="card c1"><strong>Total Reservations</strong><div>{{ $total }}</div></div>
                <div class="card c2"><strong>Active</strong><div>{{ $active }}</div></div>
                <div class="card c3"><strong>Completed</strong><div>{{ $completed }}</div></div>
                <div class="card c4"><strong>Cancelled</strong><div>{{ $cancelled }}</div></div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Property</th>
                        <th>Reserved On</th>
                        <th>Fee</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reservations as $reservation)
                        <tr>
                            <td>{{ $reservation->customer->first_name }} {{ $reservation->customer->last_name }}</td>
                            <td>{{ $reservation->property->block_number }}/{{ $reservation->property->lot_number }}</td>
                            <td>{{ $reservation->reserved_on ?: '-' }}</td>
                            <td>PHP {{ number_format((float) $reservation->reservation_fee, 2) }}</td>
                            <td><span class="pill">{{ $reservation->status }}</span></td>
                            <td>
                                <div class="row-actions">
                                    <button
                                        type="button"
                                        class="link editReservationBtn"
                                        data-id="{{ $reservation->id }}"
                                        data-customer_id="{{ $reservation->customer_id }}"
                                        data-property_id="{{ $reservation->property_id }}"
                                        data-reservation_fee="{{ $reservation->reservation_fee }}"
                                        data-status="{{ $reservation->status }}"
                                        data-reserved_on="{{ $reservation->reserved_on }}"
                                        data-expires_at="{{ $reservation->expires_at }}"
                                        data-notes="{{ $reservation->notes }}"
                                    >Edit</button>
                                    <form method="POST" action="{{ route('reservations.destroy', $reservation) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="danger" onclick="return confirm('Delete this reservation?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6">No reservation records yet.</td></tr>
                    @endforelse
                </tbody>
            </table>

            <div style="margin-top:12px;">{{ $reservations->links() }}</div>
        </section>
    </main>
</div>

<div class="modal-backdrop" id="createReservationModal" aria-hidden="true">
    <div class="modal-card" role="dialog" aria-labelledby="createReservationTitle">
        <div class="modal-head">
            <div class="modal-title-wrap">
                <h2 class="modal-title" id="createReservationTitle">Add reservation</h2>
                <p class="modal-subtitle">Link customer to property &amp; set reservation details</p>
            </div>
            <div class="modal-head-actions">
                <button type="button" class="close-btn" id="closeCreateReservationModal">Close</button>
            </div>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('reservations.store') }}" class="form-grid">
                @csrf
                <div class="field">
                    <label>Customer</label>
                    <select name="customer_id" required>
                        @foreach (\App\Models\Customer::orderBy('last_name')->get() as $customer)
                            <option value="{{ $customer->id }}" @selected(old('customer_id') == $customer->id)>{{ $customer->first_name }} {{ $customer->last_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Property</label>
                    <select name="property_id" required>
                        @foreach (\App\Models\Property::whereIn('status', ['available', 'under_construction'])->orderBy('block_number')->get() as $property)
                            <option value="{{ $property->id }}" @selected(old('property_id') == $property->id)>Block {{ $property->block_number }} / Lot {{ $property->lot_number }} ({{ $property->status }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Reservation fee (PHP)</label>
                    <input name="reservation_fee" type="number" step="0.01" value="{{ old('reservation_fee') }}" placeholder="0.00" required>
                </div>
                <div class="field">
                    <label>Status</label>
                    <select name="status" required>
                        @foreach (['active' => 'Active', 'completed' => 'Completed', 'cancelled' => 'Cancelled', 'skipped' => 'Skipped'] as $val => $label)
                            <option value="{{ $val }}" @selected(old('status', 'active') === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Reserved on</label>
                    <input name="reserved_on" type="date" value="{{ old('reserved_on') }}">
                </div>
                <div class="field">
                    <label>Expires at</label>
                    <input name="expires_at" type="date" value="{{ old('expires_at') }}">
                </div>
                <div class="field span-2">
                    <label>Notes</label>
                    <textarea name="notes" rows="3" placeholder="Additional notes…">{{ old('notes') }}</textarea>
                </div>
                <div class="span-2 form-actions">
                    <button type="button" class="btn" id="cancelCreateReservationModal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save reservation</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal-backdrop" id="editReservationModal" aria-hidden="true">
    <div class="modal-card" role="dialog" aria-labelledby="editReservationTitle">
        <div class="modal-head">
            <div class="modal-title-wrap">
                <h2 class="modal-title" id="editReservationTitle">Edit reservation</h2>
                <p class="modal-subtitle">Update customer, property &amp; reservation status</p>
            </div>
            <div class="modal-head-actions">
                <button type="button" class="close-btn" id="closeEditReservationModal">Close</button>
            </div>
        </div>
        <div class="modal-body">
            <form method="POST" id="editReservationForm" class="form-grid">
                @csrf
                @method('PUT')
                <div class="field">
                    <label>Customer</label>
                    <select name="customer_id" id="editReservationCustomerId" required>
                        @foreach (\App\Models\Customer::orderBy('last_name')->get() as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->first_name }} {{ $customer->last_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Property</label>
                    <select name="property_id" id="editReservationPropertyId" required>
                        @foreach (\App\Models\Property::orderBy('block_number')->get() as $property)
                            <option value="{{ $property->id }}">Block {{ $property->block_number }} / Lot {{ $property->lot_number }} ({{ $property->status }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Reservation fee (PHP)</label>
                    <input name="reservation_fee" id="editReservationFee" type="number" step="0.01" placeholder="0.00" required>
                </div>
                <div class="field">
                    <label>Status</label>
                    <select name="status" id="editReservationStatus" required>
                        @foreach (['active' => 'Active', 'completed' => 'Completed', 'cancelled' => 'Cancelled', 'skipped' => 'Skipped'] as $val => $label)
                            <option value="{{ $val }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Reserved on</label>
                    <input name="reserved_on" id="editReservationReservedOn" type="date">
                </div>
                <div class="field">
                    <label>Expires at</label>
                    <input name="expires_at" id="editReservationExpiresAt" type="date">
                </div>
                <div class="field span-2">
                    <label>Notes</label>
                    <textarea name="notes" id="editReservationNotes" rows="3" placeholder="Additional notes…"></textarea>
                </div>
                <div class="span-2 form-actions">
                    <button type="button" class="btn" id="cancelEditReservationModal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update reservation</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    const reservationModal = document.getElementById('createReservationModal');
    const openReservationBtn = document.getElementById('openCreateReservationModal');
    const closeReservationBtn = document.getElementById('closeCreateReservationModal');
    const cancelReservationBtn = document.getElementById('cancelCreateReservationModal');

    const openReservationModal = () => reservationModal.classList.add('show');
    const closeReservationModal = () => reservationModal.classList.remove('show');

    openReservationBtn.addEventListener('click', openReservationModal);
    closeReservationBtn.addEventListener('click', closeReservationModal);
    cancelReservationBtn.addEventListener('click', closeReservationModal);
    reservationModal.addEventListener('click', (e) => {
        if (e.target === reservationModal) closeReservationModal();
    });

    const editReservationModal = document.getElementById('editReservationModal');
    const editReservationForm = document.getElementById('editReservationForm');
    const closeEditReservationBtn = document.getElementById('closeEditReservationModal');
    const cancelEditReservationBtn = document.getElementById('cancelEditReservationModal');
    const openEditReservationModal = () => editReservationModal.classList.add('show');
    const closeEditReservationModal = () => editReservationModal.classList.remove('show');

    document.querySelectorAll('.editReservationBtn').forEach((button) => {
        button.addEventListener('click', () => {
            editReservationForm.action = `/reservations/${button.dataset.id}`;
            document.getElementById('editReservationCustomerId').value = button.dataset.customer_id || '';
            document.getElementById('editReservationPropertyId').value = button.dataset.property_id || '';
            document.getElementById('editReservationFee').value = button.dataset.reservation_fee || '';
            document.getElementById('editReservationStatus').value = button.dataset.status || 'active';
            document.getElementById('editReservationReservedOn').value = button.dataset.reserved_on || '';
            document.getElementById('editReservationExpiresAt').value = button.dataset.expires_at || '';
            document.getElementById('editReservationNotes').value = button.dataset.notes || '';
            openEditReservationModal();
        });
    });

    closeEditReservationBtn.addEventListener('click', closeEditReservationModal);
    cancelEditReservationBtn.addEventListener('click', closeEditReservationModal);
    editReservationModal.addEventListener('click', (e) => {
        if (e.target === editReservationModal) closeEditReservationModal();
    });
</script>
</body>
</html>
