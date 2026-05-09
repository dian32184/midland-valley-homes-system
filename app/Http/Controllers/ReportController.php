<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Payment;
use App\Models\Property;
use App\Models\Reservation;

class ReportController extends Controller
{
    public function index()
    {
        $data = [
            'property_status' => Property::selectRaw('status, COUNT(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status')
                ->toArray(),
            'reservation_status' => Reservation::selectRaw('status, COUNT(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status')
                ->toArray(),
            'document_status' => Document::selectRaw('status, COUNT(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status')
                ->toArray(),
            'total_collections' => (float) Payment::sum('amount'),
            'monthly_collections' => Payment::selectRaw('DATE_FORMAT(payment_date, "%Y-%m") as month, SUM(amount) as total')
                ->whereNotNull('payment_date')
                ->groupBy('month')
                ->orderBy('month')
                ->limit(12)
                ->get(),
        ];

        return view('reports.index', compact('data'));
    }
}
