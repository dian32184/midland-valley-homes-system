@php
    $typeOptions = [
        'downpayment' => 'Downpayment',
        'installment' => 'Installment',
        'equity' => 'Equity',
        'reservation_fee' => 'Reservation fee',
        'other' => 'Other',
    ];
@endphp

<div style="display:grid; grid-template-columns: 1fr 1fr; gap: 10px;">
    <div class="field">
        <div class="label">Customer *</div>
        <select class="select" name="customer_id">
            <option value="">Select customer</option>
            @foreach ($customers as $customer)
                <option value="{{ $customer->id }}" @selected((int) old('customer_id', $payment->customer_id) === (int) $customer->id)>
                    {{ $customer->last_name }}, {{ $customer->first_name }}{{ $customer->email ? " • {$customer->email}" : '' }}
                </option>
            @endforeach
        </select>
        @error('customer_id') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <div class="label">Property *</div>
        <select class="select" name="property_id">
            <option value="">Select property</option>
            @foreach ($properties as $property)
                <option value="{{ $property->id }}" @selected((int) old('property_id', $payment->property_id) === (int) $property->id)>
                    B{{ $property->block_number }} / L{{ $property->lot_number }} • {{ $property->house_type }} • {{ str_replace('_', ' ', $property->status) }}
                </option>
            @endforeach
        </select>
        @error('property_id') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <div class="label">Reservation (optional)</div>
        <select class="select" name="reservation_id">
            <option value="">None</option>
            @foreach ($reservations as $reservation)
                @php
                    $rCustomer = $reservation->customer ? "{$reservation->customer->last_name}, {$reservation->customer->first_name}" : '—';
                    $rProperty = $reservation->property ? "B{$reservation->property->block_number}/L{$reservation->property->lot_number}" : '—';
                @endphp
                <option value="{{ $reservation->id }}" @selected((int) old('reservation_id', $payment->reservation_id) === (int) $reservation->id)>
                    #{{ $reservation->id }} • {{ $rCustomer }} • {{ $rProperty }} • {{ $reservation->status }}
                </option>
            @endforeach
        </select>
        @error('reservation_id') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <div class="label">Payment type *</div>
        <select class="select" name="payment_type">
            @foreach ($typeOptions as $value => $label)
                <option value="{{ $value }}" @selected(old('payment_type', $payment->payment_type ?? 'downpayment') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('payment_type') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <div class="label">Amount (PHP) *</div>
        <input class="input" name="amount" type="number" step="0.01" min="0" value="{{ old('amount', $payment->amount ?? 0) }}" />
        @error('amount') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <div class="label">Payment date</div>
        <input class="input" name="payment_date" type="date" value="{{ old('payment_date', optional($payment->payment_date)->format('Y-m-d') ?? $payment->payment_date) }}" />
        @error('payment_date') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <div class="label">Method</div>
        <input class="input" name="payment_method" value="{{ old('payment_method', $payment->payment_method) }}" placeholder="Cash / bank transfer / etc." />
        @error('payment_method') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field" style="grid-column: 1 / -1;">
        <div class="label">Notes</div>
        <textarea class="textarea" name="notes" rows="3">{{ old('notes', $payment->notes) }}</textarea>
        @error('notes') <div class="error">{{ $message }}</div> @enderror
    </div>
</div>

