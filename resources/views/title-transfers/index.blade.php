@php
    $title = 'Title Transfers';
    $headerTitle = 'Title Transfers';
@endphp

<x-manager-shell :title="$title" :header-title="$headerTitle">
    @if (session('status'))
        <div class="flash flash-success">{{ session('status') }}</div>
    @endif

    <div class="panel">
        <div class="panel-head">
            <div>
                <div class="panel-title">Title Transfers Dashboard</div>
                <div class="muted" style="font-size:12px; margin-top:2px;">Track title processing stages</div>
            </div>
            <a href="{{ route('title-transfers.create') }}" class="btn btn-primary">Add Transfer</a>
        </div>

        <div style="display:grid; grid-template-columns: repeat(6, minmax(0, 1fr)); gap: 10px;">
            <div class="panel" style="padding:10px;"><div class="label">Total</div><div style="font-size:18px; font-weight:800; margin-top:4px;">{{ $stats['total'] }}</div></div>
            <div class="panel" style="padding:10px;"><div class="label">Pending</div><div style="font-size:18px; font-weight:800; margin-top:4px;">{{ $stats['pending'] }}</div></div>
            <div class="panel" style="padding:10px;"><div class="label">Submitted</div><div style="font-size:18px; font-weight:800; margin-top:4px;">{{ $stats['submitted_to_bir'] }}</div></div>
            <div class="panel" style="padding:10px;"><div class="label">CAR issued</div><div style="font-size:18px; font-weight:800; margin-top:4px;">{{ $stats['car_issued'] }}</div></div>
            <div class="panel" style="padding:10px;"><div class="label">Registered</div><div style="font-size:18px; font-weight:800; margin-top:4px;">{{ $stats['registered'] }}</div></div>
            <div class="panel" style="padding:10px;"><div class="label">TCT issued</div><div style="font-size:18px; font-weight:800; margin-top:4px;">{{ $stats['tct_issued'] }}</div></div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">Transfers List</div>
            <div class="chip">Search + filter</div>
        </div>

        <form method="GET" action="{{ route('title-transfers.index') }}" style="display:grid; grid-template-columns: 1fr 240px auto; gap: 10px; align-items:end; margin-bottom: 10px;">
            <div class="field">
                <div class="label">Search</div>
                <input class="input" name="q" value="{{ $search }}" placeholder="Customer name/email" />
            </div>
            <div class="field">
                <div class="label">Status</div>
                <select class="select" name="status">
                    <option value="all" @selected(($status ?? 'all') === 'all')>All</option>
                    <option value="pending" @selected($status === 'pending')>Pending</option>
                    <option value="submitted_to_bir" @selected($status === 'submitted_to_bir')>Submitted to BIR</option>
                    <option value="car_issued" @selected($status === 'car_issued')>CAR issued</option>
                    <option value="registered" @selected($status === 'registered')>Registered</option>
                    <option value="tct_issued" @selected($status === 'tct_issued')>TCT issued</option>
                    <option value="rejected" @selected($status === 'rejected')>Rejected</option>
                </select>
            </div>
            <div style="display:flex; gap: 8px; justify-content:flex-end;">
                <button class="btn btn-primary" type="submit">Filter</button>
                <a class="btn" href="{{ route('title-transfers.index') }}">Reset</a>
            </div>
        </form>

        <div style="overflow:auto; border-radius: 12px; border: 1px solid #e2e8f0; background: #fff;">
            <table>
                <thead>
                <tr>
                    <th style="min-width:80px;">#</th>
                    <th style="min-width:240px;">Customer</th>
                    <th style="min-width:180px;">Property</th>
                    <th style="min-width:160px;">Status</th>
                    <th style="min-width:140px;">Submitted</th>
                    <th style="min-width:140px;">CAR</th>
                    <th style="min-width:140px;">Registered</th>
                    <th style="min-width:140px;">TCT</th>
                    <th style="min-width:220px; text-align:right;">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($transfers as $transfer)
                    @php
                        $customerName = $transfer->customer
                            ? "{$transfer->customer->last_name}, {$transfer->customer->first_name}"
                            : '—';
                        $propertyLabel = $transfer->property
                            ? "B{$transfer->property->block_number}/L{$transfer->property->lot_number}"
                            : '—';
                        $badgeClass = match($transfer->status) {
                            'pending' => 'badge badge-gray',
                            'submitted_to_bir' => 'badge badge-yellow',
                            'car_issued' => 'badge badge-yellow',
                            'registered' => 'badge badge-green',
                            'tct_issued' => 'badge badge-green',
                            'rejected' => 'badge badge-red',
                            default => 'badge badge-gray',
                        };
                    @endphp
                    <tr>
                        <td class="muted">{{ $transfer->id }}</td>
                        <td>
                            <a href="{{ route('title-transfers.show', $transfer) }}" style="font-weight:800; color:#0f172a; text-decoration:none;">
                                {{ $customerName }}
                            </a>
                        </td>
                        <td class="muted">{{ $propertyLabel }}</td>
                        <td><span class="{{ $badgeClass }}">{{ str_replace('_', ' ', $transfer->status) }}</span></td>
                        <td class="muted">{{ $transfer->submitted_to_bir_at ?? '—' }}</td>
                        <td class="muted">{{ $transfer->car_issued_at ?? '—' }}</td>
                        <td class="muted">{{ $transfer->registered_at ?? '—' }}</td>
                        <td class="muted">{{ $transfer->tct_issued_at ?? '—' }}</td>
                        <td style="text-align:right;">
                            <div style="display:inline-flex; gap: 8px; align-items:center;">
                                <a class="btn" href="{{ route('title-transfers.edit', $transfer) }}">Edit</a>
                                <form method="POST" action="{{ route('title-transfers.destroy', $transfer) }}" onsubmit="return confirm('Delete this title transfer?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" type="submit">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="muted" style="padding: 18px; text-align:center;">No title transfers found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 10px;">
            {{ $transfers->links() }}
        </div>
    </div>
</x-manager-shell>

