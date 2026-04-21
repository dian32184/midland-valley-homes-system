@php
    $title = 'Reservations';
    $headerTitle = 'Reservations';
@endphp

<x-manager-shell :title="$title" :header-title="$headerTitle">
    @if (session('status'))
        <div class="flash flash-success">{{ session('status') }}</div>
    @endif

    <div class="panel">
        <div class="panel-head">
            <div>
                <div class="panel-title">Reservations Dashboard</div>
                <div class="muted" style="font-size:12px; margin-top:2px;">Active reservations and history</div>
            </div>
            <a href="{{ route('reservations.create') }}" class="btn btn-primary">Add Reservation</a>
        </div>

        <div style="display:grid; grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 10px;">
            <div class="panel" style="padding:10px;">
                <div class="label">Total</div>
                <div style="font-size:18px; font-weight:800; margin-top:4px;">{{ $stats['total'] }}</div>
            </div>
            <div class="panel" style="padding:10px;">
                <div class="label">Active</div>
                <div style="font-size:18px; font-weight:800; margin-top:4px;">{{ $stats['active'] }}</div>
            </div>
            <div class="panel" style="padding:10px;">
                <div class="label">Completed</div>
                <div style="font-size:18px; font-weight:800; margin-top:4px;">{{ $stats['completed'] }}</div>
            </div>
            <div class="panel" style="padding:10px;">
                <div class="label">Cancelled</div>
                <div style="font-size:18px; font-weight:800; margin-top:4px;">{{ $stats['cancelled'] }}</div>
            </div>
            <div class="panel" style="padding:10px;">
                <div class="label">Skipped</div>
                <div style="font-size:18px; font-weight:800; margin-top:4px;">{{ $stats['skipped'] }}</div>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">Reservations List</div>
            <div class="chip">Search + filter</div>
        </div>

        <form method="GET" action="{{ route('reservations.index') }}" style="display:grid; grid-template-columns: 1fr 220px auto; gap: 10px; align-items:end; margin-bottom: 10px;">
            <div class="field">
                <div class="label">Search</div>
                <input class="input" name="q" value="{{ $search }}" placeholder="Customer or property info" />
            </div>
            <div class="field">
                <div class="label">Status</div>
                <select class="select" name="status">
                    <option value="all" @selected(($status ?? 'all') === 'all')>All</option>
                    <option value="active" @selected($status === 'active')>Active</option>
                    <option value="completed" @selected($status === 'completed')>Completed</option>
                    <option value="cancelled" @selected($status === 'cancelled')>Cancelled</option>
                    <option value="skipped" @selected($status === 'skipped')>Skipped</option>
                </select>
            </div>
            <div style="display:flex; gap: 8px; justify-content:flex-end;">
                <button class="btn btn-primary" type="submit">Filter</button>
                <a class="btn" href="{{ route('reservations.index') }}">Reset</a>
            </div>
        </form>

        <div style="overflow:auto; border-radius: 12px; border: 1px solid #e2e8f0; background: #fff;">
            <table>
                <thead>
                <tr>
                    <th style="min-width:240px;">Customer</th>
                    <th style="min-width:220px;">Property</th>
                    <th style="min-width:140px;">Fee</th>
                    <th style="min-width:160px;">Reserved on</th>
                    <th style="min-width:160px;">Expires at</th>
                    <th style="min-width:140px;">Status</th>
                    <th style="min-width:220px; text-align:right;">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($reservations as $reservation)
                    @php
                        $badgeClass = match($reservation->status) {
                            'active' => 'badge badge-green',
                            'completed' => 'badge badge-gray',
                            'cancelled' => 'badge badge-red',
                            'skipped' => 'badge badge-yellow',
                            default => 'badge badge-gray',
                        };
                        $customerName = $reservation->customer
                            ? "{$reservation->customer->last_name}, {$reservation->customer->first_name}"
                            : '—';
                        $propertyLabel = $reservation->property
                            ? "B{$reservation->property->block_number} / L{$reservation->property->lot_number} • {$reservation->property->house_type}"
                            : '—';
                    @endphp
                    <tr>
                        <td>
                            <a href="{{ route('reservations.show', $reservation) }}" style="font-weight:800; color:#0f172a; text-decoration:none;">
                                {{ $customerName }}
                            </a>
                            @if ($reservation->customer?->email)
                                <div class="muted" style="margin-top:2px;">{{ $reservation->customer->email }}</div>
                            @endif
                        </td>
                        <td class="muted">{{ $propertyLabel }}</td>
                        <td><strong>PHP {{ number_format((float) $reservation->reservation_fee, 2) }}</strong></td>
                        <td class="muted">{{ $reservation->reserved_on ?? '—' }}</td>
                        <td class="muted">{{ $reservation->expires_at ?? '—' }}</td>
                        <td><span class="{{ $badgeClass }}">{{ $reservation->status }}</span></td>
                        <td style="text-align:right;">
                            <div style="display:inline-flex; gap: 8px; align-items:center;">
                                <a class="btn" href="{{ route('reservations.edit', $reservation) }}">Edit</a>
                                <form method="POST" action="{{ route('reservations.destroy', $reservation) }}" onsubmit="return confirm('Delete this reservation?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" type="submit">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="muted" style="padding: 18px; text-align:center;">No reservations found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 10px;">
            {{ $reservations->links() }}
        </div>
    </div>
</x-manager-shell>

