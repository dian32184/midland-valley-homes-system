@php
    $title = 'Customers';
    $headerTitle = 'Customers';
@endphp

<x-manager-shell :title="$title" :header-title="$headerTitle">
    @if (session('status'))
        <div class="flash flash-success">{{ session('status') }}</div>
    @endif

    <div class="panel">
        <div class="panel-head">
            <div>
                <div class="panel-title">Customer Dashboard</div>
                <div class="muted" style="font-size:12px; margin-top:2px;">Quick overview and list</div>
            </div>
            <a href="{{ route('customers.create') }}" class="btn btn-primary">Add Customer</a>
        </div>

        <div style="display:grid; grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 10px;">
            <div class="panel" style="padding:10px;">
                <div class="label">Total</div>
                <div style="font-size:18px; font-weight:800; margin-top:4px;">{{ $stats['total'] }}</div>
            </div>
            <div class="panel" style="padding:10px;">
                <div class="label">Pending</div>
                <div style="font-size:18px; font-weight:800; margin-top:4px;">{{ $stats['pending'] }}</div>
            </div>
            <div class="panel" style="padding:10px;">
                <div class="label">For approval</div>
                <div style="font-size:18px; font-weight:800; margin-top:4px;">{{ $stats['for_approval'] }}</div>
            </div>
            <div class="panel" style="padding:10px;">
                <div class="label">Approved</div>
                <div style="font-size:18px; font-weight:800; margin-top:4px;">{{ $stats['approved'] }}</div>
            </div>
            <div class="panel" style="padding:10px;">
                <div class="label">Rejected</div>
                <div style="font-size:18px; font-weight:800; margin-top:4px;">{{ $stats['rejected'] }}</div>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">Customers List</div>
            <div class="chip">Search + filter</div>
        </div>

        <form method="GET" action="{{ route('customers.index') }}" style="display:grid; grid-template-columns: 1fr 220px auto; gap: 10px; align-items:end; margin-bottom: 10px;">
            <div class="field">
                <div class="label">Search</div>
                <input class="input" name="q" value="{{ $search }}" placeholder="Name, email, phone" />
            </div>
            <div class="field">
                <div class="label">Status</div>
                <select class="select" name="status">
                    <option value="all" @selected(($status ?? 'all') === 'all')>All</option>
                    <option value="pending" @selected($status === 'pending')>Pending</option>
                    <option value="for_approval" @selected($status === 'for_approval')>For approval</option>
                    <option value="approved" @selected($status === 'approved')>Approved</option>
                    <option value="rejected" @selected($status === 'rejected')>Rejected</option>
                </select>
            </div>
            <div style="display:flex; gap: 8px; justify-content:flex-end;">
                <button class="btn btn-primary" type="submit">Filter</button>
                <a class="btn" href="{{ route('customers.index') }}">Reset</a>
            </div>
        </form>

        <div style="overflow:auto; border-radius: 12px; border: 1px solid #e2e8f0; background: #fff;">
            <table>
                <thead>
                <tr>
                    <th style="min-width:220px;">Name</th>
                    <th style="min-width:200px;">Email</th>
                    <th style="min-width:140px;">Phone</th>
                    <th style="min-width:140px;">Status</th>
                    <th style="min-width:220px; text-align:right;">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($customers as $customer)
                    <tr>
                        <td>
                            <a href="{{ route('customers.show', $customer) }}" style="font-weight:800; color:#0f172a; text-decoration:none;">
                                {{ $customer->last_name }}, {{ $customer->first_name }}{{ $customer->middle_name ? ' ' . $customer->middle_name : '' }}
                            </a>
                        </td>
                        <td class="muted">{{ $customer->email ?? '—' }}</td>
                        <td class="muted">{{ $customer->phone ?? '—' }}</td>
                        <td>
                            @php
                                $badgeClass = match($customer->status) {
                                    'pending' => 'badge badge-gray',
                                    'for_approval' => 'badge badge-yellow',
                                    'approved' => 'badge badge-green',
                                    'rejected' => 'badge badge-red',
                                    default => 'badge badge-gray',
                                };
                            @endphp
                            <span class="{{ $badgeClass }}">{{ str_replace('_', ' ', $customer->status) }}</span>
                        </td>
                        <td style="text-align:right;">
                            <div style="display:inline-flex; gap: 8px; align-items:center;">
                                <a class="btn" href="{{ route('customers.edit', $customer) }}">Edit</a>
                                <form method="POST" action="{{ route('customers.destroy', $customer) }}" onsubmit="return confirm('Delete this customer?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" type="submit">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="muted" style="padding: 18px; text-align:center;">No customers found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 10px;">
            {{ $customers->links() }}
        </div>
    </div>
</x-manager-shell>

