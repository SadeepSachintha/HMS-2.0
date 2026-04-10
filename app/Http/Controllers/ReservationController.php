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
        $query = Reservation::with(['room.hotel', 'user'])->latest();
        
        $user = auth()->user();
        if ($user && $user->role && $user->role->slug === 'guest') {
            $query->where('user_id', $user->id);
        }

        $reservations = $query->get();
        return view('reservations.index', compact('reservations'));
    }

    public function calendar()
    {
        return view('reservations.calendar');
    }

    public function calendarEvents(Request $request)
    {
        $query = Reservation::with(['room.hotel', 'user']);
        
        $user = auth()->user();
        if ($user && $user->role && $user->role->slug === 'guest') {
            $query->where('user_id', $user->id);
        }

        $reservations = $query->get();
        
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
        $user = auth()->user();
        if ($user && $user->role && $user->role->slug === 'guest' && $reservation->user_id !== $user->id) {
            abort(403, 'Unauthorized. This reservation does not belong to you.');
        }

        $reservation->load(['room.hotel', 'user']);
        return view('reservations.show', compact('reservation'));
    }

    public function downloadInvoice(Reservation $reservation)
    {
        $user = auth()->user();
        if ($user && $user->role && $user->role->slug === 'guest' && $reservation->user_id !== $user->id) {
            abort(403, 'Unauthorized. This reservation does not belong to you.');
        }

        $reservation->load(['room.hotel', 'user']);
        
        $pdf = Pdf::loadView('reservations.invoice', compact('reservation'));
        return $pdf->download('invoice-' . $reservation->id . '.pdf');
    }

    public function exportReport()
    {
        $user = auth()->user();
        if ($user && $user->role && $user->role->slug === 'guest') {
            abort(403, 'Guests are not permitted to export global reports.');
        }

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\ReservationsExport, 'reservations-report.xlsx');
    }

    public function create()
    {
        $rooms = Room::where('status', 'available')->get();
        return view('reservations.create', compact('rooms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
            'special_requests' => 'nullable|string'
        ]);

        $room = Room::findOrFail($validated['room_id']);
        $days = \Carbon\Carbon::parse($validated['check_in_date'])->diffInDays(\Carbon\Carbon::parse($validated['check_out_date']));
        $totalPrice = $room->price_per_night * max($days, 1);

        $reservation = Reservation::create([
            'user_id' => auth()->id() ?? 1, // fallback to 1 if testing without auth
            'room_id' => $validated['room_id'],
            'check_in_date' => $validated['check_in_date'],
            'check_out_date' => $validated['check_out_date'],
            'total_price' => $totalPrice,
            'status' => 'confirmed',
            'special_requests' => $validated['special_requests'],
            'payment_status' => 'unpaid'
        ]);

        // Broadcast Real-time WebSocket Event!
        event(new \App\Events\NewReservationCreated($reservation));

        return redirect()->route('reservations.index')->with('success', 'Reservation confirmed successfully! Admin notified in real-time.');
    }
}
