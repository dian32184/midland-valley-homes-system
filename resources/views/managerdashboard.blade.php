@php
    $managerStats = [
        'total_customers' => \App\Models\Customer::count(),
        'pending_customers' => \App\Models\Customer::where('status', 'pending')->count(),
        'approved_customers' => \App\Models\Customer::where('status', 'approved')->count(),
        'total_properties' => \App\Models\Property::count(),
        'available_properties' => \App\Models\Property::where('status', 'available')->count(),
        'sold_properties' => \App\Models\Property::where('status', 'sold')->count(),
        'total_collections' => \App\Models\Payment::sum('amount'),
        'pending_payrolls' => \App\Models\Payroll::where('status', 'pending')->count(),
        'today_attendance' => \App\Models\AttendanceRecord::whereDate('attendance_date', now()->toDateString())->count(),
    ];
@endphp

@php
    $title = 'Manager Dashboard';
    $headerTitle = 'Manager Dashboard';
@endphp

<x-manager-shell :title="$title" :header-title="$headerTitle">
    <section style="display:grid; grid-template-rows: 90px 1fr 1fr; gap: var(--gap);">
        <div class="summary" style="display:grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: var(--gap);">
            <div class="card c-teal" style="border-radius: var(--radius); box-shadow: 0 8px 18px rgba(15, 23, 42, 0.08); padding: 12px; color: #fff; height: 90px; overflow: hidden; background: linear-gradient(135deg, #73d9ef, #5aa8f2);">
                <div class="k-label" style="font-size: 12px; font-weight: 700; text-transform: uppercase; opacity: 0.95;">Customers</div>
                <div class="k-number" style="font-size: 16px; font-weight: 700; margin-top: 4px; line-height: 1.2;">{{ $managerStats['total_customers'] }}</div>
                <div class="k-sub" style="font-size: 12px; margin-top: 4px; opacity: 0.95; line-height: 1.2;">Pending {{ $managerStats['pending_customers'] }} | Approved {{ $managerStats['approved_customers'] }}</div>
            </div>
            <div class="card c-blue" style="border-radius: var(--radius); box-shadow: 0 8px 18px rgba(15, 23, 42, 0.08); padding: 12px; color: #fff; height: 90px; overflow: hidden; background: linear-gradient(135deg, #9cb5ff, #6d90f6);">
                <div class="k-label" style="font-size: 12px; font-weight: 700; text-transform: uppercase; opacity: 0.95;">Properties</div>
                <div class="k-number" style="font-size: 16px; font-weight: 700; margin-top: 4px; line-height: 1.2;">{{ $managerStats['total_properties'] }}</div>
                <div class="k-sub" style="font-size: 12px; margin-top: 4px; opacity: 0.95; line-height: 1.2;">Available {{ $managerStats['available_properties'] }} | Sold {{ $managerStats['sold_properties'] }}</div>
            </div>
            <div class="card c-pink" style="border-radius: var(--radius); box-shadow: 0 8px 18px rgba(15, 23, 42, 0.08); padding: 12px; color: #fff; height: 90px; overflow: hidden; background: linear-gradient(135deg, #f5add1, #ea7fbe);">
                <div class="k-label" style="font-size: 12px; font-weight: 700; text-transform: uppercase; opacity: 0.95;">Collections</div>
                <div class="k-number" style="font-size: 16px; font-weight: 700; margin-top: 4px; line-height: 1.2;">PHP {{ number_format((float) $managerStats['total_collections'], 2) }}</div>
                <div class="k-sub" style="font-size: 12px; margin-top: 4px; opacity: 0.95; line-height: 1.2;">Total recorded payments</div>
            </div>
            <div class="card c-purple" style="border-radius: var(--radius); box-shadow: 0 8px 18px rgba(15, 23, 42, 0.08); padding: 12px; color: #fff; height: 90px; overflow: hidden; background: linear-gradient(135deg, #d2bcff, #a88ef0);">
                <div class="k-label" style="font-size: 12px; font-weight: 700; text-transform: uppercase; opacity: 0.95;">Workforce</div>
                <div class="k-number" style="font-size: 16px; font-weight: 700; margin-top: 4px; line-height: 1.2;">Attendance {{ $managerStats['today_attendance'] }}</div>
                <div class="k-sub" style="font-size: 12px; margin-top: 4px; opacity: 0.95; line-height: 1.2;">Pending Payroll {{ $managerStats['pending_payrolls'] }}</div>
            </div>
        </div>

        <div class="middle" style="display:grid; grid-template-columns: 65fr 35fr; gap: var(--gap);">
            <div class="panel">
                <div class="panel-head">
                    <div class="panel-title">Operations Trend</div>
                    <div class="chip">Last 6 months</div>
                </div>
                <div style="position: relative; height: 170px; border-radius: 12px; background: linear-gradient(180deg, #f8fbff, #fff); overflow: hidden;">
                    <svg viewBox="0 0 600 220" preserveAspectRatio="none" style="width: 100%; height: 100%; position: absolute; inset: 0;">
                        <defs>
                            <linearGradient id="fillLine" x1="0" x2="0" y1="0" y2="1">
                                <stop offset="0%" stop-color="#93c5fd" stop-opacity="0.55"></stop>
                                <stop offset="100%" stop-color="#93c5fd" stop-opacity="0"></stop>
                            </linearGradient>
                        </defs>
                        <path d="M0,188 C60,165 90,170 135,142 C180,120 225,155 280,116 C330,84 360,108 420,94 C472,80 520,86 600,64 L600,220 L0,220 Z" fill="url(#fillLine)"></path>
                        <path d="M0,188 C60,165 90,170 135,142 C180,120 225,155 280,116 C330,84 360,108 420,94 C472,80 520,86 600,64" fill="none" stroke="#60a5fa" stroke-width="4"></path>
                    </svg>
                </div>
            </div>

            <div class="panel">
                <div class="panel-head">
                    <div class="panel-title">Activity Split</div>
                    <div class="chip">This month</div>
                </div>
                <div style="width: 140px; height: 140px; border-radius: 999px; margin: 0 auto 8px; background: conic-gradient(#8b5cf6 0 35%, #22d3ee 35% 57%, #60a5fa 57% 77%, #f472b6 77% 100%); display: grid; place-items: center;">
                    <div style="width: 82px; height: 82px; border-radius: 999px; background: #fff; border: 1px solid #e2e8f0;"></div>
                </div>
                <div style="font-size: 12px; color: #475569; display: grid; gap: 3px;">
                    <div style="display:flex; justify-content: space-between;"><span>Customers</span><strong>35%</strong></div>
                    <div style="display:flex; justify-content: space-between;"><span>Payments</span><strong>22%</strong></div>
                    <div style="display:flex; justify-content: space-between;"><span>Construction</span><strong>20%</strong></div>
                    <div style="display:flex; justify-content: space-between;"><span>Payroll</span><strong>23%</strong></div>
                </div>
            </div>
        </div>

        <div class="bottom" style="display:grid; grid-template-columns: 1fr 1fr; gap: var(--gap);">
            <div class="panel">
                <div class="panel-title">Module Goals</div>
                <div style="margin-top: 8px;">
                    <div style="display:flex; justify-content: space-between; font-size: 12px; color: #475569; margin-bottom: 5px;"><span>Customer Approval Rate</span><strong>68%</strong></div>
                    <div style="height: 6px; background: #e5e7eb; border-radius: 999px; overflow: hidden;"><div style="height: 100%; background: linear-gradient(90deg, #60a5fa, #6366f1); width: 68%;"></div></div>
                </div>
                <div style="margin-top: 8px;">
                    <div style="display:flex; justify-content: space-between; font-size: 12px; color: #475569; margin-bottom: 5px;"><span>Collection Completion</span><strong>54%</strong></div>
                    <div style="height: 6px; background: #e5e7eb; border-radius: 999px; overflow: hidden;"><div style="height: 100%; background: linear-gradient(90deg, #60a5fa, #6366f1); width: 54%;"></div></div>
                </div>
                <div style="margin-top: 8px;">
                    <div style="display:flex; justify-content: space-between; font-size: 12px; color: #475569; margin-bottom: 5px;"><span>Construction Progress</span><strong>42%</strong></div>
                    <div style="height: 6px; background: #e5e7eb; border-radius: 999px; overflow: hidden;"><div style="height: 100%; background: linear-gradient(90deg, #60a5fa, #6366f1); width: 42%;"></div></div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-title">Quick Summary / Notes</div>
                <div style="margin-top: 10px; font-size: 12px; color: #475569; display: grid; gap: 8px;">
                    <div style="border-radius: 10px; background: #f8fafc; border: 1px solid #e2e8f0; padding: 8px;">No pending approvals.</div>
                    <div style="border-radius: 10px; background: #f8fafc; border: 1px solid #e2e8f0; padding: 8px;">Low collection this month.</div>
                    <div style="border-radius: 10px; background: #f8fafc; border: 1px solid #e2e8f0; padding: 8px;">Construction progress remains below target at 42%.</div>
                    <div style="border-radius: 10px; background: #f8fafc; border: 1px solid #e2e8f0; padding: 8px;">Attendance logs are complete for today.</div>
                </div>
            </div>
        </div>
    </section>
</x-manager-shell>
