<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TitleTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'property_id',
        'status',
        'submitted_to_bir_at',
        'car_issued_at',
        'registered_at',
        'tct_issued_at',
        'remarks',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
