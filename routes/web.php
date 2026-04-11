<?php

use App\Http\Controllers\BillingController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\UserController;
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
        Route::resource('hotels', HotelController::class);
        Route::resource('rooms', RoomController::class);
        Route::resource('customers', CustomerController::class)->except(['edit', 'update', 'destroy']);

        // Billings Central
        Route::get('billing', [BillingController::class, 'index'])->name('billing.index');
        Route::patch('billing/{reservation}', [BillingController::class, 'updateStatus'])->name('billing.update');
    });

    // Manager & Admin Access (Staff Management & Reports)
    Route::middleware(['role:admin,manager'])->group(function () {
        // Report Dashboard Nexus
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/export/custom', [ReportController::class, 'exportCustom'])->name('reports.export.custom');

        // Staff Management Module
        Route::resource('staff', StaffController::class);
    });

    // Absolute Admin Governance Engine (Users)
    Route::middleware(['role:admin'])->group(function () {

        // User Master Array
        Route::resource('users', UserController::class)->except(['show']);
        Route::post('users/{user}/force-password', [UserController::class, 'forcePasswordReset'])->name('users.password.force');
    });

    // Reservations (Everyone can see calendar and export, but maybe we restrict export to Admin later. Standard for now)
    Route::get('reservations/calendar', [ReservationController::class, 'calendar'])->name('reservations.calendar');
    Route::get('reservations/calendar-events', [ReservationController::class, 'calendarEvents'])->name('reservations.api.events');
    Route::get('reservations/{reservation}/invoice', [ReservationController::class, 'downloadInvoice'])->name('reservations.invoice');
    Route::get('reservations/export', [ReservationController::class, 'exportReport'])->name('reservations.export');
    Route::get('reservations/check-availability', [ReservationController::class, 'checkAvailability'])->name('reservations.check-availability');
    Route::resource('reservations', ReservationController::class);
});
