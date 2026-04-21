@php
    $title = 'Add Document';
    $headerTitle = 'Documents';
    $headerSubtitle = 'Create a document tracker record';
@endphp

<x-manager-shell :title="$title" :header-title="$headerTitle" :header-subtitle="$headerSubtitle">
    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">New Document</div>
            <a class="btn" href="{{ route('documents.index') }}">Back</a>
        </div>

        <form method="POST" action="{{ route('documents.store') }}">
            @csrf
            @include('documents._form', ['document' => $document, 'customers' => $customers, 'properties' => $properties])

            <div style="display:flex; justify-content:flex-end; gap: 8px; margin-top: 12px;">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</x-manager-shell>

