@extends('layouts.app')

@section('title', '| Reservation Details')

@section('content')
<div class="row justify-content-center mb-5">
    <div class="col-md-7">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-white fw-bold mb-0">Booking #RES-{{ str_pad($reservation->id, 5, '0', STR_PAD_LEFT) }}</h2>
            <a href="{{ route('reservations.index') }}" class="btn btn-outline-light rounded-pill px-4 hover-lift">
                <i class="bi bi-calendar3 me-2"></i> Calendar
            </a>
        </div>

        <div class="card glass-panel border-0 position-relative overflow-hidden">
            <!-- Decorative Stripe -->
            <div class="position-absolute top-0 start-0 w-100" style="height: 6px; background: linear-gradient(90deg, #4F46E5, #06B6D4);"></div>
            
            <div class="card-body p-5">
                <div class="row mb-5 align-items-center">
                    <div class="col-8">
                        <h4 class="text-info fw-bold mb-1"><i class="bi bi-person-badge me-2"></i> {{ $reservation->user->name ?? 'Guest' }}</h4>
                        <p class="text-light opacity-75 mb-0">Booking Controller: {{ $reservation->user->email ?? 'N/A' }} | Phone: {{ $reservation->user->phone ?? 'N/A' }}</p>
                    </div>
                    <div class="col-4 text-end">
                        @if($reservation->status === 'confirmed')
                            <span class="badge bg-success rounded-pill px-4 py-2 fs-6 shadow-sm">Confirmed</span>
                        @elseif($reservation->status === 'cancelled')
                            <span class="badge bg-danger rounded-pill px-4 py-2 fs-6 shadow-sm">Cancelled</span>
                        @else
                            <span class="badge bg-secondary rounded-pill px-4 py-2 fs-6 shadow-sm">{{ ucfirst($reservation->status) }}</span>
                        @endif
                    </div>
                </div>

                <div class="row g-4 mb-5">
                    <div class="col-md-6">
                        <div class="p-4 bg-dark bg-opacity-25 rounded-4 h-100 border border-secondary border-opacity-25">
                            <h6 class="text-uppercase text-muted small fw-bold mb-3">Facility Context</h6>
                            <h5 class="text-white mb-1">{{ $reservation->room->hotel->name ?? 'Unknown Hotel' }}</h5>
                            <p class="text-light opacity-75 mb-0 small">{{ $reservation->room->hotel->city ?? '' }}, {{ $reservation->room->hotel->country ?? '' }}</p>
                            
                            <hr class="border-secondary opacity-25 my-3">
                            
                            <h6 class="text-white mb-0">Room: <span class="fw-bold fs-5 text-warning ms-2">#{{ $reservation->room->room_number ?? 'N/A' }}</span></h6>
                            <span class="badge bg-secondary mt-2">{{ ucfirst($reservation->room->type ?? 'Standard') }}</span>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="p-4 bg-dark bg-opacity-25 rounded-4 h-100 border border-secondary border-opacity-25">
                            <h6 class="text-uppercase text-muted small fw-bold mb-3">Chronology</h6>
                            
                            <div class="d-flex justify-content-between mb-3">
                                <div>
                                    <span class="d-block small text-muted">Check-In</span>
                                    <strong class="text-white fs-5">{{ \Carbon\Carbon::parse($reservation->check_in_date)->format('M d, Y') }}</strong>
                                </div>
                                <div class="text-center pt-2">
                                    <i class="bi bi-arrow-right text-info fs-4"></i>
                                </div>
                                <div class="text-end">
                                    <span class="d-block small text-muted">Check-Out</span>
                                    <strong class="text-white fs-5">{{ \Carbon\Carbon::parse($reservation->check_out_date)->format('M d, Y') }}</strong>
                                </div>
                            </div>

                            <hr class="border-secondary opacity-25 my-3">

                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-light">Grand Total</span>
                                <strong class="text-success fs-4">${{ number_format($reservation->total_price, 2) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                @if($reservation->special_requests)
                <div class="p-4 bg-primary bg-opacity-10 rounded-4 border border-primary border-opacity-25 mb-5">
                    <h6 class="text-info fw-bold mb-2"><i class="bi bi-chat-quote me-2"></i> Special Requirements</h6>
                    <p class="text-light mb-0 fst-italic">{{ $reservation->special_requests }}</p>
                </div>
                @endif

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="{{ route('reservations.invoice', $reservation) }}" class="btn btn-warning text-dark fw-bold btn-lg rounded-pill px-5 hover-lift shadow">
                        <i class="bi bi-file-earmark-pdf-fill me-2"></i> Generate Official Invoice
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
