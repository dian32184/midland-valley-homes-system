@php
    $title = 'Add Attendance';
    $headerTitle = 'Attendance';
    $headerSubtitle = 'Create an attendance record';
@endphp

<x-manager-shell :title="$title" :header-title="$headerTitle" :header-subtitle="$headerSubtitle">
    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">New Attendance</div>
            <a class="btn" href="{{ route('attendance-records.index') }}">Back</a>
        </div>

        <form method="POST" action="{{ route('attendance-records.store') }}">
            @csrf
            @include('attendance-records._form', ['record' => $record, 'employees' => $employees])

            <div style="display:flex; justify-content:flex-end; gap: 8px; margin-top: 12px;">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</x-manager-shell>

