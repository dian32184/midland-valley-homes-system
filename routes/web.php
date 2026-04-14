<?php

use App\Http\Controllers\BenefitController;
use App\Http\Controllers\ConstructionProjectController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\TitleTransferController;
use App\Http\Controllers\AttendanceRecordController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/managerdashboard', function () {
        return view('managerdashboard');
    })->name('managerdashboard');

    Route::get('/marketingdashboard', function () {
        return view('marketingdashboard');
    })->name('marketingdashboard');

    Route::get('/admindashboard', function () {
        return view('admindashboard');
    })->name('admindashboard');

    Route::get('/documentationdashboard', function () {
        return view('documentationdashboard');
    })->name('documentationdashboard');

    Route::resource('customers', CustomerController::class);
    Route::resource('properties', PropertyController::class);
    Route::resource('reservations', ReservationController::class);
    Route::resource('payments', PaymentController::class);
    Route::resource('documents', DocumentController::class);
    Route::resource('title-transfers', TitleTransferController::class);
    Route::resource('construction-projects', ConstructionProjectController::class);
    Route::resource('employees', EmployeeController::class);
    Route::resource('attendance-records', AttendanceRecordController::class);
    Route::resource('payrolls', PayrollController::class);
    Route::resource('benefits', BenefitController::class);
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
