<x-manager-shell title="Benefit" header-title="Benefits" header-subtitle="Benefit #{{ $benefit->id }}">
<div class="panel"><div class="panel-head"><div class="panel-title">Details</div><a class="btn" href="{{ route('benefits.edit',$benefit) }}">Edit</a></div>
<div class="muted">{{ $benefit->employee?->last_name }}, {{ $benefit->employee?->first_name }}</div>
<div>Type: {{ $benefit->benefit_type }}</div>
<div>Status: {{ $benefit->status }}</div>
<div>Membership #: {{ $benefit->membership_number ?? '—' }}</div>
</div>
</x-manager-shell>
