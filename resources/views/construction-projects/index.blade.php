@php
    $title = 'Construction';
    $headerTitle = 'Construction';
@endphp

<x-manager-shell :title="$title" :header-title="$headerTitle">
    @if (session('status'))
        <div class="flash flash-success">{{ session('status') }}</div>
    @endif

    <div class="panel">
        <div class="panel-head">
            <div>
                <div class="panel-title">Construction Dashboard</div>
                <div class="muted" style="font-size:12px; margin-top:2px;">Track property construction progress</div>
            </div>
            <a href="{{ route('construction-projects.create') }}" class="btn btn-primary">Add Project</a>
        </div>

        <div style="display:grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 10px;">
            <div class="panel" style="padding:10px;"><div class="label">Total</div><div style="font-size:18px; font-weight:800; margin-top:4px;">{{ $stats['total'] }}</div></div>
            <div class="panel" style="padding:10px;"><div class="label">Not started</div><div style="font-size:18px; font-weight:800; margin-top:4px;">{{ $stats['not_started'] }}</div></div>
            <div class="panel" style="padding:10px;"><div class="label">Ongoing</div><div style="font-size:18px; font-weight:800; margin-top:4px;">{{ $stats['ongoing'] }}</div></div>
            <div class="panel" style="padding:10px;"><div class="label">Completed</div><div style="font-size:18px; font-weight:800; margin-top:4px;">{{ $stats['completed'] }}</div></div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">Projects List</div>
            <div class="chip">Search + filter</div>
        </div>

        <form method="GET" action="{{ route('construction-projects.index') }}" style="display:grid; grid-template-columns: 1fr 240px auto; gap: 10px; align-items:end; margin-bottom: 10px;">
            <div class="field">
                <div class="label">Search</div>
                <input class="input" name="q" value="{{ $search }}" placeholder="Block, lot, house type" />
            </div>
            <div class="field">
                <div class="label">Status</div>
                <select class="select" name="status">
                    <option value="all" @selected(($status ?? 'all') === 'all')>All</option>
                    <option value="not_started" @selected($status === 'not_started')>Not started</option>
                    <option value="ongoing" @selected($status === 'ongoing')>Ongoing</option>
                    <option value="completed" @selected($status === 'completed')>Completed</option>
                </select>
            </div>
            <div style="display:flex; gap: 8px; justify-content:flex-end;">
                <button class="btn btn-primary" type="submit">Filter</button>
                <a class="btn" href="{{ route('construction-projects.index') }}">Reset</a>
            </div>
        </form>

        <div style="overflow:auto; border-radius: 12px; border: 1px solid #e2e8f0; background: #fff;">
            <table>
                <thead>
                <tr>
                    <th style="min-width:80px;">#</th>
                    <th style="min-width:220px;">Property</th>
                    <th style="min-width:160px;">Status</th>
                    <th style="min-width:160px;">Start</th>
                    <th style="min-width:160px;">Completion</th>
                    <th style="min-width:160px;">Progress</th>
                    <th style="min-width:220px; text-align:right;">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($projects as $project)
                    @php
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
                    <tr>
                        <td class="muted">{{ $project->id }}</td>
                        <td>
                            <a href="{{ route('construction-projects.show', $project) }}" style="font-weight:800; color:#0f172a; text-decoration:none;">
                                {{ $propertyLabel }}
                            </a>
                        </td>
                        <td><span class="{{ $badgeClass }}">{{ str_replace('_', ' ', $project->status) }}</span></td>
                        <td class="muted">{{ $project->start_date ?? '—' }}</td>
                        <td class="muted">{{ $project->completion_date ?? '—' }}</td>
                        <td>
                            <div style="display:flex; gap:8px; align-items:center;">
                                <div style="flex: 1; height: 8px; background:#e5e7eb; border-radius: 999px; overflow:hidden; min-width: 80px;">
                                    <div style="height:100%; width: {{ (int) $project->progress_percent }}%; background: linear-gradient(90deg, #60a5fa, #6366f1);"></div>
                                </div>
                                <strong>{{ (int) $project->progress_percent }}%</strong>
                            </div>
                        </td>
                        <td style="text-align:right;">
                            <div style="display:inline-flex; gap: 8px; align-items:center;">
                                <a class="btn" href="{{ route('construction-projects.edit', $project) }}">Edit</a>
                                <form method="POST" action="{{ route('construction-projects.destroy', $project) }}" onsubmit="return confirm('Delete this project?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" type="submit">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="muted" style="padding: 18px; text-align:center;">No construction projects found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 10px;">
            {{ $projects->links() }}
        </div>
    </div>
</x-manager-shell>

