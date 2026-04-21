@php
    $statusOptions = [
        'not_started' => 'Not started',
        'ongoing' => 'Ongoing',
        'completed' => 'Completed',
    ];
@endphp

<div style="display:grid; grid-template-columns: 1fr 1fr; gap: 10px;">
    <div class="field">
        <div class="label">Property *</div>
        <select class="select" name="property_id">
            <option value="">Select property</option>
            @foreach ($properties as $property)
                <option value="{{ $property->id }}" @selected((int) old('property_id', $project->property_id) === (int) $property->id)>
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
                <option value="{{ $value }}" @selected(old('status', $project->status ?? 'not_started') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('status') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <div class="label">Start date</div>
        <input class="input" name="start_date" type="date" value="{{ old('start_date', optional($project->start_date)->format('Y-m-d') ?? $project->start_date) }}" />
        @error('start_date') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field">
        <div class="label">Completion date</div>
        <input class="input" name="completion_date" type="date" value="{{ old('completion_date', optional($project->completion_date)->format('Y-m-d') ?? $project->completion_date) }}" />
        @error('completion_date') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field" style="grid-column: 1 / -1;">
        <div class="label">Progress percent (0-100) *</div>
        <input class="input" name="progress_percent" type="number" min="0" max="100" value="{{ old('progress_percent', $project->progress_percent ?? 0) }}" />
        @error('progress_percent') <div class="error">{{ $message }}</div> @enderror
    </div>

    <div class="field" style="grid-column: 1 / -1;">
        <div class="label">Notes</div>
        <textarea class="textarea" name="notes" rows="3">{{ old('notes', $project->notes) }}</textarea>
        @error('notes') <div class="error">{{ $message }}</div> @enderror
    </div>
</div>

