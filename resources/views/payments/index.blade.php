@php
    $title = 'Payments';
    $headerTitle = 'Payments';
@endphp

<x-manager-shell :title="$title" :header-title="$headerTitle">
    @if (session('status'))
        <div class="flash flash-success">{{ session('status') }}</div>
    @endif

    <div class="panel">
        <div class="panel-head">
            <div>
                <div class="panel-title">Payments Dashboard</div>
                <div class="muted" style="font-size:12px; margin-top:2px;">Collections summary and records</div>
            </div>
            <a href="{{ route('payments.create') }}" class="btn btn-primary">Add Payment</a>
        </div>

        <div style="display:grid; grid-template-columns: 1.2fr 1fr 1fr 1fr 1fr 1fr; gap: 10px;">
            <div class="panel" style="padding:10px;">
                <div class="label">Total amount</div>
                <div style="font-size:18px; font-weight:800; margin-top:4px;">PHP {{ number_format((float) $stats['total_amount'], 2) }}</div>
                <div class="muted" style="margin-top:4px;">{{ $stats['total'] }} payments</div>
            </div>
            <div class="panel" style="padding:10px;">
                <div class="label">Downpayment</div>
                <div style="font-size:18px; font-weight:800; margin-top:4px;">{{ $stats['downpayment'] }}</div>
            </div>
            <div class="panel" style="padding:10px;">
                <div class="label">Installment</div>
                <div style="font-size:18px; font-weight:800; margin-top:4px;">{{ $stats['installment'] }}</div>
            </div>
            <div class="panel" style="padding:10px;">
                <div class="label">Equity</div>
                <div style="font-size:18px; font-weight:800; margin-top:4px;">{{ $stats['equity'] }}</div>
            </div>
            <div class="panel" style="padding:10px;">
                <div class="label">Reservation fee</div>
                <div style="font-size:18px; font-weight:800; margin-top:4px;">{{ $stats['reservation_fee'] }}</div>
            </div>
            <div class="panel" style="padding:10px;">
                <div class="label">Other</div>
                <div style="font-size:18px; font-weight:800; margin-top:4px;">{{ $stats['other'] }}</div>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">Payments List</div>
            <div class="chip">Search + filter</div>
        </div>

        <form method="GET" action="{{ route('payments.index') }}" style="display:grid; grid-template-columns: 1fr 220px auto; gap: 10px; align-items:end; margin-bottom: 10px;">
            <div class="field">
                <div class="label">Search</div>
                <input class="input" name="q" value="{{ $search }}" placeholder="Customer, property, method" />
            </div>
            <div class="field">
                <div class="label">Type</div>
                <select class="select" name="type">
                    <option value="all" @selected(($type ?? 'all') === 'all')>All</option>
                    <option value="downpayment" @selected($type === 'downpayment')>Downpayment</option>
                    <option value="installment" @selected($type === 'installment')>Installment</option>
                    <option value="equity" @selected($type === 'equity')>Equity</option>
                    <option value="reservation_fee" @selected($type === 'reservation_fee')>Reservation fee</option>
                    <option value="other" @selected($type === 'other')>Other</option>
                </select>
            </div>
            <div style="display:flex; gap: 8px; justify-content:flex-end;">
                <button class="btn btn-primary" type="submit">Filter</button>
                <a class="btn" href="{{ route('payments.index') }}">Reset</a>
            </div>
        </form>

        <div style="overflow:auto; border-radius: 12px; border: 1px solid #e2e8f0; background: #fff;">
            <table>
                <thead>
                <tr>
                    <th style="min-width:120px;">Date</th>
                    <th style="min-width:240px;">Customer</th>
                    <th style="min-width:220px;">Property</th>
                    <th style="min-width:160px;">Type</th>
                    <th style="min-width:140px;">Amount</th>
                    <th style="min-width:180px;">Method</th>
                    <th style="min-width:220px; text-align:right;">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($payments as $payment)
                    @php
                        $customerName = $payment->customer
                            ? "{$payment->customer->last_name}, {$payment->customer->first_name}"
                            : '—';
                        $propertyLabel = $payment->property
                            ? "B{$payment->property->block_number} / L{$payment->property->lot_number} • {$payment->property->house_type}"
                            : '—';
                    @endphp
                    <tr>
                        <td class="muted">{{ $payment->payment_date ?? '—' }}</td>
                        <td>
                            <a href="{{ route('payments.show', $payment) }}" style="font-weight:800; color:#0f172a; text-decoration:none;">
                                {{ $customerName }}
                            </a>
                        </td>
                        <td class="muted">{{ $propertyLabel }}</td>
                        <td><span class="badge badge-gray">{{ str_replace('_', ' ', $payment->payment_type) }}</span></td>
                        <td><strong>PHP {{ number_format((float) $payment->amount, 2) }}</strong></td>
                        <td class="muted">{{ $payment->payment_method ?? '—' }}</td>
                        <td style="text-align:right;">
                            <div style="display:inline-flex; gap: 8px; align-items:center;">
                                <a class="btn" href="{{ route('payments.edit', $payment) }}">Edit</a>
                                <form method="POST" action="{{ route('payments.destroy', $payment) }}" onsubmit="return confirm('Delete this payment?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" type="submit">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="muted" style="padding: 18px; text-align:center;">No payments found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 10px;">
            {{ $payments->links() }}
        </div>
    </div>
</x-manager-shell>

