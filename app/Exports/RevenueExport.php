<?php

namespace App\Exports;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RevenueExport implements FromQuery, WithHeadings, WithMapping
{
    protected $startDate;

    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * @return Builder
     */
    public function query()
    {
        $query = Reservation::query()->with(['user', 'room.hotel'])->where('payment_status', 'paid');

        if ($this->startDate) {
            $query->whereDate('updated_at', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $query->whereDate('updated_at', '<=', $this->endDate);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Guest Name',
            'Hotel Location',
            'Room Number',
            'Check-In Date',
            'Check-Out Date',
            'Payment Status',
            'Total Revenue ($)',
        ];
    }

    public function map($reservation): array
    {
        return [
            $reservation->id,
            $reservation->user->name ?? 'N/A',
            $reservation->room->hotel->name ?? 'N/A',
            $reservation->room->room_number ?? 'N/A',
            $reservation->check_in_date,
            $reservation->check_out_date,
            ucfirst($reservation->payment_status),
            $reservation->total_price,
        ];
    }
}
