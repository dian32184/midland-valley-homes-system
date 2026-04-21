@php
    $title = 'Edit Property';
    $headerTitle = 'Properties';
    $headerSubtitle = "B{$property->block_number} / L{$property->lot_number}";
@endphp

<x-manager-shell :title="$title" :header-title="$headerTitle" :header-subtitle="$headerSubtitle">
    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">Edit Property</div>
            <div style="display:flex; gap: 8px;">
                <a class="btn" href="{{ route('properties.show', $property) }}">View</a>
                <a class="btn" href="{{ route('properties.index') }}">Back</a>
            </div>
        </div>

        <form method="POST" action="{{ route('properties.update', $property) }}">
            @csrf
            @method('PUT')
            @include('properties._form', ['property' => $property])

            <div style="display:flex; justify-content:flex-end; gap: 8px; margin-top: 12px;">
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </form>
    </div>
</x-manager-shell>

