@php
    $title = 'Add Payment';
    $headerTitle = 'Payments';
    $headerSubtitle = 'Record a payment';
@endphp

<x-manager-shell :title="$title" :header-title="$headerTitle" :header-subtitle="$headerSubtitle">
    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">New Payment</div>
            <a class="btn" href="{{ route('payments.index') }}">Back</a>
        </div>

        <form method="POST" action="{{ route('payments.store') }}">
            @csrf
            @include('payments._form', ['payment' => $payment, 'customers' => $customers, 'properties' => $properties, 'reservations' => $reservations])

            <div style="display:flex; justify-content:flex-end; gap: 8px; margin-top: 12px;">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</x-manager-shell>

