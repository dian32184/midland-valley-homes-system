@php
    $statusOptions = [
        'active' => 'Active',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
        'skipped' => 'Skipped',
    ];
@endphp

<div style="display:grid; grid-template-columns: 1fr 1fr; gap: 10px;">
    <div class="field">
        <div class="label">Customer *</div>
        <select class="select" name="customer_id">
            <option value="">Select customer</option>
            @foreach ($customers as $customer)
                <option value="{{ $customer->id }}" @selected((int) old('customer_id', $reservation->customer_id) === (int) $customer->id)>
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
                <option value="{{ $property->id }}" @selected((int) old('property_id', $reservation->property_id) === (int) $property->id)>
                    B{{ $property->block_number }} / L{{ $property->lot_number }} • {{ $property->house_type }} • {{ str_replace('_', ' ', $property->status) }}
                </option>
            @endforeach
        </select>
        @error('property_id') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <div class="label">Reservation fee (PHP) *</div>
        <input class="input" name="reservation_fee" type="number" step="0.01" min="0" value="{{ old('reservation_fee', $reservation->reservation_fee ?? 0) }}" />
        @error('reservation_fee') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <div class="label">Status *</div>
        <select class="select" name="status">
            @foreach ($statusOptions as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $reservation->status ?? 'active') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('status') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <div class="label">Reserved on</div>
        <input class="input" name="reserved_on" type="date" value="{{ old('reserved_on', optional($reservation->reserved_on)->format('Y-m-d') ?? $reservation->reserved_on) }}" />
        @error('reserved_on') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <div class="label">Expires at</div>
        <input class="input" name="expires_at" type="date" value="{{ old('expires_at', optional($reservation->expires_at)->format('Y-m-d') ?? $reservation->expires_at) }}" />
        @error('expires_at') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field" style="grid-column: 1 / -1;">
        <div class="label">Notes</div>
        <textarea class="textarea" name="notes" rows="3">{{ old('notes', $reservation->notes) }}</textarea>
        @error('notes') <div class="error">{{ $message }}</div> @enderror
    </div>
</div>

