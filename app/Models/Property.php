<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'block_number',
        'lot_number',
        'house_type',
        'price',
        'lot_size',
        'floor_area',
        'status',
        'description',
        'available_at',
    ];

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

    public function titleTransfers()
    {
        return $this->hasMany(TitleTransfer::class);
    }

    public function constructionProject()
    {
        return $this->hasOne(ConstructionProject::class);
    }
}
