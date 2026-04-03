@extends('layouts.app')

@section('title', '| Analytical Reports')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col-md-6">
        <h2 class="text-white fw-bold mb-0">Global Analytics Matrix</h2>
        <p class="text-light opacity-75 mb-0">Monitor high-level organizational heuristics and performance data.</p>
    </div>
    <div class="col-md-6 text-md-end mt-3 mt-md-0 d-flex gap-2 justify-content-md-end">
        <a href="{{ route('reservations.export') }}" class="btn btn-success fw-bold rounded-pill px-4 hover-lift shadow-sm">
            <i class="bi bi-file-earmark-excel me-2"></i> Export Complete Ledger
        </a>
    </div>
</div>

<div class="row g-4 mb-5">
    <!-- Occupancy Metric Card -->
    <div class="col-md-4">
        <div class="card glass-panel border-0 border-top border-4 border-info h-100 position-relative overflow-hidden">
            <div class="card-body p-4 position-relative z-1">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="text-info text-uppercase fw-bold mb-0">Live Occupancy</h6>
                    <i class="bi bi-buildings text-info fs-4 opacity-50"></i>
                </div>
                <h1 class="text-white display-5 fw-bold mb-1">{{ $occupancyRate }}<span class="fs-4 text-muted">%</span></h1>
                <p class="text-light opacity-75 mb-0 small">
                    {{ $currentlyOccupiedRooms }} out of {{ $totalRooms }} rooms currently secured.
                </p>
                <div class="progress mt-3 bg-dark" style="height: 6px;">
                    <div class="progress-bar bg-info" role="progressbar" style="width: {{ $occupancyRate }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Revenue Card -->
    <div class="col-md-4">
        <div class="card glass-panel border-0 border-top border-4 border-success h-100 position-relative overflow-hidden">
            <div class="card-body p-4 position-relative z-1">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="text-success text-uppercase fw-bold mb-0">30-Day Revenue Yield</h6>
                    <i class="bi bi-graph-up-arrow text-success fs-4 opacity-50"></i>
                </div>
                <h1 class="text-white display-5 fw-bold mb-1"><span class="fs-4 text-muted">$</span>{{ number_format($trailingRevenue) }}</h1>
                <p class="text-light opacity-75 mb-0 small">
                    Accumulated liquid asset mapping (trailing 30 days).
                </p>
            </div>
        </div>
    </div>

    <!-- Pipeline Card -->
    <div class="col-md-4">
        <div class="card glass-panel border-0 border-top border-4 border-warning h-100 position-relative overflow-hidden">
            <div class="card-body p-4 position-relative z-1">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="text-warning text-uppercase fw-bold mb-0">7-Day Booking Pipeline</h6>
                    <i class="bi bi-calendar-event text-warning fs-4 opacity-50"></i>
                </div>
                <h1 class="text-white display-5 fw-bold mb-1">{{ $upcomingReservations }} <span class="fs-4 text-muted">Arrivals</span></h1>
                <p class="text-light opacity-75 mb-0 small">
                    Pending check-ins projected over the next 7 operational cycles.
                </p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Lifetime Data -->
    <div class="col-md-6">
        <div class="card glass-panel border-0 shadow">
            <div class="card-body p-5 text-center">
                <h4 class="text-white fw-bold mb-4 border-bottom pb-3 border-secondary border-opacity-25">Lifetime Structural Bookings</h4>
                <div class="display-1 text-primary fw-bold mb-3">{{ $lifetimeBookings }}</div>
                <p class="text-muted">Total reservations generated through HMS.</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card glass-panel border-0 shadow">
            <div class="card-body p-5 text-center">
                <h4 class="text-white fw-bold mb-4 border-bottom pb-3 border-secondary border-opacity-25">Lifetime Realized Revenue</h4>
                <div class="display-3 text-success fw-bold mb-3">${{ number_format($lifetimeRevenue, 2) }}</div>
                <p class="text-muted">Aggregate platform valuation metrics based on cleared payments.</p>
            </div>
        </div>
    </div>
</div>

@endsection
