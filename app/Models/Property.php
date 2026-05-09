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
        'street_name',
        'subdivision',
        'barangay',
        'city',
        'province',
        'zip_code',
        'house_model',
        'house_type',
        'lot_type',
        'price',
        'lot_size',
        'floor_area',
        'status',
        'is_custom_request',
        'custom_requirements',
        'requested_bedrooms',
        'requested_bathrooms',
        'preferred_finish',
        'estimation_status',
        'estimated_budget',
        'estimated_timeline_months',
        'engineer_notes',
        'description',
        'available_at',
    ];

    protected $casts = [
        'is_custom_request' => 'boolean',
        'available_at'      => 'date',
    ];

    // ── Prices ────────────────────────────────────────────────
    const PRICE_REGULAR     = 1500000;
    const PRICE_CORNER_LOT  = 1650000;

    // ── Streets ───────────────────────────────────────────────
    const STREETS = [
        'Sampaguita St.',
        'Rosal St.',
        'Ilang-Ilang St.',
        'Dahlia St.',
        'Orchid St.',
    ];

    // ── Display name  e.g. "Ruby (Corner Lot) – Blk 1 Lot 3, Sampaguita St." ──
    public function getDisplayNameAttribute(): string
    {
        $model    = ucfirst($this->house_model);
        $lotType  = $this->lot_type === 'corner_lot' ? ' (Corner Lot)' : '';
        $street   = $this->street_name ? ', ' . $this->street_name : '';

        return "{$model}{$lotType} – Blk {$this->block_number} Lot {$this->lot_number}{$street}";
    }

    // ── Relationships ─────────────────────────────────────────
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