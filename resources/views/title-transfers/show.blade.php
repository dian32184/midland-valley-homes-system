@php
    $title = 'Title Transfer';
    $headerTitle = 'Title Transfers';
    $headerSubtitle = "Transfer #{$transfer->id}";

    $customerName = $transfer->customer
        ? "{$transfer->customer->last_name}, {$transfer->customer->first_name}"
        : '—';

    $propertyLabel = $transfer->property
        ? "B{$transfer->property->block_number} / L{$transfer->property->lot_number} • {$transfer->property->house_type}"
        : '—';

    $badgeClass = match($transfer->status) {
        'pending' => 'badge badge-gray',
        'submitted_to_bir' => 'badge badge-yellow',
        'car_issued' => 'badge badge-yellow',
        'registered' => 'badge badge-green',
        'tct_issued' => 'badge badge-green',
        'rejected' => 'badge badge-red',
        default => 'badge badge-gray',
    };
@endphp

<x-manager-shell :title="$title" :header-title="$headerTitle" :header-subtitle="$headerSubtitle">
    @if (session('status'))
        <div class="flash flash-success">{{ session('status') }}</div>
    @endif

    <div class="panel">
        <div class="panel-head">
            <div style="display:flex; gap: 10px; align-items:center;">
                <div class="panel-title">Transfer Details</div>
                <span class="{{ $badgeClass }}">{{ str_replace('_', ' ', $transfer->status) }}</span>
            </div>
            <div style="display:flex; gap: 8px;">
                <a class="btn btn-primary" href="{{ route('title-transfers.edit', $transfer) }}">Edit</a>
                <a class="btn" href="{{ route('title-transfers.index') }}">Back</a>
            </div>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 10px;">
            <div class="panel" style="padding:10px;">
                <div class="label">Customer</div>
                <div style="display:grid; gap: 6px; margin-top: 8px;">
                    <div><strong>{{ $customerName }}</strong></div>
                    <div class="muted">{{ $transfer->customer?->email ?? '—' }}</div>
                </div>
            </div>
            <div class="panel" style="padding:10px;">
                <div class="label">Property</div>
                <div style="display:grid; gap: 6px; margin-top: 8px;">
                    <div><strong>{{ $propertyLabel }}</strong></div>
                </div>
            </div>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 10px; margin-top: 10px;">
            <div class="panel" style="padding:10px;"><div class="label">Submitted to BIR</div><div style="margin-top: 8px;"><strong>{{ $transfer->submitted_to_bir_at ?? '—' }}</strong></div></div>
            <div class="panel" style="padding:10px;"><div class="label">CAR issued</div><div style="margin-top: 8px;"><strong>{{ $transfer->car_issued_at ?? '—' }}</strong></div></div>
            <div class="panel" style="padding:10px;"><div class="label">Registered</div><div style="margin-top: 8px;"><strong>{{ $transfer->registered_at ?? '—' }}</strong></div></div>
            <div class="panel" style="padding:10px;"><div class="label">TCT issued</div><div style="margin-top: 8px;"><strong>{{ $transfer->tct_issued_at ?? '—' }}</strong></div></div>
        </div>

        <div class="panel" style="margin-top: 10px; padding:10px;">
            <div class="label">Remarks</div>
            <div style="margin-top: 8px; white-space: pre-wrap;">{{ $transfer->remarks ?? '—' }}</div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">Danger zone</div>
            <div class="chip">Delete transfer</div>
        </div>
        <form method="POST" action="{{ route('title-transfers.destroy', $transfer) }}" onsubmit="return confirm('Delete this title transfer?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete transfer</button>
        </form>
    </div>
</x-manager-shell>

