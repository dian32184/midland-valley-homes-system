@php
    $title = 'Edit Employee';
    $headerTitle = 'Employees';
    $headerSubtitle = "Employee #{$employee->id}";
@endphp

<x-manager-shell :title="$title" :header-title="$headerTitle" :header-subtitle="$headerSubtitle">
    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">Edit Employee</div>
            <div style="display:flex; gap: 8px;">
                <a class="btn" href="{{ route('employees.show', $employee) }}">View</a>
                <a class="btn" href="{{ route('employees.index') }}">Back</a>
            </div>
        </div>

        <form method="POST" action="{{ route('employees.update', $employee) }}">
            @csrf
            @method('PUT')
            @include('employees._form', ['employee' => $employee])

            <div style="display:flex; justify-content:flex-end; gap: 8px; margin-top: 12px;">
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </form>
    </div>
</x-manager-shell>

