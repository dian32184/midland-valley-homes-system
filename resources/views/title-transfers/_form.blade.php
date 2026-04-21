@php
    $statusOptions = [
        'pending' => 'Pending',
        'submitted_to_bir' => 'Submitted to BIR',
        'car_issued' => 'CAR issued',
        'registered' => 'Registered',
        'tct_issued' => 'TCT issued',
        'rejected' => 'Rejected',
    ];
@endphp

<div style="display:grid; grid-template-columns: 1fr 1fr; gap: 10px;">
    <div class="field">
        <div class="label">Customer *</div>
        <select class="select" name="customer_id">
            <option value="">Select customer</option>
            @foreach ($customers as $customer)
                <option value="{{ $customer->id }}" @selected((int) old('customer_id', $transfer->customer_id) === (int) $customer->id)>
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
                <option value="{{ $property->id }}" @selected((int) old('property_id', $transfer->property_id) === (int) $property->id)>
                    B{{ $property->block_number }} / L{{ $property->lot_number }} • {{ $property->house_type }}
                </option>
            @endforeach
        </select>
        @error('property_id') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <div class="label">Status *</div>
        <select class="select" name="status">
            @foreach ($statusOptions as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $transfer->status ?? 'pending') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('status') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <div class="label">Submitted to BIR</div>
        <input class="input" name="submitted_to_bir_at" type="date" value="{{ old('submitted_to_bir_at', optional($transfer->submitted_to_bir_at)->format('Y-m-d') ?? $transfer->submitted_to_bir_at) }}" />
        @error('submitted_to_bir_at') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <div class="label">CAR issued</div>
        <input class="input" name="car_issued_at" type="date" value="{{ old('car_issued_at', optional($transfer->car_issued_at)->format('Y-m-d') ?? $transfer->car_issued_at) }}" />
        @error('car_issued_at') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <div class="label">Registered</div>
        <input class="input" name="registered_at" type="date" value="{{ old('registered_at', optional($transfer->registered_at)->format('Y-m-d') ?? $transfer->registered_at) }}" />
        @error('registered_at') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <div class="label">TCT issued</div>
        <input class="input" name="tct_issued_at" type="date" value="{{ old('tct_issued_at', optional($transfer->tct_issued_at)->format('Y-m-d') ?? $transfer->tct_issued_at) }}" />
        @error('tct_issued_at') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field" style="grid-column: 1 / -1;">
        <div class="label">Remarks</div>
        <textarea class="textarea" name="remarks" rows="3">{{ old('remarks', $transfer->remarks) }}</textarea>
        @error('remarks') <div class="error">{{ $message }}</div> @enderror
    </div>
</div>

