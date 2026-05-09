<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Customer Management</title>
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
        .btn-primary { background: linear-gradient(135deg, #6366f1, #4f46e5); color: #fff; border-color: transparent; }
        .content { flex: 1; min-height: 0; overflow: auto; border-radius: var(--radius); border: 1px solid #e2e8f0; background: linear-gradient(160deg, rgba(255,255,255,0.94), rgba(244,249,255,0.9)); box-shadow: 0 8px 18px rgba(15, 23, 42, 0.06); padding: 14px; }
        .stats { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: var(--gap); margin-bottom: 12px; }
        .card { border-radius: 14px; padding: 10px; color: #fff; }
        .c1 { background: linear-gradient(135deg, #73d9ef, #5aa8f2); }
        .c2 { background: linear-gradient(135deg, #9cb5ff, #6d90f6); }
        .c3 { background: linear-gradient(135deg, #f5add1, #ea7fbe); }
        .c4 { background: linear-gradient(135deg, #d2bcff, #a88ef0); }
        .flash { margin-bottom: 12px; padding: 10px 12px; border-radius: 10px; font-size: 13px; background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; }
        .error-box { margin-bottom: 12px; padding: 10px 12px; border-radius: 10px; font-size: 13px; background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        th, td { padding: 10px 8px; text-align: left; border-bottom: 1px solid #e2e8f0; color: #334155; vertical-align: top; }
        th { font-size: 12px; text-transform: uppercase; color: #64748b; }
        .pill { font-size: 11px; padding: 4px 8px; border-radius: 999px; background: #eef2ff; color: #4338ca; border: 1px solid #c7d2fe; display: inline-block; }
        .muted { color: #64748b; font-size: 12px; }
        .row-actions { display: flex; gap: 8px; flex-wrap: wrap; }
        .link { color: #4338ca; text-decoration: none; font-weight: 600; background: none; border: none; padding: 0; cursor: pointer; }
        .danger { color: #be123c; background: none; border: none; padding: 0; font: inherit; cursor: pointer; font-weight: 600; }

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
        .view-icon   { color: #0369a1; }
        .view-icon:hover  { background: #f0f9ff; border-color: #bae6fd; }
        .edit-icon   { color: #4338ca; }
        .edit-icon:hover  { background: #eef2ff; border-color: #c7d2fe; }
        .delete-icon { color: #be123c; }
        .delete-icon:hover { background: #fff1f2; border-color: #fecaca; }

        /* ── Modals ── */
        .modal-backdrop { position: fixed; inset: 0; background: rgba(15, 23, 42, 0.5); backdrop-filter: blur(6px); display: none; align-items: center; justify-content: center; z-index: 50; padding: 16px; }
        .modal-backdrop.show { display: flex; }
        .customer-modal .modal-card {
            width: min(1080px, 96vw);
            max-height: min(90vh, 900px);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            border-radius: 20px;
            border: 1px solid rgba(226, 232, 240, 0.95);
            background: linear-gradient(165deg, #ffffff 0%, #f8fafc 42%, #f1f5f9 100%);
            box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.28), 0 0 0 1px rgba(255, 255, 255, 0.65) inset;
        }
        .customer-modal .modal-head {
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 18px 22px;
            margin: 0;
            border-bottom: 1px solid #e2e8f0;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.12) 0%, rgba(139, 92, 246, 0.06) 55%, rgba(248, 250, 252, 0.9) 100%);
        }
        .customer-modal .modal-title-wrap { display: flex; flex-direction: column; gap: 4px; }
        .customer-modal .modal-title { font-size: 19px; font-weight: 800; letter-spacing: -0.03em; color: #0f172a; margin: 0; line-height: 1.2; }
        .customer-modal .modal-subtitle { font-size: 12px; font-weight: 500; color: #64748b; margin: 0; }
        .customer-modal .modal-head-actions { display: flex; align-items: center; gap: 8px; }
        .customer-modal .close-btn { border: 1px solid #e2e8f0; background: rgba(255, 255, 255, 0.85); border-radius: 11px; padding: 8px 14px; cursor: pointer; font-weight: 600; font-size: 13px; color: #475569; transition: background 0.15s ease, border-color 0.15s ease; }
        .customer-modal .close-btn:hover { background: #fff; border-color: #cbd5e1; color: #0f172a; }
        .customer-modal .modal-body { overflow: auto; flex: 1; min-height: 0; padding: 18px 22px 22px; -webkit-overflow-scrolling: touch; }
        .customer-modal .form-actions { margin-top: 4px; padding-top: 18px; border-top: 1px solid #e2e8f0; background: linear-gradient(180deg, rgba(248, 250, 252, 0) 0%, #f8fafc 18%); }
        .customer-modal .btn { border-radius: 11px; padding: 10px 18px; font-size: 13px; }
        .customer-modal .btn-primary { box-shadow: 0 4px 14px rgba(79, 70, 229, 0.32); }
        .customer-modal .form-grid { margin: 0; }

        /* ── Forms ── */
        .form-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px 14px; }
        .form-grid input, .form-grid select, .form-grid textarea { width: 100%; border: 1px solid #cbd5e1; border-radius: 11px; padding: 10px 12px; font-size: 13px; background: #fff; transition: border-color 0.15s ease, box-shadow 0.15s ease; }
        .form-grid input:hover, .form-grid select:hover, .form-grid textarea:hover { border-color: #94a3b8; }
        .form-grid input:focus, .form-grid select:focus, .form-grid textarea:focus { outline: none; border-color: #818cf8; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.18); }
        .form-grid select { appearance: none; -webkit-appearance: none; -moz-appearance: none; padding-right: 38px; background-color: #fff; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='none' stroke='%2364748b' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round' d='M2 4l4 4 4-4'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: calc(100% - 12px) 50%; background-size: 12px 12px; }
        .form-grid input[type="checkbox"] { width: auto; }
        .field label { display: block; font-size: 11px; font-weight: 700; margin-bottom: 5px; color: #475569; letter-spacing: 0.01em; }
        .field.hidden { display: none !important; }
        .field.span-2 { grid-column: span 2; }
        .span-2 { grid-column: span 2; }
        .customer-modal .section-title { grid-column: span 2; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: #6366f1; margin-top: 18px; padding-top: 14px; border-top: 1px solid #e2e8f0; }
        .customer-modal .section-title:first-of-type { margin-top: 0; padding-top: 0; border-top: none; color: #4f46e5; }
        .checkbox-grid { grid-column: span 2; display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 8px 16px; }
        .checkbox-item { display: flex; align-items: center; gap: 8px; font-size: 13px; color: #334155; }
        .form-actions { display: flex; gap: 8px; justify-content: flex-end; margin-top: 4px; }
        .section-note { grid-column: span 2; font-size: 12px; color: #64748b; margin-top: -2px; }

        /* ── View modal readonly fields ── */
        #viewCustomerGrid input[readonly],
        #viewCustomerGrid textarea[readonly] {
            background: #f8fafc;
            color: #475569;
            cursor: default;
            border-color: #e2e8f0;
        }
        #viewCustomerGrid input[readonly]:hover,
        #viewCustomerGrid textarea[readonly]:hover {
            border-color: #e2e8f0;
        }

        @media (max-width: 1000px) {
            .stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .form-grid, .checkbox-grid { grid-template-columns: 1fr; }
            .customer-modal .section-title, .section-note, .span-2, .field.span-2 { grid-column: span 1; }
        }
    </style>
</head>
<body>
@php
    $userRole = strtolower((string) (auth()->user()->role ?? ''));
    $isAdmin = $userRole === 'admin';
    $canCreate = in_array($userRole, ['marketing', 'admin'], true);
    $canDelete = in_array($userRole, ['marketing', 'admin'], true);
    $isMarketing = $userRole === 'marketing';
    $isDocumentation = $userRole === 'documentation';
    $isManager = $userRole === 'manager';

    $customerStatuses = [
        'new_applicant' => 'New Applicant',
        'for_profiling' => 'For Profiling',
        'requirements_incomplete' => 'Requirements Incomplete',
        'eligible_for_submission' => 'Eligible for Submission',
        'submitted_to_financing' => 'Submitted to Financing',
        'under_review' => 'Under Review',
        'approved' => 'Approved',
        'declined' => 'Declined',
        'pending_compliance' => 'Pending Compliance',
        'released' => 'Released',
        'moved_in' => 'Moved In',
    ];
    $documentStatuses = [
        'pending' => 'Pending',
        'received' => 'Received',
        'approved' => 'Approved',
        'for_resubmission' => 'For Resubmission',
    ];
    $financingTypes = [
        'pagibig' => 'Pag-IBIG',
        'bank_financing' => 'Bank Financing',
        'in_house_financing' => 'In-House Financing',
    ];

    $statusOptions = match (true) {
        $isMarketing => ['new_applicant', 'for_profiling'],
        $isDocumentation => ['requirements_incomplete', 'eligible_for_submission', 'submitted_to_financing', 'pending_compliance'],
        $isManager => ['under_review', 'approved', 'declined', 'released', 'moved_in'],
        default => array_keys($customerStatuses),
    };

    $statusCounts = [
        'new_applicant' => \App\Models\Customer::where('status', 'new_applicant')->count(),
        'requirements_incomplete' => \App\Models\Customer::where('status', 'requirements_incomplete')->count(),
        'under_review' => \App\Models\Customer::where('status', 'under_review')->count(),
        'approved' => \App\Models\Customer::where('status', 'approved')->count(),
    ];

    $sidebarModules = match (true) {
        $userRole === 'documentation' => [
            ['label' => 'Dashboard', 'route' => 'documentationdashboard'],
            ['label' => 'Documents', 'route' => 'documents.index'],
            ['label' => 'Title Transfers', 'route' => 'title-transfers.index'],
            ['label' => 'Customers', 'route' => 'customers.index'],
            ['label' => 'Properties', 'route' => 'properties.index'],
            ['label' => 'Reports', 'route' => 'reports.index'],
        ],
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
            <div class="h-title">Customer Loan Profiling</div>
            <div class="top-actions">
                @if($canCreate)
                    <button type="button" class="btn btn-primary" id="openCreateCustomerModal">+ Add Customer</button>
                @endif
            </div>
        </header>

        <section class="content">
            @if(session('success'))
                <div class="flash">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="error-box">{{ $errors->first() }}</div>
            @endif

            <div class="stats">
                <div class="card c1"><strong>New Applicants</strong><div>{{ $statusCounts['new_applicant'] }}</div></div>
                <div class="card c2"><strong>Requirements Incomplete</strong><div>{{ $statusCounts['requirements_incomplete'] }}</div></div>
                <div class="card c3"><strong>Under Review</strong><div>{{ $statusCounts['under_review'] }}</div></div>
                <div class="card c4"><strong>Approved</strong><div>{{ $statusCounts['approved'] }}</div></div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Applicant</th>
                        <th>Employment / Income</th>
                        <th>Property / Financing</th>
                        <th>Checklist</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customers as $customer)
                        @php
                            $checklistFields = [
                                $customer->valid_id_status,
                                $customer->proof_of_billing_status,
                                $customer->proof_of_income_status,
                                $customer->birth_certificate_status,
                                $customer->marriage_certificate_status,
                                $customer->reservation_form_status,
                                $customer->financing_documents_status,
                            ];
                            $approvedChecklist = collect($checklistFields)->filter(fn ($value) => $value === 'approved')->count();
                            $customerPayload = [
                                'id' => $customer->id,
                                'first_name' => $customer->first_name,
                                'last_name' => $customer->last_name,
                                'middle_name' => $customer->middle_name,
                                'email' => $customer->email,
                                'phone' => $customer->phone,
                                'present_address_street' => $customer->present_address_street ?: $customer->address,
                                'present_address_barangay' => $customer->present_address_barangay,
                                'present_address_city' => $customer->present_address_city,
                                'present_address_province' => $customer->present_address_province,
                                'present_address_zip' => $customer->present_address_zip,
                                'date_of_birth' => optional($customer->date_of_birth)->format('Y-m-d'),
                                'civil_status' => $customer->civil_status,
                                'citizenship' => $customer->citizenship,
                                'tin_number' => $customer->tin_number,
                                'sss_gsis_number' => $customer->sss_gsis_number,
                                'pagibig_mid_number' => $customer->pagibig_mid_number,
                                'spouse_first_name' => $customer->spouse_first_name ?: $customer->spouse_name,
                                'spouse_middle_name' => $customer->spouse_middle_name,
                                'spouse_last_name' => $customer->spouse_last_name,
                                'spouse_employment' => $customer->spouse_employment,
                                'spouse_monthly_income' => $customer->spouse_monthly_income,
                                'employment_status' => $customer->employment_status,
                                'employer_name' => $customer->employer_name,
                                'job_title' => $customer->job_title,
                                'business_name' => $customer->business_name,
                                'business_nature' => $customer->business_nature,
                                'years_employed' => $customer->years_employed,
                                'years_in_business' => $customer->years_in_business,
                                'ofw_employer_name' => $customer->ofw_employer_name,
                                'ofw_country' => $customer->ofw_country,
                                'monthly_income' => $customer->monthly_income,
                                'is_pagibig_member' => (bool) $customer->is_pagibig_member,
                                'has_required_pagibig_contributions' => (bool) $customer->has_required_pagibig_contributions,
                                'has_outstanding_debts' => (bool) $customer->has_outstanding_debts,
                                'savings_amount' => $customer->savings_amount,
                                'has_downpayment_capacity' => (bool) $customer->has_downpayment_capacity,
                                'has_stable_income' => (bool) $customer->has_stable_income,
                                'selected_property_id' => $customer->selected_property_id,
                                'project_name' => $customer->project_name,
                                'contract_price' => $customer->contract_price,
                                'reservation_fee_amount' => $customer->reservation_fee_amount,
                                'downpayment_amount' => $customer->downpayment_amount,
                                'preferred_financing_type' => $customer->preferred_financing_type,
                                'monthly_amortization_estimate' => $customer->monthly_amortization_estimate,
                                'affordability_notes' => $customer->affordability_notes,
                                'qualification_notes' => $customer->qualification_notes,
                                'documentation_notes' => $customer->documentation_notes,
                                'manager_notes' => $customer->manager_notes,
                                'valid_id_status' => $customer->valid_id_status,
                                'proof_of_billing_status' => $customer->proof_of_billing_status,
                                'proof_of_income_status' => $customer->proof_of_income_status,
                                'birth_certificate_status' => $customer->birth_certificate_status,
                                'marriage_certificate_status' => $customer->marriage_certificate_status,
                                'reservation_form_status' => $customer->reservation_form_status,
                                'financing_documents_status' => $customer->financing_documents_status,
                                'profile_notes' => $customer->profile_notes,
                                'contact_person_name' => $customer->contact_person_name,
                                'contact_person_phone' => $customer->contact_person_phone,
                                'status' => $customer->status,
                            ];
                        @endphp
                        <tr>
                            <td>
                                <strong>{{ $customer->last_name }}, {{ $customer->first_name }} {{ $customer->middle_name }}</strong><br>
                                <span class="muted">{{ $customer->email ?: 'No email' }}</span><br>
                                <span class="muted">{{ $customer->phone ?: 'No mobile number' }}</span>
                            </td>
                            <td>
                                {{ ucfirst(str_replace('_', ' ', $customer->employment_status)) }}<br>
                                <span class="muted">Income: PHP {{ number_format((float) ($customer->monthly_income ?? 0), 2) }}</span>
                            </td>
                            <td>
                                {{ $customer->project_name ?: 'Midland Valley Homes' }}<br>
                                <span class="muted">
                                    {{ $customer->selectedProperty?->block_number ? 'Block '.$customer->selectedProperty->block_number.', Lot '.$customer->selectedProperty->lot_number : 'No property linked' }}
                                </span><br>
                                <span class="muted">{{ $financingTypes[$customer->preferred_financing_type] ?? 'No financing selected' }}</span>
                            </td>
                            <td>
                                <span class="pill">{{ $approvedChecklist }}/7 approved</span><br>
                                <span class="muted">Docs handled by Documentation</span>
                            </td>
                            <td><span class="pill">{{ $customerStatuses[$customer->status] ?? ucfirst(str_replace('_', ' ', $customer->status)) }}</span></td>
                            <td>
                                <div class="row-actions">
                                    {{-- VIEW --}}
                                    <button type="button" class="icon-btn view-icon viewCustomerBtn" aria-label="View customer" data-customer='@json($customerPayload)'>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </button>
                                    {{-- EDIT --}}
                                    <button type="button" class="icon-btn edit-icon editCustomerBtn" aria-label="Edit customer" data-customer='@json($customerPayload)'>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4Z"/></svg>
                                    </button>
                                    {{-- DELETE --}}
                                    @if($canDelete)
                                        <button
                                            type="button"
                                            class="icon-btn delete-icon deleteCustomerBtn"
                                            aria-label="Delete customer"
                                            data-id="{{ $customer->id }}"
                                            data-name="{{ $customer->last_name }}, {{ $customer->first_name }} {{ $customer->middle_name }}"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6">No customer records yet.</td></tr>
                    @endforelse
                </tbody>
            </table>

            <div style="margin-top:12px;">{{ $customers->links() }}</div>
        </section>
    </main>
</div>

@php
    $employmentOptions = ['employed', 'self_employed', 'ofw', 'unemployed', 'contractual', 'retired'];
    $civilStatusOptions = ['single', 'married', 'widowed', 'separated'];
@endphp

{{-- ══════════════════════════════════════════════════════════
     CREATE MODAL
══════════════════════════════════════════════════════════ --}}
@if($canCreate)
<div class="modal-backdrop customer-modal" id="createCustomerModal" aria-hidden="true">
    <div class="modal-card" role="dialog" aria-labelledby="createCustomerModalTitle">
        <div class="modal-head">
            <div class="modal-title-wrap">
                <h2 class="modal-title" id="createCustomerModalTitle">Create customer</h2>
                <p class="modal-subtitle">Applicant profile, address, employment &amp; financing</p>
            </div>
            <div class="modal-head-actions">
                <button type="button" class="close-btn" id="closeCreateCustomerModal">Close</button>
            </div>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('customers.store') }}" class="form-grid">
                @csrf
                @include('customers.partials.loan-form-fields', ['prefix' => 'create_', 'properties' => $properties, 'customerStatuses' => $customerStatuses, 'statusOptions' => $statusOptions, 'documentStatuses' => $documentStatuses, 'employmentOptions' => $employmentOptions, 'civilStatusOptions' => $civilStatusOptions, 'financingTypes' => $financingTypes, 'userRole' => $userRole, 'isCreate' => true])
                <div class="span-2 form-actions">
                    <button type="button" class="btn" id="cancelCreateCustomerModal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save customer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

{{-- ══════════════════════════════════════════════════════════
     EDIT MODAL
══════════════════════════════════════════════════════════ --}}
<div class="modal-backdrop customer-modal" id="editCustomerModal" aria-hidden="true">
    <div class="modal-card" role="dialog" aria-labelledby="editCustomerModalTitle">
        <div class="modal-head">
            <div class="modal-title-wrap">
                <h2 class="modal-title" id="editCustomerModalTitle">Edit customer</h2>
                <p class="modal-subtitle">Update profile, documents checklist &amp; workflow status</p>
            </div>
            <div class="modal-head-actions">
                <button type="button" class="close-btn" id="closeEditCustomerModal">Close</button>
            </div>
        </div>
        <div class="modal-body">
            <form method="POST" id="editCustomerForm" class="form-grid">
                @csrf
                @method('PUT')
                @include('customers.partials.loan-form-fields', ['prefix' => 'edit_', 'properties' => $properties, 'customerStatuses' => $customerStatuses, 'statusOptions' => $statusOptions, 'documentStatuses' => $documentStatuses, 'employmentOptions' => $employmentOptions, 'civilStatusOptions' => $civilStatusOptions, 'financingTypes' => $financingTypes, 'userRole' => $userRole, 'isCreate' => false])
                <div class="span-2 form-actions">
                    <button type="button" class="btn" id="cancelEditCustomerModal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update customer</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     VIEW MODAL
══════════════════════════════════════════════════════════ --}}
<div class="modal-backdrop customer-modal" id="viewCustomerModal" aria-hidden="true">
    <div class="modal-card" role="dialog" aria-labelledby="viewCustomerModalTitle">
        <div class="modal-head">
            <div class="modal-title-wrap">
                <h2 class="modal-title" id="viewCustomerModalTitle">View customer</h2>
                <p class="modal-subtitle" id="viewCustomerModalSubtitle">Customer profile &amp; details</p>
            </div>
            <div class="modal-head-actions">
                <button type="button" class="close-btn" id="closeViewCustomerModal">Close</button>
            </div>
        </div>
        <div class="modal-body">
            <div class="form-grid" id="viewCustomerGrid">

                <div class="section-title" style="grid-column:span 2;">Personal Information</div>

                <div class="field">
                    <label>First name</label>
                    <input id="view_first_name" readonly>
                </div>
                <div class="field">
                    <label>Last name</label>
                    <input id="view_last_name" readonly>
                </div>
                <div class="field">
                    <label>Middle name</label>
                    <input id="view_middle_name" readonly>
                </div>
                <div class="field">
                    <label>Date of birth</label>
                    <input id="view_date_of_birth" type="date" readonly>
                </div>
                <div class="field">
                    <label>Civil status</label>
                    <input id="view_civil_status" readonly>
                </div>
                <div class="field">
                    <label>Citizenship</label>
                    <input id="view_citizenship" readonly>
                </div>
                <div class="field">
                    <label>Email</label>
                    <input id="view_email" readonly>
                </div>
                <div class="field">
                    <label>Phone</label>
                    <input id="view_phone" readonly>
                </div>
                <div class="field">
                    <label>TIN number</label>
                    <input id="view_tin_number" readonly>
                </div>
                <div class="field">
                    <label>SSS / GSIS number</label>
                    <input id="view_sss_gsis_number" readonly>
                </div>
                <div class="field">
                    <label>Pag-IBIG MID number</label>
                    <input id="view_pagibig_mid_number" readonly>
                </div>

                <div class="section-title" style="grid-column:span 2;">Present Address</div>

                <div class="field span-2">
                    <label>Street</label>
                    <input id="view_present_address_street" readonly>
                </div>
                <div class="field">
                    <label>Barangay</label>
                    <input id="view_present_address_barangay" readonly>
                </div>
                <div class="field">
                    <label>City / Municipality</label>
                    <input id="view_present_address_city" readonly>
                </div>
                <div class="field">
                    <label>Province</label>
                    <input id="view_present_address_province" readonly>
                </div>
                <div class="field">
                    <label>ZIP code</label>
                    <input id="view_present_address_zip" readonly>
                </div>

                <div class="section-title" style="grid-column:span 2;">Spouse Information</div>

                <div class="field">
                    <label>Spouse first name</label>
                    <input id="view_spouse_first_name" readonly>
                </div>
                <div class="field">
                    <label>Spouse middle name</label>
                    <input id="view_spouse_middle_name" readonly>
                </div>
                <div class="field">
                    <label>Spouse last name</label>
                    <input id="view_spouse_last_name" readonly>
                </div>
                <div class="field">
                    <label>Spouse employment</label>
                    <input id="view_spouse_employment" readonly>
                </div>
                <div class="field">
                    <label>Spouse monthly income</label>
                    <input id="view_spouse_monthly_income" readonly>
                </div>

                <div class="section-title" style="grid-column:span 2;">Employment &amp; Income</div>

                <div class="field">
                    <label>Employment status</label>
                    <input id="view_employment_status" readonly>
                </div>
                <div class="field">
                    <label>Employer name</label>
                    <input id="view_employer_name" readonly>
                </div>
                <div class="field">
                    <label>Job title</label>
                    <input id="view_job_title" readonly>
                </div>
                <div class="field">
                    <label>Years employed</label>
                    <input id="view_years_employed" readonly>
                </div>
                <div class="field">
                    <label>Business name</label>
                    <input id="view_business_name" readonly>
                </div>
                <div class="field">
                    <label>Business nature</label>
                    <input id="view_business_nature" readonly>
                </div>
                <div class="field">
                    <label>Years in business</label>
                    <input id="view_years_in_business" readonly>
                </div>
                <div class="field">
                    <label>OFW employer name</label>
                    <input id="view_ofw_employer_name" readonly>
                </div>
                <div class="field">
                    <label>OFW country</label>
                    <input id="view_ofw_country" readonly>
                </div>
                <div class="field">
                    <label>Monthly income</label>
                    <input id="view_monthly_income" readonly>
                </div>
                <div class="field">
                    <label>Savings amount</label>
                    <input id="view_savings_amount" readonly>
                </div>

                <div class="section-title" style="grid-column:span 2;">Property &amp; Financing</div>

                <div class="field">
                    <label>Project name</label>
                    <input id="view_project_name" readonly>
                </div>
                <div class="field">
                    <label>Preferred financing</label>
                    <input id="view_preferred_financing_type" readonly>
                </div>
                <div class="field">
                    <label>Contract price</label>
                    <input id="view_contract_price" readonly>
                </div>
                <div class="field">
                    <label>Reservation fee</label>
                    <input id="view_reservation_fee_amount" readonly>
                </div>
                <div class="field">
                    <label>Downpayment amount</label>
                    <input id="view_downpayment_amount" readonly>
                </div>
                <div class="field">
                    <label>Monthly amortization estimate</label>
                    <input id="view_monthly_amortization_estimate" readonly>
                </div>
                <div class="field span-2">
                    <label>Affordability notes</label>
                    <textarea id="view_affordability_notes" rows="2" readonly></textarea>
                </div>

                <div class="section-title" style="grid-column:span 2;">Document Checklist</div>

                <div class="field">
                    <label>Valid ID</label>
                    <input id="view_valid_id_status" readonly>
                </div>
                <div class="field">
                    <label>Proof of billing</label>
                    <input id="view_proof_of_billing_status" readonly>
                </div>
                <div class="field">
                    <label>Proof of income</label>
                    <input id="view_proof_of_income_status" readonly>
                </div>
                <div class="field">
                    <label>Birth certificate</label>
                    <input id="view_birth_certificate_status" readonly>
                </div>
                <div class="field">
                    <label>Marriage certificate</label>
                    <input id="view_marriage_certificate_status" readonly>
                </div>
                <div class="field">
                    <label>Reservation form</label>
                    <input id="view_reservation_form_status" readonly>
                </div>
                <div class="field">
                    <label>Financing documents</label>
                    <input id="view_financing_documents_status" readonly>
                </div>

                <div class="section-title" style="grid-column:span 2;">Notes &amp; Status</div>

                <div class="field span-2">
                    <label>Profile notes</label>
                    <textarea id="view_profile_notes" rows="2" readonly></textarea>
                </div>
                <div class="field span-2">
                    <label>Qualification notes</label>
                    <textarea id="view_qualification_notes" rows="2" readonly></textarea>
                </div>
                <div class="field span-2">
                    <label>Documentation notes</label>
                    <textarea id="view_documentation_notes" rows="2" readonly></textarea>
                </div>
                <div class="field span-2">
                    <label>Manager notes</label>
                    <textarea id="view_manager_notes" rows="2" readonly></textarea>
                </div>
                <div class="field">
                    <label>Contact person</label>
                    <input id="view_contact_person_name" readonly>
                </div>
                <div class="field">
                    <label>Contact person phone</label>
                    <input id="view_contact_person_phone" readonly>
                </div>
                <div class="field">
                    <label>Status</label>
                    <input id="view_status" readonly>
                </div>

                <div class="span-2 form-actions">
                    <button type="button" class="btn" id="cancelViewCustomerModal">Close</button>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     DELETE CONFIRMATION MODAL
══════════════════════════════════════════════════════════ --}}
<div class="modal-backdrop" id="deleteConfirmModal" aria-hidden="true">
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
                <div style="font-size: 16px; font-weight: 800; color: #0f172a; letter-spacing: -0.02em;">Delete customer?</div>
                <div style="font-size: 12px; color: #64748b; margin-top: 2px;">This action cannot be undone</div>
            </div>
        </div>
        {{-- Body --}}
        <div style="padding: 18px 22px;">
            <p style="margin: 0 0 6px; font-size: 13px; color: #475569;">You are about to permanently delete:</p>
            <div style="
                background: #fef2f2; border: 1px solid #fecaca; border-radius: 10px;
                padding: 10px 14px; font-size: 14px; font-weight: 700; color: #be123c;
            " id="deleteCustomerName">—</div>
            <p style="margin: 10px 0 0; font-size: 12px; color: #94a3b8;">
                All associated records including profile, documents, and financing data will be removed.
            </p>
        </div>
        {{-- Footer --}}
        <div style="
            padding: 14px 22px 20px;
            display: flex; gap: 8px; justify-content: flex-end;
            border-top: 1px solid #f1f5f9;
        ">
            <button type="button" class="btn" id="cancelDeleteModal" style="border-radius:11px; padding:10px 18px; font-size:13px;">
                Cancel
            </button>
            <form method="POST" id="deleteCustomerForm">
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
    const modal      = document.getElementById('createCustomerModal');
    const editModal  = document.getElementById('editCustomerModal');
    const viewModal  = document.getElementById('viewCustomerModal');
    const editForm   = document.getElementById('editCustomerForm');
    const createForm = modal?.querySelector('form');

    /* ── Spouse section visibility ───────────────────── */
    function syncSpouseSection(form) {
        const civilSelect = form?.querySelector('[id$="_civil_status"]');
        const spouseWrap  = form?.querySelector('.js-spouse-section');
        if (!civilSelect || !spouseWrap) return;
        const isMarried = civilSelect.value === 'married';
        spouseWrap.style.display = isMarried ? 'contents' : 'none';
        spouseWrap.querySelectorAll('input, select, textarea').forEach(f => {
            f.disabled = !isMarried;
        });
    }

    /* ── Employment panel visibility ─────────────────── */
    function syncEmploymentPanels(form) {
        const select = form?.querySelector('.js-employment-status');
        if (!select) return;
        const status = select.value;
        form.querySelectorAll('[data-show-when]').forEach(el => {
            const types = el.getAttribute('data-show-when').trim().split(/\s+/).filter(Boolean);
            const show  = types.includes(status);
            el.classList.toggle('hidden', !show);
            el.querySelectorAll('input, select, textarea').forEach(field => {
                if (!field.name || field.type === 'hidden') return;
                field.disabled = !show;
            });
        });
    }

    /* ── Boot both syncs on every form in the page ───── */
    document.querySelectorAll('form').forEach(form => {
        const civilSelect      = form.querySelector('[id$="_civil_status"]');
        const employmentSelect = form.querySelector('.js-employment-status');
        if (civilSelect) {
            civilSelect.addEventListener('change', () => syncSpouseSection(form));
            syncSpouseSection(form);
        }
        if (employmentSelect) {
            employmentSelect.addEventListener('change', () => syncEmploymentPanels(form));
            syncEmploymentPanels(form);
        }
    });

    /* ── Create modal ────────────────────────────────── */
    if (modal && createForm) {
        const openBtn   = document.getElementById('openCreateCustomerModal');
        const closeBtn  = document.getElementById('closeCreateCustomerModal');
        const cancelBtn = document.getElementById('cancelCreateCustomerModal');

        const openCreateModal = () => modal.classList.add('show');
        const closeCreateModal = () => {
            modal.classList.remove('show');
            createForm.reset();
            syncSpouseSection(createForm);
            syncEmploymentPanels(createForm);
        };

        openBtn?.addEventListener('click', openCreateModal);
        closeBtn?.addEventListener('click', closeCreateModal);
        cancelBtn?.addEventListener('click', closeCreateModal);
        modal.addEventListener('click', e => { if (e.target === modal) closeCreateModal(); });
    }

    /* ── Edit modal ──────────────────────────────────── */
    const fieldNames = [
        'first_name','last_name','middle_name','email','phone',
        'date_of_birth','civil_status','citizenship','tin_number',
        'sss_gsis_number','pagibig_mid_number',
        'present_address_street','present_address_barangay',
        'present_address_city','present_address_province','present_address_zip',
        'spouse_first_name','spouse_middle_name','spouse_last_name',
        'spouse_employment','spouse_monthly_income',
        'employment_status','employer_name','job_title','business_name',
        'business_nature','years_employed','years_in_business',
        'ofw_employer_name','ofw_country','monthly_income','selected_property_id',
        'project_name','contract_price','reservation_fee_amount','downpayment_amount',
        'preferred_financing_type','monthly_amortization_estimate','affordability_notes',
        'qualification_notes','documentation_notes','manager_notes',
        'valid_id_status','proof_of_billing_status','proof_of_income_status',
        'birth_certificate_status','marriage_certificate_status',
        'reservation_form_status','financing_documents_status',
        'profile_notes','contact_person_name','contact_person_phone',
        'status','savings_amount',
    ];

    const checkboxFields = [
        'is_pagibig_member','has_required_pagibig_contributions',
        'has_outstanding_debts','has_downpayment_capacity','has_stable_income',
    ];

    const openEditModal  = () => editModal.classList.add('show');
    const closeEditModal = () => editModal.classList.remove('show');

    document.querySelectorAll('.editCustomerBtn').forEach(btn => {
        btn.addEventListener('click', () => {
            const customer = JSON.parse(btn.dataset.customer || '{}');
            editForm.action = `/customers/${customer.id}`;

            fieldNames.forEach(field => {
                const input = document.getElementById(`edit_${field}`);
                if (!input) return;
                const val = customer[field] ?? '';
                input.value = (val === null || val === undefined) ? '' : val;
            });

            checkboxFields.forEach(field => {
                const input = document.getElementById(`edit_${field}`);
                if (input) input.checked = Boolean(customer[field]);
            });

            syncEmploymentPanels(editForm);
            syncSpouseSection(editForm);
            openEditModal();
        });
    });

    document.getElementById('closeEditCustomerModal')?.addEventListener('click', closeEditModal);
    document.getElementById('cancelEditCustomerModal')?.addEventListener('click', closeEditModal);
    editModal?.addEventListener('click', e => { if (e.target === editModal) closeEditModal(); });

    /* ── View modal ──────────────────────────────────── */
    const viewFieldNames = [
        'first_name','last_name','middle_name','email','phone',
        'date_of_birth','civil_status','citizenship','tin_number',
        'sss_gsis_number','pagibig_mid_number',
        'present_address_street','present_address_barangay',
        'present_address_city','present_address_province','present_address_zip',
        'spouse_first_name','spouse_middle_name','spouse_last_name',
        'spouse_employment','spouse_monthly_income',
        'employment_status','employer_name','job_title','business_name',
        'business_nature','years_employed','years_in_business',
        'ofw_employer_name','ofw_country','monthly_income','savings_amount',
        'project_name','contract_price','reservation_fee_amount','downpayment_amount',
        'preferred_financing_type','monthly_amortization_estimate','affordability_notes',
        'qualification_notes','documentation_notes','manager_notes',
        'valid_id_status','proof_of_billing_status','proof_of_income_status',
        'birth_certificate_status','marriage_certificate_status',
        'reservation_form_status','financing_documents_status',
        'profile_notes','contact_person_name','contact_person_phone','status',
    ];

    const openViewModal  = () => viewModal.classList.add('show');
    const closeViewModal = () => viewModal.classList.remove('show');

    document.querySelectorAll('.viewCustomerBtn').forEach(btn => {
        btn.addEventListener('click', () => {
            const customer = JSON.parse(btn.dataset.customer || '{}');

            document.getElementById('viewCustomerModalSubtitle').textContent =
                `${customer.last_name ?? ''}, ${customer.first_name ?? ''} ${customer.middle_name ?? ''}`.trim();

            viewFieldNames.forEach(field => {
                const el = document.getElementById(`view_${field}`);
                if (!el) return;
                el.value = customer[field] ?? '';
            });

            openViewModal();
        });
    });

    document.getElementById('closeViewCustomerModal')?.addEventListener('click', closeViewModal);
    document.getElementById('cancelViewCustomerModal')?.addEventListener('click', closeViewModal);
    viewModal?.addEventListener('click', e => { if (e.target === viewModal) closeViewModal(); });

    /* ── Delete confirmation modal ───────────────────── */
    const deleteModal   = document.getElementById('deleteConfirmModal');
    const deleteForm    = document.getElementById('deleteCustomerForm');
    const deleteNameEl  = document.getElementById('deleteCustomerName');

    const openDeleteModal  = () => deleteModal.classList.add('show');
    const closeDeleteModal = () => deleteModal.classList.remove('show');

    document.querySelectorAll('.deleteCustomerBtn').forEach(btn => {
        btn.addEventListener('click', () => {
            const id   = btn.dataset.id;
            const name = btn.dataset.name;
            deleteForm.action    = `/customers/${id}`;
            deleteNameEl.textContent = name;
            openDeleteModal();
        });
    });

    document.getElementById('cancelDeleteModal')?.addEventListener('click', closeDeleteModal);
    deleteModal?.addEventListener('click', e => { if (e.target === deleteModal) closeDeleteModal(); });
</script>


</body>
</html>