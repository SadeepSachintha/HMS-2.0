<?php

namespace App\Http\Controllers;

use App\Events\NewReservationCreated;
use App\Exports\ReservationsExport;
use App\Models\Reservation;
use App\Models\Room;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Class ReservationController
 *
 * Handles the core business logic for room bookings, availability checking,
 * resolving calendar overlap logic, and exporting transactional reports.
 */
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
            if ($res->status == 'confirmed') {
                $color = '#10B981';
            } // success
            if ($res->status == 'cancelled') {
                $color = '#EF4444';
            } // danger
            if ($res->status == 'completed') {
                $color = '#64748b';
            } // slate

            $events[] = [
                'id' => $res->id,
                'title' => 'Room '.($res->room->room_number ?? 'N/A').' - '.($res->user->name ?? 'Guest'),
                'start' => $res->check_in_date,
                'end' => Carbon::parse($res->check_out_date)->addDay()->format('Y-m-d'), // FullCalendar needs +1 day for inclusive end
                'color' => $color,
                'url' => route('reservations.show', $res->id),
                'extendedProps' => [
                    'hotel' => $res->room->hotel->name ?? 'Unknown',
                    'status' => ucfirst($res->status),
                ],
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

        return $pdf->download('invoice-'.$reservation->id.'.pdf');
    }

    public function exportReport()
    {
        $user = auth()->user();
        if ($user && $user->role && $user->role->slug === 'guest') {
            abort(403, 'Guests are not permitted to export global reports.');
        }

        return Excel::download(new ReservationsExport, 'reservations-report.xlsx');
    }

    /**
     * Engine to determine currently available rooms by verifying the requested
     * dates against the active booking overlaps in the database via standard SQL bounds.
     *
     * @return JsonResponse
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
        ]);

        $bookedRoomIds = Reservation::whereIn('status', ['confirmed'])
            ->where(function ($q) use ($request) {
                $q->where('check_in_date', '<', $request->check_out_date)
                    ->where('check_out_date', '>', $request->check_in_date);
            })->pluck('room_id');

        $availableRooms = Room::where('status', 'available')
            ->whereNotIn('id', $bookedRoomIds)
            ->get();

        return response()->json(['available_rooms' => $availableRooms]);
    }

    public function create()
    {
        $guests = collect();
        $user = auth()->user();
        if ($user && in_array($user->role->slug ?? '', ['admin', 'manager', 'staff'])) {
            $guests = \App\Models\User::whereHas('role', function ($query) {
                $query->where('slug', 'guest');
            })->latest()->get();
        }

        return view('reservations.create', compact('guests'));
    }

    /**
     * Persist a reservation payload into the database.
     * Implements strict checking constraints against double-booking and
     * triggers web-socket notifications to operational staff via Laravel Events.
     *
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'room_id' => 'required|exists:rooms,id',
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
            'special_requests' => 'nullable|string',
        ]);

        // Check for double-booking (overlapping reservations for the same room)
        $conflict = Reservation::where('room_id', $validated['room_id'])
            ->whereIn('status', ['confirmed'])
            ->where(function ($q) use ($validated) {
                $q->where('check_in_date', '<', $validated['check_out_date'])
                    ->where('check_out_date', '>', $validated['check_in_date']);
            })->first();

        if ($conflict) {
            return back()->withErrors(['room_id' => 'This room is already booked for the selected dates (Conflicts with a reservation from '.Carbon::parse($conflict->check_in_date)->format('M d').' to '.Carbon::parse($conflict->check_out_date)->format('M d').'). Please adjust your dates or choose a different room.'])->withInput();
        }

        $room = Room::findOrFail($validated['room_id']);
        $days = Carbon::parse($validated['check_in_date'])->diffInDays(Carbon::parse($validated['check_out_date']));
        $totalPrice = $room->price_per_night * max($days, 1);

        $user = auth()->user();
        $isStaff = $user && in_array($user->role->slug ?? '', ['admin', 'manager', 'staff']);
        $assignedUserId = ($isStaff && !empty($validated['user_id'])) ? $validated['user_id'] : ($user->id ?? 1);

        $reservation = Reservation::create([
            'user_id' => $assignedUserId,
            'room_id' => $validated['room_id'],
            'check_in_date' => $validated['check_in_date'],
            'check_out_date' => $validated['check_out_date'],
            'total_price' => $totalPrice,
            'status' => 'confirmed',
            'special_requests' => $validated['special_requests'],
            'payment_status' => 'unpaid',
        ]);

        // Broadcast Real-time WebSocket Event!
        event(new NewReservationCreated($reservation));

        return redirect()->route('reservations.index')->with('success', 'Reservation confirmed successfully! Admin notified in real-time.');
    }
}
