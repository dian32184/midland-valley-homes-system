@php
    $title = 'Add Reservation';
    $headerTitle = 'Reservations';
    $headerSubtitle = 'Reserve a property for a customer';
@endphp

<x-manager-shell :title="$title" :header-title="$headerTitle" :header-subtitle="$headerSubtitle">
    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">New Reservation</div>
            <a class="btn" href="{{ route('reservations.index') }}">Back</a>
        </div>

        <form method="POST" action="{{ route('reservations.store') }}">
            @csrf
            @include('reservations._form', ['reservation' => $reservation, 'customers' => $customers, 'properties' => $properties])

            <div style="display:flex; justify-content:flex-end; gap: 8px; margin-top: 12px;">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</x-manager-shell>

