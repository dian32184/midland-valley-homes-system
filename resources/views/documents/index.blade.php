<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --gap: 12px; --radius: 16px; --sidebar: 220px; }
        * { box-sizing: border-box; } body { margin: 0; overflow: hidden; font-family: Figtree, Arial, sans-serif; background: linear-gradient(145deg, #f2f3ff 0%, #f9fbff 100%); }
        .shell { height: 100vh; display: flex; } .sidebar { width: var(--sidebar); flex: 0 0 var(--sidebar); background: linear-gradient(165deg, #eef2ff 0%, #e6ecff 45%, #eaf5ff 100%); border-right: 1px solid #e6ebf7; padding: 10px; display: flex; flex-direction: column; gap: 10px; }
        .brand { border-radius: var(--radius); background: #ffffffd9; border: 1px solid #e2e8f0; padding: 10px; text-align: center; } .brand img { height: 48px; width: auto; border-radius: 8px; margin-left: 50px; } .brand h1 { margin: 8px 0 0; font-size: 14px; font-weight: 700; color: #1e293b; }
        .menu { display: flex; flex-direction: column; gap: 6px; } .menu a { padding: 8px 10px; border-radius: 10px; text-decoration: none; font-size: 12px; font-weight: 600; color: #475569; border: 1px solid #e6eaf5; background: rgba(255, 255, 255, 0.7); } .menu a.active { background: linear-gradient(135deg, #6366f1, #4f46e5); color: #fff; border-color: transparent; }
        .main { flex: 1; display: flex; flex-direction: column; min-width: 0; padding: 10px; gap: 10px; background: linear-gradient(165deg, #f7f9ff 0%, #f1f5ff 50%, #edf4ff 100%); }
        .header { height: 56px; display: flex; align-items: center; justify-content: space-between; padding: 0 14px; border: 1px solid #dfe6f5; border-radius: 14px; background: linear-gradient(135deg, rgba(255,255,255,0.9), rgba(240,246,255,0.86)); }
        .h-title { font-size: 18px; font-weight: 700; color: #0f172a; } .btn { border: 1px solid #c7d2fe; background: linear-gradient(135deg, #6366f1, #4f46e5); color: #fff; border-radius: 10px; padding: 8px 12px; text-decoration: none; font-size: 12px; font-weight: 600; }
        .content { flex: 1; min-height: 0; overflow: auto; border-radius: var(--radius); border: 1px solid #e2e8f0; background: linear-gradient(160deg, rgba(255,255,255,0.94), rgba(244,249,255,0.9)); box-shadow: 0 8px 18px rgba(15, 23, 42, 0.06); padding: 14px; }
        .stats { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: var(--gap); margin-bottom: 12px; } .card { border-radius: 14px; padding: 10px; color: #fff; }
        .c1 { background: linear-gradient(135deg, #73d9ef, #5aa8f2); } .c2 { background: linear-gradient(135deg, #9cb5ff, #6d90f6); } .c3 { background: linear-gradient(135deg, #f5add1, #ea7fbe); } .c4 { background: linear-gradient(135deg, #d2bcff, #a88ef0); }
        table { width: 100%; border-collapse: collapse; font-size: 13px; } th, td { padding: 10px 8px; text-align: left; border-bottom: 1px solid #e2e8f0; color: #334155; } th { font-size: 12px; text-transform: uppercase; color: #64748b; }
        .pill { font-size: 11px; padding: 4px 8px; border-radius: 999px; background: #eef2ff; color: #4338ca; border: 1px solid #c7d2fe; }
        .row-actions { display: flex; gap: 8px; } .link { color: #4338ca; text-decoration: none; font-weight: 600; background: none; border: none; padding: 0; cursor: pointer; } .danger { color: #be123c; background: none; border: none; padding: 0; font: inherit; cursor: pointer; font-weight: 600; }
        .modal-backdrop { position: fixed; inset: 0; background: rgba(15, 23, 42, 0.5); backdrop-filter: blur(6px); display: none; align-items: center; justify-content: center; z-index: 50; padding: 16px; }
.modal-backdrop.show { display: flex; }
.modal-card { width: min(700px, 96vw); max-height: min(90vh, 860px); overflow: hidden; display: flex; flex-direction: column; border-radius: 20px; border: 1px solid rgba(226, 232, 240, 0.95); background: linear-gradient(165deg, #ffffff 0%, #f8fafc 42%, #f1f5f9 100%); box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.28), 0 0 0 1px rgba(255, 255, 255, 0.65) inset; }
.modal-head { flex-shrink: 0; display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 18px 22px; margin: 0; border-bottom: 1px solid #e2e8f0; background: linear-gradient(135deg, rgba(99, 102, 241, 0.12) 0%, rgba(139, 92, 246, 0.06) 55%, rgba(248, 250, 252, 0.9) 100%); }
.modal-title-wrap { display: flex; flex-direction: column; gap: 4px; }
.modal-title { font-size: 19px; font-weight: 800; letter-spacing: -0.03em; color: #0f172a; margin: 0; line-height: 1.2; }
.modal-subtitle { font-size: 12px; font-weight: 500; color: #64748b; margin: 0; }
.modal-head-actions { display: flex; align-items: center; gap: 8px; }
.close-btn { border: 1px solid #e2e8f0; background: rgba(255, 255, 255, 0.85); border-radius: 11px; padding: 8px 14px; cursor: pointer; font-weight: 600; font-size: 13px; color: #475569; transition: background 0.15s ease, border-color 0.15s ease; }
.close-btn:hover { background: #fff; border-color: #cbd5e1; color: #0f172a; }
.modal-body { overflow: auto; flex: 1; min-height: 0; padding: 18px 22px 22px; -webkit-overflow-scrolling: touch; }
.form-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px 14px; }
.form-grid input, .form-grid select, .form-grid textarea { width: 100%; border: 1px solid #cbd5e1; border-radius: 11px; padding: 10px 12px; font-size: 13px; background: #fff; transition: border-color 0.15s ease, box-shadow 0.15s ease; }
.form-grid input:hover, .form-grid select:hover, .form-grid textarea:hover { border-color: #94a3b8; }
.form-grid input:focus, .form-grid select:focus, .form-grid textarea:focus { outline: none; border-color: #818cf8; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.18); }
.form-grid select { appearance: none; -webkit-appearance: none; -moz-appearance: none; padding-right: 38px; background-color: #fff; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='none' stroke='%2364748b' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round' d='M2 4l4 4 4-4'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: calc(100% - 12px) 50%; background-size: 12px 12px; }
.field label { display: block; font-size: 11px; font-weight: 700; margin-bottom: 5px; color: #475569; letter-spacing: 0.01em; }
.span-2 { grid-column: span 2; }
.form-actions { display: flex; gap: 8px; justify-content: flex-end; margin-top: 4px; padding-top: 18px; border-top: 1px solid #e2e8f0; }
.btn-secondary { border: 1px solid #c7d2fe; background: #fff; color: #3730a3; border-radius: 11px; padding: 10px 18px; font-size: 13px; font-weight: 600; cursor: pointer; }
    </style>
</head>
<body>
@php
    $total = $documents->total();
    $pending = \App\Models\Document::where('status', 'pending')->count();
    $processing = \App\Models\Document::where('status', 'processing')->count();
    $completed = \App\Models\Document::where('status', 'completed')->count();
    $userRole = strtolower((string) (auth()->user()->role ?? ''));
    $sidebarModules = match (true) {
        $userRole === 'documentation' => [
            ['label' => 'Dashboard', 'route' => 'documentationdashboard'],
            ['label' => 'Documents', 'route' => 'documents.index'],
            ['label' => 'Title Transfers', 'route' => 'title-transfers.index'],
            ['label' => 'Customers', 'route' => 'customers.index'],
            ['label' => 'Properties', 'route' => 'properties.index'],
            ['label' => 'Reports', 'route' => 'reports.index'],
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
<main class="main"><header class="header"><div class="h-title">Document Module</div><button type="button" class="btn" id="openCreateDocumentModal">+ Add Document</button></header>
<section class="content">
<div class="stats"><div class="card c1"><strong>Total Documents</strong><div>{{ $total }}</div></div><div class="card c2"><strong>Pending</strong><div>{{ $pending }}</div></div><div class="card c3"><strong>Processing</strong><div>{{ $processing }}</div></div><div class="card c4"><strong>Customers Missing Requirements</strong><div>{{ $incompleteCustomers->count() }}</div></div></div>
@if($incompleteCustomers->isNotEmpty())
<div class="notice-panel">
    <div class="notice-title">Customers with incomplete housing loan documents</div>
    <table class="notice-table">
        <thead>
            <tr>
                <th>Customer</th>
                <th>Property</th>
                <th>Missing Requirements</th>
                <th>Checklist</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($incompleteCustomers as $customer)
                <tr>
                    <td>
                        {{ $customer->first_name }} {{ $customer->last_name }}<br>
                        <span class="tiny">{{ $customer->phone ?: 'No mobile number' }}</span>
                    </td>
                    <td>
                        {{ $customer->selectedProperty?->block_number ? 'Block '.$customer->selectedProperty->block_number.', Lot '.$customer->selectedProperty->lot_number : 'No property linked' }}
                    </td>
                    <td>{{ implode(', ', $customer->missingChecklistItems()) }}</td>
                    <td>{{ $customer->approvedChecklistCount() }}/7 approved</td>
                    <td><span class="pill">{{ str_replace('_', ' ', $customer->status) }}</span></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
<table><thead><tr><th>Title</th><th>Type</th><th>Customer</th><th>Status</th><th>Actions</th></tr></thead><tbody>@forelse ($documents as $document)<tr><td>{{ $document->title ?: 'Untitled' }}</td><td>{{ $document->document_type }}</td><td>{{ $document->customer->first_name }} {{ $document->customer->last_name }}</td><td><span class="pill">{{ $document->status }}</span></td><td><div class="row-actions"><button type="button" class="link editDocumentBtn" data-id="{{ $document->id }}" data-customer_id="{{ $document->customer_id }}" data-property_id="{{ $document->property_id }}" data-document_type="{{ $document->document_type }}" data-status="{{ $document->status }}" data-title="{{ $document->title }}" data-due_date="{{ $document->due_date }}" data-completed_at="{{ $document->completed_at }}" data-notes="{{ $document->notes }}">Edit</button><form method="POST" action="{{ route('documents.destroy', $document) }}">@csrf @method('DELETE')<button class="danger" onclick="return confirm('Delete this document?')">Delete</button></form></div></td></tr>@empty<tr><td colspan="5">No document records yet.</td></tr>@endforelse</tbody></table>
<div style="margin-top:12px;">{{ $documents->links() }}</div></section></main></div>
<div class="modal-backdrop" id="createDocumentModal" aria-hidden="true">
    <div class="modal-card" role="dialog" aria-labelledby="createDocumentTitle">
        <div class="modal-head">
            <div class="modal-title-wrap">
                <h2 class="modal-title" id="createDocumentTitle">Add document</h2>
                <p class="modal-subtitle">Link to customer, property &amp; document details</p>
            </div>
            <div class="modal-head-actions">
                <button type="button" class="close-btn" id="closeCreateDocumentModal">Close</button>
            </div>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('documents.store') }}" class="form-grid">
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
                    <select name="property_id">
                        <option value="">No property linked</option>
                        @foreach (\App\Models\Property::orderBy('block_number')->get() as $property)
                            <option value="{{ $property->id }}" @selected(old('property_id') == $property->id)>Block {{ $property->block_number }} / Lot {{ $property->lot_number }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Document type</label>
                    <select name="document_type" required>
                        @foreach (['contract_to_sell' => 'Contract to Sell', 'deed_of_absolute_sale' => 'Deed of Absolute Sale', 'bir_related' => 'BIR Related', 'other' => 'Other'] as $val => $label)
                            <option value="{{ $val }}" @selected(old('document_type', 'contract_to_sell') === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Status</label>
                    <select name="status" required>
                        @foreach (['pending' => 'Pending', 'processing' => 'Processing', 'completed' => 'Completed'] as $val => $label)
                            <option value="{{ $val }}" @selected(old('status', 'pending') === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field span-2">
                    <label>Title</label>
                    <input name="title" placeholder="Document title" value="{{ old('title') }}">
                </div>
                <div class="field">
                    <label>Due date</label>
                    <input name="due_date" type="date" value="{{ old('due_date') }}">
                </div>
                <div class="field">
                    <label>Completed at</label>
                    <input name="completed_at" type="date" value="{{ old('completed_at') }}">
                </div>
                <div class="field span-2">
                    <label>Notes</label>
                    <textarea name="notes" rows="3" placeholder="Additional notes…">{{ old('notes') }}</textarea>
                </div>
                <div class="span-2 form-actions">
                    <button type="button" class="btn-secondary" id="cancelCreateDocumentModal">Cancel</button>
                    <button type="submit" class="btn">Save document</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal-backdrop" id="editDocumentModal" aria-hidden="true">
    <div class="modal-card" role="dialog" aria-labelledby="editDocumentTitle">
        <div class="modal-head">
            <div class="modal-title-wrap">
                <h2 class="modal-title" id="editDocumentTitle">Edit document</h2>
                <p class="modal-subtitle">Update document type, status &amp; details</p>
            </div>
            <div class="modal-head-actions">
                <button type="button" class="close-btn" id="closeEditDocumentModal">Close</button>
            </div>
        </div>
        <div class="modal-body">
            <form method="POST" id="editDocumentForm" class="form-grid">
                @csrf
                @method('PUT')
                <div class="field">
                    <label>Customer</label>
                    <select name="customer_id" id="editDocumentCustomerId" required>
                        @foreach (\App\Models\Customer::orderBy('last_name')->get() as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->first_name }} {{ $customer->last_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Property</label>
                    <select name="property_id" id="editDocumentPropertyId">
                        <option value="">No property linked</option>
                        @foreach (\App\Models\Property::orderBy('block_number')->get() as $property)
                            <option value="{{ $property->id }}">Block {{ $property->block_number }} / Lot {{ $property->lot_number }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Document type</label>
                    <select name="document_type" id="editDocumentType" required>
                        @foreach (['contract_to_sell' => 'Contract to Sell', 'deed_of_absolute_sale' => 'Deed of Absolute Sale', 'bir_related' => 'BIR Related', 'other' => 'Other'] as $val => $label)
                            <option value="{{ $val }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Status</label>
                    <select name="status" id="editDocumentStatus" required>
                        @foreach (['pending' => 'Pending', 'processing' => 'Processing', 'completed' => 'Completed'] as $val => $label)
                            <option value="{{ $val }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field span-2">
                    <label>Title</label>
                    <input name="title" id="editDocumentTitle" placeholder="Document title">
                </div>
                <div class="field">
                    <label>Due date</label>
                    <input name="due_date" id="editDocumentDueDate" type="date">
                </div>
                <div class="field">
                    <label>Completed at</label>
                    <input name="completed_at" id="editDocumentCompletedAt" type="date">
                </div>
                <div class="field span-2">
                    <label>Notes</label>
                    <textarea name="notes" id="editDocumentNotes" rows="3" placeholder="Additional notes…"></textarea>
                </div>
                <div class="span-2 form-actions">
                    <button type="button" class="btn-secondary" id="cancelEditDocumentModal">Cancel</button>
                    <button type="submit" class="btn">Update document</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    const documentModal = document.getElementById('createDocumentModal');
    const openDocumentBtn = document.getElementById('openCreateDocumentModal');
    const closeDocumentBtn = document.getElementById('closeCreateDocumentModal');
    const cancelDocumentBtn = document.getElementById('cancelCreateDocumentModal');

    const openDocumentModal = () => documentModal.classList.add('show');
    const closeDocumentModal = () => documentModal.classList.remove('show');

    openDocumentBtn.addEventListener('click', openDocumentModal);
    closeDocumentBtn.addEventListener('click', closeDocumentModal);
    cancelDocumentBtn.addEventListener('click', closeDocumentModal);
    documentModal.addEventListener('click', (e) => {
        if (e.target === documentModal) closeDocumentModal();
    });

    const editDocumentModal = document.getElementById('editDocumentModal');
    const editDocumentForm = document.getElementById('editDocumentForm');
    const closeEditDocumentBtn = document.getElementById('closeEditDocumentModal');
    const cancelEditDocumentBtn = document.getElementById('cancelEditDocumentModal');
    const openEditDocumentModal = () => editDocumentModal.classList.add('show');
    const closeEditDocumentModal = () => editDocumentModal.classList.remove('show');

    document.querySelectorAll('.editDocumentBtn').forEach((button) => {
        button.addEventListener('click', () => {
            editDocumentForm.action = `/documents/${button.dataset.id}`;
            document.getElementById('editDocumentCustomerId').value = button.dataset.customer_id || '';
            document.getElementById('editDocumentPropertyId').value = button.dataset.property_id || '';
            document.getElementById('editDocumentType').value = button.dataset.document_type || 'contract_to_sell';
            document.getElementById('editDocumentStatus').value = button.dataset.status || 'pending';
            document.getElementById('editDocumentTitle').value = button.dataset.title || '';
            document.getElementById('editDocumentDueDate').value = button.dataset.due_date || '';
            document.getElementById('editDocumentCompletedAt').value = button.dataset.completed_at || '';
            document.getElementById('editDocumentNotes').value = button.dataset.notes || '';
            openEditDocumentModal();
        });
    });

    closeEditDocumentBtn.addEventListener('click', closeEditDocumentModal);
    cancelEditDocumentBtn.addEventListener('click', closeEditDocumentModal);
    editDocumentModal.addEventListener('click', (e) => {
        if (e.target === editDocumentModal) closeEditDocumentModal();
    });
</script>
</body>
</html>
