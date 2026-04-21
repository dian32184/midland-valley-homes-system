@php
    $title = 'Add Property';
    $headerTitle = 'Properties';
    $headerSubtitle = 'Add new property unit';
@endphp

<x-manager-shell :title="$title" :header-title="$headerTitle" :header-subtitle="$headerSubtitle">
    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">New Property</div>
            <a class="btn" href="{{ route('properties.index') }}">Back</a>
        </div>

        <form method="POST" action="{{ route('properties.store') }}">
            @csrf
            @include('properties._form', ['property' => $property])

            <div style="display:flex; justify-content:flex-end; gap: 8px; margin-top: 12px;">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</x-manager-shell>

