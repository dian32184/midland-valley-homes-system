<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Document;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Document::query()->with(['customer', 'property']);

        $search = trim((string) request('q', ''));
        if ($search !== '') {
            $query->whereHas('customer', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('title', 'like', "%{$search}%");
        }

        $status = request('status');
        if (is_string($status) && $status !== '' && $status !== 'all') {
            $query->where('status', $status);
        }

        $type = request('type');
        if (is_string($type) && $type !== '' && $type !== 'all') {
            $query->where('document_type', $type);
        }

        $documents = $query
            ->orderByDesc('due_date')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => Document::count(),
            'pending' => Document::where('status', 'pending')->count(),
            'processing' => Document::where('status', 'processing')->count(),
            'completed' => Document::where('status', 'completed')->count(),
        ];

        return view('documents.index', compact('documents', 'search', 'status', 'type', 'stats'));
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

        return view('documents.create', [
            'document' => new Document(),
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

        if (($data['status'] ?? null) !== 'completed') {
            $data['completed_at'] = null;
        } elseif (empty($data['completed_at'])) {
            $data['completed_at'] = now()->toDateString();
        }

        $document = Document::create($data);

        return redirect()
            ->route('documents.show', $document)
            ->with('status', 'Document created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document)
    {
        $document->load(['customer', 'property']);

        return view('documents.show', compact('document'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $document)
    {
        $customers = Customer::query()
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        $properties = Property::query()
            ->orderBy('block_number')
            ->orderBy('lot_number')
            ->get();

        return view('documents.edit', compact('document', 'customers', 'properties'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Document $document)
    {
        $data = $request->validate($this->rules());

        if (($data['status'] ?? null) !== 'completed') {
            $data['completed_at'] = null;
        } elseif (empty($data['completed_at'])) {
            $data['completed_at'] = now()->toDateString();
        }

        $document->update($data);

        return redirect()
            ->route('documents.show', $document)
            ->with('status', 'Document updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        $document->delete();

        return redirect()
            ->route('documents.index')
            ->with('status', 'Document deleted.');
    }

    private function rules(): array
    {
        $types = ['contract_to_sell', 'deed_of_absolute_sale', 'bir_related', 'other'];
        $statuses = ['pending', 'processing', 'completed'];

        return [
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'property_id' => ['nullable', 'integer', 'exists:properties,id'],
            'document_type' => ['required', Rule::in($types)],
            'title' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in($statuses)],
            'due_date' => ['nullable', 'date'],
            'completed_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
