@extends('layouts.app')

@section('title', '| Booking Calendar')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h2 class="text-white fw-bold mb-0">Interactive Calendar</h2>
        <p class="text-light opacity-75 mb-0">Global reservation mapping and time-slot tracking.</p>
    </div>
    <div class="col-md-6 text-md-end mt-3 mt-md-0 d-flex gap-2 justify-content-md-end">
        <a href="{{ route('reservations.export') }}" class="btn btn-success fw-bold rounded-pill px-4 hover-lift shadow-sm">
            <i class="bi bi-file-earmark-excel me-2"></i> Export Bookings
        </a>
        <a href="{{ route('reservations.index') }}" class="btn btn-outline-light rounded-pill px-4 hover-lift shadow-sm">
            <i class="bi bi-list-ul me-2"></i> List View
        </a>
    </div>
</div>

<div class="card glass-panel border-0 mb-5 shadow-lg">
    <div class="card-body p-4">
        <!-- FullCalendar Hook -->
        <div id="calendar" class="text-white" style="min-height: 80vh;"></div>
    </div>
</div>

<!-- Custom CSS logic to override FullCalendar base styling for Dark Glass aesthetic -->
<style>
    .fc-theme-bootstrap5 .fc-scrollgrid, .fc-theme-bootstrap5 td, .fc-theme-bootstrap5 th {
        border-color: rgba(255, 255, 255, 0.1) !important;
    }
    .fc-theme-bootstrap5 .fc-day-today {
        background-color: rgba(16, 185, 129, 0.1) !important; /* Emerald slight glow */
    }
    .fc-theme-bootstrap5 .fc-button-primary {
        background-color: #4F46E5 !important;
        border-color: #4F46E5 !important;
    }
    .fc-event {
        cursor: pointer;
        border: none !important;
        transition: transform 0.2s;
    }
    .fc-event:hover {
        transform: scale(1.02);
    }
    .fc-col-header-cell-cushion, .fc-daygrid-day-number {
        color: #F8FAFC !important;
        text-decoration: none;
    }
</style>
@endsection
