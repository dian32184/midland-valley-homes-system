<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Employees</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root{--gap:12px;--radius:16px;--sidebar:220px}
        *{box-sizing:border-box}
        body{margin:0;overflow:hidden;font-family:Figtree,Arial,sans-serif;background:linear-gradient(145deg,#f2f3ff 0%,#f9fbff 100%)}
        .shell{height:100vh;display:flex}
        .sidebar{width:var(--sidebar);flex:0 0 var(--sidebar);background:linear-gradient(165deg,#eef2ff 0%,#e6ecff 45%,#eaf5ff 100%);border-right:1px solid #e6ebf7;padding:10px;display:flex;flex-direction:column;gap:10px}
        .brand{border-radius:var(--radius);background:#ffffffd9;border:1px solid #e2e8f0;padding:10px;text-align:center}
        .brand img{height:48px;width:auto;border-radius:8px;margin-left:50px}
        .brand h1{margin:8px 0 0;font-size:14px;font-weight:700;color:#1e293b}
        .menu{display:flex;flex-direction:column;gap:6px}
        .menu a{padding:8px 10px;border-radius:10px;text-decoration:none;font-size:12px;font-weight:600;color:#475569;border:1px solid #e6eaf5;background:rgba(255,255,255,.7)}
        .menu a.active{background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff;border-color:transparent}
        .main{flex:1;display:flex;flex-direction:column;min-width:0;padding:10px;gap:10px;background:linear-gradient(165deg,#f7f9ff 0%,#f1f5ff 50%,#edf4ff 100%)}
        .header{height:56px;display:flex;align-items:center;justify-content:space-between;padding:0 14px;border:1px solid #dfe6f5;border-radius:14px;background:linear-gradient(135deg,rgba(255,255,255,.9),rgba(240,246,255,.86))}
        .h-title{font-size:18px;font-weight:700;color:#0f172a}
        .content{flex:1;min-height:0;overflow:auto;border-radius:var(--radius);border:1px solid #e2e8f0;background:linear-gradient(160deg,rgba(255,255,255,.94),rgba(244,249,255,.9));box-shadow:0 8px 18px rgba(15,23,42,.06);padding:14px}
        .stats{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:var(--gap);margin-bottom:12px}
        .card{border-radius:14px;padding:10px;color:#fff}
        .c1{background:linear-gradient(135deg,#73d9ef,#5aa8f2)}
        .c2{background:linear-gradient(135deg,#9cb5ff,#6d90f6)}
        .c3{background:linear-gradient(135deg,#f5add1,#ea7fbe)}
        .c4{background:linear-gradient(135deg,#d2bcff,#a88ef0)}
        table{width:100%;border-collapse:collapse;font-size:13px}
        th,td{padding:10px 8px;text-align:left;border-bottom:1px solid #e2e8f0;color:#334155}
        th{font-size:12px;text-transform:uppercase;color:#64748b}
        .pill{font-size:11px;padding:4px 8px;border-radius:999px;background:#eef2ff;color:#4338ca;border:1px solid #c7d2fe}
        .pill-active{background:#ecfdf5;color:#065f46;border-color:#6ee7b7}
        .pill-inactive{background:#fef2f2;color:#991b1b;border-color:#fca5a5}
        .btn{border:1px solid #c7d2fe;background:#fff;color:#3730a3;border-radius:10px;padding:8px 12px;text-decoration:none;font-size:12px;font-weight:600;cursor:pointer}
        .btn-primary{background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff;border-color:transparent}
        .row-actions{display:flex;gap:8px;align-items:center}
        .link{color:#4338ca;text-decoration:none;font-weight:600;background:none;border:none;padding:0;cursor:pointer;font-size:13px}

        /* Modals */
        .modal-backdrop{position:fixed;inset:0;background:rgba(15,23,42,0.5);backdrop-filter:blur(6px);display:none;align-items:center;justify-content:center;z-index:50;padding:16px}
        .modal-backdrop.show{display:flex}
        .modal-card{width:min(700px,96vw);max-height:min(90vh,860px);overflow:hidden;display:flex;flex-direction:column;border-radius:20px;border:1px solid rgba(226,232,240,0.95);background:linear-gradient(165deg,#ffffff 0%,#f8fafc 42%,#f1f5f9 100%);box-shadow:0 25px 50px -12px rgba(15,23,42,0.28),0 0 0 1px rgba(255,255,255,0.65) inset}
        .modal-head{flex-shrink:0;display:flex;align-items:center;justify-content:space-between;gap:12px;padding:18px 22px;margin:0;border-bottom:1px solid #e2e8f0;background:linear-gradient(135deg,rgba(99,102,241,0.12) 0%,rgba(139,92,246,0.06) 55%,rgba(248,250,252,0.9) 100%)}
        .modal-title-wrap{display:flex;flex-direction:column;gap:4px}
        .modal-title{font-size:19px;font-weight:800;letter-spacing:-0.03em;color:#0f172a;margin:0;line-height:1.2}
        .modal-subtitle{font-size:12px;font-weight:500;color:#64748b;margin:0}
        .modal-head-actions{display:flex;align-items:center;gap:8px}
        .close-btn{border:1px solid #e2e8f0;background:rgba(255,255,255,0.85);border-radius:11px;padding:8px 14px;cursor:pointer;font-weight:600;font-size:13px;color:#475569;transition:background 0.15s ease,border-color 0.15s ease}
        .close-btn:hover{background:#fff;border-color:#cbd5e1;color:#0f172a}
        .modal-body{overflow:auto;flex:1;min-height:0;padding:18px 22px 22px;-webkit-overflow-scrolling:touch}
        .form-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px 14px}
        .form-grid input,.form-grid select,.form-grid textarea{width:100%;border:1px solid #cbd5e1;border-radius:11px;padding:10px 12px;font-size:13px;background:#fff;transition:border-color 0.15s ease,box-shadow 0.15s ease}
        .form-grid input:hover,.form-grid select:hover,.form-grid textarea:hover{border-color:#94a3b8}
        .form-grid input:focus,.form-grid select:focus,.form-grid textarea:focus{outline:none;border-color:#818cf8;box-shadow:0 0 0 3px rgba(99,102,241,0.18)}
        .form-grid select{appearance:none;-webkit-appearance:none;-moz-appearance:none;padding-right:38px;background-color:#fff;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='none' stroke='%2364748b' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round' d='M2 4l4 4 4-4'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:calc(100% - 12px) 50%;background-size:12px 12px}
        .form-grid input[readonly]{background:#f8fafc;color:#64748b}
        .field label{display:block;font-size:11px;font-weight:700;margin-bottom:5px;color:#475569;letter-spacing:0.01em}
        .span-2{grid-column:span 2}
        .form-actions{display:flex;gap:8px;justify-content:flex-end;margin-top:4px;padding-top:18px;border-top:1px solid #e2e8f0}
        .worker-info{grid-column:span 2;font-size:12px;color:#4338ca;background:#eef2ff;border:1px solid #c7d2fe;border-radius:10px;padding:8px 12px}
        .hint{grid-column:span 2;font-size:12px;color:#64748b;background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:8px 12px}
        .edit-icon-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    border: 1px solid #c7d2fe;
    background: #eef2ff;
    color: #4338ca;
    border-radius: 8px;
    padding: 0;
    cursor: pointer;
    transition: background 0.15s, border-color 0.15s, transform 0.1s;
}
.edit-icon-btn:hover {
    background: linear-gradient(135deg, #6366f1, #4f46e5);
    color: #fff;
    border-color: transparent;
    transform: scale(1.08);
}
    </style>
</head>
<body>
@php
    $userRole = strtolower((string) (auth()->user()->role ?? ''));
    $sidebarModules = $userRole === 'admin'
        ? [
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
        ]
        : [
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
        ];
@endphp
<div class="shell">
    <aside class="sidebar">
        <div class="brand"><img src="{{ asset('images/midland.jpg') }}" alt="Midland Valley Homes Logo"><h1>Midland Valley Homes</h1></div>
        <nav class="menu">@foreach($sidebarModules as $item)<a href="{{ route($item['route']) }}" class="{{ request()->routeIs($item['route']) ? 'active' : '' }}">{{ $item['label'] }}</a>@endforeach</nav>
    </aside>
    <main class="main">
        <header class="header">
            <div class="h-title">Employee Module</div>
            <button type="button" class="btn btn-primary" id="openEmployeeModal">+ Add Employee</button>
        </header>
        <section class="content">
            <div class="stats">
                <div class="card c1"><strong>Total Employees</strong><div>{{ $employees->total() }}</div></div>
                <div class="card c2"><strong>Active</strong><div>{{ \App\Models\Employee::where('is_active',true)->count() }}</div></div>
                <div class="card c3"><strong>Onsite</strong><div>{{ \App\Models\Employee::where('employment_type','onsite')->count() }}</div></div>
                <div class="card c4"><strong>Office</strong><div>{{ \App\Models\Employee::where('employment_type','office')->count() }}</div></div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Employment</th>
                        <th>Salary Type</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $employee)
                        <tr>
                            <td>{{ $employee->last_name }}, {{ $employee->first_name }}</td>
                            <td>{{ $employee->position ?: '-' }}</td>
                            <td>{{ ucfirst($employee->employment_type) }}</td>
                            <td>{{ ucfirst($employee->salary_type) }}</td>
                            <td>
                                <span class="pill {{ $employee->is_active ? 'pill-active' : 'pill-inactive' }}">
                                    {{ $employee->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="row-actions">
                                    <button type="button" class="edit-icon-btn editEmployeeBtn"
                                        data-id="{{ $employee->id }}"
                                        data-first_name="{{ $employee->first_name }}"
                                        data-last_name="{{ $employee->last_name }}"
                                        data-middle_name="{{ $employee->middle_name }}"
                                        data-email="{{ $employee->email }}"
                                        data-phone="{{ $employee->phone }}"
                                        data-position="{{ $employee->position }}"
                                        data-employment_type="{{ $employee->employment_type }}"
                                        data-salary_type="{{ $employee->salary_type }}"
                                        data-monthly_salary="{{ $employee->monthly_salary }}"
                                        data-daily_rate="{{ $employee->daily_rate }}"
                                        data-hire_date="{{ optional($employee->hire_date)->format('Y-m-d') }}"
                                        data-is_active="{{ $employee->is_active ? '1' : '0' }}"
                                        data-notes="{{ $employee->notes }}"
                                    >
                                         <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
         fill="none" stroke="currentColor" stroke-width="2"
         stroke-linecap="round" stroke-linejoin="round">
        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
    </svg>
</button>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6">No employee records yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div style="margin-top:12px;">{{ $employees->links() }}</div>
        </section>
    </main>
</div>

{{-- Add Employee Modal --}}
<div id="employeeModal" class="modal-backdrop">
    <div class="modal-card" role="dialog" aria-labelledby="employeeModalTitle">
        <div class="modal-head">
            <div class="modal-title-wrap">
                <h2 class="modal-title" id="employeeModalTitle">Add employee</h2>
                <p class="modal-subtitle">Select a worker type to auto-fill role &amp; salary</p>
            </div>
            <div class="modal-head-actions">
                <button type="button" id="closeEmployeeModal" class="close-btn">Close</button>
            </div>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('employees.store') }}" class="form-grid">
                @csrf
                <div class="field span-2">
                    <label>Worker type</label>
                    <select id="workerTypeSelect">
                        <option value="">Choose worker type…</option>
                    </select>
                </div>
                <p class="worker-info" id="selectedWorkerInfo" style="display:none;"></p>
                <div class="field">
                    <label>First name</label>
                    <input name="first_name" placeholder="e.g. Juan" required>
                </div>
                <div class="field">
                    <label>Last name</label>
                    <input name="last_name" placeholder="e.g. Dela Cruz" required>
                </div>
                <div class="field">
                    <label>Middle name</label>
                    <input name="middle_name" placeholder="Optional">
                </div>
                <div class="field">
                    <label>Email</label>
                    <input name="email" type="email" placeholder="e.g. juan@email.com">
                </div>
                <div class="field">
                    <label>Phone</label>
                    <input name="phone" placeholder="e.g. 09xx xxx xxxx">
                </div>
                <div class="field">
                    <label>Position</label>
                    <input id="positionField" name="position" placeholder="Auto-filled from worker type" readonly>
                </div>
                <input id="employmentTypeField" type="hidden" name="employment_type" value="office">
                <input id="salaryTypeHidden" type="hidden" name="salary_type" value="monthly">
                <div class="field" id="monthlySalaryWrap">
                    <label>Monthly salary (PHP)</label>
                    <input id="monthlySalaryField" name="monthly_salary" type="number" step="0.01" placeholder="0.00" readonly>
                </div>
                <div class="field" id="dailyRateWrap" style="display:none;">
                    <label>Daily rate (PHP)</label>
                    <input id="dailyRateField" name="daily_rate" type="number" step="0.01" placeholder="0.00" readonly>
                </div>
                <div class="field">
                    <label>Hire date</label>
                    <input id="hireDateField" name="hire_date" type="date">
                </div>
                <div class="field">
                    <label>Status</label>
                    <select name="is_active">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                <div class="field span-2">
                    <label>Notes</label>
                    <textarea id="notesField" name="notes" rows="3" placeholder="Additional notes…"></textarea>
                </div>
                <p class="hint" id="benefitEligibilityHint">Benefit eligibility will appear after setting hire date.</p>
                <div class="span-2 form-actions">
                    <button type="button" id="cancelEmployeeModal" class="btn">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save employee</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Employee Modal --}}
<div id="editEmployeeModal" class="modal-backdrop">
    <div class="modal-card" role="dialog" aria-labelledby="editEmployeeModalTitle">
        <div class="modal-head">
            <div class="modal-title-wrap">
                <h2 class="modal-title" id="editEmployeeModalTitle">Edit employee</h2>
                <p class="modal-subtitle">Update details, salary &amp; status</p>
            </div>
            <div class="modal-head-actions">
                <button type="button" id="closeEditEmployeeModal" class="close-btn">Close</button>
            </div>
        </div>
        <div class="modal-body">
            <form method="POST" id="editEmployeeForm" class="form-grid">
                @csrf
                @method('PUT')
                <div class="field">
                    <label>First name</label>
                    <input id="editFirstName" name="first_name" placeholder="e.g. Juan" required>
                </div>
                <div class="field">
                    <label>Last name</label>
                    <input id="editLastName" name="last_name" placeholder="e.g. Dela Cruz" required>
                </div>
                <div class="field">
                    <label>Middle name</label>
                    <input id="editMiddleName" name="middle_name" placeholder="Optional">
                </div>
                <div class="field">
                    <label>Email</label>
                    <input id="editEmail" name="email" type="email" placeholder="e.g. juan@email.com">
                </div>
                <div class="field">
                    <label>Phone</label>
                    <input id="editPhone" name="phone" placeholder="e.g. 09xx xxx xxxx">
                </div>
                <div class="field">
                    <label>Position</label>
                    <input id="editPosition" name="position" placeholder="Position">
                </div>
                <div class="field">
                    <label>Employment type</label>
                    <select id="editEmploymentType" name="employment_type">
                        <option value="office">Office</option>
                        <option value="onsite">Onsite</option>
                    </select>
                </div>
                <div class="field">
                    <label>Status</label>
                    <select id="editIsActive" name="is_active">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                <div class="field" id="editMonthlySalaryWrap">
                    <label>Monthly salary (PHP)</label>
                    <input id="editMonthlySalary" name="monthly_salary" type="number" step="0.01" placeholder="0.00">
                </div>
                <div class="field" id="editDailyRateWrap">
                    <label>Daily rate (PHP)</label>
                    <input id="editDailyRate" name="daily_rate" type="number" step="0.01" placeholder="0.00">
                </div>
                <div class="field">
                    <label>Hire date</label>
                    <input id="editHireDate" name="hire_date" type="date">
                </div>
                <div class="field span-2">
                    <label>Notes</label>
                    <textarea id="editNotes" name="notes" rows="3" placeholder="Additional notes…"></textarea>
                </div>
                <div class="span-2 form-actions">
                    <button type="button" id="cancelEditEmployeeModal" class="btn">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update employee</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const workerTypes = [
    { position: 'Engineer', employment: 'office', salaryType: 'monthly', monthlySalary: 50000, dailyRate: null, label: 'Engineer', pay: '₱50,000 / month' },
    { position: 'Marketing Agent', employment: 'office', salaryType: 'monthly', monthlySalary: 20000, dailyRate: null, label: 'Marketing Agent', pay: '₱20,000 / month' },
    { position: 'Documentation In Charge', employment: 'office', salaryType: 'monthly', monthlySalary: 20000, dailyRate: null, label: 'Documentation In Charge', pay: '₱20,000 / month' },
    { position: 'Admin Staff', employment: 'office', salaryType: 'monthly', monthlySalary: 20000, dailyRate: null, label: 'Admin Staff', pay: '₱20,000 / month' },
    { position: 'Manager', employment: 'office', salaryType: 'monthly', monthlySalary: 80000, dailyRate: null, label: 'Manager', pay: '₱80,000 / month' },
    { position: 'Mason', employment: 'onsite', salaryType: 'weekly', monthlySalary: null, dailyRate: 600, label: 'Mason', pay: '₱600 / day — payroll every Saturday' },
    { position: 'Painter', employment: 'onsite', salaryType: 'weekly', monthlySalary: null, dailyRate: 600, label: 'Painter', pay: '₱600 / day — payroll every Saturday' },
    { position: 'Welder', employment: 'onsite', salaryType: 'weekly', monthlySalary: null, dailyRate: 600, label: 'Welder', pay: '₱600 / day — payroll every Saturday' },
    { position: 'Plumber', employment: 'onsite', salaryType: 'weekly', monthlySalary: null, dailyRate: 550, label: 'Plumber', pay: '₱550 / day — payroll every Saturday' },
    { position: 'Electrical Engineer', employment: 'onsite', salaryType: 'weekly', monthlySalary: null, dailyRate: 550, label: 'Electrical Engineer', pay: '₱550 / day — payroll every Saturday' },
    { position: 'Laborer', employment: 'onsite', salaryType: 'weekly', monthlySalary: null, dailyRate: 350, label: 'Laborer', pay: '₱350 / day — payroll every Saturday' },
    { position: 'Heavy Truck Operator', employment: 'onsite', salaryType: 'weekly', monthlySalary: null, dailyRate: 700, label: 'Heavy Truck Operator', pay: '₱700 / day — payroll every Saturday' },
];

/* ── Add Modal ── */
const eModal = document.getElementById('employeeModal');
const workerTypeSelect = document.getElementById('workerTypeSelect');
const selectedWorkerInfo = document.getElementById('selectedWorkerInfo');
const employmentTypeField = document.getElementById('employmentTypeField');
const salaryTypeHidden = document.getElementById('salaryTypeHidden');
const positionField = document.getElementById('positionField');
const dailyRateField = document.getElementById('dailyRateField');
const dailyRateWrap = document.getElementById('dailyRateWrap');
const monthlySalaryField = document.getElementById('monthlySalaryField');
const monthlySalaryWrap = document.getElementById('monthlySalaryWrap');
const hireDateField = document.getElementById('hireDateField');
const benefitEligibilityHint = document.getElementById('benefitEligibilityHint');
let selectedWorker = null;

workerTypes.forEach((worker, index) => {
    const option = document.createElement('option');
    option.value = String(index);
    option.textContent = worker.label;
    workerTypeSelect.appendChild(option);
});

function applyWorkerType(worker) {
    selectedWorker = worker;
    positionField.value = worker.position;
    employmentTypeField.value = worker.employment;
    salaryTypeHidden.value = worker.salaryType;
    monthlySalaryField.value = worker.monthlySalary ?? '';
    dailyRateField.value = worker.dailyRate ?? '';
    selectedWorkerInfo.textContent = `${worker.label} — ${worker.pay}`;
    selectedWorkerInfo.style.display = '';
    togglePayFields();
    refreshBenefitHint();
}

function togglePayFields() {
    const isMonthly = salaryTypeHidden.value === 'monthly';
    monthlySalaryWrap.style.display = isMonthly ? '' : 'none';
    dailyRateWrap.style.display = isMonthly ? 'none' : '';
}

function refreshBenefitHint() {
    if (!hireDateField.value) {
        benefitEligibilityHint.textContent = 'Benefit eligibility will appear after setting hire date.';
        return;
    }
    const diffYears = (new Date() - new Date(hireDateField.value)) / (1000 * 60 * 60 * 24 * 365.25);
    const roleText = selectedWorker ? selectedWorker.label : (positionField.value || 'Employee');
    benefitEligibilityHint.textContent = diffYears >= 2
        ? `${roleText} is eligible for benefits (2+ years in company).`
        : `${roleText} becomes eligible for benefits after completing 2 years.`;
}

workerTypeSelect.addEventListener('change', e => {
    const index = Number(e.target.value);
    if (!isNaN(index) && workerTypes[index]) {
        applyWorkerType(workerTypes[index]);
    } else {
        selectedWorker = null;
        selectedWorkerInfo.style.display = 'none';
        refreshBenefitHint();
    }
});

hireDateField.addEventListener('change', refreshBenefitHint);

document.getElementById('openEmployeeModal').onclick = () => eModal.classList.add('show');
document.getElementById('closeEmployeeModal').onclick = () => eModal.classList.remove('show');
document.getElementById('cancelEmployeeModal').onclick = () => eModal.classList.remove('show');
eModal.addEventListener('click', e => { if (e.target === eModal) eModal.classList.remove('show'); });

/* ── Edit Modal ── */
const editEModal = document.getElementById('editEmployeeModal');
const editEmployeeForm = document.getElementById('editEmployeeForm');

document.querySelectorAll('.editEmployeeBtn').forEach(btn => {
    btn.addEventListener('click', () => {
        editEmployeeForm.action = `/employees/${btn.dataset.id}`;
        document.getElementById('editFirstName').value = btn.dataset.first_name || '';
        document.getElementById('editLastName').value = btn.dataset.last_name || '';
        document.getElementById('editMiddleName').value = btn.dataset.middle_name || '';
        document.getElementById('editEmail').value = btn.dataset.email || '';
        document.getElementById('editPhone').value = btn.dataset.phone || '';
        document.getElementById('editPosition').value = btn.dataset.position || '';
        document.getElementById('editEmploymentType').value = btn.dataset.employment_type || 'office';
        document.getElementById('editIsActive').value = btn.dataset.is_active || '1';
        document.getElementById('editMonthlySalary').value = btn.dataset.monthly_salary || '';
        document.getElementById('editDailyRate').value = btn.dataset.daily_rate || '';
        document.getElementById('editHireDate').value = btn.dataset.hire_date || '';
        document.getElementById('editNotes').value = btn.dataset.notes || '';

        const isMonthly = btn.dataset.salary_type === 'monthly';
        document.getElementById('editMonthlySalaryWrap').style.display = isMonthly ? '' : 'none';
        document.getElementById('editDailyRateWrap').style.display = isMonthly ? 'none' : '';

        editEModal.classList.add('show');
    });
});

document.getElementById('closeEditEmployeeModal').onclick = () => editEModal.classList.remove('show');
document.getElementById('cancelEditEmployeeModal').onclick = () => editEModal.classList.remove('show');
editEModal.addEventListener('click', e => { if (e.target === editEModal) editEModal.classList.remove('show'); });
</script>
</body>
</html>