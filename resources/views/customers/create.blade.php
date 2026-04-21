@php
    $title = 'Add Customer';
    $headerTitle = 'Customers';
    $headerSubtitle = 'Add customer profile';
@endphp

<x-manager-shell :title="$title" :header-title="$headerTitle" :header-subtitle="$headerSubtitle">
    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">New Customer</div>
            <a class="btn" href="{{ route('customers.index') }}">Back</a>
        </div>

        <form method="POST" action="{{ route('customers.store') }}">
            @csrf

            @include('customers._form', ['customer' => $customer])

            <div style="display:flex; justify-content:flex-end; gap: 8px; margin-top: 12px;">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</x-manager-shell>

