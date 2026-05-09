<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PropertyController extends Controller
{
    public function index()
    {
        $properties = Property::orderByDesc('created_at')->paginate(10);

        return view('properties.index', compact('properties'));
    }

    public function create()
    {
        return view('properties.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'street_name'  => ['required', 'string', 'max:100'],
            'subdivision'  => ['nullable', 'string', 'max:100'],
            'barangay'     => ['nullable', 'string', 'max:100'],
            'city'         => ['nullable', 'string', 'max:100'],
            'province'     => ['nullable', 'string', 'max:100'],
            'zip_code'     => ['nullable', 'string', 'max:20'],
            'block_number' => ['required', 'string', 'max:50'],
            'lot_number'   => ['required', 'string', 'max:50'],
            'house_model'  => ['required', Rule::in(['diamond', 'ruby', 'custom'])],
            'lot_type'     => ['required', Rule::in(['regular', 'corner_lot'])],
            'house_type'   => ['nullable', 'string', 'max:100'],
            'price'        => ['nullable', 'numeric', 'min:0'],
            'lot_size'     => ['nullable', 'string', 'max:50'],
            'floor_area'   => ['nullable', 'string', 'max:50'],
            'status'       => ['required', Rule::in(['available', 'reserved', 'sold', 'under_construction', 'turned_over'])],
            'description'  => ['nullable', 'string'],
            'available_at' => ['nullable', 'date'],
        ]);

        $this->normalizeHouseDetails($validated);

        Property::create($validated);

        return redirect()->route('properties.index')->with('success', 'Property created successfully.');
    }

    public function show(Property $property)
    {
        return redirect()->route('properties.edit', $property);
    }

    public function edit(Property $property)
    {
        return view('properties.edit', compact('property'));
    }

    public function update(Request $request, Property $property)
    {
        $validated = $request->validate([
            'street_name'  => ['required', 'string', 'max:100'],
            'subdivision'  => ['nullable', 'string', 'max:100'],
            'barangay'     => ['nullable', 'string', 'max:100'],
            'city'         => ['nullable', 'string', 'max:100'],
            'province'     => ['nullable', 'string', 'max:100'],
            'zip_code'     => ['nullable', 'string', 'max:20'],
            'block_number' => ['required', 'string', 'max:50'],
            'lot_number'   => ['required', 'string', 'max:50'],
            'house_model'  => ['required', Rule::in(['diamond', 'ruby', 'custom'])],
            'lot_type'     => ['required', Rule::in(['regular', 'corner_lot'])],
            'house_type'   => ['nullable', 'string', 'max:100'],
            'price'        => ['nullable', 'numeric', 'min:0'],
            'lot_size'     => ['nullable', 'string', 'max:50'],
            'floor_area'   => ['nullable', 'string', 'max:50'],
            'status'       => ['required', Rule::in(['available', 'reserved', 'sold', 'under_construction', 'turned_over'])],
            'description'  => ['nullable', 'string'],
            'available_at' => ['nullable', 'date'],
        ]);

        $this->normalizeHouseDetails($validated);

        $property->update($validated);

        return redirect()->route('properties.index')->with('success', 'Property updated successfully.');
    }

    public function destroy(Property $property)
    {
        $property->delete();

        return redirect()->route('properties.index')->with('success', 'Property deleted successfully.');
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function normalizeHouseDetails(array &$validated): void
    {
        $model   = $validated['house_model'] ?? 'diamond';
        $lotType = $validated['lot_type']    ?? 'regular';

        $validated['is_custom_request'] = $model === 'custom';
        $validated['subdivision']       = $validated['subdivision'] ?? 'Midland Valley Homes';

        if ($model !== 'custom') {
            // Standard units — lock sizes and auto-price
            $validated['house_type']  = ucfirst($model) . ($lotType === 'corner_lot' ? ' (Corner Lot)' : '');
            $validated['lot_size']    = '120 sqm';
            $validated['floor_area']  = '35 sqm';
            $validated['price']       = $lotType === 'corner_lot'
                ? Property::PRICE_CORNER_LOT
                : Property::PRICE_REGULAR;
        } else {
            // Custom — keep user-supplied values
            $validated['house_type']  = 'Custom' . ($lotType === 'corner_lot' ? ' (Corner Lot)' : '');
        }
    }
}