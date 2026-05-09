<?php

namespace App\Http\Controllers;

use App\Models\TitleTransfer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TitleTransferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $titleTransfers = TitleTransfer::with(['customer', 'property'])->orderByDesc('created_at')->paginate(12);

        return view('title-transfers.index', compact('titleTransfers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'property_id' => ['nullable', 'exists:properties,id'],
            'status' => ['required', Rule::in(['pending', 'submitted_to_bir', 'car_issued', 'registered', 'tct_issued', 'rejected'])],
            'submitted_to_bir_at' => ['nullable', 'date'],
            'car_issued_at' => ['nullable', 'date'],
            'registered_at' => ['nullable', 'date'],
            'tct_issued_at' => ['nullable', 'date'],
            'remarks' => ['nullable', 'string'],
        ]);

        TitleTransfer::create($validated);

        return redirect()
            ->route('title-transfers.index')
            ->with('status', 'Title transfer record added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
