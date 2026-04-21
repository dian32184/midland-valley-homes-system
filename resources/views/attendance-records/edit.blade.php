@php
    $title = 'Edit Attendance';
    $headerTitle = 'Attendance';
    $headerSubtitle = "Record #{$record->id}";
@endphp

<x-manager-shell :title="$title" :header-title="$headerTitle" :header-subtitle="$headerSubtitle">
    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">Edit Attendance</div>
            <div style="display:flex; gap: 8px;">
                <a class="btn" href="{{ route('attendance-records.show', $record) }}">View</a>
                <a class="btn" href="{{ route('attendance-records.index') }}">Back</a>
            </div>
        </div>

        <form method="POST" action="{{ route('attendance-records.update', $record) }}">
            @csrf
            @method('PUT')
            @include('attendance-records._form', ['record' => $record, 'employees' => $employees])

            <div style="display:flex; justify-content:flex-end; gap: 8px; margin-top: 12px;">
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </form>
    </div>
</x-manager-shell>

