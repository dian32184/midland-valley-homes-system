@php
    $title = 'Edit Construction Project';
    $headerTitle = 'Construction';
    $headerSubtitle = "Project #{$project->id}";
@endphp

<x-manager-shell :title="$title" :header-title="$headerTitle" :header-subtitle="$headerSubtitle">
    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">Edit Project</div>
            <div style="display:flex; gap: 8px;">
                <a class="btn" href="{{ route('construction-projects.show', $project) }}">View</a>
                <a class="btn" href="{{ route('construction-projects.index') }}">Back</a>
            </div>
        </div>

        <form method="POST" action="{{ route('construction-projects.update', $project) }}">
            @csrf
            @method('PUT')
            @include('construction-projects._form', ['project' => $project, 'properties' => $properties])

            <div style="display:flex; justify-content:flex-end; gap: 8px; margin-top: 12px;">
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </form>
    </div>
</x-manager-shell>

