@php
    $title = 'Document';
    $headerTitle = 'Documents';
    $headerSubtitle = "Document #{$document->id}";

    $customerName = $document->customer
        ? "{$document->customer->last_name}, {$document->customer->first_name}"
        : '—';

    $propertyLabel = $document->property
        ? "B{$document->property->block_number} / L{$document->property->lot_number} • {$document->property->house_type}"
        : '—';

    $badgeClass = match($document->status) {
        'pending' => 'badge badge-gray',
        'processing' => 'badge badge-yellow',
        'completed' => 'badge badge-green',
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
                <div class="panel-title">Document Details</div>
                <span class="{{ $badgeClass }}">{{ $document->status }}</span>
            </div>
            <div style="display:flex; gap: 8px;">
                <a class="btn btn-primary" href="{{ route('documents.edit', $document) }}">Edit</a>
                <a class="btn" href="{{ route('documents.index') }}">Back</a>
            </div>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 10px;">
            <div class="panel" style="padding:10px;">
                <div class="label">Customer</div>
                <div style="display:grid; gap: 6px; margin-top: 8px;">
                    <div><strong>{{ $customerName }}</strong></div>
                    <div class="muted">{{ $document->customer?->email ?? '—' }}</div>
                </div>
            </div>
            <div class="panel" style="padding:10px;">
                <div class="label">Property</div>
                <div style="display:grid; gap: 6px; margin-top: 8px;">
                    <div><strong>{{ $propertyLabel }}</strong></div>
                </div>
            </div>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; margin-top: 10px;">
            <div class="panel" style="padding:10px;">
                <div class="label">Type</div>
                <div style="margin-top: 8px;"><span class="badge badge-gray">{{ str_replace('_', ' ', $document->document_type) }}</span></div>
            </div>
            <div class="panel" style="padding:10px;">
                <div class="label">Due date</div>
                <div style="margin-top: 8px;"><strong>{{ $document->due_date ?? '—' }}</strong></div>
            </div>
            <div class="panel" style="padding:10px;">
                <div class="label">Completed at</div>
                <div style="margin-top: 8px;"><strong>{{ $document->completed_at ?? '—' }}</strong></div>
            </div>
        </div>

        <div class="panel" style="margin-top: 10px; padding:10px;">
            <div class="label">Title</div>
            <div style="margin-top: 8px;"><strong>{{ $document->title ?? '—' }}</strong></div>
        </div>

        <div class="panel" style="margin-top: 10px; padding:10px;">
            <div class="label">Notes</div>
            <div style="margin-top: 8px; white-space: pre-wrap;">{{ $document->notes ?? '—' }}</div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">Danger zone</div>
            <div class="chip">Delete document</div>
        </div>
        <form method="POST" action="{{ route('documents.destroy', $document) }}" onsubmit="return confirm('Delete this document?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete document</button>
        </form>
    </div>
</x-manager-shell>

