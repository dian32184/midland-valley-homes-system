<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Document;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::with(['customer', 'property'])->orderByDesc('created_at')->paginate(10);
        $incompleteCustomers = Customer::with('selectedProperty')
            ->orderBy('last_name')
            ->get()
            ->filter(fn (Customer $customer) => count($customer->missingChecklistItems()) > 0)
            ->values();

        return view('documents.index', compact('documents', 'incompleteCustomers'));
    }

    public function create()
    {
        $customers = Customer::orderBy('last_name')->get();
        $properties = Property::orderBy('block_number')->get();

        return view('documents.create', compact('customers', 'properties'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'property_id' => ['nullable', 'exists:properties,id'],
            'document_type' => ['required', Rule::in(['contract_to_sell', 'deed_of_absolute_sale', 'bir_related', 'other'])],
            'title' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['pending', 'processing', 'completed'])],
            'due_date' => ['nullable', 'date'],
            'completed_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        Document::create($validated);

        return redirect()->route('documents.index')->with('success', 'Document tracked successfully.');
    }

    public function show(Document $document)
    {
        return redirect()->route('documents.edit', $document);
    }

    public function edit(Document $document)
    {
        $customers = Customer::orderBy('last_name')->get();
        $properties = Property::orderBy('block_number')->get();

        return view('documents.edit', compact('document', 'customers', 'properties'));
    }

    public function update(Request $request, Document $document)
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'property_id' => ['nullable', 'exists:properties,id'],
            'document_type' => ['required', Rule::in(['contract_to_sell', 'deed_of_absolute_sale', 'bir_related', 'other'])],
            'title' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['pending', 'processing', 'completed'])],
            'due_date' => ['nullable', 'date'],
            'completed_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        $document->update($validated);

        return redirect()->route('documents.index')->with('success', 'Document updated successfully.');
    }

    public function destroy(Document $document)
    {
        $document->delete();

        return redirect()->route('documents.index')->with('success', 'Document deleted successfully.');
    }
}
