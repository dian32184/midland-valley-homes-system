<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Property;
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
        $query = TitleTransfer::query()->with(['customer', 'property']);

        $search = trim((string) request('q', ''));
        if ($search !== '') {
            $query->whereHas('customer', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $status = request('status');
        if (is_string($status) && $status !== '' && $status !== 'all') {
            $query->where('status', $status);
        }

        $transfers = $query
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => TitleTransfer::count(),
            'pending' => TitleTransfer::where('status', 'pending')->count(),
            'submitted_to_bir' => TitleTransfer::where('status', 'submitted_to_bir')->count(),
            'car_issued' => TitleTransfer::where('status', 'car_issued')->count(),
            'registered' => TitleTransfer::where('status', 'registered')->count(),
            'tct_issued' => TitleTransfer::where('status', 'tct_issued')->count(),
            'rejected' => TitleTransfer::where('status', 'rejected')->count(),
        ];

        return view('title-transfers.index', compact('transfers', 'search', 'status', 'stats'));
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

        return view('title-transfers.create', [
            'transfer' => new TitleTransfer(),
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

        $transfer = TitleTransfer::create($data);

        return redirect()
            ->route('title-transfers.show', $transfer)
            ->with('status', 'Title transfer created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TitleTransfer $title_transfer)
    {
        $title_transfer->load(['customer', 'property']);

        return view('title-transfers.show', [
            'transfer' => $title_transfer,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TitleTransfer $title_transfer)
    {
        $customers = Customer::query()
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        $properties = Property::query()
            ->orderBy('block_number')
            ->orderBy('lot_number')
            ->get();

        return view('title-transfers.edit', [
            'transfer' => $title_transfer,
            'customers' => $customers,
            'properties' => $properties,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TitleTransfer $title_transfer)
    {
        $data = $request->validate($this->rules());

        $title_transfer->update($data);

        return redirect()
            ->route('title-transfers.show', $title_transfer)
            ->with('status', 'Title transfer updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TitleTransfer $title_transfer)
    {
        $title_transfer->delete();

        return redirect()
            ->route('title-transfers.index')
            ->with('status', 'Title transfer deleted.');
    }

    private function rules(): array
    {
        $statuses = ['pending', 'submitted_to_bir', 'car_issued', 'registered', 'tct_issued', 'rejected'];

        return [
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'property_id' => ['nullable', 'integer', 'exists:properties,id'],
            'status' => ['required', Rule::in($statuses)],
            'submitted_to_bir_at' => ['nullable', 'date'],
            'car_issued_at' => ['nullable', 'date'],
            'registered_at' => ['nullable', 'date'],
            'tct_issued_at' => ['nullable', 'date'],
            'remarks' => ['nullable', 'string'],
        ];
    }
}
