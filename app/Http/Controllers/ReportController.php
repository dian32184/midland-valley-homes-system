<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\Benefit;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Payment;
use App\Models\Payroll;
use App\Models\Property;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $metrics = [
            'customers_total' => Customer::count(),
            'properties_total' => Property::count(),
            'reservations_total' => Reservation::count(),
            'payments_total' => Payment::count(),
            'payments_amount_total' => (float) Payment::sum('amount'),
            'employees_total' => Employee::count(),
            'attendance_today' => AttendanceRecord::whereDate('attendance_date', now()->toDateString())->count(),
            'payroll_pending' => Payroll::where('status', 'pending')->count(),
            'payroll_paid' => Payroll::where('status', 'paid')->count(),
            'benefits_active' => Benefit::where('status', 'active')->count(),
        ];

        $collectionsByType = Payment::query()
            ->selectRaw('payment_type, COUNT(*) as total_count, COALESCE(SUM(amount), 0) as total_amount')
            ->groupBy('payment_type')
            ->orderByDesc('total_amount')
            ->get();

        return view('reports.index', compact('metrics', 'collectionsByType'));
    }
}
