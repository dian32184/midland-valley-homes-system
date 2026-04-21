@php
    $title = 'Add Employee';
    $headerTitle = 'Employees';
    $headerSubtitle = 'Create a new employee profile';
@endphp

<x-manager-shell :title="$title" :header-title="$headerTitle" :header-subtitle="$headerSubtitle">
    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">New Employee</div>
            <a class="btn" href="{{ route('employees.index') }}">Back</a>
        </div>

        <form method="POST" action="{{ route('employees.store') }}">
            @csrf
            @include('employees._form', ['employee' => $employee])

            <div style="display:flex; justify-content:flex-end; gap: 8px; margin-top: 12px;">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</x-manager-shell>

