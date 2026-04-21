<div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
<div class="field"><div class="label">Employee</div><select class="select" name="employee_id">@foreach($employees as $e)<option value="{{ $e->id }}" @selected((int)old('employee_id',$payroll->employee_id)===(int)$e->id)>{{ $e->last_name }}, {{ $e->first_name }}</option>@endforeach</select></div>
<div class="field"><div class="label">Payroll type</div><select class="select" name="payroll_type"><option value="weekly" @selected(old('payroll_type',$payroll->payroll_type??'weekly')==='weekly')>weekly</option><option value="monthly" @selected(old('payroll_type',$payroll->payroll_type)==='monthly')>monthly</option></select></div>
<div class="field"><div class="label">Period start</div><input class="input" type="date" name="period_start_date" value="{{ old('period_start_date',$payroll->period_start_date) }}"></div>
<div class="field"><div class="label">Period end</div><input class="input" type="date" name="period_end_date" value="{{ old('period_end_date',$payroll->period_end_date) }}"></div>
<div class="field"><div class="label">Gross</div><input class="input" type="number" step="0.01" name="gross_amount" value="{{ old('gross_amount',$payroll->gross_amount??0) }}"></div>
<div class="field"><div class="label">Deductions</div><input class="input" type="number" step="0.01" name="deductions" value="{{ old('deductions',$payroll->deductions??0) }}"></div>
<div class="field"><div class="label">Net</div><input class="input" type="number" step="0.01" name="net_amount" value="{{ old('net_amount',$payroll->net_amount??0) }}"></div>
<div class="field"><div class="label">Status</div><select class="select" name="status"><option value="pending" @selected(old('status',$payroll->status??'pending')==='pending')>pending</option><option value="processed" @selected(old('status',$payroll->status)==='processed')>processed</option><option value="paid" @selected(old('status',$payroll->status)==='paid')>paid</option></select></div>
<div class="field"><div class="label">Paid at</div><input class="input" type="date" name="paid_at" value="{{ old('paid_at',$payroll->paid_at) }}"></div>
<div class="field" style="grid-column:1/-1;"><div class="label">Notes</div><textarea class="textarea" name="notes">{{ old('notes',$payroll->notes) }}</textarea></div>
</div>
