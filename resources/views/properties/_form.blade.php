@php
    $statusOptions = [
        'available' => 'Available',
        'reserved' => 'Reserved',
        'sold' => 'Sold',
        'under_construction' => 'Under construction',
        'turned_over' => 'Turned over',
    ];
@endphp

<div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px;">
    <div class="field">
        <div class="label">Block number *</div>
        <input class="input" name="block_number" value="{{ old('block_number', $property->block_number) }}" />
        @error('block_number') <div class="error">{{ $message }}</div> @enderror
    </div>
    <div class="field">
        <div class="label">Lot number *</div>
        <input class="input" name="lot_number" value="{{ old('lot_number', $property->lot_number) }}" />
        @error('lot_number') <div class="error">{{ $message }}</div> @enderror
    </div>
    <div class="field">
        <div class="label">House type *</div>
        <input class="input" name="house_type" value="{{ old('house_type', $property->house_type) }}" />
        @error('house_type') <div class="error">{{ $message }}</div> @enderror
    </div>
    <div class="field">
        <div class="label">Price (PHP) *</div>
        <input class="input" name="price" type="number" step="0.01" min="0" value="{{ old('price', $property->price ?? 0) }}" />
        @error('price') <div class="error">{{ $message }}</div> @enderror
    </div>
    <div class="field">
        <div class="label">Lot size</div>
        <input class="input" name="lot_size" value="{{ old('lot_size', $property->lot_size) }}" />
        @error('lot_size') <div class="error">{{ $message }}</div> @enderror
    </div>
    <div class="field">
        <div class="label">Floor area</div>
        <input class="input" name="floor_area" value="{{ old('floor_area', $property->floor_area) }}" />
        @error('floor_area') <div class="error">{{ $message }}</div> @enderror
    </div>
    <div class="field">
        <div class="label">Status *</div>
        <select class="select" name="status">
            @foreach ($statusOptions as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $property->status ?? 'available') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('status') <div class="error">{{ $message }}</div> @enderror
    </div>
    <div class="field">
        <div class="label">Available at</div>
        <input class="input" name="available_at" type="date" value="{{ old('available_at', optional($property->available_at)->format('Y-m-d') ?? $property->available_at) }}" />
        @error('available_at') <div class="error">{{ $message }}</div> @enderror
    </div>
    <div class="field" style="grid-column: 1 / -1;">
        <div class="label">Description</div>
        <textarea class="textarea" name="description" rows="3">{{ old('description', $property->description) }}</textarea>
        @error('description') <div class="error">{{ $message }}</div> @enderror
    </div>
</div>

