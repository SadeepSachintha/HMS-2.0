<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    
    // Admin & Staff Only Access
    Route::middleware(['role:admin,staff'])->group(function () {
        Route::resource('hotels', \App\Http\Controllers\HotelController::class);
        Route::resource('rooms', \App\Http\Controllers\RoomController::class);
        Route::resource('staff', \App\Http\Controllers\StaffController::class);
        Route::resource('customers', \App\Http\Controllers\CustomerController::class)->only(['index', 'show']);
        
        // Report Dashboard Nexus
        Route::get('reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
        
        // Absolute Admin Governance Engine (Billing, Audits, Users)
        Route::middleware(['role:admin'])->group(function () {
            // Billings Central
            Route::get('billing', [\App\Http\Controllers\BillingController::class, 'index'])->name('billing.index');
            Route::patch('billing/{reservation}', [\App\Http\Controllers\BillingController::class, 'updateStatus'])->name('billing.update');

            // User Master Array
            Route::resource('users', \App\Http\Controllers\UserController::class)->except(['show']);
            Route::post('users/{user}/force-password', [\App\Http\Controllers\UserController::class, 'forcePasswordReset'])->name('users.password.force');
        });
    });

    // Reservations (Everyone can see calendar and export, but maybe we restrict export to Admin later. Standard for now)
    Route::get('reservations/calendar', [\App\Http\Controllers\ReservationController::class, 'calendar'])->name('reservations.calendar');
    Route::get('reservations/calendar-events', [\App\Http\Controllers\ReservationController::class, 'calendarEvents'])->name('reservations.api.events');
    Route::get('reservations/{reservation}/invoice', [\App\Http\Controllers\ReservationController::class, 'downloadInvoice'])->name('reservations.invoice');
    Route::get('reservations/export', [\App\Http\Controllers\ReservationController::class, 'exportReport'])->name('reservations.export');
    Route::resource('reservations', \App\Http\Controllers\ReservationController::class);
});
