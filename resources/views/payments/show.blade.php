@php
    $title = 'Payment';
    $headerTitle = 'Payments';
    $headerSubtitle = "Payment #{$payment->id}";

    $customerName = $payment->customer
        ? "{$payment->customer->last_name}, {$payment->customer->first_name}"
        : '—';

    $propertyLabel = $payment->property
        ? "B{$payment->property->block_number} / L{$payment->property->lot_number} • {$payment->property->house_type}"
        : '—';
@endphp

<x-manager-shell :title="$title" :header-title="$headerTitle" :header-subtitle="$headerSubtitle">
    @if (session('status'))
        <div class="flash flash-success">{{ session('status') }}</div>
    @endif

    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">Payment Details</div>
            <div style="display:flex; gap: 8px;">
                <a class="btn btn-primary" href="{{ route('payments.edit', $payment) }}">Edit</a>
                <a class="btn" href="{{ route('payments.index') }}">Back</a>
            </div>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 10px;">
            <div class="panel" style="padding:10px;">
                <div class="label">Customer</div>
                <div style="display:grid; gap: 6px; margin-top: 8px;">
                    <div><strong>{{ $customerName }}</strong></div>
                    <div class="muted">{{ $payment->customer?->email ?? '—' }}</div>
                    <div class="muted">{{ $payment->customer?->phone ?? '—' }}</div>
                </div>
            </div>
            <div class="panel" style="padding:10px;">
                <div class="label">Property</div>
                <div style="display:grid; gap: 6px; margin-top: 8px;">
                    <div><strong>{{ $propertyLabel }}</strong></div>
                    <div class="muted">{{ $payment->property?->status ? str_replace('_', ' ', $payment->property->status) : '—' }}</div>
                </div>
            </div>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; margin-top: 10px;">
            <div class="panel" style="padding:10px;">
                <div class="label">Amount</div>
                <div style="margin-top: 8px; font-size: 16px; font-weight: 800;">PHP {{ number_format((float) $payment->amount, 2) }}</div>
            </div>
            <div class="panel" style="padding:10px;">
                <div class="label">Type</div>
                <div style="margin-top: 8px;"><span class="badge badge-gray">{{ str_replace('_', ' ', $payment->payment_type) }}</span></div>
            </div>
            <div class="panel" style="padding:10px;">
                <div class="label">Date</div>
                <div style="margin-top: 8px;"><strong>{{ $payment->payment_date ?? '—' }}</strong></div>
            </div>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 10px;">
            <div class="panel" style="padding:10px;">
                <div class="label">Method</div>
                <div style="margin-top: 8px;"><strong>{{ $payment->payment_method ?? '—' }}</strong></div>
            </div>
            <div class="panel" style="padding:10px;">
                <div class="label">Reservation</div>
                <div style="margin-top: 8px;"><strong>{{ $payment->reservation_id ? "#{$payment->reservation_id}" : '—' }}</strong></div>
            </div>
        </div>

        <div class="panel" style="margin-top: 10px; padding:10px;">
            <div class="label">Notes</div>
            <div style="margin-top: 8px; white-space: pre-wrap;">{{ $payment->notes ?? '—' }}</div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">Danger zone</div>
            <div class="chip">Delete payment</div>
        </div>
        <form method="POST" action="{{ route('payments.destroy', $payment) }}" onsubmit="return confirm('Delete this payment?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete payment</button>
        </form>
    </div>
</x-manager-shell>

