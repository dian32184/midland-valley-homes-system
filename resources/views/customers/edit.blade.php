@php
    $title = 'Edit Customer';
    $headerTitle = 'Customers';
    $headerSubtitle = 'Update customer profile';
@endphp

<x-manager-shell :title="$title" :header-title="$headerTitle" :header-subtitle="$headerSubtitle">
    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">
                Edit: {{ $customer->last_name }}, {{ $customer->first_name }}
            </div>
            <div style="display:flex; gap: 8px;">
                <a class="btn" href="{{ route('customers.show', $customer) }}">View</a>
                <a class="btn" href="{{ route('customers.index') }}">Back</a>
            </div>
        </div>

        <form method="POST" action="{{ route('customers.update', $customer) }}">
            @csrf
            @method('PUT')

            @include('customers._form', ['customer' => $customer])

            <div style="display:flex; justify-content:flex-end; gap: 8px; margin-top: 12px;">
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </form>
    </div>
</x-manager-shell>

