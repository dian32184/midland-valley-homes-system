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
    public function index()
    {
        $customers = Customer::whereHas('payments')
            ->with(['payments.property', 'selectedProperty', 'loans', 'notifications'])
            ->orderByDesc('created_at')
            ->paginate(10);

        $total = Payment::count();
        $collections = Payment::sum('amount');
        $today = Payment::whereDate('payment_date', today())->sum('amount');
        $installments = Payment::where('payment_type', 'installment')->count();

        // Also fetch data needed for the create payment modal
        $allCustomers = Customer::orderBy('last_name')->get();
        $properties = Property::orderBy('block_number')->get();
        $reservations = Reservation::orderByDesc('created_at')->get();

        return view('payments.index', compact('customers', 'total', 'collections', 'today', 'installments', 'allCustomers', 'properties', 'reservations'));
    }

    public function create()
    {
        $customers = Customer::orderBy('last_name')->get();
        $properties = Property::orderBy('block_number')->get();
        $reservations = Reservation::orderByDesc('created_at')->get();

        return view('payments.create', compact('customers', 'properties', 'reservations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'property_id' => ['required', 'exists:properties,id'],
            'reservation_id' => ['nullable', 'exists:reservations,id'],
            'payment_type' => ['required', Rule::in(['downpayment', 'installment', 'equity', 'reservation_fee', 'other'])],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_date' => ['nullable', 'date'],
            'payment_method' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
        ]);

        Payment::create($validated);

        return redirect()->route('payments.index')->with('success', 'Payment recorded successfully.');
    }

    public function show(Payment $payment)
    {
        return redirect()->route('payments.edit', $payment);
    }

    public function edit(Payment $payment)
    {
        $customers = Customer::orderBy('last_name')->get();
        $properties = Property::orderBy('block_number')->get();
        $reservations = Reservation::orderByDesc('created_at')->get();

        return view('payments.edit', compact('payment', 'customers', 'properties', 'reservations'));
    }

    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'property_id' => ['required', 'exists:properties,id'],
            'reservation_id' => ['nullable', 'exists:reservations,id'],
            'payment_type' => ['required', Rule::in(['downpayment', 'installment', 'equity', 'reservation_fee', 'other'])],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_date' => ['nullable', 'date'],
            'payment_method' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
        ]);

        $payment->update($validated);

        return redirect()->route('payments.index')->with('success', 'Payment updated successfully.');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();

        return redirect()->route('payments.index')->with('success', 'Payment deleted successfully.');
    }
}
