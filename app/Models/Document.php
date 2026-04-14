<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'property_id',
        'document_type',
        'title',
        'status',
        'due_date',
        'completed_at',
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
}
