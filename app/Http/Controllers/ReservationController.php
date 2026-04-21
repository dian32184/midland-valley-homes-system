<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Property;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Reservation::query()
            ->with(['customer', 'property']);

        $search = trim((string) request('q', ''));
        if ($search !== '') {
            $query->whereHas('customer', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })->orWhereHas('property', function ($q) use ($search) {
                $q->where('block_number', 'like', "%{$search}%")
                    ->orWhere('lot_number', 'like', "%{$search}%")
                    ->orWhere('house_type', 'like', "%{$search}%");
            });
        }

        $status = request('status');
        if (is_string($status) && $status !== '' && $status !== 'all') {
            $query->where('status', $status);
        }

        $reservations = $query
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => Reservation::count(),
            'active' => Reservation::where('status', 'active')->count(),
            'completed' => Reservation::where('status', 'completed')->count(),
            'cancelled' => Reservation::where('status', 'cancelled')->count(),
            'skipped' => Reservation::where('status', 'skipped')->count(),
        ];

        return view('reservations.index', compact('reservations', 'search', 'status', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::query()
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        $properties = Property::query()
            ->orderBy('block_number')
            ->orderBy('lot_number')
            ->get();

        return view('reservations.create', [
            'reservation' => new Reservation(),
            'customers' => $customers,
            'properties' => $properties,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate($this->rules());

        $reservation = Reservation::create($data);

        return redirect()
            ->route('reservations.show', $reservation)
            ->with('status', 'Reservation created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation)
    {
        $reservation->load(['customer', 'property']);

        return view('reservations.show', compact('reservation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservation $reservation)
    {
        $customers = Customer::query()
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        $properties = Property::query()
            ->orderBy('block_number')
            ->orderBy('lot_number')
            ->get();

        return view('reservations.edit', compact('reservation', 'customers', 'properties'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reservation $reservation)
    {
        $data = $request->validate($this->rules());

        $reservation->update($data);

        return redirect()
            ->route('reservations.show', $reservation)
            ->with('status', 'Reservation updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        $reservation->delete();

        return redirect()
            ->route('reservations.index')
            ->with('status', 'Reservation deleted.');
    }

    private function rules(): array
    {
        $statuses = ['active', 'completed', 'cancelled', 'skipped'];

        return [
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'property_id' => ['required', 'integer', 'exists:properties,id'],
            'reservation_fee' => ['required', 'numeric', 'min:0'],
            'reserved_on' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after_or_equal:reserved_on'],
            'status' => ['required', Rule::in($statuses)],
            'notes' => ['nullable', 'string'],
        ];
    }
}
