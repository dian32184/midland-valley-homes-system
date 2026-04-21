<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Payment;
use App\Models\Property;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Payment::query()->with(['customer', 'property', 'reservation']);

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
            })->orWhere('payment_method', 'like', "%{$search}%");
        }

        $type = request('type');
        if (is_string($type) && $type !== '' && $type !== 'all') {
            $query->where('payment_type', $type);
        }

        $payments = $query
            ->orderByDesc('payment_date')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => Payment::count(),
            'total_amount' => (float) Payment::sum('amount'),
            'downpayment' => Payment::where('payment_type', 'downpayment')->count(),
            'installment' => Payment::where('payment_type', 'installment')->count(),
            'equity' => Payment::where('payment_type', 'equity')->count(),
            'reservation_fee' => Payment::where('payment_type', 'reservation_fee')->count(),
            'other' => Payment::where('payment_type', 'other')->count(),
        ];

        return view('payments.index', compact('payments', 'search', 'type', 'stats'));
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

        $reservations = Reservation::query()
            ->with(['customer', 'property'])
            ->orderByDesc('id')
            ->limit(200)
            ->get();

        return view('payments.create', [
            'payment' => new Payment(),
            'customers' => $customers,
            'properties' => $properties,
            'reservations' => $reservations,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate($this->rules());

        $payment = Payment::create($data);

        return redirect()
            ->route('payments.show', $payment)
            ->with('status', 'Payment created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        $payment->load(['customer', 'property', 'reservation']);

        return view('payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        $customers = Customer::query()
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        $properties = Property::query()
            ->orderBy('block_number')
            ->orderBy('lot_number')
            ->get();

        $reservations = Reservation::query()
            ->with(['customer', 'property'])
            ->orderByDesc('id')
            ->limit(200)
            ->get();

        return view('payments.edit', compact('payment', 'customers', 'properties', 'reservations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        $data = $request->validate($this->rules());

        $payment->update($data);

        return redirect()
            ->route('payments.show', $payment)
            ->with('status', 'Payment updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();

        return redirect()
            ->route('payments.index')
            ->with('status', 'Payment deleted.');
    }

    private function rules(): array
    {
        $types = ['downpayment', 'installment', 'equity', 'reservation_fee', 'other'];

        return [
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'property_id' => ['required', 'integer', 'exists:properties,id'],
            'reservation_id' => ['nullable', 'integer', 'exists:reservations,id'],
            'payment_type' => ['required', Rule::in($types)],
            'amount' => ['required', 'numeric', 'min:0'],
            'payment_date' => ['nullable', 'date'],
            'payment_method' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
