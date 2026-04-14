<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConstructionProject extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'status',
        'start_date',
        'completion_date',
        'progress_percent',
        'notes',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
