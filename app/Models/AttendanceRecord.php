<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'attendance_date',
        'time_in',
        'time_out',
        'status',
        'hours_worked',
        'notes',
    ];

    protected $casts = [
        'attendance_date' => 'date:Y-m-d',
        'hours_worked'    => 'float',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}