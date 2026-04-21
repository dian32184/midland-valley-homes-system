@php
    $title = 'Edit Title Transfer';
    $headerTitle = 'Title Transfers';
    $headerSubtitle = "Transfer #{$transfer->id}";
@endphp

<x-manager-shell :title="$title" :header-title="$headerTitle" :header-subtitle="$headerSubtitle">
    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">Edit Title Transfer</div>
            <div style="display:flex; gap: 8px;">
                <a class="btn" href="{{ route('title-transfers.show', $transfer) }}">View</a>
                <a class="btn" href="{{ route('title-transfers.index') }}">Back</a>
            </div>
        </div>

        <form method="POST" action="{{ route('title-transfers.update', $transfer) }}">
            @csrf
            @method('PUT')
            @include('title-transfers._form', ['transfer' => $transfer, 'customers' => $customers, 'properties' => $properties])

            <div style="display:flex; justify-content:flex-end; gap: 8px; margin-top: 12px;">
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </form>
    </div>
</x-manager-shell>

