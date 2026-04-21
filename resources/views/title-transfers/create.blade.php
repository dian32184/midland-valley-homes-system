@php
    $title = 'Add Title Transfer';
    $headerTitle = 'Title Transfers';
    $headerSubtitle = 'Create a transfer tracking record';
@endphp

<x-manager-shell :title="$title" :header-title="$headerTitle" :header-subtitle="$headerSubtitle">
    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">New Title Transfer</div>
            <a class="btn" href="{{ route('title-transfers.index') }}">Back</a>
        </div>

        <form method="POST" action="{{ route('title-transfers.store') }}">
            @csrf
            @include('title-transfers._form', ['transfer' => $transfer, 'customers' => $customers, 'properties' => $properties])

            <div style="display:flex; justify-content:flex-end; gap: 8px; margin-top: 12px;">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</x-manager-shell>

