@php
    $title = 'Customer';
    $headerTitle = 'Customers';
    $headerSubtitle = "{$customer->last_name}, {$customer->first_name}";
    $badgeClass = match($customer->status) {
        'pending' => 'badge badge-gray',
        'for_approval' => 'badge badge-yellow',
        'approved' => 'badge badge-green',
        'rejected' => 'badge badge-red',
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
                <div class="panel-title">Customer Profile</div>
                <span class="{{ $badgeClass }}">{{ str_replace('_', ' ', $customer->status) }}</span>
            </div>
            <div style="display:flex; gap: 8px;">
                <a class="btn btn-primary" href="{{ route('customers.edit', $customer) }}">Edit</a>
                <a class="btn" href="{{ route('customers.index') }}">Back</a>
            </div>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 10px;">
            <div class="panel" style="padding:10px;">
                <div class="label">Contact</div>
                <div style="display:grid; gap: 6px; margin-top: 8px;">
                    <div><span class="muted">Email:</span> <strong>{{ $customer->email ?? '—' }}</strong></div>
                    <div><span class="muted">Phone:</span> <strong>{{ $customer->phone ?? '—' }}</strong></div>
                    <div><span class="muted">Address:</span> <strong>{{ $customer->address ?? '—' }}</strong></div>
                    <div><span class="muted">Date of birth:</span> <strong>{{ $customer->date_of_birth ?? '—' }}</strong></div>
                </div>
            </div>
            <div class="panel" style="padding:10px;">
                <div class="label">Employment</div>
                <div style="display:grid; gap: 6px; margin-top: 8px;">
                    <div><span class="muted">Status:</span> <strong>{{ str_replace('_', ' ', $customer->employment_status) }}</strong></div>
                    <div><span class="muted">Employer:</span> <strong>{{ $customer->employer_name ?? '—' }}</strong></div>
                    <div><span class="muted">Job title:</span> <strong>{{ $customer->job_title ?? '—' }}</strong></div>
                    <div><span class="muted">Monthly income:</span> <strong>{{ $customer->monthly_income !== null ? number_format((float) $customer->monthly_income, 2) : '—' }}</strong></div>
                </div>
            </div>
        </div>

        <div class="panel" style="margin-top: 10px; padding:10px;">
            <div class="label">Notes</div>
            <div style="margin-top: 8px; white-space: pre-wrap;">{{ $customer->profile_notes ?? '—' }}</div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">Emergency Contact</div>
            <div class="chip">Optional</div>
        </div>
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 10px;">
            <div class="panel" style="padding:10px;">
                <div class="label">Name</div>
                <div style="margin-top: 8px;"><strong>{{ $customer->contact_person_name ?? '—' }}</strong></div>
            </div>
            <div class="panel" style="padding:10px;">
                <div class="label">Phone</div>
                <div style="margin-top: 8px;"><strong>{{ $customer->contact_person_phone ?? '—' }}</strong></div>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">Danger zone</div>
            <div class="chip">Delete customer</div>
        </div>
        <form method="POST" action="{{ route('customers.destroy', $customer) }}" onsubmit="return confirm('Delete this customer?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete customer</button>
        </form>
    </div>
</x-manager-shell>

