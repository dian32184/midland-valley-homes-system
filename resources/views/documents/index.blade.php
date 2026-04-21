@php
    $title = 'Documents';
    $headerTitle = 'Documents';
@endphp

<x-manager-shell :title="$title" :header-title="$headerTitle">
    @if (session('status'))
        <div class="flash flash-success">{{ session('status') }}</div>
    @endif

    <div class="panel">
        <div class="panel-head">
            <div>
                <div class="panel-title">Documents Dashboard</div>
                <div class="muted" style="font-size:12px; margin-top:2px;">Track document processing per customer</div>
            </div>
            <a href="{{ route('documents.create') }}" class="btn btn-primary">Add Document</a>
        </div>

        <div style="display:grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 10px;">
            <div class="panel" style="padding:10px;">
                <div class="label">Total</div>
                <div style="font-size:18px; font-weight:800; margin-top:4px;">{{ $stats['total'] }}</div>
            </div>
            <div class="panel" style="padding:10px;">
                <div class="label">Pending</div>
                <div style="font-size:18px; font-weight:800; margin-top:4px;">{{ $stats['pending'] }}</div>
            </div>
            <div class="panel" style="padding:10px;">
                <div class="label">Processing</div>
                <div style="font-size:18px; font-weight:800; margin-top:4px;">{{ $stats['processing'] }}</div>
            </div>
            <div class="panel" style="padding:10px;">
                <div class="label">Completed</div>
                <div style="font-size:18px; font-weight:800; margin-top:4px;">{{ $stats['completed'] }}</div>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">Documents List</div>
            <div class="chip">Search + filter</div>
        </div>

        <form method="GET" action="{{ route('documents.index') }}" style="display:grid; grid-template-columns: 1fr 200px 220px auto; gap: 10px; align-items:end; margin-bottom: 10px;">
            <div class="field">
                <div class="label">Search</div>
                <input class="input" name="q" value="{{ $search }}" placeholder="Customer or title" />
            </div>
            <div class="field">
                <div class="label">Type</div>
                <select class="select" name="type">
                    <option value="all" @selected(($type ?? 'all') === 'all')>All</option>
                    <option value="contract_to_sell" @selected($type === 'contract_to_sell')>Contract to sell</option>
                    <option value="deed_of_absolute_sale" @selected($type === 'deed_of_absolute_sale')>Deed of absolute sale</option>
                    <option value="bir_related" @selected($type === 'bir_related')>BIR related</option>
                    <option value="other" @selected($type === 'other')>Other</option>
                </select>
            </div>
            <div class="field">
                <div class="label">Status</div>
                <select class="select" name="status">
                    <option value="all" @selected(($status ?? 'all') === 'all')>All</option>
                    <option value="pending" @selected($status === 'pending')>Pending</option>
                    <option value="processing" @selected($status === 'processing')>Processing</option>
                    <option value="completed" @selected($status === 'completed')>Completed</option>
                </select>
            </div>
            <div style="display:flex; gap: 8px; justify-content:flex-end;">
                <button class="btn btn-primary" type="submit">Filter</button>
                <a class="btn" href="{{ route('documents.index') }}">Reset</a>
            </div>
        </form>

        <div style="overflow:auto; border-radius: 12px; border: 1px solid #e2e8f0; background: #fff;">
            <table>
                <thead>
                <tr>
                    <th style="min-width:240px;">Customer</th>
                    <th style="min-width:220px;">Property</th>
                    <th style="min-width:180px;">Type</th>
                    <th style="min-width:220px;">Title</th>
                    <th style="min-width:140px;">Status</th>
                    <th style="min-width:140px;">Due</th>
                    <th style="min-width:220px; text-align:right;">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($documents as $document)
                    @php
                        $customerName = $document->customer
                            ? "{$document->customer->last_name}, {$document->customer->first_name}"
                            : '—';
                        $propertyLabel = $document->property
                            ? "B{$document->property->block_number} / L{$document->property->lot_number}"
                            : '—';
                        $badgeClass = match($document->status) {
                            'pending' => 'badge badge-gray',
                            'processing' => 'badge badge-yellow',
                            'completed' => 'badge badge-green',
                            default => 'badge badge-gray',
                        };
                    @endphp
                    <tr>
                        <td>
                            <a href="{{ route('documents.show', $document) }}" style="font-weight:800; color:#0f172a; text-decoration:none;">
                                {{ $customerName }}
                            </a>
                        </td>
                        <td class="muted">{{ $propertyLabel }}</td>
                        <td><span class="badge badge-gray">{{ str_replace('_', ' ', $document->document_type) }}</span></td>
                        <td class="muted">{{ $document->title ?? '—' }}</td>
                        <td><span class="{{ $badgeClass }}">{{ $document->status }}</span></td>
                        <td class="muted">{{ $document->due_date ?? '—' }}</td>
                        <td style="text-align:right;">
                            <div style="display:inline-flex; gap: 8px; align-items:center;">
                                <a class="btn" href="{{ route('documents.edit', $document) }}">Edit</a>
                                <form method="POST" action="{{ route('documents.destroy', $document) }}" onsubmit="return confirm('Delete this document?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" type="submit">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="muted" style="padding: 18px; text-align:center;">No documents found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 10px;">
            {{ $documents->links() }}
        </div>
    </div>
</x-manager-shell>

