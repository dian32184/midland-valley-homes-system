@php
    $title = 'Property';
    $headerTitle = 'Properties';
    $headerSubtitle = "B{$property->block_number} / L{$property->lot_number}";

    $badgeClass = match($property->status) {
        'available' => 'badge badge-green',
        'reserved' => 'badge badge-yellow',
        'sold' => 'badge badge-red',
        'under_construction' => 'badge badge-gray',
        'turned_over' => 'badge badge-gray',
        default => 'badge badge-gray',
    };
@endphp

<x-manager-shell :title="$title" :header-title="$headerTitle" :header-subtitle="$headerSubtitle">
    @if (session('status'))
        <div class="flash flash-success">{{ session('status') }}</div>
    @endif

    <div class="panel">
        <div class="panel-head">
            <div style="display:flex; gap: 10px; align-items:center;">
                <div class="panel-title">Property Profile</div>
                <span class="{{ $badgeClass }}">{{ str_replace('_', ' ', $property->status) }}</span>
            </div>
            <div style="display:flex; gap: 8px;">
                <a class="btn btn-primary" href="{{ route('properties.edit', $property) }}">Edit</a>
                <a class="btn" href="{{ route('properties.index') }}">Back</a>
            </div>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 10px;">
            <div class="panel" style="padding:10px;">
                <div class="label">Details</div>
                <div style="display:grid; gap: 6px; margin-top: 8px;">
                    <div><span class="muted">Block:</span> <strong>{{ $property->block_number }}</strong></div>
                    <div><span class="muted">Lot:</span> <strong>{{ $property->lot_number }}</strong></div>
                    <div><span class="muted">House type:</span> <strong>{{ $property->house_type }}</strong></div>
                    <div><span class="muted">Price:</span> <strong>PHP {{ number_format((float) $property->price, 2) }}</strong></div>
                </div>
            </div>
            <div class="panel" style="padding:10px;">
                <div class="label">Sizing</div>
                <div style="display:grid; gap: 6px; margin-top: 8px;">
                    <div><span class="muted">Lot size:</span> <strong>{{ $property->lot_size ?? '—' }}</strong></div>
                    <div><span class="muted">Floor area:</span> <strong>{{ $property->floor_area ?? '—' }}</strong></div>
                    <div><span class="muted">Available at:</span> <strong>{{ $property->available_at ?? '—' }}</strong></div>
                </div>
            </div>
        </div>

        <div class="panel" style="margin-top: 10px; padding:10px;">
            <div class="label">Description</div>
            <div style="margin-top: 8px; white-space: pre-wrap;">{{ $property->description ?? '—' }}</div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">Danger zone</div>
            <div class="chip">Delete property</div>
        </div>
        <form method="POST" action="{{ route('properties.destroy', $property) }}" onsubmit="return confirm('Delete this property?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete property</button>
        </form>
    </div>
</x-manager-shell>

