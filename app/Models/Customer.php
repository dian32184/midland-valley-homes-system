<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'email',
        'phone',
        'address',
        'employment_status',
        'employer_name',
        'job_title',
        'monthly_income',
        'status',
        'profile_notes',
        'date_of_birth',
        'contact_person_name',
        'contact_person_phone',
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
}
