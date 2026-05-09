<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Attendance Records</title>
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
        .header-actions{display:flex;gap:8px;align-items:center}
        .content{flex:1;min-height:0;overflow:auto;border-radius:var(--radius);border:1px solid #e2e8f0;background:linear-gradient(160deg,rgba(255,255,255,.94),rgba(244,249,255,.9));box-shadow:0 8px 18px rgba(15,23,42,.06);padding:14px}

        .filter-row{display:flex;gap:8px;margin-bottom:12px;align-items:center;flex-wrap:wrap}
        .filter-row input,.filter-row select{border:1px solid #cbd5e1;border-radius:10px;padding:8px 10px;font-size:13px;color:#334155;background:#fff;transition:border-color .15s}
        .filter-row input:focus,.filter-row select:focus{outline:none;border-color:#818cf8;box-shadow:0 0 0 3px rgba(99,102,241,.15)}

        .stats{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:var(--gap);margin-bottom:12px}
        .card{border-radius:14px;padding:10px;color:#fff}
        .card strong{font-size:11px;opacity:.85;display:block;margin-bottom:4px}
        .card div{font-size:22px;font-weight:800}
        .c1{background:linear-gradient(135deg,#73d9ef,#5aa8f2)}
        .c2{background:linear-gradient(135deg,#9cb5ff,#6d90f6)}
        .c3{background:linear-gradient(135deg,#f5add1,#ea7fbe)}
        .c4{background:linear-gradient(135deg,#d2bcff,#a88ef0)}

        table{width:100%;border-collapse:collapse;font-size:13px}
        th,td{padding:10px 8px;text-align:left;border-bottom:1px solid #e2e8f0;color:#334155;vertical-align:middle}
        th{font-size:11px;text-transform:uppercase;color:#64748b;font-weight:700;letter-spacing:.04em}
        tbody tr:hover{background:rgba(99,102,241,.04)}

        .pill{font-size:11px;padding:4px 10px;border-radius:999px;font-weight:600;display:inline-block}
        .pill-present{background:#ecfdf5;color:#065f46;border:1px solid #6ee7b7}
        .pill-late{background:#fffbeb;color:#92400e;border:1px solid #fcd34d}
        .pill-absent{background:#fef2f2;color:#991b1b;border:1px solid #fca5a5}
        .pill-partial{background:#fff7ed;color:#9a3412;border:1px solid #fdba74}
        .pill-default{background:#eef2ff;color:#4338ca;border:1px solid #c7d2fe}
        .muted{color:#64748b}

        /* row action buttons */
        .row-actions{display:flex;gap:6px;flex-wrap:wrap}
        .act-btn{font-size:11px;font-weight:600;padding:4px 10px;border-radius:8px;border:1px solid;cursor:pointer;transition:opacity .15s}
        .act-btn:hover{opacity:.75}
        .act-view{color:#4338ca;border-color:#c7d2fe;background:#eef2ff}
        .act-edit{color:#065f46;border-color:#6ee7b7;background:#ecfdf5}

        .btn{border:1px solid #c7d2fe;background:#fff;color:#3730a3;border-radius:10px;padding:8px 12px;font-size:12px;font-weight:600;cursor:pointer;transition:background .15s,border-color .15s}
        .btn:hover{background:#f5f3ff;border-color:#a5b4fc}
        .btn-primary{background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff;border-color:transparent;box-shadow:0 4px 14px rgba(79,70,229,.28)}
        .btn-primary:hover{background:linear-gradient(135deg,#4f46e5,#4338ca)}
        .btn-sm{padding:6px 10px;font-size:12px;border-radius:9px}

        .flash{margin-bottom:12px;padding:10px 12px;border-radius:10px;font-size:13px;background:#ecfdf5;border:1px solid #a7f3d0;color:#065f46}
        .error-box{margin-bottom:12px;padding:10px 12px;border-radius:10px;font-size:13px;background:#fef2f2;border:1px solid #fecaca;color:#991b1b}

        /* ── shared modal chrome ── */
        .modal-backdrop{position:fixed;inset:0;background:rgba(15,23,42,0.5);backdrop-filter:blur(6px);display:none;align-items:center;justify-content:center;z-index:50;padding:16px}
        .modal-backdrop.show{display:flex}
        .modal-card{overflow:hidden;display:flex;flex-direction:column;border-radius:20px;border:1px solid rgba(226,232,240,0.95);background:linear-gradient(165deg,#ffffff 0%,#f8fafc 42%,#f1f5f9 100%);box-shadow:0 25px 50px -12px rgba(15,23,42,0.28),0 0 0 1px rgba(255,255,255,0.65) inset}
        .modal-head{flex-shrink:0;display:flex;align-items:center;justify-content:space-between;gap:12px;padding:18px 22px;border-bottom:1px solid #e2e8f0;background:linear-gradient(135deg,rgba(99,102,241,0.12) 0%,rgba(139,92,246,0.06) 55%,rgba(248,250,252,0.9) 100%)}
        .modal-title-wrap{display:flex;flex-direction:column;gap:4px}
        .modal-title{font-size:19px;font-weight:800;letter-spacing:-0.03em;color:#0f172a;margin:0;line-height:1.2}
        .modal-subtitle{font-size:12px;font-weight:500;color:#64748b;margin:0}
        .close-btn{border:1px solid #e2e8f0;background:rgba(255,255,255,0.85);border-radius:11px;padding:8px 14px;cursor:pointer;font-weight:600;font-size:13px;color:#475569;transition:background .15s,border-color .15s}
        .close-btn:hover{background:#fff;border-color:#cbd5e1;color:#0f172a}
        .modal-body{overflow:auto;flex:1;min-height:0;padding:18px 22px 22px}

        /* bulk + edit table inside modals */
        .date-row{display:flex;align-items:center;gap:10px;margin-bottom:14px;flex-wrap:wrap}
        .date-row label{font-size:13px;font-weight:700;color:#334155}
        .date-row input[type=date]{border:1px solid #cbd5e1;border-radius:10px;padding:8px 12px;font-size:13px;transition:border-color .15s,box-shadow .15s}
        .date-row input[type=date]:focus{outline:none;border-color:#818cf8;box-shadow:0 0 0 3px rgba(99,102,241,.18)}

        .bulk-wrap{max-height:48vh;overflow-y:auto;border:1px solid #e2e8f0;border-radius:12px}
        .bulk-table{width:100%;border-collapse:separate;border-spacing:0;font-size:12.5px}
        .bulk-table th{background:#f1f4ff;position:sticky;top:0;z-index:10;padding:10px 12px;text-align:left;color:#475569;font-size:11px;text-transform:uppercase;font-weight:700;letter-spacing:.04em}
        .bulk-table td{padding:7px 10px;border-bottom:1px solid #f1f5f9;vertical-align:middle;color:#334155}
        .bulk-table tbody tr:hover{background:rgba(99,102,241,.03)}
        .bulk-table input[type=time],.bulk-table select{border:1px solid #cbd5e1;border-radius:8px;padding:6px 8px;font-size:12px;width:100%;background:#fff;transition:border-color .15s}
        .bulk-table input[type=time]:focus,.bulk-table select:focus{outline:none;border-color:#818cf8;box-shadow:0 0 0 2px rgba(99,102,241,.15)}
        .bulk-table select{max-width:130px}
        .emp-name{font-weight:600;color:#1e293b}
        .emp-pos{font-size:11px;color:#94a3b8;margin-top:1px}

        /* partial badge inside bulk table (time-in only saved) */
        .partial-badge{font-size:10px;background:#fff7ed;color:#9a3412;border:1px solid #fdba74;border-radius:6px;padding:2px 6px;margin-top:2px;display:inline-block}

        .form-actions{display:flex;justify-content:flex-end;gap:8px;margin-top:14px;padding-top:14px;border-top:1px solid #e2e8f0}

        /* view modal detail grid */
        .detail-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px 20px;margin-top:4px}
        .detail-item label{font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;display:block;margin-bottom:4px}
        .detail-item .val{font-size:14px;font-weight:600;color:#0f172a}
        .detail-item .val.empty{color:#cbd5e1;font-style:italic;font-weight:400}
        .section-divider{grid-column:span 2;border-top:1px solid #e2e8f0;margin:4px 0}

        /* single-record edit form */
        .edit-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px 14px}
        .edit-field{display:flex;flex-direction:column;gap:5px}
        .edit-field label{font-size:11px;font-weight:700;color:#475569;letter-spacing:.01em}
        .edit-field input,.edit-field select,.edit-field textarea{border:1px solid #cbd5e1;border-radius:11px;padding:9px 12px;font-size:13px;background:#fff;transition:border-color .15s,box-shadow .15s}
        .edit-field input:focus,.edit-field select:focus,.edit-field textarea:focus{outline:none;border-color:#818cf8;box-shadow:0 0 0 3px rgba(99,102,241,.18)}
        .edit-field select{appearance:none;-webkit-appearance:none;padding-right:36px;background-image:linear-gradient(45deg,transparent 50%,#64748b 50%),linear-gradient(135deg,#64748b 50%,transparent 50%),linear-gradient(to right,#e2e8f0,#e2e8f0);background-position:calc(100% - 16px) calc(50% - 2px),calc(100% - 11px) calc(50% - 2px),calc(100% - 31px) 50%;background-size:5px 5px,5px 5px,1px 20px;background-repeat:no-repeat}
        .edit-field textarea{resize:vertical;min-height:68px}
        .edit-field.span-2{grid-column:span 2}
        .section-label{grid-column:span 2;font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:#6366f1;padding-top:10px;border-top:1px solid #e2e8f0;margin-top:2px}
        .section-label:first-child{border-top:none;padding-top:0;margin-top:0}
    </style>
</head>
<body>
@php
    $userRole = strtolower((string) (auth()->user()->role ?? ''));
    $sidebarModules = $userRole === 'admin'
        ? [
            ['label' => 'Dashboard',    'route' => 'admindashboard'],
            ['label' => 'Customers',    'route' => 'customers.index'],
            ['label' => 'Properties',   'route' => 'properties.index'],
            ['label' => 'Payments',     'route' => 'payments.index'],
            ['label' => 'Employees',    'route' => 'employees.index'],
            ['label' => 'Payroll',      'route' => 'payrolls.index'],
            ['label' => 'Benefits',     'route' => 'benefits.index'],
            ['label' => 'User Roles',   'route' => 'user-roles.index'],
            ['label' => 'Audit Logs',   'route' => 'audit-logs.index'],
            ['label' => 'Reports',      'route' => 'reports.index'],
        ]
        : [
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
        ];
@endphp

<div class="shell">
    <aside class="sidebar">
        <div class="brand">
            <img src="{{ asset('images/midland.jpg') }}" alt="Midland Valley Homes Logo">
            <h1>Midland Valley Homes</h1>
        </div>
        <nav class="menu">
            @foreach($sidebarModules as $item)
                <a href="{{ route($item['route']) }}" class="{{ request()->routeIs($item['route']) ? 'active' : '' }}">{{ $item['label'] }}</a>
            @endforeach
        </nav>
    </aside>

    <main class="main">
        <header class="header">
            <div class="h-title">Attendance Module</div>
            <div class="header-actions">
                <button type="button" class="btn" id="openBulkUpdateModal">↻ Update a Day</button>
                <button type="button" class="btn btn-primary" id="openAttendanceModal">+ Add Attendance</button>
            </div>
        </header>

        <section class="content">
            @if(session('status'))
                <div class="flash">{{ session('status') }}</div>
            @endif
            @if($errors->any())
                <div class="error-box">{{ $errors->first() }}</div>
            @endif

            <form method="GET" class="filter-row">
                <input type="month" name="month" value="{{ $selectedMonth }}">
                <select name="position">
                    <option value="">All Positions</option>
                    @foreach($positions as $position)
                        <option value="{{ $position }}" @selected($selectedPosition === $position)>{{ $position }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn">Apply Filter</button>
            </form>

            <div class="stats">
                <div class="card c1"><strong>Present</strong><div>{{ $summary['present'] }}</div></div>
                <div class="card c2"><strong>Late</strong><div>{{ $summary['late'] }}</div></div>
                <div class="card c3"><strong>Absent</strong><div>{{ $summary['absent'] }}</div></div>
                <div class="card c4"><strong>Total Hours</strong><div>{{ number_format($summary['hours'], 2) }}</div></div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Position</th>
                        <th>Date</th>
                        <th>Time In / Out</th>
                        <th>Hours</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($attendanceRecords as $record)
                    @php
                        $status     = strtolower($record->status ?? '');
                        $isPartial  = $record->time_in && !$record->time_out;
                        $pillClass  = $isPartial ? 'pill-partial' : match($status) {
                            'present' => 'pill-present',
                            'late'    => 'pill-late',
                            'absent'  => 'pill-absent',
                            default   => 'pill-default',
                        };
                        $pillLabel  = $isPartial ? 'Partial (no time-out)' : ucfirst($record->status);
                    @endphp
                    <tr>
                        <td><strong>{{ $record->employee->last_name }}, {{ $record->employee->first_name }}</strong></td>
                        <td class="muted">{{ $record->employee->position ?? '-' }}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($record->attendance_date)->format('M d, Y') }}<br>
                            <span class="muted" style="font-size:11px">{{ \Carbon\Carbon::parse($record->attendance_date)->format('l') }}</span>
                        </td>
                        <td class="muted">
                            {{ $record->time_in ?: '—' }} / {{ $record->time_out ?: '—' }}
                        </td>
                        <td>{{ $record->hours_worked ?: '—' }}</td>
                        <td><span class="pill {{ $pillClass }}">{{ $pillLabel }}</span></td>
                        <td>
                            <div class="row-actions">
                                {{-- VIEW --}}
                                <button type="button" class="act-btn act-view viewRecordBtn"
                                    data-id="{{ $record->id }}"
                                    data-employee="{{ $record->employee->last_name }}, {{ $record->employee->first_name }}"
                                    data-position="{{ $record->employee->position ?? '' }}"
                                    data-date="{{ \Carbon\Carbon::parse($record->attendance_date)->format('M d, Y (l)') }}"
                                    data-time_in="{{ $record->time_in ?: '' }}"
                                    data-time_out="{{ $record->time_out ?: '' }}"
                                    data-hours="{{ $record->hours_worked ?: '' }}"
                                    data-status="{{ $record->status }}"
                                    data-notes="{{ $record->notes ?? '' }}"
                                >View</button>
                                {{-- EDIT --}}
                                <button type="button" class="act-btn act-edit editRecordBtn"
                                    data-id="{{ $record->id }}"
                                    data-employee_id="{{ $record->employee_id }}"
                                    data-attendance_date="{{ $record->attendance_date }}"
                                    data-time_in="{{ $record->time_in ?: '' }}"
                                    data-time_out="{{ $record->time_out ?: '' }}"
                                    data-status="{{ $record->status }}"
                                    data-hours_worked="{{ $record->hours_worked ?: '' }}"
                                    data-notes="{{ $record->notes ?? '' }}"
                                >Edit</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="muted" style="text-align:center;padding:24px">No attendance records for selected month.</td></tr>
                @endforelse
                </tbody>
            </table>

            <div style="margin-top:12px;">{{ $attendanceRecords->links() }}</div>
        </section>
    </main>
</div>

{{-- ══════════════════════════════════════════════
     MODAL 1 — BULK CREATE (new date)
══════════════════════════════════════════════ --}}
<div id="attendanceModal" class="modal-backdrop">
    <div class="modal-card" style="width:min(900px,96vw);max-height:min(90vh,880px)" role="dialog">
        <div class="modal-head">
            <div class="modal-title-wrap">
                <h2 class="modal-title">Daily Attendance</h2>
                <p class="modal-subtitle">Pick a date and fill in time in / out per employee. Rows left blank are skipped.</p>
            </div>
            <button type="button" id="closeAttendanceModal" class="close-btn">Close</button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('attendance-records.bulk-store') }}" id="bulkCreateForm">
                @csrf
                <div class="date-row">
                    <label for="attendance_date_input">Date</label>
                    <input id="attendance_date_input" name="attendance_date" type="date" required value="{{ now()->format('Y-m-d') }}">
                </div>

                <div class="bulk-wrap">
                    <table class="bulk-table">
                        <thead>
                            <tr>
                                <th style="width:28%">Employee</th>
                                <th>Time In</th>
                                <th>Time Out</th>
                                <th>Status</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($employees as $i => $employee)
                            <tr>
                                <td>
                                    <input type="hidden" name="entries[{{ $i }}][employee_id]" value="{{ $employee->id }}">
                                    <div class="emp-name">{{ $employee->last_name }}, {{ $employee->first_name }}</div>
                                    <div class="emp-pos">{{ $employee->position ?? '' }}</div>
                                </td>
                                <td><input type="time" name="entries[{{ $i }}][time_in]"></td>
                                <td><input type="time" name="entries[{{ $i }}][time_out]"></td>
                                <td>
                                    <select name="entries[{{ $i }}][status]">
                                        <option value="">— skip —</option>
                                        <option value="present">Present</option>
                                        <option value="late">Late</option>
                                        <option value="absent">Absent</option>
                                    </select>
                                </td>
                                <td><input type="text" name="entries[{{ $i }}][notes]" placeholder="Optional" style="border:1px solid #cbd5e1;border-radius:8px;padding:6px 8px;font-size:12px;width:100%"></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="form-actions">
                    <button type="button" id="cancelAttendanceModal" class="btn">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save attendance for this date</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════
     MODAL 2 — BULK UPDATE (existing date)
══════════════════════════════════════════════ --}}
<div id="bulkUpdateModal" class="modal-backdrop">
    <div class="modal-card" style="width:min(900px,96vw);max-height:min(90vh,880px)" role="dialog">
        <div class="modal-head">
            <div class="modal-title-wrap">
                <h2 class="modal-title">Update Day's Attendance</h2>
                <p class="modal-subtitle">Pick a saved date to load existing records — fill in missing time-outs or fix any entry.</p>
            </div>
            <button type="button" id="closeBulkUpdateModal" class="close-btn">Close</button>
        </div>
        <div class="modal-body">
            {{-- Step 1: pick the date --}}
            <div id="bulkUpdateStep1">
                <div class="date-row">
                    <label for="bulk_update_date_input">Date to update</label>
                    <input id="bulk_update_date_input" type="date" value="{{ now()->format('Y-m-d') }}">
                    <button type="button" class="btn btn-primary btn-sm" id="loadDayBtn">Load Records</button>
                </div>
                <p id="bulkUpdateMsg" style="font-size:13px;color:#64748b;margin:0"></p>
            </div>

            {{-- Step 2: the editable table (hidden until records load) --}}
            <div id="bulkUpdateStep2" style="display:none">
                <form method="POST" action="{{ route('attendance-records.bulk-update') }}" id="bulkUpdateForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="attendance_date" id="bulkUpdateDateHidden">

                    <div class="bulk-wrap">
                        <table class="bulk-table">
                            <thead>
                                <tr>
                                    <th style="width:28%">Employee</th>
                                    <th>Time In</th>
                                    <th>Time Out</th>
                                    <th>Status</th>
                                    <th>Hours</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody id="bulkUpdateTbody">
                                {{-- filled via JS --}}
                            </tbody>
                        </table>
                    </div>

                    <div class="form-actions">
                        <button type="button" id="cancelBulkUpdateModal" class="btn">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save updates</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════
     MODAL 3 — VIEW single record
══════════════════════════════════════════════ --}}
<div id="viewRecordModal" class="modal-backdrop">
    <div class="modal-card" style="width:min(520px,96vw);max-height:min(90vh,700px)" role="dialog">
        <div class="modal-head">
            <div class="modal-title-wrap">
                <h2 class="modal-title" id="viewModalEmployeeName">—</h2>
                <p class="modal-subtitle" id="viewModalPosition">—</p>
            </div>
            <button type="button" id="closeViewModal" class="close-btn">Close</button>
        </div>
        <div class="modal-body">
            <div class="detail-grid">
                <div class="detail-item">
                    <label>Date</label>
                    <div class="val" id="vDate">—</div>
                </div>
                <div class="detail-item">
                    <label>Status</label>
                    <div id="vStatusWrap"></div>
                </div>
                <div class="section-divider"></div>
                <div class="detail-item">
                    <label>Time In</label>
                    <div class="val" id="vTimeIn">—</div>
                </div>
                <div class="detail-item">
                    <label>Time Out</label>
                    <div class="val" id="vTimeOut">—</div>
                </div>
                <div class="detail-item">
                    <label>Hours Worked</label>
                    <div class="val" id="vHours">—</div>
                </div>
                <div class="section-divider"></div>
                <div class="detail-item" style="grid-column:span 2">
                    <label>Notes</label>
                    <div class="val" id="vNotes">—</div>
                </div>
            </div>
            <div style="display:flex;justify-content:flex-end;margin-top:20px;padding-top:14px;border-top:1px solid #e2e8f0">
                <button type="button" id="viewToEditBtn" class="btn btn-primary btn-sm">Edit this record</button>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════
     MODAL 4 — EDIT single record
══════════════════════════════════════════════ --}}
<div id="editRecordModal" class="modal-backdrop">
    <div class="modal-card" style="width:min(560px,96vw);max-height:min(90vh,760px)" role="dialog">
        <div class="modal-head">
            <div class="modal-title-wrap">
                <h2 class="modal-title">Edit Attendance Record</h2>
                <p class="modal-subtitle">Update time in / out, status, hours or notes for this entry.</p>
            </div>
            <button type="button" id="closeEditModal" class="close-btn">Close</button>
        </div>
        <div class="modal-body">
            <form method="POST" id="editRecordForm" class="edit-grid">
                @csrf
                @method('PUT')

                <div class="section-label">Employee & Date</div>

                <div class="edit-field">
                    <label>Employee *</label>
                    <select name="employee_id" id="editEmployeeId" required>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->last_name }}, {{ $emp->first_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="edit-field">
                    <label>Date *</label>
                    <input type="date" name="attendance_date" id="editDate" required>
                </div>

                <div class="section-label">Time & Status</div>

                <div class="edit-field">
                    <label>Time In</label>
                    <input type="time" name="time_in" id="editTimeIn">
                </div>

                <div class="edit-field">
                    <label>Time Out <span style="font-weight:400;color:#94a3b8">(leave blank if not yet out)</span></label>
                    <input type="time" name="time_out" id="editTimeOut">
                </div>

                <div class="edit-field">
                    <label>Status *</label>
                    <select name="status" id="editStatus" required>
                        <option value="present">Present</option>
                        <option value="late">Late</option>
                        <option value="absent">Absent</option>
                    </select>
                </div>

                <div class="edit-field">
                    <label>Hours Worked <span style="font-weight:400;color:#94a3b8">(auto-calculated if blank)</span></label>
                    <input type="number" name="hours_worked" id="editHoursWorked" step="0.01" min="0" max="24">
                </div>

                <div class="edit-field span-2">
                    <label>Notes</label>
                    <textarea name="notes" id="editNotes"></textarea>
                </div>

                <div class="span-2 form-actions" style="margin-top:0">
                    <button type="button" id="cancelEditModal" class="btn">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// ── helpers ──────────────────────────────────────────────────────────────────
const $  = id => document.getElementById(id);
const show = id => $(id).classList.add('show');
const hide = id => $(id).classList.remove('show');
const outsideClose = (modalId) => {
    $(modalId).addEventListener('click', e => { if (e.target === $(modalId)) hide(modalId); });
};

// ── MODAL 1: Bulk Create ──────────────────────────────────────────────────
$('openAttendanceModal').onclick  = () => show('attendanceModal');
$('closeAttendanceModal').onclick = () => hide('attendanceModal');
$('cancelAttendanceModal').onclick= () => hide('attendanceModal');
outsideClose('attendanceModal');

// ── MODAL 2: Bulk Update ──────────────────────────────────────────────────
$('openBulkUpdateModal').onclick  = () => show('bulkUpdateModal');
$('closeBulkUpdateModal').onclick = () => hide('bulkUpdateModal');
$('cancelBulkUpdateModal').onclick= () => hide('bulkUpdateModal');
outsideClose('bulkUpdateModal');

$('loadDayBtn').addEventListener('click', async () => {
    const date = $('bulk_update_date_input').value;
    if (!date) { $('bulkUpdateMsg').textContent = 'Please pick a date first.'; return; }

    $('bulkUpdateMsg').textContent = 'Loading…';
    $('bulkUpdateStep2').style.display = 'none';

    try {
        const res  = await fetch(`/attendance-records/day?date=${date}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        });
        const data = await res.json(); // { records: [...], employees: [...] }

        if (!data.records || data.records.length === 0) {
            $('bulkUpdateMsg').textContent = 'No records found for that date. Use "+ Add Attendance" to create them first.';
            return;
        }

        $('bulkUpdateMsg').textContent = '';
        $('bulkUpdateDateHidden').value = date;

        // Build rows
        const tbody = $('bulkUpdateTbody');
        tbody.innerHTML = '';
        data.records.forEach((r, i) => {
            const partial = r.time_in && !r.time_out;
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <input type="hidden" name="entries[${i}][id]" value="${r.id}">
                    <input type="hidden" name="entries[${i}][employee_id]" value="${r.employee_id}">
                    <div class="emp-name">${r.employee_name}</div>
                    <div class="emp-pos">${r.position || ''}</div>
                    ${partial ? '<span class="partial-badge">⏳ No time-out yet</span>' : ''}
                </td>
                <td><input type="time" name="entries[${i}][time_in]"  value="${r.time_in  || ''}"></td>
                <td><input type="time" name="entries[${i}][time_out]" value="${r.time_out || ''}"></td>
                <td>
                    <select name="entries[${i}][status]">
                        <option value="present" ${r.status==='present'?'selected':''}>Present</option>
                        <option value="late"    ${r.status==='late'   ?'selected':''}>Late</option>
                        <option value="absent"  ${r.status==='absent' ?'selected':''}>Absent</option>
                    </select>
                </td>
                <td><input type="number" name="entries[${i}][hours_worked]" value="${r.hours_worked||''}" step="0.01" min="0" max="24" style="border:1px solid #cbd5e1;border-radius:8px;padding:6px 8px;font-size:12px;width:80px"></td>
                <td><input type="text" name="entries[${i}][notes]" value="${r.notes||''}" placeholder="Optional" style="border:1px solid #cbd5e1;border-radius:8px;padding:6px 8px;font-size:12px;width:100%"></td>
            `;
            tbody.appendChild(row);
        });

        $('bulkUpdateStep2').style.display = 'block';
    } catch (err) {
        $('bulkUpdateMsg').textContent = 'Failed to load records. Please try again.';
        console.error(err);
    }
});

// ── MODAL 3: View ─────────────────────────────────────────────────────────
const pillClassMap = {
    present: 'pill-present', late: 'pill-late', absent: 'pill-absent'
};
let _currentViewData = null; // hold data for "Edit this record" button

document.querySelectorAll('.viewRecordBtn').forEach(btn => {
    btn.addEventListener('click', () => {
        const d = btn.dataset;
        _currentViewData = d;

        $('viewModalEmployeeName').textContent = d.employee || '—';
        $('viewModalPosition').textContent     = d.position  || '—';
        $('vDate').textContent                 = d.date       || '—';
        $('vTimeIn').textContent               = d.time_in    || '—';
        $('vTimeOut').textContent              = d.time_out   || '— (not yet recorded)';
        $('vHours').textContent                = d.hours      || '—';
        $('vNotes').textContent                = d.notes      || '—';

        const statusWrap = $('vStatusWrap');
        const cls = pillClassMap[d.status] || 'pill-default';
        const partial = d.time_in && !d.time_out;
        statusWrap.innerHTML = partial
            ? `<span class="pill pill-partial">Partial (no time-out)</span>`
            : `<span class="pill ${cls}">${d.status ? d.status.charAt(0).toUpperCase()+d.status.slice(1) : '—'}</span>`;

        show('viewRecordModal');
    });
});
$('closeViewModal').onclick = () => hide('viewRecordModal');
outsideClose('viewRecordModal');

// "Edit this record" from view modal
$('viewToEditBtn').addEventListener('click', () => {
    if (!_currentViewData) return;
    hide('viewRecordModal');
    openEditModal(_currentViewData);
});

// ── MODAL 4: Edit single record ───────────────────────────────────────────
function openEditModal(d) {
    $('editRecordForm').action = `/attendance-records/${d.id}`;
    $('editEmployeeId').value  = d.employee_id  || '';
    $('editDate').value        = d.attendance_date || '';
    $('editTimeIn').value      = d.time_in       || '';
    $('editTimeOut').value     = d.time_out      || '';
    $('editStatus').value      = d.status        || 'present';
    $('editHoursWorked').value = d.hours_worked  || '';
    $('editNotes').value       = d.notes         || '';
    show('editRecordModal');
}

document.querySelectorAll('.editRecordBtn').forEach(btn => {
    btn.addEventListener('click', () => openEditModal(btn.dataset));
});
$('closeEditModal').onclick  = () => hide('editRecordModal');
$('cancelEditModal').onclick = () => hide('editRecordModal');
outsideClose('editRecordModal');

// ── Bulk create validation ────────────────────────────────────────────────
$('bulkCreateForm').addEventListener('submit', function(e) {
    const selects = this.querySelectorAll('select[name$="[status]"]');
    const missing = [];
    selects.forEach(sel => {
        if (!sel.value) {
            const row  = sel.closest('tr');
            const name = row.querySelector('.emp-name')?.textContent.trim();
            missing.push(name);
            sel.style.borderColor = '#f87171';
            sel.style.boxShadow   = '0 0 0 2px rgba(248,113,113,.25)';
        } else {
            sel.style.borderColor = '';
            sel.style.boxShadow   = '';
        }
    });
    if (missing.length) {
        e.preventDefault();
        let msg = document.getElementById('bulkValidationMsg');
        if (!msg) {
            msg = document.createElement('div');
            msg.id = 'bulkValidationMsg';
            msg.style.cssText = 'margin-bottom:10px;padding:10px 12px;border-radius:10px;font-size:12px;background:#fef2f2;border:1px solid #fca5a5;color:#991b1b;';
            this.querySelector('.bulk-wrap').before(msg);
        }
        msg.textContent = `Please set a status for: ${missing.join(', ')}.`;
        msg.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
});
document.querySelectorAll('#attendanceModal select[name$="[status]"]').forEach(sel => {
    sel.addEventListener('change', function() {
        if (this.value) {
            this.style.borderColor = '';
            this.style.boxShadow   = '';
            const allFilled = [...document.querySelectorAll('#attendanceModal select[name$="[status]"]')].every(s => s.value);
            if (allFilled) document.getElementById('bulkValidationMsg')?.remove();
        }
    });
});
</script>
</body>
</html>