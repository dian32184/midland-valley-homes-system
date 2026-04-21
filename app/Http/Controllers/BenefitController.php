<?php

namespace App\Http\Controllers;

use App\Models\Benefit;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BenefitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Benefit::query()->with('employee');

        $search = trim((string) request('q', ''));
        if ($search !== '') {
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('membership_number', 'like', "%{$search}%");
        }

        $status = request('status');
        if (is_string($status) && $status !== '' && $status !== 'all') {
            $query->where('status', $status);
        }

        $type = request('type');
        if (is_string($type) && $type !== '' && $type !== 'all') {
            $query->where('benefit_type', $type);
        }

        $benefits = $query
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => Benefit::count(),
            'pending' => Benefit::where('status', 'pending')->count(),
            'active' => Benefit::where('status', 'active')->count(),
            'inactive' => Benefit::where('status', 'inactive')->count(),
        ];

        return view('benefits.index', compact('benefits', 'search', 'status', 'type', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::query()
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        return view('benefits.create', [
            'benefit' => new Benefit(),
            'employees' => $employees,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate($this->rules());

        $benefit = Benefit::create($data);

        return redirect()
            ->route('benefits.show', $benefit)
            ->with('status', 'Benefit created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Benefit $benefit)
    {
        $benefit->load('employee');

        return view('benefits.show', compact('benefit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Benefit $benefit)
    {
        $employees = Employee::query()
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        return view('benefits.edit', compact('benefit', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Benefit $benefit)
    {
        $data = $request->validate($this->rules());

        $benefit->update($data);

        return redirect()
            ->route('benefits.show', $benefit)
            ->with('status', 'Benefit updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Benefit $benefit)
    {
        $benefit->delete();

        return redirect()
            ->route('benefits.index')
            ->with('status', 'Benefit deleted.');
    }

    private function rules(): array
    {
        $types = ['sss', 'pagibig', 'philhealth', 'other'];
        $statuses = ['pending', 'active', 'inactive'];

        return [
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
            'benefit_type' => ['required', Rule::in($types)],
            'membership_number' => ['nullable', 'string', 'max:255'],
            'start_date' => ['nullable', 'date'],
            'eligibility_date' => ['nullable', 'date'],
            'status' => ['required', Rule::in($statuses)],
            'notes' => ['nullable', 'string'],
        ];
    }
}
