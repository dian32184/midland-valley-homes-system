@php
    $typeOptions = [
        'contract_to_sell' => 'Contract to sell',
        'deed_of_absolute_sale' => 'Deed of absolute sale',
        'bir_related' => 'BIR related',
        'other' => 'Other',
    ];
    $statusOptions = [
        'pending' => 'Pending',
        'processing' => 'Processing',
        'completed' => 'Completed',
    ];
@endphp

<div style="display:grid; grid-template-columns: 1fr 1fr; gap: 10px;">
    <div class="field">
        <div class="label">Customer *</div>
        <select class="select" name="customer_id">
            <option value="">Select customer</option>
            @foreach ($customers as $customer)
                <option value="{{ $customer->id }}" @selected((int) old('customer_id', $document->customer_id) === (int) $customer->id)>
                    {{ $customer->last_name }}, {{ $customer->first_name }}{{ $customer->email ? " • {$customer->email}" : '' }}
                </option>
            @endforeach
        </select>
        @error('customer_id') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <div class="label">Property (optional)</div>
        <select class="select" name="property_id">
            <option value="">None</option>
            @foreach ($properties as $property)
                <option value="{{ $property->id }}" @selected((int) old('property_id', $document->property_id) === (int) $property->id)>
                    B{{ $property->block_number }} / L{{ $property->lot_number }} • {{ $property->house_type }}
                </option>
            @endforeach
        </select>
        @error('property_id') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <div class="label">Document type *</div>
        <select class="select" name="document_type">
            @foreach ($typeOptions as $value => $label)
                <option value="{{ $value }}" @selected(old('document_type', $document->document_type ?? 'contract_to_sell') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('document_type') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <div class="label">Status *</div>
        <select class="select" name="status">
            @foreach ($statusOptions as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $document->status ?? 'pending') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('status') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field" style="grid-column: 1 / -1;">
        <div class="label">Title</div>
        <input class="input" name="title" value="{{ old('title', $document->title) }}" placeholder="Optional title/label" />
        @error('title') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <div class="label">Due date</div>
        <input class="input" name="due_date" type="date" value="{{ old('due_date', optional($document->due_date)->format('Y-m-d') ?? $document->due_date) }}" />
        @error('due_date') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <div class="label">Completed at</div>
        <input class="input" name="completed_at" type="date" value="{{ old('completed_at', optional($document->completed_at)->format('Y-m-d') ?? $document->completed_at) }}" />
        @error('completed_at') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field" style="grid-column: 1 / -1;">
        <div class="label">Notes</div>
        <textarea class="textarea" name="notes" rows="3">{{ old('notes', $document->notes) }}</textarea>
        @error('notes') <div class="error">{{ $message }}</div> @enderror
    </div>
</div>

