<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return response()->json([
            'module' => 'reports',
            'message' => 'Reports module is available. Implement report generation logic here.',
        ]);
    }
}
