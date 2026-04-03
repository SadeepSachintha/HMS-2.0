<?php

namespace App\Exports;

use App\Models\Reservation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReservationsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Reservation::with(['user', 'room.hotel'])->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Guest Name',
            'Guest Email',
            'Hotel Location',
            'Room Number',
            'Check-In Date',
            'Check-Out Date',
            'Status',
            'Total Price ($)'
        ];
    }

    public function map($reservation): array
    {
        return [
            $reservation->id,
            $reservation->user->name ?? 'N/A',
            $reservation->user->email ?? 'N/A',
            $reservation->room->hotel->name ?? 'N/A',
            $reservation->room->room_number ?? 'N/A',
            $reservation->check_in_date,
            $reservation->check_out_date,
            ucfirst($reservation->status),
            $reservation->total_price,
        ];
    }
}
