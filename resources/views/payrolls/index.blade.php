@php($title='Payroll')
<x-manager-shell :title="$title" header-title="Payroll">
@if(session('status'))<div class="flash flash-success">{{ session('status') }}</div>@endif
<div class="panel">
  <div class="panel-head"><div class="panel-title">Payroll Dashboard</div><a class="btn btn-primary" href="{{ route('payrolls.create') }}">Add Payroll</a></div>
  <div style="display:grid;grid-template-columns:repeat(5,minmax(0,1fr));gap:10px;">
    <div class="panel" style="padding:10px;"><div class="label">Total</div><div style="font-size:18px;font-weight:800;">{{ $stats['total'] }}</div></div>
    <div class="panel" style="padding:10px;"><div class="label">Pending</div><div style="font-size:18px;font-weight:800;">{{ $stats['pending'] }}</div></div>
    <div class="panel" style="padding:10px;"><div class="label">Processed</div><div style="font-size:18px;font-weight:800;">{{ $stats['processed'] }}</div></div>
    <div class="panel" style="padding:10px;"><div class="label">Paid</div><div style="font-size:18px;font-weight:800;">{{ $stats['paid'] }}</div></div>
    <div class="panel" style="padding:10px;"><div class="label">Net total</div><div style="font-size:18px;font-weight:800;">PHP {{ number_format((float)$stats['net_total'],2) }}</div></div>
  </div>
</div>
<div class="panel">
  <form method="GET" action="{{ route('payrolls.index') }}" style="display:grid;grid-template-columns:1fr 220px auto;gap:10px;align-items:end;margin-bottom:10px;">
    <div class="field"><div class="label">Search</div><input class="input" name="q" value="{{ $search }}" placeholder="Employee name/email"></div>
    <div class="field"><div class="label">Status</div><select class="select" name="status"><option value="all" @selected(($status??'all')==='all')>All</option><option value="pending" @selected($status==='pending')>Pending</option><option value="processed" @selected($status==='processed')>Processed</option><option value="paid" @selected($status==='paid')>Paid</option></select></div>
    <div style="display:flex;gap:8px;"><button class="btn btn-primary">Filter</button><a class="btn" href="{{ route('payrolls.index') }}">Reset</a></div>
  </form>
  <div style="overflow:auto;border:1px solid #e2e8f0;border-radius:12px;background:#fff;">
    <table><thead><tr><th>Employee</th><th>Period</th><th>Type</th><th>Status</th><th>Net</th><th style="text-align:right;">Actions</th></tr></thead><tbody>
      @forelse($payrolls as $payroll)
      <tr><td><a href="{{ route('payrolls.show',$payroll) }}" style="font-weight:800;text-decoration:none;color:#0f172a;">{{ $payroll->employee?->last_name }}, {{ $payroll->employee?->first_name }}</a></td><td class="muted">{{ $payroll->period_start_date }} to {{ $payroll->period_end_date }}</td><td class="muted">{{ $payroll->payroll_type }}</td><td><span class="badge badge-gray">{{ $payroll->status }}</span></td><td><strong>PHP {{ number_format((float)$payroll->net_amount,2) }}</strong></td><td style="text-align:right;"><a class="btn" href="{{ route('payrolls.edit',$payroll) }}">Edit</a></td></tr>
      @empty <tr><td colspan="6" class="muted" style="text-align:center;padding:18px;">No payroll records.</td></tr> @endforelse
    </tbody></table>
  </div>
  <div style="margin-top:10px;">{{ $payrolls->links() }}</div>
</div>
</x-manager-shell>

