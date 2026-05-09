<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Payment Management</title>
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
        .btn { border: 1px solid #c7d2fe; background: linear-gradient(135deg, #6366f1, #4f46e5); color: #fff; border-radius: 10px; padding: 8px 12px; text-decoration: none; font-size: 12px; font-weight: 600; }
        .content { flex: 1; min-height: 0; overflow: auto; border-radius: var(--radius); border: 1px solid #e2e8f0; background: linear-gradient(160deg, rgba(255,255,255,0.94), rgba(244,249,255,0.9)); box-shadow: 0 8px 18px rgba(15, 23, 42, 0.06); padding: 14px; }
        .stats { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: var(--gap); margin-bottom: 12px; }
        .card { border-radius: 14px; padding: 10px; color: #fff; }
        .c1 { background: linear-gradient(135deg, #73d9ef, #5aa8f2); } .c2 { background: linear-gradient(135deg, #9cb5ff, #6d90f6); } .c3 { background: linear-gradient(135deg, #f5add1, #ea7fbe); } .c4 { background: linear-gradient(135deg, #d2bcff, #a88ef0); }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        th, td { padding: 10px 8px; text-align: left; border-bottom: 1px solid #e2e8f0; color: #334155; }
        th { font-size: 12px; text-transform: uppercase; color: #64748b; }
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
.btn { border: 1px solid #c7d2fe; background: #fff; color: #3730a3; border-radius: 11px; padding: 10px 18px; text-decoration: none; font-size: 13px; font-weight: 600; cursor: pointer; }
.btn-primary { background: linear-gradient(135deg, #6366f1, #4f46e5); color: #fff; border-color: transparent; box-shadow: 0 4px 14px rgba(79, 70, 229, 0.32); }
.icon-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    background: #fff;
    color: #4338ca;
    cursor: pointer;
    transition: background 0.15s, border-color 0.15s, color 0.15s;
}
.icon-btn:hover {
    background: #eef2ff;
    border-color: #c7d2fe;
    color: #3730a3;
}
.icon-btn--manage {
    color: #6366f1;
    border-color: #c7d2fe;
    background: #eef2ff;
}
.icon-btn--manage:hover {
    background: #e0e7ff;
    border-color: #a5b4fc;
    color: #4338ca;
}
   </style>
</head>
<body>
@php
    $userRole = strtolower((string) (auth()->user()->role ?? ''));
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
<main class="main"><header class="header"><div class="h-title">Payment Module</div><button type="button" class="btn btn-primary" id="openCreatePaymentModal">+ Record Payment</button></header>
<section class="content">
<div class="stats"><div class="card c1"><strong>Total Payments</strong><div>{{ $total }}</div></div><div class="card c2"><strong>Total Collections</strong><div>PHP {{ number_format((float) $collections, 2) }}</div></div><div class="card c3"><strong>Today</strong><div>PHP {{ number_format((float) $today, 2) }}</div></div><div class="card c4"><strong>Installments</strong><div>{{ $installments }}</div></div></div>
<table><thead><tr><th>Customer</th><th>Property</th><th>Total Equity Paid</th><th>Loan Balance</th><th>Actions</th></tr></thead><tbody>@forelse ($customers as $customer)<tr><td>{{ $customer->first_name }} {{ $customer->last_name }}</td><td>{{ $customer->selectedProperty ? $customer->selectedProperty->block_number . '/' . $customer->selectedProperty->lot_number : 'None' }}</td><td>PHP {{ number_format((float) $customer->getTotalEquityPaid(), 2) }}</td><td>PHP {{ number_format((float) $customer->getTotalLoanBalance(), 2) }}</td>
<td>
    <div class="row-actions">
        {{-- Eye = view only (no forms/buttons inside) --}}
        <button type="button" class="openViewBtn icon-btn" data-target="viewModal_{{ $customer->id }}" title="View Financials">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>
        </button>
        {{-- Gear = manage (with forms) --}}
        <button type="button" class="viewFinancialsBtn icon-btn icon-btn--manage" data-target="financialsModal_{{ $customer->id }}" title="Manage">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/></svg>
        </button>
    </div>
</td>
</tr>@empty<tr><td colspan="5">No customers with payments yet.</td></tr>@endforelse</tbody></table>
<div style="margin-top:12px;">{{ $customers->links() }}</div></section>

<div class="modal-backdrop" id="createPaymentModal" aria-hidden="true">
    <div class="modal-card" role="dialog" aria-labelledby="createPaymentTitle">
        <div class="modal-head">
            <div class="modal-title-wrap">
                <h2 class="modal-title" id="createPaymentTitle">Record payment</h2>
                <p class="modal-subtitle">Link to customer, property &amp; payment details</p>
            </div>
            <div class="modal-head-actions">
                <button type="button" class="close-btn" id="closeCreatePaymentModal">Close</button>
            </div>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('payments.store') }}" class="form-grid">
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
                        @foreach (\App\Models\Property::orderBy('block_number')->get() as $property)
                            <option value="{{ $property->id }}" @selected(old('property_id') == $property->id)>Block {{ $property->block_number }} / Lot {{ $property->lot_number }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Linked reservation</label>
                    <select name="reservation_id">
                        <option value="">No linked reservation</option>
                        @foreach (\App\Models\Reservation::orderByDesc('created_at')->get() as $reservation)
                            <option value="{{ $reservation->id }}" @selected(old('reservation_id') == $reservation->id)>#{{ $reservation->id }} — {{ $reservation->status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Payment type</label>
                    <select name="payment_type" required>
                        @foreach (['downpayment' => 'Downpayment', 'installment' => 'Installment', 'equity' => 'Equity', 'reservation_fee' => 'Reservation Fee', 'other' => 'Other'] as $val => $label)
                            <option value="{{ $val }}" @selected(old('payment_type', 'downpayment') === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Amount (PHP)</label>
                    <input name="amount" type="number" step="0.01" placeholder="0.00" value="{{ old('amount') }}" required>
                </div>
                <div class="field">
                    <label>Payment date</label>
                    <input name="payment_date" type="date" value="{{ old('payment_date') }}">
                </div>
                <div class="field span-2">
                    <label>Payment method</label>
                    <input name="payment_method" placeholder="e.g. Cash, Bank Transfer, GCash" value="{{ old('payment_method') }}">
                </div>
                <div class="field span-2">
                    <label>Notes</label>
                    <textarea name="notes" placeholder="Additional notes…" rows="3">{{ old('notes') }}</textarea>
                </div>
                <div class="span-2 form-actions">
                    <button type="button" class="btn" id="cancelCreatePaymentModal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save payment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal-backdrop" id="editPaymentModal" aria-hidden="true">
    <div class="modal-card" role="dialog" aria-labelledby="editPaymentTitle">
        <div class="modal-head">
            <div class="modal-title-wrap">
                <h2 class="modal-title" id="editPaymentTitle">Edit payment</h2>
                <p class="modal-subtitle">Update payment record &amp; details</p>
            </div>
            <div class="modal-head-actions">
                <button type="button" class="close-btn" id="closeEditPaymentModal">Close</button>
            </div>
        </div>
        <div class="modal-body">
            <form method="POST" id="editPaymentForm" class="form-grid">
                @csrf
                @method('PUT')
                <div class="field">
                    <label>Customer</label>
                    <select name="customer_id" id="editPaymentCustomerId" required>
                        @foreach (\App\Models\Customer::orderBy('last_name')->get() as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->first_name }} {{ $customer->last_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Property</label>
                    <select name="property_id" id="editPaymentPropertyId" required>
                        @foreach (\App\Models\Property::orderBy('block_number')->get() as $property)
                            <option value="{{ $property->id }}">Block {{ $property->block_number }} / Lot {{ $property->lot_number }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Linked reservation</label>
                    <select name="reservation_id" id="editPaymentReservationId">
                        <option value="">No linked reservation</option>
                        @foreach (\App\Models\Reservation::orderByDesc('created_at')->get() as $reservation)
                            <option value="{{ $reservation->id }}">#{{ $reservation->id }} — {{ $reservation->status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Payment type</label>
                    <select name="payment_type" id="editPaymentType" required>
                        @foreach (['downpayment' => 'Downpayment', 'installment' => 'Installment', 'equity' => 'Equity', 'reservation_fee' => 'Reservation Fee', 'other' => 'Other'] as $val => $label)
                            <option value="{{ $val }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Amount (PHP)</label>
                    <input name="amount" id="editPaymentAmount" type="number" step="0.01" placeholder="0.00" required>
                </div>
                <div class="field">
                    <label>Payment date</label>
                    <input name="payment_date" id="editPaymentDate" type="date">
                </div>
                <div class="field span-2">
                    <label>Payment method</label>
                    <input name="payment_method" id="editPaymentMethod" placeholder="e.g. Cash, Bank Transfer, GCash">
                </div>
                <div class="field span-2">
                    <label>Notes</label>
                    <textarea name="notes" id="editPaymentNotes" rows="3" placeholder="Additional notes…"></textarea>
                </div>
                <div class="span-2 form-actions">
                    <button type="button" class="btn" id="cancelEditPaymentModal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update payment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal-backdrop" id="editPaymentModal" aria-hidden="true">
    <div class="modal-card" role="dialog" aria-labelledby="editPaymentTitle">
        <div class="modal-head">
            <div class="modal-title-wrap">
                <h2 class="modal-title" id="editPaymentTitle">Edit payment</h2>
                <p class="modal-subtitle">Update payment record &amp; details</p>
            </div>
            <div class="modal-head-actions">
                <button type="button" class="close-btn" id="closeEditPaymentModal">Close</button>
            </div>
        </div>
        <div class="modal-body">
            <form method="POST" id="editPaymentForm" class="form-grid">
                @csrf
                @method('PUT')
                <div class="field">
                    <label>Customer</label>
                    <select name="customer_id" id="editPaymentCustomerId" required>
                        @foreach (\App\Models\Customer::orderBy('last_name')->get() as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->first_name }} {{ $customer->last_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Property</label>
                    <select name="property_id" id="editPaymentPropertyId" required>
                        @foreach (\App\Models\Property::orderBy('block_number')->get() as $property)
                            <option value="{{ $property->id }}">Block {{ $property->block_number }} / Lot {{ $property->lot_number }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Linked reservation</label>
                    <select name="reservation_id" id="editPaymentReservationId">
                        <option value="">No linked reservation</option>
                        @foreach (\App\Models\Reservation::orderByDesc('created_at')->get() as $reservation)
                            <option value="{{ $reservation->id }}">#{{ $reservation->id }} — {{ $reservation->status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Payment type</label>
                    <select name="payment_type" id="editPaymentType" required>
                        @foreach (['downpayment' => 'Downpayment', 'installment' => 'Installment', 'equity' => 'Equity', 'reservation_fee' => 'Reservation Fee', 'other' => 'Other'] as $val => $label)
                            <option value="{{ $val }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Amount (PHP)</label>
                    <input name="amount" id="editPaymentAmount" type="number" step="0.01" placeholder="0.00" required>
                </div>
                <div class="field">
                    <label>Payment date</label>
                    <input name="payment_date" id="editPaymentDate" type="date">
                </div>
                <div class="field span-2">
                    <label>Payment method</label>
                    <input name="payment_method" id="editPaymentMethod" placeholder="e.g. Cash, Bank Transfer, GCash">
                </div>
                <div class="field span-2">
                    <label>Notes</label>
                    <textarea name="notes" id="editPaymentNotes" rows="3" placeholder="Additional notes…"></textarea>
                </div>
                <div class="span-2 form-actions">
                    <button type="button" class="btn" id="cancelEditPaymentModal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update payment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const paymentModal = document.getElementById('createPaymentModal');
    const openPaymentBtn = document.getElementById('openCreatePaymentModal');
    const closePaymentBtn = document.getElementById('closeCreatePaymentModal');
    const cancelPaymentBtn = document.getElementById('cancelCreatePaymentModal');

    const openPaymentModal = () => paymentModal.classList.add('show');
    const closePaymentModal = () => paymentModal.classList.remove('show');

    openPaymentBtn.addEventListener('click', openPaymentModal);
    closePaymentBtn.addEventListener('click', closePaymentModal);
    cancelPaymentBtn.addEventListener('click', closePaymentModal);
    paymentModal.addEventListener('click', (e) => {
        if (e.target === paymentModal) closePaymentModal();
    });

    const editPaymentModal = document.getElementById('editPaymentModal');
    const editPaymentForm = document.getElementById('editPaymentForm');
    const closeEditPaymentBtn = document.getElementById('closeEditPaymentModal');
    const cancelEditPaymentBtn = document.getElementById('cancelEditPaymentModal');
    const openEditPaymentModal = () => editPaymentModal.classList.add('show');
    const closeEditPaymentModal = () => editPaymentModal.classList.remove('show');

    document.querySelectorAll('.editPaymentBtn').forEach((button) => {
        button.addEventListener('click', () => {
            editPaymentForm.action = `/payments/${button.dataset.id}`;
            document.getElementById('editPaymentCustomerId').value = button.dataset.customer_id || '';
            document.getElementById('editPaymentPropertyId').value = button.dataset.property_id || '';
            document.getElementById('editPaymentReservationId').value = button.dataset.reservation_id || '';
            document.getElementById('editPaymentType').value = button.dataset.payment_type || 'downpayment';
            document.getElementById('editPaymentAmount').value = button.dataset.amount || '';
            document.getElementById('editPaymentDate').value = button.dataset.payment_date || '';
            document.getElementById('editPaymentMethod').value = button.dataset.payment_method || '';
            document.getElementById('editPaymentNotes').value = button.dataset.notes || '';
            openEditPaymentModal();
        });
    });

    closeEditPaymentBtn.addEventListener('click', closeEditPaymentModal);
    cancelEditPaymentBtn.addEventListener('click', closeEditPaymentModal);
    editPaymentModal.addEventListener('click', (e) => {
        if (e.target === editPaymentModal) closeEditPaymentModal();
    });

</script>

{{-- ── VIEW-ONLY MODALS ── --}}
@foreach($customers as $customer)
@php
    $equityPaid = $customer->getTotalEquityPaid();
    $loanBalance = $customer->getTotalLoanBalance();
@endphp
<div class="modal-backdrop" id="viewModal_{{ $customer->id }}" aria-hidden="true">
    <div class="modal-card" style="width: min(600px, 96vw);" role="dialog">
        <div class="modal-head">
            <div class="modal-title-wrap">
                <h2 class="modal-title">Customer Financials</h2>
                <p class="modal-subtitle">{{ $customer->first_name }} {{ $customer->last_name }} — {{ $customer->project_name }}</p>
            </div>
            <div class="modal-head-actions">
                <button type="button" class="close-btn closeViewBtn">Close</button>
            </div>
        </div>
        <div class="modal-body" style="display:flex; flex-direction:column; gap:20px;">

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                <div style="background:#f8fafc; border:1px solid #e2e8f0; padding:16px; border-radius:12px;">
                    <h3 style="margin:0 0 6px; font-size:12px; color:#475569; text-transform:uppercase; letter-spacing:.05em;">Total Equity Paid</h3>
                    <div style="font-size:22px; font-weight:800; color:#10b981;">PHP {{ number_format((float) $equityPaid, 2) }}</div>
                </div>
                <div style="background:#f8fafc; border:1px solid #e2e8f0; padding:16px; border-radius:12px;">
                    <h3 style="margin:0 0 6px; font-size:12px; color:#475569; text-transform:uppercase; letter-spacing:.05em;">Pag-IBIG Loan Balance</h3>
                    <div style="font-size:22px; font-weight:800; color:#3b82f6;">PHP {{ number_format((float) $loanBalance, 2) }}</div>
                </div>
            </div>

            <div>
                <h3 style="margin:0 0 10px; font-size:14px; font-weight:700; color:#1e293b;">Payment History</h3>
                <div style="max-height:220px; overflow-y:auto; border:1px solid #cbd5e1; border-radius:10px;">
                    <table style="margin:0;">
                        <thead>
                            <tr style="background:#f1f5f9;">
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Method</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customer->payments()->latest('payment_date')->get() as $payment)
                                <tr>
                                    <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_type)) }}</td>
                                    <td>PHP {{ number_format((float) $payment->amount, 2) }}</td>
                                    <td>{{ $payment->payment_date ?: '—' }}</td>
                                    <td>{{ $payment->payment_method ?: '—' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" style="text-align:center; color:#94a3b8;">No payments yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div>
                <h3 style="margin:0 0 10px; font-size:14px; font-weight:700; color:#1e293b;">Notification Logs</h3>
                <div style="max-height:180px; overflow-y:auto; border:1px solid #cbd5e1; border-radius:10px;">
                    <table style="margin:0;">
                        <thead>
                            <tr style="background:#f1f5f9;">
                                <th>Date</th>
                                <th>Type</th>
                                <th>Message</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customer->notifications()->latest('notified_at')->get() as $notif)
                                <tr>
                                    <td style="white-space:nowrap;">{{ \Carbon\Carbon::parse($notif->notified_at)->format('M d, Y') }}</td>
                                    <td><span class="pill" style="text-transform:uppercase;">{{ $notif->notification_type }}</span></td>
                                    <td>{{ $notif->message }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" style="text-align:center; color:#94a3b8;">No logs yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endforeach

{{-- ── MANAGE MODALS ── --}}
@foreach($customers as $customer)
@php
    $equityPaid = $customer->getTotalEquityPaid();
    $loanBalance = $customer->getTotalLoanBalance();
    $loan = $customer->loans()->first();
@endphp
<div class="modal-backdrop" id="financialsModal_{{ $customer->id }}" aria-hidden="true">
    <div class="modal-card" style="width: min(800px, 96vw);" role="dialog">
        <div class="modal-head">
            <div class="modal-title-wrap">
                <h2 class="modal-title">Financials & Logs</h2>
                <p class="modal-subtitle">{{ $customer->first_name }} {{ $customer->last_name }} ({{ $customer->project_name }})</p>
            </div>
            <div class="modal-head-actions">
                <button type="button" class="close-btn closeFinancialsBtn">Close</button>
            </div>
        </div>
        <div class="modal-body" style="display: flex; flex-direction: column; gap: 20px;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 16px; border-radius: 12px;">
                    <h3 style="margin: 0 0 10px; font-size: 14px; color: #475569;">Total Equity Paid</h3>
                    <div style="font-size: 24px; font-weight: 800; color: #10b981;">PHP {{ number_format((float) $equityPaid, 2) }}</div>
                </div>
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 16px; border-radius: 12px;">
                    <h3 style="margin: 0 0 10px; font-size: 14px; color: #475569;">Pag-IBIG Loan Balance</h3>
                    <div style="font-size: 24px; font-weight: 800; color: #3b82f6;">PHP {{ number_format((float) $loanBalance, 2) }}</div>
                </div>
            </div>

            <div style="border-top: 1px solid #e2e8f0; padding-top: 20px;">
                <h3 style="margin: 0 0 12px; font-size: 16px; font-weight: 700; color: #1e293b;">Payment History</h3>
                <div style="max-height: 200px; overflow-y: auto; border: 1px solid #cbd5e1; border-radius: 10px; margin-bottom: 12px;">
                    <table style="margin: 0;">
                        <thead>
                            <tr style="background: #f1f5f9;">
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customer->payments()->latest('payment_date')->get() as $payment)
                                <tr>
                                    <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_type)) }}</td>
                                    <td>PHP {{ number_format((float) $payment->amount, 2) }}</td>
                                    <td>{{ $payment->payment_date ?: '-' }}</td>
                                    <td>
                                        <div class="row-actions">
                                            <button type="button" class="link editPaymentBtn"
                                                data-id="{{ $payment->id }}"
                                                data-customer_id="{{ $payment->customer_id }}"
                                                data-property_id="{{ $payment->property_id }}"
                                                data-reservation_id="{{ $payment->reservation_id }}"
                                                data-payment_type="{{ $payment->payment_type }}"
                                                data-amount="{{ $payment->amount }}"
                                                data-payment_date="{{ $payment->payment_date }}"
                                                data-payment_method="{{ $payment->payment_method }}"
                                                data-notes="{{ $payment->notes }}">Edit</button>
                                            <form method="POST" action="{{ route('payments.destroy', $payment) }}" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button class="danger" onclick="return confirm('Delete this payment?')">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" style="text-align: center; color: #94a3b8;">No payments yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div style="border-top: 1px solid #e2e8f0; padding-top: 20px;">
                <h3 style="margin: 0 0 12px; font-size: 16px; font-weight: 700; color: #1e293b;">Manage Loan</h3>
                <form method="POST" action="{{ route('customer-loans.update', $customer->id) }}" class="form-grid">
                    @csrf
                    @method('PUT')
                    <div class="field">
                        <label>Loan Amount (PHP)</label>
                        <input name="loan_amount" type="number" step="0.01" value="{{ old('loan_amount', $loan?->loan_amount ?? '0.00') }}" required>
                    </div>
                    <div class="field">
                        <label>Loan Status</label>
                        <select name="status" required>
                            @foreach(['pending' => 'Pending', 'approved' => 'Approved', 'paid' => 'Paid', 'cancelled' => 'Cancelled'] as $val => $label)
                                <option value="{{ $val }}" @selected(old('status', $loan?->status) === $val)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="span-2 form-actions">
                        <button type="submit" class="btn btn-primary" style="padding: 6px 12px; font-size: 12px;">Save Loan Details</button>
                    </div>
                </form>
            </div>

            <div style="border-top: 1px solid #e2e8f0; padding-top: 20px;">
                <h3 style="margin: 0 0 12px; font-size: 16px; font-weight: 700; color: #1e293b;">Notification Logs</h3>
                <div style="max-height: 200px; overflow-y: auto; border: 1px solid #cbd5e1; border-radius: 10px; margin-bottom: 12px;">
                    <table style="margin: 0;">
                        <thead>
                            <tr style="background: #f1f5f9;">
                                <th>Date</th>
                                <th>Type</th>
                                <th>Message</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customer->notifications()->latest('notified_at')->get() as $notif)
                                <tr>
                                    <td style="white-space: nowrap;">{{ \Carbon\Carbon::parse($notif->notified_at)->format('M d, Y') }}</td>
                                    <td><span class="pill" style="text-transform: uppercase;">{{ $notif->notification_type }}</span></td>
                                    <td>{{ $notif->message }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" style="text-align: center; color: #94a3b8;">No notifications logged yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <form method="POST" action="{{ route('customer-notifications.store', $customer->id) }}" style="background: #f8fafc; padding: 14px; border-radius: 10px; border: 1px solid #e2e8f0;">
                    @csrf
                    <h4 style="margin: 0 0 10px; font-size: 13px; font-weight: 700; color: #475569;">+ Add New Log</h4>
                    <div class="form-grid" style="align-items: end;">
                        <div class="field" style="grid-column: span 1;">
                            <label>Date Notified</label>
                            <input name="notified_at" type="date" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="field" style="grid-column: span 1;">
                            <label>Method</label>
                            <select name="notification_type" required>
                                <option value="sms">SMS / Text</option>
                                <option value="email">Email</option>
                                <option value="phone_call">Phone Call</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="field span-2">
                            <label>Message / Details</label>
                            <input name="message" placeholder="e.g. Texted about loan approval..." required>
                        </div>
                        <div class="span-2 form-actions" style="margin-top: 0; padding-top: 0; border: none;">
                            <button type="submit" class="btn btn-primary" style="padding: 6px 12px; font-size: 12px;">Save Log Entry</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

<script>
    document.querySelectorAll('.viewFinancialsBtn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const targetId = btn.getAttribute('data-target');
            const modal = document.getElementById(targetId);
            if (modal) modal.classList.add('show');
        });
    });

    document.querySelectorAll('.closeFinancialsBtn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const modal = btn.closest('.modal-backdrop');
            if (modal) modal.classList.remove('show');
        });
    });

    document.querySelectorAll('.modal-backdrop').forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) modal.classList.remove('show');
        });
    });

    document.querySelectorAll('.openViewBtn').forEach(btn => {
    btn.addEventListener('click', () => {
        const modal = document.getElementById(btn.dataset.target);
        if (modal) modal.classList.add('show');
    });
});

document.querySelectorAll('.closeViewBtn').forEach(btn => {
    btn.addEventListener('click', () => {
        btn.closest('.modal-backdrop').classList.remove('show');
    });
});

</script>
</body>
</html>
