@php
    $title = 'Edit Reservation';
    $headerTitle = 'Reservations';
    $headerSubtitle = "Reservation #{$reservation->id}";
@endphp

<x-manager-shell :title="$title" :header-title="$headerTitle" :header-subtitle="$headerSubtitle">
    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">Edit Reservation</div>
            <div style="display:flex; gap: 8px;">
                <a class="btn" href="{{ route('reservations.show', $reservation) }}">View</a>
                <a class="btn" href="{{ route('reservations.index') }}">Back</a>
            </div>
        </div>

        <form method="POST" action="{{ route('reservations.update', $reservation) }}">
            @csrf
            @method('PUT')
            @include('reservations._form', ['reservation' => $reservation, 'customers' => $customers, 'properties' => $properties])

            <div style="display:flex; justify-content:flex-end; gap: 8px; margin-top: 12px;">
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </form>
    </div>
</x-manager-shell>

