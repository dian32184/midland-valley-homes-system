<?php

use App\Http\Controllers\AdminUserRoleController;
use App\Http\Controllers\AttendanceRecordController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\BenefitController;
use App\Http\Controllers\ConstructionProjectController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerLoanController;
use App\Http\Controllers\CustomerNotificationController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\TitleTransferController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified', 'audit'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        $role = $user->role ?? 'marketing';
        $email = strtolower((string) ($user->email ?? ''));

        if ($role === 'documentation' || str_contains($email, 'docs@') || str_contains($email, 'documentation@')) {
            return redirect()->route('documentationdashboard');
        }

        if ($role === 'manager' || str_contains($email, 'manager@')) {
            return redirect()->route('managerdashboard');
        }

        if ($role === 'admin' || str_contains($email, 'admin@')) {
            return redirect()->route('admindashboard');
        }

        return redirect()->route('marketingdashboard');
    })->name('dashboard');

    Route::get('/managerdashboard', function () {
        return view('managerdashboard');
    })->middleware('role:manager')->name('managerdashboard');

    Route::get('/marketingdashboard', function () {
        return view('marketingdashboard');
    })->middleware('role:marketing')->name('marketingdashboard');

    Route::get('/admindashboard', function () {
        return view('admindashboard');
    })->middleware('role:admin')->name('admindashboard');

    Route::get('/documentationdashboard', function () {
        return view('documentationdashboard');
    })->middleware('role:documentation')->name('documentationdashboard');

    Route::middleware('role:admin,manager,marketing,documentation')->group(function () {
        Route::resource('customers', CustomerController::class);
        Route::resource('properties', PropertyController::class);
    });

    Route::middleware('role:marketing')->group(function () {
        Route::resource('reservations', ReservationController::class);
    });

    Route::middleware('role:admin,manager,marketing')->group(function () {
        Route::resource('payments', PaymentController::class);
        Route::put('customers/{customer}/loans', [CustomerLoanController::class, 'update'])->name('customer-loans.update');
        Route::post('customers/{customer}/notifications', [CustomerNotificationController::class, 'store'])->name('customer-notifications.store');
    });

    Route::middleware('role:manager,documentation')->group(function () {
        Route::resource('documents', DocumentController::class);
        Route::resource('title-transfers', TitleTransferController::class);
    });

    Route::middleware('role:admin,manager,documentation')->group(function () {
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    });

    Route::middleware('role:manager')->group(function () {
    Route::resource('construction-projects', ConstructionProjectController::class);

    
    Route::post('attendance-records/bulk-store', [AttendanceRecordController::class, 'bulkStore'])
        ->name('attendance-records.bulk-store');
    Route::get('attendance-records/day', [AttendanceRecordController::class, 'day'])
        ->name('attendance-records.day');
    Route::put('attendance-records/bulk-update', [AttendanceRecordController::class, 'bulkUpdate'])
        ->name('attendance-records.bulk-update');

    Route::resource('attendance-records', AttendanceRecordController::class);
});

    Route::middleware('role:admin,manager')->group(function () {
        Route::get('payrolls', [PayrollController::class, 'index'])->name('payrolls.index');
    });

    Route::middleware('role:manager')->patch('payrolls/{payroll}', [PayrollController::class, 'update'])->name('payrolls.update');

    Route::middleware('role:admin')->post('payrolls', [PayrollController::class, 'store'])->name('payrolls.store');

    Route::middleware('role:admin,manager')->group(function () {
        Route::resource('employees', EmployeeController::class);
        Route::resource('benefits', BenefitController::class);
    });

    Route::middleware('role:admin')->group(function () {
        Route::get('user-roles', [AdminUserRoleController::class, 'index'])->name('user-roles.index');
        Route::patch('user-roles/{user}', [AdminUserRoleController::class, 'update'])->name('user-roles.update');
        Route::delete('user-roles/{user}', [AdminUserRoleController::class, 'destroy'])->name('user-roles.destroy');
        Route::get('audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
