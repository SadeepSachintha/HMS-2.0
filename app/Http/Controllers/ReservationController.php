<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReservationController extends Controller
{
    public function index()
    {
        return view('reservations.calendar');
    }

    public function calendarEvents(Request $request)
    {
        $reservations = Reservation::with(['room.hotel', 'user'])->get();
        
        $events = [];
        foreach ($reservations as $res) {
            
            // Color coding based on status
            $color = '#3788d8'; // default primary
            if ($res->status == 'confirmed') $color = '#10B981'; // success
            if ($res->status == 'cancelled') $color = '#EF4444'; // danger
            if ($res->status == 'completed') $color = '#64748b'; // slate

            $events[] = [
                'id' => $res->id,
                'title' => 'Room ' . ($res->room->room_number ?? 'N/A') . ' - ' . ($res->user->name ?? 'Guest'),
                'start' => $res->check_in_date,
                'end' => \Carbon\Carbon::parse($res->check_out_date)->addDay()->format('Y-m-d'), // FullCalendar needs +1 day for inclusive end
                'color' => $color,
                'url' => route('reservations.show', $res->id),
                'extendedProps' => [
                    'hotel' => $res->room->hotel->name ?? 'Unknown',
                    'status' => ucfirst($res->status)
                ]
            ];
        }

        return response()->json($events);
    }

    public function show(Reservation $reservation)
    {
        $reservation->load(['room.hotel', 'user']);
        return view('reservations.show', compact('reservation'));
    }

    public function downloadInvoice(Reservation $reservation)
    {
        $reservation->load(['room.hotel', 'user']);
        
        $pdf = Pdf::loadView('reservations.invoice', compact('reservation'));
        return $pdf->download('invoice-' . $reservation->id . '.pdf');
    }

    public function exportReport()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\ReservationsExport, 'reservations-report.xlsx');
    }
}
