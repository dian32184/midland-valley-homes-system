@php
    $title = 'Properties';
    $headerTitle = 'Properties';
@endphp

<x-manager-shell :title="$title" :header-title="$headerTitle">
    @if (session('status'))
        <div class="flash flash-success">{{ session('status') }}</div>
    @endif

    <div class="panel">
        <div class="panel-head">
            <div>
                <div class="panel-title">Property Dashboard</div>
                <div class="muted" style="font-size:12px; margin-top:2px;">Inventory overview and list</div>
            </div>
            <a href="{{ route('properties.create') }}" class="btn btn-primary">Add Property</a>
        </div>

        <div style="display:grid; grid-template-columns: repeat(6, minmax(0, 1fr)); gap: 10px;">
            <div class="panel" style="padding:10px;">
                <div class="label">Total</div>
                <div style="font-size:18px; font-weight:800; margin-top:4px;">{{ $stats['total'] }}</div>
            </div>
            <div class="panel" style="padding:10px;">
                <div class="label">Available</div>
                <div style="font-size:18px; font-weight:800; margin-top:4px;">{{ $stats['available'] }}</div>
            </div>
            <div class="panel" style="padding:10px;">
                <div class="label">Reserved</div>
                <div style="font-size:18px; font-weight:800; margin-top:4px;">{{ $stats['reserved'] }}</div>
            </div>
            <div class="panel" style="padding:10px;">
                <div class="label">Sold</div>
                <div style="font-size:18px; font-weight:800; margin-top:4px;">{{ $stats['sold'] }}</div>
            </div>
            <div class="panel" style="padding:10px;">
                <div class="label">Construction</div>
                <div style="font-size:18px; font-weight:800; margin-top:4px;">{{ $stats['under_construction'] }}</div>
            </div>
            <div class="panel" style="padding:10px;">
                <div class="label">Turned over</div>
                <div style="font-size:18px; font-weight:800; margin-top:4px;">{{ $stats['turned_over'] }}</div>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">Properties List</div>
            <div class="chip">Search + filter</div>
        </div>

        <form method="GET" action="{{ route('properties.index') }}" style="display:grid; grid-template-columns: 1fr 220px auto; gap: 10px; align-items:end; margin-bottom: 10px;">
            <div class="field">
                <div class="label">Search</div>
                <input class="input" name="q" value="{{ $search }}" placeholder="Block, lot, house type" />
            </div>
            <div class="field">
                <div class="label">Status</div>
                <select class="select" name="status">
                    <option value="all" @selected(($status ?? 'all') === 'all')>All</option>
                    <option value="available" @selected($status === 'available')>Available</option>
                    <option value="reserved" @selected($status === 'reserved')>Reserved</option>
                    <option value="sold" @selected($status === 'sold')>Sold</option>
                    <option value="under_construction" @selected($status === 'under_construction')>Under construction</option>
                    <option value="turned_over" @selected($status === 'turned_over')>Turned over</option>
                </select>
            </div>
            <div style="display:flex; gap: 8px; justify-content:flex-end;">
                <button class="btn btn-primary" type="submit">Filter</button>
                <a class="btn" href="{{ route('properties.index') }}">Reset</a>
            </div>
        </form>

        <div style="overflow:auto; border-radius: 12px; border: 1px solid #e2e8f0; background: #fff;">
            <table>
                <thead>
                <tr>
                    <th style="min-width:160px;">Block / Lot</th>
                    <th style="min-width:180px;">House type</th>
                    <th style="min-width:140px;">Price</th>
                    <th style="min-width:140px;">Status</th>
                    <th style="min-width:160px;">Available at</th>
                    <th style="min-width:220px; text-align:right;">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($properties as $property)
                    @php
                        $badgeClass = match($property->status) {
                            'available' => 'badge badge-green',
                            'reserved' => 'badge badge-yellow',
                            'sold' => 'badge badge-red',
                            'under_construction' => 'badge badge-gray',
                            'turned_over' => 'badge badge-gray',
                            default => 'badge badge-gray',
                        };
                    @endphp
                    <tr>
                        <td>
                            <a href="{{ route('properties.show', $property) }}" style="font-weight:800; color:#0f172a; text-decoration:none;">
                                B{{ $property->block_number }} / L{{ $property->lot_number }}
                            </a>
                        </td>
                        <td class="muted">{{ $property->house_type }}</td>
                        <td><strong>PHP {{ number_format((float) $property->price, 2) }}</strong></td>
                        <td><span class="{{ $badgeClass }}">{{ str_replace('_', ' ', $property->status) }}</span></td>
                        <td class="muted">{{ $property->available_at ?? '—' }}</td>
                        <td style="text-align:right;">
                            <div style="display:inline-flex; gap: 8px; align-items:center;">
                                <a class="btn" href="{{ route('properties.edit', $property) }}">Edit</a>
                                <form method="POST" action="{{ route('properties.destroy', $property) }}" onsubmit="return confirm('Delete this property?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" type="submit">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="muted" style="padding: 18px; text-align:center;">No properties found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 10px;">
            {{ $properties->links() }}
        </div>
    </div>
</x-manager-shell>

