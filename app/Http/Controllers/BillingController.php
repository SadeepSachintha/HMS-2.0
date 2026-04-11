<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with(['user', 'room.hotel'])->orderBy('created_at', 'desc')->get();

        $totalRevenue = $reservations->where('payment_status', 'paid')->sum('total_price');
        $pendingRevenue = $reservations->whereIn('payment_status', ['unpaid', 'partial'])->sum('total_price');

        return view('billing.index', compact('reservations', 'totalRevenue', 'pendingRevenue'));
    }

    public function updateStatus(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'payment_status' => 'required|in:unpaid,partial,paid,refunded',
        ]);

        $reservation->update(['payment_status' => $validated['payment_status']]);

        return back()->with('success', 'Payment status updated successfully.');
    }
}
