<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Property;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with(['customer', 'property'])->orderByDesc('created_at')->paginate(10);

        return view('reservations.index', compact('reservations'));
    }

    public function create()
    {
        $customers = Customer::orderBy('last_name')->get();
        $properties = Property::whereIn('status', ['available', 'under_construction'])->orderBy('block_number')->get();

        return view('reservations.create', compact('customers', 'properties'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'property_id' => ['required', 'exists:properties,id'],
            'reservation_fee' => ['required', 'numeric', 'min:0'],
            'reserved_on' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after_or_equal:reserved_on'],
            'status' => ['required', Rule::in(['active', 'completed', 'cancelled', 'skipped'])],
            'notes' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated): void {
            $property = Property::lockForUpdate()->findOrFail($validated['property_id']);

            if ($property->status === 'sold') {
                abort(422, 'This property is already sold and cannot be reserved.');
            }

            if ($property->status === 'reserved' && $validated['status'] === 'active') {
                abort(422, 'This property is already reserved.');
            }

            Reservation::create($validated);

            if ($validated['status'] === 'active') {
                $property->update(['status' => 'reserved']);
            }

            if ($validated['status'] === 'completed') {
                $property->update(['status' => 'sold']);
            }
        });

        return redirect()->route('reservations.index')->with('success', 'Reservation created successfully.');
    }

    public function show(Reservation $reservation)
    {
        return redirect()->route('reservations.edit', $reservation);
    }

    public function edit(Reservation $reservation)
    {
        $customers = Customer::orderBy('last_name')->get();
        $properties = Property::orderBy('block_number')->get();

        return view('reservations.edit', compact('reservation', 'customers', 'properties'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'property_id' => ['required', 'exists:properties,id'],
            'reservation_fee' => ['required', 'numeric', 'min:0'],
            'reserved_on' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after_or_equal:reserved_on'],
            'status' => ['required', Rule::in(['active', 'completed', 'cancelled', 'skipped'])],
            'notes' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated, $reservation): void {
            $oldProperty = Property::lockForUpdate()->findOrFail($reservation->property_id);
            $newProperty = Property::lockForUpdate()->findOrFail($validated['property_id']);

            if ($newProperty->id !== $oldProperty->id && $newProperty->status === 'sold') {
                abort(422, 'Target property is already sold.');
            }

            if ($newProperty->id !== $oldProperty->id && $validated['status'] === 'active' && $newProperty->status === 'reserved') {
                abort(422, 'Target property is already reserved.');
            }

            $reservation->update($validated);

            if ($oldProperty->id !== $newProperty->id && $oldProperty->status === 'reserved') {
                $oldProperty->update(['status' => 'available']);
            }

            if ($validated['status'] === 'active') {
                $newProperty->update(['status' => 'reserved']);
            } elseif ($validated['status'] === 'completed') {
                $newProperty->update(['status' => 'sold']);
            } else {
                $newProperty->update(['status' => 'available']);
            }
        });

        return redirect()->route('reservations.index')->with('success', 'Reservation updated successfully.');
    }

    public function destroy(Reservation $reservation)
    {
        DB::transaction(function () use ($reservation): void {
            $property = Property::lockForUpdate()->findOrFail($reservation->property_id);
            $status = $reservation->status;
            $reservation->delete();

            if (in_array($status, ['active', 'cancelled', 'skipped'], true) && $property->status !== 'sold') {
                $property->update(['status' => 'available']);
            }
        });

        return redirect()->route('reservations.index')->with('success', 'Reservation deleted successfully.');
    }
}
