<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('hotels', \App\Http\Controllers\HotelController::class);
    Route::resource('rooms', \App\Http\Controllers\RoomController::class);
    
    // Reservations calendar and custom actions
    Route::get('reservations/calendar', [\App\Http\Controllers\ReservationController::class, 'calendar'])->name('reservations.calendar');
    Route::get('reservations/calendar-events', [\App\Http\Controllers\ReservationController::class, 'calendarEvents'])->name('reservations.api.events');
    Route::get('reservations/{reservation}/invoice', [\App\Http\Controllers\ReservationController::class, 'downloadInvoice'])->name('reservations.invoice');
    Route::get('reservations/export', [\App\Http\Controllers\ReservationController::class, 'exportReport'])->name('reservations.export');
    Route::resource('reservations', \App\Http\Controllers\ReservationController::class);

    // Billings & Financials Engine
    Route::get('billing', [\App\Http\Controllers\BillingController::class, 'index'])->name('billing.index');
    Route::patch('billing/{reservation}', [\App\Http\Controllers\BillingController::class, 'updateStatus'])->name('billing.update');
});
