<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerLoan extends Model
{
    protected $fillable = [
        'customer_id',
        'property_id',
        'loan_amount',
        'status',
        'approved_at',
        'paid_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'loan_amount' => 'decimal:2',
            'approved_at' => 'date',
            'paid_at' => 'date',
        ];
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
