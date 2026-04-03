@extends('layouts.app')

@section('title', '| Customer Profile')

@section('content')
<div class="row justify-content-center mb-5">
    <div class="col-md-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-white fw-bold mb-0">Guest Diagnostic Profile</h2>
            <a href="{{ route('customers.index') }}" class="btn btn-outline-light rounded-pill px-4 hover-lift">
                <i class="bi bi-arrow-left me-2"></i> Intelligence Matrix
            </a>
        </div>

        <div class="card glass-panel border-0 mb-4 position-relative overflow-hidden">
            <div class="position-absolute top-0 start-0 w-100" style="height: 6px; background: linear-gradient(90deg, #10B981, #06B6D4);"></div>
            <div class="card-body p-4 d-flex align-items-center">
                <div class="bg-dark bg-opacity-50 rounded-circle me-4 d-flex justify-content-center align-items-center" style="width:80px; height:80px;">
                    <i class="bi bi-person-circle fs-1 text-success"></i>
                </div>
                <div>
                    <h3 class="text-white fw-bold mb-1">{{ $customer->name }}</h3>
                    <div class="d-flex text-muted gap-3">
                        <span><i class="bi bi-envelope text-info me-1"></i> {{ $customer->email }}</span>
                        <span><i class="bi bi-telephone text-info me-1"></i> {{ $customer->phone ?? 'Unlisted' }}</span>
                        <span><i class="bi bi-calendar-event text-info me-1"></i> Registered: {{ \Carbon\Carbon::parse($customer->created_at)->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <h4 class="text-info fw-bold mb-3 mt-4"><i class="bi bi-journal-bookmark me-2"></i> Booking Chronology</h4>
        <div class="card glass-panel border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-hover table-borderless text-white mb-0 align-middle">
                        <thead class="border-bottom border-light border-opacity-10">
                            <tr>
                                <th class="ps-4 py-3 text-uppercase text-secondary small fw-bold">ID</th>
                                <th class="py-3 text-uppercase text-secondary small fw-bold">Location</th>
                                <th class="py-3 text-uppercase text-secondary small fw-bold">Check In/Out</th>
                                <th class="py-3 text-uppercase text-secondary small fw-bold text-center">Price</th>
                                <th class="pe-4 py-3 text-uppercase text-secondary small fw-bold text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customer->reservations as $res)
                            <tr>
                                <td class="ps-4 py-3 text-muted">RES-{{ str_pad($res->id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td class="py-3">
                                    <div class="text-white">{{ $res->room->hotel->name ?? 'Unknown' }}</div>
                                    <div class="small text-info">Room #{{ $res->room->room_number ?? 'N/A' }}</div>
                                </td>
                                <td class="py-3">
                                    <div class="small"><i class="bi bi-arrow-down-right text-success"></i> {{ \Carbon\Carbon::parse($res->check_in_date)->format('M d, Y') }}</div>
                                    <div class="small"><i class="bi bi-arrow-up-right text-danger"></i> {{ \Carbon\Carbon::parse($res->check_out_date)->format('M d, Y') }}</div>
                                </td>
                                <td class="py-3 text-center text-warning fw-bold">
                                    ${{ number_format($res->total_price, 2) }}
                                </td>
                                <td class="pe-4 py-3 text-end">
                                    <a href="{{ route('reservations.show', $res->id) }}" class="btn btn-sm btn-outline-info rounded-pill px-3">View</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-4 text-center text-muted">No historical bookings linked to this identity.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
