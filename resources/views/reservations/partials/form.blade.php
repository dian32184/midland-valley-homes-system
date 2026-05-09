<select name="customer_id" class="rounded border-gray-300">
    @foreach ($customers as $customer)
        <option value="{{ $customer->id }}" @selected(old('customer_id', $reservation?->customer_id) == $customer->id)>
            {{ $customer->first_name }} {{ $customer->last_name }}
        </option>
    @endforeach
</select>
<select name="property_id" class="rounded border-gray-300">
    @foreach ($properties as $propertyOption)
        <option value="{{ $propertyOption->id }}" @selected(old('property_id', $reservation?->property_id) == $propertyOption->id)>
            {{ $propertyOption->block_number }}/{{ $propertyOption->lot_number }} ({{ $propertyOption->status }})
        </option>
    @endforeach
</select>
<input name="reservation_fee" type="number" step="0.01" placeholder="Reservation Fee" value="{{ old('reservation_fee', $reservation?->reservation_fee) }}" class="rounded border-gray-300">
<select name="status" class="rounded border-gray-300">
    @foreach (['active', 'completed', 'cancelled', 'skipped'] as $status)
        <option value="{{ $status }}" @selected(old('status', $reservation?->status ?? 'active') === $status)>{{ $status }}</option>
    @endforeach
</select>
<input name="reserved_on" type="date" value="{{ old('reserved_on', $reservation?->reserved_on) }}" class="rounded border-gray-300">
<input name="expires_at" type="date" value="{{ old('expires_at', $reservation?->expires_at) }}" class="rounded border-gray-300">
<textarea name="notes" placeholder="Notes" class="rounded border-gray-300 md:col-span-2">{{ old('notes', $reservation?->notes) }}</textarea>
<div class="md:col-span-2 flex gap-2">
    <button class="px-4 py-2 bg-indigo-600 text-white rounded">Save</button>
    <a href="{{ route('reservations.index') }}" class="px-4 py-2 bg-gray-200 rounded">Cancel</a>
</div>
