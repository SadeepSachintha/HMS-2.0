@extends('layouts.app')

@section('title', '| Analytical Reports')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col-md-6">
        <h2 class="text-white fw-bold mb-0">Global Analytics Matrix</h2>
        <p class="text-light opacity-75 mb-0">Monitor high-level organizational heuristics and performance data.</p>
    </div>
    <div class="col-md-6 text-md-end mt-3 mt-md-0 d-flex gap-2 justify-content-md-end">
        <button type="button" class="btn btn-primary fw-bold px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#customReportModal">
            <i class="bi bi-file-earmark-bar-graph me-2"></i> Custom Report Generation
        </button>
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

<!-- Custom Report Modal -->
<div class="modal fade" id="customReportModal" tabindex="-1" aria-labelledby="customReportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-panel border-0 text-white">
            <div class="modal-header border-bottom border-secondary border-opacity-25">
                <h5 class="modal-title fw-bold" id="customReportModalLabel">
                    <i class="bi bi-funnel-fill me-2 text-primary"></i> Report Parameters
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('reports.export.custom') }}" method="GET">
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label class="form-label text-white-50 fw-semibold small text-uppercase">Report Type</label>
                        <select name="type" class="form-select bg-dark border-secondary text-white shadow-none" required>
                            <option value="occupancy">Occupancy Report (Live Snapshot)</option>
                            <option value="revenue">Revenue Report (Filtered by Payment Date)</option>
                            <option value="reservations">Reservations Report (Filtered by Check-In Date)</option>
                        </select>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-white-50 fw-semibold small text-uppercase">Start Date</label>
                            <input type="date" name="start_date" class="form-control bg-dark border-secondary text-white shadow-none">
                            <small class="text-white-50 xs">Not applicable for Occupancy</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-white-50 fw-semibold small text-uppercase">End Date</label>
                            <input type="date" name="end_date" class="form-control bg-dark border-secondary text-white shadow-none">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top border-secondary border-opacity-25 p-3">
                    <button type="button" class="btn btn-dark fw-bold px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4 hover-lift">
                        <i class="bi bi-download me-2"></i> Export Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
