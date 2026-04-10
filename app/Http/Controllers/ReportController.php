<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        // 1. Calculate Occupancy Metrics (Currently Occupied Rooms / Total Active Rooms)
        $totalRooms = Room::count();
        
        $currentlyOccupiedRooms = Reservation::where('status', 'confirmed')
            ->where('check_in_date', '<=', Carbon::now())
            ->where('check_out_date', '>=', Carbon::now())
            ->count();

        $occupancyRate = $totalRooms > 0 ? round(($currentlyOccupiedRooms / $totalRooms) * 100, 1) : 0;

        // 2. Calculate Revenue Boundaries (Trailing 30 Days)
        $thirtyDaysAgo = Carbon::now()->subDays(30);
        
        $trailingRevenue = Reservation::where('payment_status', 'paid')
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->sum('total_price');

        // 3. Extrapolate Upcoming Bookings (Next 7 Days Pipeline)
        $upcomingReservations = Reservation::where('status', 'confirmed')
            ->whereBetween('check_in_date', [Carbon::now(), Carbon::now()->addDays(7)])
            ->count();

        // 4. Lifetime Structural Analytics
        $lifetimeRevenue = Reservation::where('payment_status', 'paid')->sum('total_price');
        $lifetimeBookings = Reservation::count();

        return view('reports.index', compact(
            'occupancyRate', 
            'currentlyOccupiedRooms', 
            'totalRooms', 
            'trailingRevenue', 
            'upcomingReservations',
            'lifetimeRevenue',
            'lifetimeBookings'
        ));
    }

    public function exportCustom(Request $request)
    {
        $type = $request->input('type', 'reservations');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($type === 'occupancy') {
            return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\OccupancyExport(), 'occupancy-report.xlsx');
        } elseif ($type === 'revenue') {
            return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\RevenueExport($startDate, $endDate), "revenue-report-{$startDate}-to-{$endDate}.xlsx");
        } else {
            return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\ReservationsExport($startDate, $endDate), "reservations-report-{$startDate}-to-{$endDate}.xlsx");
        }
    }
}
