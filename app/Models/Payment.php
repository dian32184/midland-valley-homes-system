<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'property_id',
        'reservation_id',
        'payment_type',
        'amount',
        'payment_date',
        'payment_method',
        'notes',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
