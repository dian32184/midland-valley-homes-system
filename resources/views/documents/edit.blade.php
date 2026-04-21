@php
    $title = 'Edit Document';
    $headerTitle = 'Documents';
    $headerSubtitle = "Document #{$document->id}";
@endphp

<x-manager-shell :title="$title" :header-title="$headerTitle" :header-subtitle="$headerSubtitle">
    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">Edit Document</div>
            <div style="display:flex; gap: 8px;">
                <a class="btn" href="{{ route('documents.show', $document) }}">View</a>
                <a class="btn" href="{{ route('documents.index') }}">Back</a>
            </div>
        </div>

        <form method="POST" action="{{ route('documents.update', $document) }}">
            @csrf
            @method('PUT')
            @include('documents._form', ['document' => $document, 'customers' => $customers, 'properties' => $properties])

            <div style="display:flex; justify-content:flex-end; gap: 8px; margin-top: 12px;">
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </form>
    </div>
</x-manager-shell>

