@php
    $title = 'Construction Project';
    $headerTitle = 'Construction';
    $headerSubtitle = "Project #{$project->id}";

    $propertyLabel = $project->property
        ? "B{$project->property->block_number} / L{$project->property->lot_number} • {$project->property->house_type}"
        : '—';

    $badgeClass = match($project->status) {
        'not_started' => 'badge badge-gray',
        'ongoing' => 'badge badge-yellow',
        'completed' => 'badge badge-green',
        default => 'badge badge-gray',
    };
@endphp

<x-manager-shell :title="$title" :header-title="$headerTitle" :header-subtitle="$headerSubtitle">
    @if (session('status'))
        <div class="flash flash-success">{{ session('status') }}</div>
    @endif

    <div class="panel">
        <div class="panel-head">
            <div style="display:flex; gap: 10px; align-items:center;">
                <div class="panel-title">Project Details</div>
                <span class="{{ $badgeClass }}">{{ str_replace('_', ' ', $project->status) }}</span>
            </div>
            <div style="display:flex; gap: 8px;">
                <a class="btn btn-primary" href="{{ route('construction-projects.edit', $project) }}">Edit</a>
                <a class="btn" href="{{ route('construction-projects.index') }}">Back</a>
            </div>
        </div>

        <div class="panel" style="padding:10px;">
            <div class="label">Property</div>
            <div style="margin-top: 8px;"><strong>{{ $propertyLabel }}</strong></div>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; margin-top: 10px;">
            <div class="panel" style="padding:10px;">
                <div class="label">Start</div>
                <div style="margin-top: 8px;"><strong>{{ $project->start_date ?? '—' }}</strong></div>
            </div>
            <div class="panel" style="padding:10px;">
                <div class="label">Completion</div>
                <div style="margin-top: 8px;"><strong>{{ $project->completion_date ?? '—' }}</strong></div>
            </div>
            <div class="panel" style="padding:10px;">
                <div class="label">Progress</div>
                <div style="margin-top: 8px; display:flex; gap:8px; align-items:center;">
                    <div style="flex: 1; height: 10px; background:#e5e7eb; border-radius: 999px; overflow:hidden;">
                        <div style="height:100%; width: {{ (int) $project->progress_percent }}%; background: linear-gradient(90deg, #60a5fa, #6366f1);"></div>
                    </div>
                    <strong>{{ (int) $project->progress_percent }}%</strong>
                </div>
            </div>
        </div>

        <div class="panel" style="margin-top: 10px; padding:10px;">
            <div class="label">Notes</div>
            <div style="margin-top: 8px; white-space: pre-wrap;">{{ $project->notes ?? '—' }}</div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">Danger zone</div>
            <div class="chip">Delete project</div>
        </div>
        <form method="POST" action="{{ route('construction-projects.destroy', $project) }}" onsubmit="return confirm('Delete this project?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete project</button>
        </form>
    </div>
</x-manager-shell>

