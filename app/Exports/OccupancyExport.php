<?php

namespace App\Exports;

use App\Models\Room;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OccupancyExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Room::with('hotel')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Hotel Location',
            'Room Number',
            'Type',
            'Price Per Night ($)',
            'Status',
        ];
    }

    public function map($room): array
    {
        return [
            $room->id,
            $room->hotel->name ?? 'N/A',
            $room->room_number ?? 'N/A',
            ucfirst($room->type),
            $room->price_per_night,
            ucfirst($room->status),
        ];
    }
}
