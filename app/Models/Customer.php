<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
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
        'address',
        'civil_status',
        'citizenship',
        'tin_number',
        'sss_gsis_number',
        'pagibig_mid_number',
        'spouse_name',
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
        'status',
        'selected_property_id',
        'project_name',
        'contract_price',
        'reservation_fee_amount',
        'downpayment_amount',
        'preferred_financing_type',
        'monthly_amortization_estimate',
        'affordability_notes',
        'qualification_notes',
        'documentation_notes',
        'manager_notes',
        'valid_id_status',
        'proof_of_billing_status',
        'proof_of_income_status',
        'birth_certificate_status',
        'marriage_certificate_status',
        'reservation_form_status',
        'financing_documents_status',
        'profile_notes',
        'date_of_birth',
        'contact_person_name',
        'contact_person_phone',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'monthly_income' => 'decimal:2',
            'spouse_monthly_income' => 'decimal:2',
            'savings_amount' => 'decimal:2',
            'contract_price' => 'decimal:2',
            'reservation_fee_amount' => 'decimal:2',
            'downpayment_amount' => 'decimal:2',
            'monthly_amortization_estimate' => 'decimal:2',
            'is_pagibig_member' => 'boolean',
            'has_required_pagibig_contributions' => 'boolean',
            'has_outstanding_debts' => 'boolean',
            'has_downpayment_capacity' => 'boolean',
            'has_stable_income' => 'boolean',
        ];
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function selectedProperty()
    {
        return $this->belongsTo(Property::class, 'selected_property_id');
    }

    public function checklistStatuses(): array
    {
        return [
            'Valid IDs' => $this->valid_id_status,
            'Proof of Billing' => $this->proof_of_billing_status,
            'Proof of Income' => $this->proof_of_income_status,
            'Birth Certificate' => $this->birth_certificate_status,
            'Marriage Certificate' => $this->marriage_certificate_status,
            'Reservation Form' => $this->reservation_form_status,
            'Financing Documents' => $this->financing_documents_status,
        ];
    }

    public function missingChecklistItems(): array
    {
        return collect($this->checklistStatuses())
            ->filter(fn ($status) => $status !== 'approved')
            ->keys()
            ->values()
            ->all();
    }

    public function approvedChecklistCount(): int
    {
        return collect($this->checklistStatuses())
            ->filter(fn ($status) => $status === 'approved')
            ->count();
    }

    public function titleTransfers()
    {
        return $this->hasMany(TitleTransfer::class);
    }

    public function loans()
    {
        return $this->hasMany(CustomerLoan::class);
    }

    public function notifications()
    {
        return $this->hasMany(CustomerNotification::class);
    }

    public function getTotalEquityPaid(): float
    {
        return (float) $this->payments()
            ->whereIn('payment_type', ['downpayment', 'equity'])
            ->sum('amount');
    }

    public function getTotalLoanBalance(): float
    {
        return (float) $this->loans()
            ->where('status', '!=', 'paid')
            ->sum('loan_amount');
    }
}
