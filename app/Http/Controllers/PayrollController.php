<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Payroll;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PayrollController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Payroll::query()->with('employee');

        $search = trim((string) request('q', ''));
        if ($search !== '') {
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $status = request('status');
        if (is_string($status) && $status !== '' && $status !== 'all') {
            $query->where('status', $status);
        }

        $payrolls = $query
            ->orderByDesc('period_end_date')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => Payroll::count(),
            'pending' => Payroll::where('status', 'pending')->count(),
            'processed' => Payroll::where('status', 'processed')->count(),
            'paid' => Payroll::where('status', 'paid')->count(),
            'net_total' => (float) Payroll::sum('net_amount'),
        ];

        return view('payrolls.index', compact('payrolls', 'search', 'status', 'stats'));
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

        return view('payrolls.create', [
            'payroll' => new Payroll(),
            'employees' => $employees,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate($this->rules());

        if (($data['status'] ?? null) !== 'paid') {
            $data['paid_at'] = null;
        } elseif (empty($data['paid_at'])) {
            $data['paid_at'] = now()->toDateString();
        }

        $payroll = Payroll::create($data);

        return redirect()
            ->route('payrolls.show', $payroll)
            ->with('status', 'Payroll created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Payroll $payroll)
    {
        $payroll->load('employee');

        return view('payrolls.show', compact('payroll'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payroll $payroll)
    {
        $employees = Employee::query()
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        return view('payrolls.edit', compact('payroll', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payroll $payroll)
    {
        $data = $request->validate($this->rules($payroll));

        if (($data['status'] ?? null) !== 'paid') {
            $data['paid_at'] = null;
        } elseif (empty($data['paid_at'])) {
            $data['paid_at'] = now()->toDateString();
        }

        $payroll->update($data);

        return redirect()
            ->route('payrolls.show', $payroll)
            ->with('status', 'Payroll updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payroll $payroll)
    {
        $payroll->delete();

        return redirect()
            ->route('payrolls.index')
            ->with('status', 'Payroll deleted.');
    }

    private function rules(?Payroll $payroll = null): array
    {
        $types = ['weekly', 'monthly'];
        $statuses = ['pending', 'processed', 'paid'];

        return [
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
            'period_start_date' => ['required', 'date'],
            'period_end_date' => [
                'required',
                'date',
                'after_or_equal:period_start_date',
                Rule::unique('payrolls', 'period_end_date')
                    ->where(fn ($q) => $q
                        ->where('employee_id', request('employee_id'))
                        ->where('period_start_date', request('period_start_date')))
                    ->ignore($payroll?->id),
            ],
            'gross_amount' => ['required', 'numeric', 'min:0'],
            'deductions' => ['required', 'numeric', 'min:0'],
            'net_amount' => ['required', 'numeric', 'min:0'],
            'payroll_type' => ['required', Rule::in($types)],
            'status' => ['required', Rule::in($statuses)],
            'paid_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
