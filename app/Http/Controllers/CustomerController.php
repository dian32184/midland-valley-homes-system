<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    private const CUSTOMER_STATUSES = [
        'new_applicant',
        'for_profiling',
        'requirements_incomplete',
        'eligible_for_submission',
        'submitted_to_financing',
        'under_review',
        'approved',
        'declined',
        'pending_compliance',
        'released',
        'moved_in',
    ];

    private const DOCUMENT_STATUSES = [
        'pending',
        'received',
        'approved',
        'for_resubmission',
    ];

    private const EMPLOYMENT_STATUSES = [
        'employed',
        'self_employed',
        'ofw',
        'unemployed',
        'contractual',
        'retired',
    ];

    private const CIVIL_STATUSES = [
        'single',
        'married',
        'widowed',
        'separated',
    ];

    private const FINANCING_TYPES = [
        'pagibig',
        'bank_financing',
        'in_house_financing',
    ];

    public function index()
    {
        $customers = Customer::with('selectedProperty')->orderByDesc('created_at')->paginate(12);
        $properties = Property::orderBy('block_number')->orderBy('lot_number')->get();

        return view('customers.index', compact('customers', 'properties'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $role = $this->role($request);
        if (! in_array($role, ['marketing', 'admin'], true)) {
            return redirect()->route('customers.index')->withErrors([
                'customer' => 'Only marketing can add customer profiles.',
            ]);
        }

        $this->normalizeCustomerRequest($request);
        $validated = $this->validateCustomer($request);
        $payload = $this->buildPayload($validated, $role, true);
        $this->applyMarketingDerivedFields($payload, $validated, $role);

        Customer::create($payload);

        return redirect()->route('customers.index')->with('success', 'Customer added successfully.');
    }

    public function show(Customer $customer)
    {
        return redirect()->route('customers.edit', $customer);
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $role = $this->role($request);
        $this->normalizeCustomerRequest($request);
        $validated = $this->validateCustomer($request, $customer);
        $payload = $this->buildPayload($validated, $role, false);
        $this->applyMarketingDerivedFields($payload, $validated, $role);

        if ($payload === []) {
            return redirect()->route('customers.index')->withErrors([
                'customer' => 'You do not have permission to update this part of the customer record.',
            ]);
        }

        $customer->update($payload);

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        $role = strtolower((string) (auth()->user()->role ?? ''));
        if (! in_array($role, ['marketing', 'admin'], true)) {
            return redirect()->route('customers.index')->withErrors([
                'customer' => 'Only marketing can delete customer records.',
            ]);
        }

        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }

    private function validateCustomer(Request $request, ?Customer $customer = null): array
    {
        return $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:255', 'unique:customers,email,'.($customer?->id ?? 'NULL').',id'],
            'phone' => ['nullable', 'string', 'max:30'],
            'present_address_street' => ['nullable', 'string', 'max:255'],
            'present_address_barangay' => ['nullable', 'string', 'max:150'],
            'present_address_city' => ['nullable', 'string', 'max:150'],
            'present_address_province' => ['nullable', 'string', 'max:150'],
            'present_address_zip' => ['nullable', 'string', 'max:20'],
            'date_of_birth' => ['nullable', 'date'],
            'civil_status' => ['nullable', Rule::in(self::CIVIL_STATUSES)],
            'citizenship' => ['nullable', 'string', 'max:100'],
            'tin_number' => ['nullable', 'string', 'max:50'],
            'sss_gsis_number' => ['nullable', 'string', 'max:50'],
            'pagibig_mid_number' => ['nullable', 'string', 'max:50'],
            'spouse_first_name' => ['nullable', 'string', 'max:100'],
            'spouse_middle_name' => ['nullable', 'string', 'max:100'],
            'spouse_last_name' => ['nullable', 'string', 'max:100'],
            'spouse_employment' => ['nullable', Rule::in(self::EMPLOYMENT_STATUSES)],
            'spouse_monthly_income' => ['nullable', 'numeric', 'min:0'],
            'employment_status' => ['required', Rule::in(self::EMPLOYMENT_STATUSES)],
            'employer_name' => ['nullable', 'string', 'max:150'],
            'job_title' => ['nullable', 'string', 'max:150'],
            'business_name' => ['nullable', 'string', 'max:150'],
            'business_nature' => ['nullable', 'string', 'max:150'],
            'years_employed' => ['nullable', 'integer', 'min:0', 'max:80'],
            'years_in_business' => ['nullable', 'integer', 'min:0', 'max:80'],
            'ofw_employer_name' => ['nullable', 'string', 'max:150'],
            'ofw_country' => ['nullable', 'string', 'max:100'],
            'monthly_income' => ['nullable', 'numeric', 'min:0'],
            'is_pagibig_member' => ['nullable', 'boolean'],
            'has_required_pagibig_contributions' => ['nullable', 'boolean'],
            'has_outstanding_debts' => ['nullable', 'boolean'],
            'savings_amount' => ['nullable', 'numeric', 'min:0'],
            'has_downpayment_capacity' => ['nullable', 'boolean'],
            'has_stable_income' => ['nullable', 'boolean'],
            'status' => ['required', Rule::in(self::CUSTOMER_STATUSES)],
            'selected_property_id' => ['nullable', 'exists:properties,id'],
            'lead_source' => ['nullable', 'string', 'max:50'],
            'project_name' => ['nullable', 'string', 'max:150'],
            'contract_price' => ['nullable', 'numeric', 'min:0'],
            'reservation_fee_amount' => ['nullable', 'numeric', 'min:0'],
            'downpayment_amount' => ['nullable', 'numeric', 'min:0'],
            'preferred_financing_type' => ['nullable', Rule::in(self::FINANCING_TYPES)],
            'monthly_amortization_estimate' => ['nullable', 'numeric', 'min:0'],
            'affordability_notes' => ['nullable', 'string'],
            'qualification_notes' => ['nullable', 'string'],
            'documentation_notes' => ['nullable', 'string'],
            'manager_notes' => ['nullable', 'string'],
            'valid_id_status' => ['nullable', Rule::in(self::DOCUMENT_STATUSES)],
            'proof_of_billing_status' => ['nullable', Rule::in(self::DOCUMENT_STATUSES)],
            'proof_of_income_status' => ['nullable', Rule::in(self::DOCUMENT_STATUSES)],
            'birth_certificate_status' => ['nullable', Rule::in(self::DOCUMENT_STATUSES)],
            'marriage_certificate_status' => ['nullable', Rule::in(self::DOCUMENT_STATUSES)],
            'reservation_form_status' => ['nullable', Rule::in(self::DOCUMENT_STATUSES)],
            'financing_documents_status' => ['nullable', Rule::in(self::DOCUMENT_STATUSES)],
            'profile_notes' => ['nullable', 'string'],
            'contact_person_name' => ['nullable', 'string', 'max:150'],
            'contact_person_phone' => ['nullable', 'string', 'max:30'],
        ]);
    }

    private function buildPayload(array $validated, string $role, bool $isCreate): array
    {
        $payload = $validated;
        $booleanFields = [
            'is_pagibig_member',
            'has_required_pagibig_contributions',
            'has_outstanding_debts',
            'has_downpayment_capacity',
            'has_stable_income',
        ];

        foreach ($booleanFields as $field) {
            if (array_key_exists($field, $validated)) {
                $payload[$field] = (bool) $validated[$field];
            }
        }

        $allowedFields = match ($role) {
            'marketing' => $this->marketingFields(),
            'documentation' => $this->documentationFields(),
            'manager' => $this->managerFields(),
            'admin' => array_keys($validated),
            default => [],
        };

        $payload = array_intersect_key($payload, array_flip($allowedFields));

        if ($role === 'marketing') {
            $allowedStatuses = ['new_applicant', 'for_profiling'];
            $payload['status'] = in_array($validated['status'] ?? 'new_applicant', $allowedStatuses, true)
                ? $validated['status']
                : 'new_applicant';
        } elseif ($role === 'documentation') {
            $allowedStatuses = ['requirements_incomplete', 'eligible_for_submission', 'submitted_to_financing', 'pending_compliance'];
            if (isset($validated['status']) && in_array($validated['status'], $allowedStatuses, true)) {
                $payload['status'] = $validated['status'];
            } else {
                unset($payload['status']);
            }
        } elseif ($role === 'manager') {
            $allowedStatuses = ['under_review', 'approved', 'declined', 'released', 'moved_in'];
            if (isset($validated['status']) && in_array($validated['status'], $allowedStatuses, true)) {
                $payload['status'] = $validated['status'];
            } else {
                unset($payload['status']);
            }
        }

        if ($isCreate) {
            $payload['status'] = $payload['status'] ?? 'new_applicant';
            $payload['project_name'] = $payload['project_name'] ?? 'Midland Valley Homes';
        }

        return $payload;
    }

    private function marketingFields(): array
    {
        return [
            'first_name',
            'last_name',
            'middle_name',
            'email',
            'phone',
            'present_address_street',
            'present_address_barangay',
            'present_address_city',
            'present_address_province',
            'present_address_zip',
            'date_of_birth',
            'civil_status',
            'citizenship',
            'tin_number',
            'sss_gsis_number',
            'pagibig_mid_number',
            'spouse_first_name',
            'spouse_middle_name',
            'spouse_last_name',
            'spouse_employment',
            'spouse_monthly_income',
            'employment_status',
            'employer_name',
            'job_title',
            'business_name',
            'business_nature',
            'years_employed',
            'years_in_business',
            'ofw_employer_name',
            'ofw_country',
            'monthly_income',
            'is_pagibig_member',
            'has_required_pagibig_contributions',
            'has_outstanding_debts',
            'savings_amount',
            'has_downpayment_capacity',
            'has_stable_income',
            'selected_property_id',
            'project_name',
            'contract_price',
            'reservation_fee_amount',
            'downpayment_amount',
            'preferred_financing_type',
            'monthly_amortization_estimate',
            'affordability_notes',
            'profile_notes',
            'contact_person_name',
            'contact_person_phone',
            'lead_source',
            'status',
        ];
    }

    private function documentationFields(): array
    {
        return [
            'valid_id_status',
            'proof_of_billing_status',
            'proof_of_income_status',
            'birth_certificate_status',
            'marriage_certificate_status',
            'reservation_form_status',
            'financing_documents_status',
            'documentation_notes',
            'qualification_notes',
            'status',
        ];
    }

    private function managerFields(): array
    {
        return [
            'qualification_notes',
            'manager_notes',
            'status',
        ];
    }

    private function role(Request $request): string
    {
        return strtolower((string) ($request->user()?->role ?? ''));
    }

    private function normalizeCustomerRequest(Request $request): void
    {
        foreach (['spouse_employment', 'preferred_financing_type', 'civil_status', 'selected_property_id'] as $key) {
            if ($request->input($key) === '') {
                $request->merge([$key => null]);
            }
        }
        if ($request->input('selected_property_id') === 'customized' || $request->input('selected_property_id') === '') {
        $request->merge(['selected_property_id' => null]);
        }
    }

    private function composeFullAddress(array $validated): ?string
    {
        $parts = array_filter(array_map('trim', [
            $validated['present_address_street'] ?? '',
            $validated['present_address_barangay'] ?? '',
            $validated['present_address_city'] ?? '',
            $validated['present_address_province'] ?? '',
            $validated['present_address_zip'] ?? '',
        ]));

        return $parts === [] ? null : implode(', ', $parts);
    }

    private function composeSpouseFullName(array $validated): ?string
    {
        $parts = array_filter(array_map('trim', [
            $validated['spouse_first_name'] ?? '',
            $validated['spouse_middle_name'] ?? '',
            $validated['spouse_last_name'] ?? '',
        ]));

        return $parts === [] ? null : implode(' ', $parts);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @param  array<string, mixed>  $validated
     */
    private function applyMarketingDerivedFields(array &$payload, array $validated, string $role): void
    {
        if (! in_array($role, ['marketing', 'admin'], true)) {
            return;
        }

        $payload['address'] = $this->composeFullAddress($validated);
        $payload['spouse_name'] = $this->composeSpouseFullName($validated);

        $employmentStatus = $validated['employment_status'] ?? '';
        $this->nullOutUnusedEmploymentColumns($payload, is_string($employmentStatus) ? $employmentStatus : '');
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function nullOutUnusedEmploymentColumns(array &$payload, string $employmentStatus): void
    {
        $nullKeys = match ($employmentStatus) {
            'employed', 'contractual' => ['business_name', 'business_nature', 'years_in_business', 'ofw_employer_name', 'ofw_country'],
            'self_employed' => ['employer_name', 'job_title', 'years_employed', 'ofw_employer_name', 'ofw_country'],
            'ofw' => ['employer_name', 'job_title', 'years_employed', 'business_name', 'business_nature', 'years_in_business'],
            default => ['employer_name', 'job_title', 'years_employed', 'business_name', 'business_nature', 'years_in_business', 'ofw_employer_name', 'ofw_country'],
        };

        foreach ($nullKeys as $key) {
            $payload[$key] = null;
        }
    }
}
