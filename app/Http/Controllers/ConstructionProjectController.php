<?php

namespace App\Http\Controllers;

use App\Models\ConstructionProject;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ConstructionProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = ConstructionProject::query()->with('property');

        $search = trim((string) request('q', ''));
        if ($search !== '') {
            $query->whereHas('property', function ($q) use ($search) {
                $q->where('block_number', 'like', "%{$search}%")
                    ->orWhere('lot_number', 'like', "%{$search}%")
                    ->orWhere('house_type', 'like', "%{$search}%");
            });
        }

        $status = request('status');
        if (is_string($status) && $status !== '' && $status !== 'all') {
            $query->where('status', $status);
        }

        $projects = $query
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => ConstructionProject::count(),
            'not_started' => ConstructionProject::where('status', 'not_started')->count(),
            'ongoing' => ConstructionProject::where('status', 'ongoing')->count(),
            'completed' => ConstructionProject::where('status', 'completed')->count(),
        ];

        return view('construction-projects.index', compact('projects', 'search', 'status', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $properties = Property::query()
            ->orderBy('block_number')
            ->orderBy('lot_number')
            ->get();

        return view('construction-projects.create', [
            'project' => new ConstructionProject(),
            'properties' => $properties,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate($this->rules());

        $project = ConstructionProject::create($data);

        return redirect()
            ->route('construction-projects.show', $project)
            ->with('status', 'Construction project created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ConstructionProject $construction_project)
    {
        $construction_project->load('property');

        return view('construction-projects.show', [
            'project' => $construction_project,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ConstructionProject $construction_project)
    {
        $properties = Property::query()
            ->orderBy('block_number')
            ->orderBy('lot_number')
            ->get();

        return view('construction-projects.edit', [
            'project' => $construction_project,
            'properties' => $properties,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ConstructionProject $construction_project)
    {
        $data = $request->validate($this->rules());

        $construction_project->update($data);

        return redirect()
            ->route('construction-projects.show', $construction_project)
            ->with('status', 'Construction project updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ConstructionProject $construction_project)
    {
        $construction_project->delete();

        return redirect()
            ->route('construction-projects.index')
            ->with('status', 'Construction project deleted.');
    }

    private function rules(): array
    {
        $statuses = ['not_started', 'ongoing', 'completed'];

        return [
            'property_id' => ['required', 'integer', 'exists:properties,id'],
            'status' => ['required', Rule::in($statuses)],
            'start_date' => ['nullable', 'date'],
            'completion_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'progress_percent' => ['required', 'integer', 'min:0', 'max:100'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
