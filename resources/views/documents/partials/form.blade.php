<select name="customer_id" class="rounded border-gray-300">
    @foreach ($customers as $customer)
        <option value="{{ $customer->id }}" @selected(old('customer_id', $document?->customer_id) == $customer->id)>{{ $customer->first_name }} {{ $customer->last_name }}</option>
    @endforeach
</select>
<select name="property_id" class="rounded border-gray-300">
    <option value="">No property linked</option>
    @foreach ($properties as $propertyOption)
        <option value="{{ $propertyOption->id }}" @selected(old('property_id', $document?->property_id) == $propertyOption->id)>{{ $propertyOption->block_number }}/{{ $propertyOption->lot_number }}</option>
    @endforeach
</select>
<select name="document_type" class="rounded border-gray-300">
    @foreach (['contract_to_sell', 'deed_of_absolute_sale', 'bir_related', 'other'] as $type)
        <option value="{{ $type }}" @selected(old('document_type', $document?->document_type ?? 'contract_to_sell') === $type)>{{ $type }}</option>
    @endforeach
</select>
<select name="status" class="rounded border-gray-300">
    @foreach (['pending', 'processing', 'completed'] as $status)
        <option value="{{ $status }}" @selected(old('status', $document?->status ?? 'pending') === $status)>{{ $status }}</option>
    @endforeach
</select>
<input name="title" value="{{ old('title', $document?->title) }}" placeholder="Title" class="rounded border-gray-300 md:col-span-2">
<input name="due_date" type="date" value="{{ old('due_date', $document?->due_date) }}" class="rounded border-gray-300">
<input name="completed_at" type="date" value="{{ old('completed_at', $document?->completed_at) }}" class="rounded border-gray-300">
<textarea name="notes" placeholder="Notes" class="rounded border-gray-300 md:col-span-2">{{ old('notes', $document?->notes) }}</textarea>
<div class="md:col-span-2 flex gap-2">
    <button class="px-4 py-2 bg-indigo-600 text-white rounded">Save</button>
    <a href="{{ route('documents.index') }}" class="px-4 py-2 bg-gray-200 rounded">Cancel</a>
</div>
