<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Dynamic Dashboard Redirect based on Role after Login
Route::get('/home', function () {
    $role = auth()->user()->role?->slug ?? 'guest';
    
    if (in_array($role, ['admin', 'manager'])) {
        return redirect()->route('reports.index'); // The main dashboard for hotel management
    }
    
    if ($role === 'staff') {
        return redirect()->route('hotels.index'); // Staff landing page
    }
    
    return redirect()->route('reservations.index'); // The landing page for guests
})->middleware('auth')->name('home');

Route::middleware(['auth'])->group(function () {
    
    // Admin, Manager & Staff Access
    Route::middleware(['role:admin,manager,staff'])->group(function () {
        Route::resource('hotels', \App\Http\Controllers\HotelController::class);
        Route::resource('rooms', \App\Http\Controllers\RoomController::class);
        Route::resource('customers', \App\Http\Controllers\CustomerController::class)->only(['index', 'show']);

        // Billings Central
        Route::get('billing', [\App\Http\Controllers\BillingController::class, 'index'])->name('billing.index');
        Route::patch('billing/{reservation}', [\App\Http\Controllers\BillingController::class, 'updateStatus'])->name('billing.update');
    });

    // Manager & Admin Access (Staff Management & Reports)
    Route::middleware(['role:admin,manager'])->group(function () {
        // Report Dashboard Nexus
        Route::get('reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/export/custom', [\App\Http\Controllers\ReportController::class, 'exportCustom'])->name('reports.export.custom');
        
        // Staff Management Module
        Route::resource('staff', \App\Http\Controllers\StaffController::class);
    });


        
        // Absolute Admin Governance Engine (Users)
        Route::middleware(['role:admin'])->group(function () {

            // User Master Array
            Route::resource('users', \App\Http\Controllers\UserController::class)->except(['show']);
            Route::post('users/{user}/force-password', [\App\Http\Controllers\UserController::class, 'forcePasswordReset'])->name('users.password.force');
        });

    // Reservations (Everyone can see calendar and export, but maybe we restrict export to Admin later. Standard for now)
    Route::get('reservations/calendar', [\App\Http\Controllers\ReservationController::class, 'calendar'])->name('reservations.calendar');
    Route::get('reservations/calendar-events', [\App\Http\Controllers\ReservationController::class, 'calendarEvents'])->name('reservations.api.events');
    Route::get('reservations/{reservation}/invoice', [\App\Http\Controllers\ReservationController::class, 'downloadInvoice'])->name('reservations.invoice');
    Route::get('reservations/export', [\App\Http\Controllers\ReservationController::class, 'exportReport'])->name('reservations.export');
    Route::resource('reservations', \App\Http\Controllers\ReservationController::class);
});
