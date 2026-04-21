<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Property::query();

        $search = trim((string) request('q', ''));
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('block_number', 'like', "%{$search}%")
                    ->orWhere('lot_number', 'like', "%{$search}%")
                    ->orWhere('house_type', 'like', "%{$search}%");
            });
        }

        $status = request('status');
        if (is_string($status) && $status !== '' && $status !== 'all') {
            $query->where('status', $status);
        }

        $properties = $query
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => Property::count(),
            'available' => Property::where('status', 'available')->count(),
            'reserved' => Property::where('status', 'reserved')->count(),
            'sold' => Property::where('status', 'sold')->count(),
            'under_construction' => Property::where('status', 'under_construction')->count(),
            'turned_over' => Property::where('status', 'turned_over')->count(),
        ];

        return view('properties.index', compact('properties', 'search', 'status', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('properties.create', [
            'property' => new Property(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate($this->rules());

        $property = Property::create($data);

        return redirect()
            ->route('properties.show', $property)
            ->with('status', 'Property created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Property $property)
    {
        return view('properties.show', compact('property'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Property $property)
    {
        return view('properties.edit', compact('property'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Property $property)
    {
        $data = $request->validate($this->rules($property));

        $property->update($data);

        return redirect()
            ->route('properties.show', $property)
            ->with('status', 'Property updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Property $property)
    {
        $property->delete();

        return redirect()
            ->route('properties.index')
            ->with('status', 'Property deleted.');
    }

    private function rules(?Property $property = null): array
    {
        $statuses = ['available', 'reserved', 'sold', 'under_construction', 'turned_over'];

        return [
            'block_number' => ['required', 'string', 'max:255'],
            'lot_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('properties', 'lot_number')
                    ->where(fn ($q) => $q->where('block_number', request('block_number')))
                    ->ignore($property?->id),
            ],
            'house_type' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'lot_size' => ['nullable', 'string', 'max:255'],
            'floor_area' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in($statuses)],
            'description' => ['nullable', 'string'],
            'available_at' => ['nullable', 'date'],
        ];
    }
}
