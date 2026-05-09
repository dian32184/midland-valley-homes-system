<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'period_start_date',
        'period_end_date',
        'gross_amount',
        'deductions',
        'net_amount',
        'payroll_type',
        'status',
        'paid_at',
        'approved_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'period_start_date' => 'date',
            'period_end_date' => 'date',
            'paid_at' => 'date',
            'approved_at' => 'date',
        ];
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
