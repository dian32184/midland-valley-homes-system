<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerLoan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomerLoanController extends Controller
{
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'loan_amount' => ['required', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(['pending', 'approved', 'paid', 'cancelled'])],
            'notes' => ['nullable', 'string'],
            'approved_at' => ['nullable', 'date'],
            'paid_at' => ['nullable', 'date'],
        ]);

        $loan = $customer->loans()->firstOrCreate(
            ['property_id' => $customer->selected_property_id]
        );

        $loan->update($validated);

        return back()->with('success', 'Customer loan details updated successfully.');
    }
}
