@extends('layouts.app')

@section('title', '| Manage Reservations')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h2 class="text-white fw-bold mb-0">Reservations Ledger</h2>
        <p class="text-light opacity-75 mb-0">Traditional list view of all historic and upcoming bookings.</p>
    </div>
    <div class="col-md-6 text-md-end mt-3 mt-md-0 d-flex gap-2 justify-content-md-end">
        @if(auth()->user() && auth()->user()->role?->slug !== 'guest')
        <a href="{{ route('reservations.export') }}" class="btn btn-success fw-bold rounded-pill px-4 hover-lift shadow-sm">
            <i class="bi bi-file-earmark-excel me-2"></i> Export Bookings
        </a>
        @endif
        <a href="{{ route('reservations.create') }}" class="btn btn-warning fw-bold rounded-pill px-4 hover-lift shadow-sm text-dark">
            <i class="bi bi-plus-lg me-2"></i> Reserve Room
        </a>
        <a href="{{ route('reservations.calendar') }}" class="btn btn-primary rounded-pill px-4 hover-lift shadow-sm">
            <i class="bi bi-calendar3 me-2"></i> Calendar View
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show rounded-4 glass-panel border-success" role="alert">
    <strong>Success!</strong> {{ session('success') }}
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card glass-panel border-0 mb-5">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-dark table-hover table-borderless text-white mb-0 align-middle">
                <thead class="border-bottom border-light border-opacity-10">
                    <tr>
                        <th class="ps-4 py-3 text-uppercase text-secondary small fw-bold">Booking ID</th>
                        <th class="py-3 text-uppercase text-secondary small fw-bold">Guest Identity</th>
                        <th class="py-3 text-uppercase text-secondary small fw-bold">Allocation</th>
                        <th class="py-3 text-uppercase text-secondary small fw-bold">Check In/Out</th>
                        <th class="py-3 text-uppercase text-secondary small fw-bold">Status</th>
                        <th class="pe-4 py-3 text-uppercase text-secondary small fw-bold text-end">Options</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations as $res)
                    <tr>
                        <td class="ps-4 py-3">
                            <div class="fw-bold fs-6">RES-{{ str_pad($res->id, 5, '0', STR_PAD_LEFT) }}</div>
                            <div class="small text-muted">${{ number_format($res->total_price, 2) }}</div>
                        </td>
                        <td class="py-3">
                            <div class="fw-bold">{{ $res->user->name ?? 'N/A' }}</div>
                            <div class="small text-muted">{{ $res->user->email ?? '' }}</div>
                        </td>
                        <td class="py-3">
                            <div class="text-info fw-bold">Room #{{ $res->room->room_number ?? 'N/A' }}</div>
                            <div class="small text-muted"><i class="bi bi-building"></i> {{ $res->room->hotel->name ?? 'Unknown' }}</div>
                        </td>
                        <td class="py-3">
                            <div><i class="bi bi-arrow-down-right text-success"></i> {{ \Carbon\Carbon::parse($res->check_in_date)->format('M d') }}</div>
                            <div><i class="bi bi-arrow-up-right text-danger"></i> {{ \Carbon\Carbon::parse($res->check_out_date)->format('M d') }}</div>
                        </td>
                        <td class="py-3">
                            @if($res->status === 'confirmed')
                                <span class="badge bg-success rounded-pill px-3 py-2">Confirmed</span>
                            @elseif($res->status === 'cancelled')
                                <span class="badge bg-danger rounded-pill px-3 py-2">Cancelled</span>
                            @else
                                <span class="badge bg-secondary rounded-pill px-3 py-2">{{ ucfirst($res->status) }}</span>
                            @endif
                        </td>
                        <td class="pe-4 py-3 text-end">
                            <a href="{{ route('reservations.show', $res) }}" class="btn btn-sm btn-outline-info rounded-circle me-1" title="View Details"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('reservations.invoice', $res) }}" class="btn btn-sm btn-outline-warning rounded-circle me-1" title="Download Invoice"><i class="bi bi-receipt"></i></a>
                            <form action="{{ route('reservations.destroy', $res) }}" method="POST" class="d-inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" onclick="return confirm('Eradicate this booking?')" title="Delete"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-journal-x fs-1 d-block mb-3"></i>
                            <p>No bookings mapped in the system.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
