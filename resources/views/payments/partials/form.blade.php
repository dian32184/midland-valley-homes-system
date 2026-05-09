<select name="customer_id" class="rounded border-gray-300">
    @foreach ($customers as $customer)
        <option value="{{ $customer->id }}" @selected(old('customer_id', $payment?->customer_id) == $customer->id)>{{ $customer->first_name }} {{ $customer->last_name }}</option>
    @endforeach
</select>
<select name="property_id" class="rounded border-gray-300">
    @foreach ($properties as $propertyOption)
        <option value="{{ $propertyOption->id }}" @selected(old('property_id', $payment?->property_id) == $propertyOption->id)>{{ $propertyOption->block_number }}/{{ $propertyOption->lot_number }}</option>
    @endforeach
</select>
<select name="reservation_id" class="rounded border-gray-300">
    <option value="">No linked reservation</option>
    @foreach ($reservations as $reservationOption)
        <option value="{{ $reservationOption->id }}" @selected(old('reservation_id', $payment?->reservation_id) == $reservationOption->id)>
            #{{ $reservationOption->id }} - {{ $reservationOption->status }}
        </option>
    @endforeach
</select>
<select name="payment_type" class="rounded border-gray-300">
    @foreach (['downpayment', 'installment', 'equity', 'reservation_fee', 'other'] as $type)
        <option value="{{ $type }}" @selected(old('payment_type', $payment?->payment_type ?? 'downpayment') === $type)>{{ $type }}</option>
    @endforeach
</select>
<input name="amount" type="number" step="0.01" value="{{ old('amount', $payment?->amount) }}" placeholder="Amount" class="rounded border-gray-300">
<input name="payment_date" type="date" value="{{ old('payment_date', $payment?->payment_date) }}" class="rounded border-gray-300">
<input name="payment_method" value="{{ old('payment_method', $payment?->payment_method) }}" placeholder="Payment Method" class="rounded border-gray-300 md:col-span-2">
<textarea name="notes" placeholder="Notes" class="rounded border-gray-300 md:col-span-2">{{ old('notes', $payment?->notes) }}</textarea>
<div class="md:col-span-2 flex gap-2">
    <button class="px-4 py-2 bg-indigo-600 text-white rounded">Save</button>
    <a href="{{ route('payments.index') }}" class="px-4 py-2 bg-gray-200 rounded">Cancel</a>
</div>
