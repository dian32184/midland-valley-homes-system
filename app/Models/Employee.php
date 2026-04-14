<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'email',
        'phone',
        'position',
        'employment_type',
        'salary_type',
        'daily_rate',
        'monthly_salary',
        'hire_date',
        'is_active',
        'notes',
    ];

    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }

    public function benefits()
    {
        return $this->hasMany(Benefit::class);
    }
}
