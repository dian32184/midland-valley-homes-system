<x-manager-shell title="Payroll" header-title="Payroll" header-subtitle="Payroll #{{ $payroll->id }}">
<div class="panel"><div class="panel-head"><div class="panel-title">Details</div><a class="btn" href="{{ route('payrolls.edit',$payroll) }}">Edit</a></div>
<div class="muted">{{ $payroll->employee?->last_name }}, {{ $payroll->employee?->first_name }}</div>
<div>Period: {{ $payroll->period_start_date }} to {{ $payroll->period_end_date }}</div>
<div>Gross: PHP {{ number_format((float)$payroll->gross_amount,2) }}</div>
<div>Deductions: PHP {{ number_format((float)$payroll->deductions,2) }}</div>
<div>Net: PHP {{ number_format((float)$payroll->net_amount,2) }}</div>
</div>
</x-manager-shell>
