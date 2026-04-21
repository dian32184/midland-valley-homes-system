@php($title='Benefits')
<x-manager-shell :title="$title" header-title="Benefits">
@if(session('status'))<div class="flash flash-success">{{ session('status') }}</div>@endif
<div class="panel"><div class="panel-head"><div class="panel-title">Benefits Dashboard</div><a class="btn btn-primary" href="{{ route('benefits.create') }}">Add Benefit</a></div>
<div style="display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:10px;">
<div class="panel" style="padding:10px;"><div class="label">Total</div><div style="font-size:18px;font-weight:800;">{{ $stats['total'] }}</div></div>
<div class="panel" style="padding:10px;"><div class="label">Pending</div><div style="font-size:18px;font-weight:800;">{{ $stats['pending'] }}</div></div>
<div class="panel" style="padding:10px;"><div class="label">Active</div><div style="font-size:18px;font-weight:800;">{{ $stats['active'] }}</div></div>
<div class="panel" style="padding:10px;"><div class="label">Inactive</div><div style="font-size:18px;font-weight:800;">{{ $stats['inactive'] }}</div></div>
</div></div>
<div class="panel"><div style="overflow:auto;border:1px solid #e2e8f0;border-radius:12px;background:#fff;"><table><thead><tr><th>Employee</th><th>Type</th><th>Status</th><th>Membership</th><th style="text-align:right;">Actions</th></tr></thead><tbody>
@forelse($benefits as $benefit)<tr><td><a href="{{ route('benefits.show',$benefit) }}" style="font-weight:800;color:#0f172a;text-decoration:none;">{{ $benefit->employee?->last_name }}, {{ $benefit->employee?->first_name }}</a></td><td class="muted">{{ $benefit->benefit_type }}</td><td><span class="badge badge-gray">{{ $benefit->status }}</span></td><td class="muted">{{ $benefit->membership_number??'—' }}</td><td style="text-align:right;"><a class="btn" href="{{ route('benefits.edit',$benefit) }}">Edit</a></td></tr>@empty<tr><td colspan="5" class="muted" style="text-align:center;padding:18px;">No benefits records.</td></tr>@endforelse
</tbody></table></div><div style="margin-top:10px;">{{ $benefits->links() }}</div></div>
</x-manager-shell>
