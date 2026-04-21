@php
    $title = 'Add Construction Project';
    $headerTitle = 'Construction';
    $headerSubtitle = 'Create a project record';
@endphp

<x-manager-shell :title="$title" :header-title="$headerTitle" :header-subtitle="$headerSubtitle">
    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">New Project</div>
            <a class="btn" href="{{ route('construction-projects.index') }}">Back</a>
        </div>

        <form method="POST" action="{{ route('construction-projects.store') }}">
            @csrf
            @include('construction-projects._form', ['project' => $project, 'properties' => $properties])

            <div style="display:flex; justify-content:flex-end; gap: 8px; margin-top: 12px;">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</x-manager-shell>

