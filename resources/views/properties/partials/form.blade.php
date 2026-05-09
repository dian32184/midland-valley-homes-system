<input name="block_number" placeholder="Block Number" value="{{ old('block_number', $property?->block_number) }}" class="rounded border-gray-300">
<input name="lot_number" placeholder="Lot Number" value="{{ old('lot_number', $property?->lot_number) }}" class="rounded border-gray-300">
<select name="house_model" class="rounded border-gray-300 js-house-model">
    @foreach (['diamond' => 'Diamond', 'ruby' => 'Ruby', 'custom' => 'Custom'] as $value => $label)
        <option value="{{ $value }}" @selected(old('house_model', $property?->house_model ?? 'diamond') === $value)>{{ $label }}</option>
    @endforeach
</select>
<input name="house_type" placeholder="House Type" value="{{ old('house_type', $property?->house_type) }}" class="rounded border-gray-300 js-house-type">
<input name="price" type="number" step="0.01" placeholder="Price" value="{{ old('price', $property?->price) }}" class="rounded border-gray-300">
<input name="lot_size" placeholder="Lot Size" value="{{ old('lot_size', $property?->lot_size) }}" class="rounded border-gray-300">
<input name="floor_area" placeholder="Floor Area" value="{{ old('floor_area', $property?->floor_area) }}" class="rounded border-gray-300">
<select name="status" class="rounded border-gray-300">
    @foreach (['available', 'reserved', 'sold', 'under_construction', 'turned_over'] as $status)
        <option value="{{ $status }}" @selected(old('status', $property?->status ?? 'available') === $status)>{{ $status }}</option>
    @endforeach
</select>
<input name="available_at" type="date" value="{{ old('available_at', $property?->available_at) }}" class="rounded border-gray-300">
<select name="estimation_status" class="rounded border-gray-300">
    @foreach (['requested', 'for_estimation', 'estimated', 'approved', 'rejected'] as $status)
        <option value="{{ $status }}" @selected(old('estimation_status', $property?->estimation_status ?? 'approved') === $status)>{{ $status }}</option>
    @endforeach
</select>
<input name="estimated_budget" type="number" step="0.01" placeholder="Estimated Budget" value="{{ old('estimated_budget', $property?->estimated_budget) }}" class="rounded border-gray-300">
<input name="estimated_timeline_months" type="number" min="1" max="60" placeholder="Estimated Timeline (months)" value="{{ old('estimated_timeline_months', $property?->estimated_timeline_months) }}" class="rounded border-gray-300">
<input name="requested_bedrooms" type="number" min="1" max="20" placeholder="Requested Bedrooms" value="{{ old('requested_bedrooms', $property?->requested_bedrooms) }}" class="rounded border-gray-300 js-custom-field">
<input name="requested_bathrooms" type="number" min="1" max="20" placeholder="Requested Bathrooms" value="{{ old('requested_bathrooms', $property?->requested_bathrooms) }}" class="rounded border-gray-300 js-custom-field">
<input name="preferred_finish" placeholder="Preferred Finish" value="{{ old('preferred_finish', $property?->preferred_finish) }}" class="rounded border-gray-300 js-custom-field">
<textarea name="custom_requirements" placeholder="Customization Request" class="rounded border-gray-300 md:col-span-2 js-custom-field">{{ old('custom_requirements', $property?->custom_requirements) }}</textarea>
<textarea name="engineer_notes" placeholder="Engineer Notes / Budget Basis" class="rounded border-gray-300 md:col-span-2">{{ old('engineer_notes', $property?->engineer_notes) }}</textarea>
<textarea name="description" placeholder="Description" class="rounded border-gray-300 md:col-span-2">{{ old('description', $property?->description) }}</textarea>
<div class="md:col-span-2 flex items-center gap-2">
    <button class="px-4 py-2 bg-indigo-600 text-white rounded">Save</button>
    <a href="{{ route('properties.index') }}" class="px-4 py-2 bg-gray-200 rounded">Cancel</a>
</div>
<script>
(() => {
    const form = document.currentScript.closest('form');
    if (!form) return;

    const houseModel = form.querySelector('.js-house-model');
    const houseType = form.querySelector('.js-house-type');
    const customFields = form.querySelectorAll('.js-custom-field');

    const syncFields = () => {
        const model = houseModel.value;
        const isCustom = model === 'custom';

        houseType.readOnly = !isCustom;
        if (!isCustom) {
            houseType.value = model.charAt(0).toUpperCase() + model.slice(1);
        } else if (!houseType.value || ['Diamond', 'Ruby'].includes(houseType.value)) {
            houseType.value = 'Custom';
        }

        customFields.forEach((field) => {
            field.style.display = isCustom ? '' : 'none';
            if (!isCustom) {
                field.value = '';
            }
        });
    };

    houseModel.addEventListener('change', syncFields);
    syncFields();
})();
</script>
