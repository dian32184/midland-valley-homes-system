@php
    $title = 'Edit Payment';
    $headerTitle = 'Payments';
    $headerSubtitle = "Payment #{$payment->id}";
@endphp

<x-manager-shell :title="$title" :header-title="$headerTitle" :header-subtitle="$headerSubtitle">
    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">Edit Payment</div>
            <div style="display:flex; gap: 8px;">
                <a class="btn" href="{{ route('payments.show', $payment) }}">View</a>
                <a class="btn" href="{{ route('payments.index') }}">Back</a>
            </div>
        </div>

        <form method="POST" action="{{ route('payments.update', $payment) }}">
            @csrf
            @method('PUT')
            @include('payments._form', ['payment' => $payment, 'customers' => $customers, 'properties' => $properties, 'reservations' => $reservations])

            <div style="display:flex; justify-content:flex-end; gap: 8px; margin-top: 12px;">
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </form>
    </div>
</x-manager-shell>

