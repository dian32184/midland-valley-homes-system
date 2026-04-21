@php
    $title = 'Reservation';
    $headerTitle = 'Reservations';
    $headerSubtitle = "Reservation #{$reservation->id}";

    $badgeClass = match($reservation->status) {
        'active' => 'badge badge-green',
        'completed' => 'badge badge-gray',
        'cancelled' => 'badge badge-red',
        'skipped' => 'badge badge-yellow',
        default => 'badge badge-gray',
    };

    $customerName = $reservation->customer
        ? "{$reservation->customer->last_name}, {$reservation->customer->first_name}"
        : '—';

    $propertyLabel = $reservation->property
        ? "B{$reservation->property->block_number} / L{$reservation->property->lot_number} • {$reservation->property->house_type}"
        : '—';
@endphp

<x-manager-shell :title="$title" :header-title="$headerTitle" :header-subtitle="$headerSubtitle">
    @if (session('status'))
        <div class="flash flash-success">{{ session('status') }}</div>
    @endif

    <div class="panel">
        <div class="panel-head">
            <div style="display:flex; gap: 10px; align-items:center;">
                <div class="panel-title">Reservation Details</div>
                <span class="{{ $badgeClass }}">{{ $reservation->status }}</span>
            </div>
            <div style="display:flex; gap: 8px;">
                <a class="btn btn-primary" href="{{ route('reservations.edit', $reservation) }}">Edit</a>
                <a class="btn" href="{{ route('reservations.index') }}">Back</a>
            </div>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 10px;">
            <div class="panel" style="padding:10px;">
                <div class="label">Customer</div>
                <div style="display:grid; gap: 6px; margin-top: 8px;">
                    <div><strong>{{ $customerName }}</strong></div>
                    <div class="muted">{{ $reservation->customer?->email ?? '—' }}</div>
                    <div class="muted">{{ $reservation->customer?->phone ?? '—' }}</div>
                </div>
            </div>
            <div class="panel" style="padding:10px;">
                <div class="label">Property</div>
                <div style="display:grid; gap: 6px; margin-top: 8px;">
                    <div><strong>{{ $propertyLabel }}</strong></div>
                    <div class="muted">{{ $reservation->property?->status ? str_replace('_', ' ', $reservation->property->status) : '—' }}</div>
                </div>
            </div>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; margin-top: 10px;">
            <div class="panel" style="padding:10px;">
                <div class="label">Reservation fee</div>
                <div style="margin-top: 8px; font-size: 16px; font-weight: 800;">PHP {{ number_format((float) $reservation->reservation_fee, 2) }}</div>
            </div>
            <div class="panel" style="padding:10px;">
                <div class="label">Reserved on</div>
                <div style="margin-top: 8px;"><strong>{{ $reservation->reserved_on ?? '—' }}</strong></div>
            </div>
            <div class="panel" style="padding:10px;">
                <div class="label">Expires at</div>
                <div style="margin-top: 8px;"><strong>{{ $reservation->expires_at ?? '—' }}</strong></div>
            </div>
        </div>

        <div class="panel" style="margin-top: 10px; padding:10px;">
            <div class="label">Notes</div>
            <div style="margin-top: 8px; white-space: pre-wrap;">{{ $reservation->notes ?? '—' }}</div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">Danger zone</div>
            <div class="chip">Delete reservation</div>
        </div>
        <form method="POST" action="{{ route('reservations.destroy', $reservation) }}" onsubmit="return confirm('Delete this reservation?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete reservation</button>
        </form>
    </div>
</x-manager-shell>

