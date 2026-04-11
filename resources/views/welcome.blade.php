@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row align-items-center min-vh-75">
        <div class="col-md-6 text-center text-md-start">
            <h1 class="display-3 fw-bolder text-white mb-4">
                Hospitality Redefined
            </h1>
            <p class="lead text-light opacity-75 mb-5 fs-4">
                Experience the next-generation Hotel Management System. Seamless reservations, intelligent scheduling, and vibrant dashboards.
            </p>
            <div class="d-flex gap-3 justify-content-center justify-content-md-start">
                <a href="{{ route('reservations.create') }}" class="btn btn-primary btn-lg rounded-pill px-5 hover-lift shadow-lg">Book a Room</a>
                <a href="{{ route('home') }}" class="btn btn-outline-light btn-lg rounded-pill px-5 hover-lift">Dashboard</a>
            </div>
        </div>
        <div class="col-md-6 mt-5 mt-md-0 position-relative">
            <!-- Decorative circle behind the glass panel -->
            <div class="position-absolute bg-primary rounded-circle" style="width: 300px; height: 300px; top: -50px; right: -50px; filter: blur(80px); opacity: 0.5;"></div>
            
            <div class="card glass-panel border-0 p-4">
                <div class="card-body text-center">
                    <h3 class="mb-4 text-white">System Status</h3>
                    
                    <div class="row g-4">
                        <div class="col-6">
                            <div class="p-3 bg-dark bg-opacity-50 rounded-4 hover-lift border border-secondary border-opacity-25">
                                <h4 class="text-info fw-bold mb-0">{{ $availableRooms }}</h4>
                                <span class="text-muted small">Available Rooms</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-dark bg-opacity-50 rounded-4 hover-lift border border-secondary border-opacity-25">
                                <h4 class="text-success fw-bold mb-0">{{ $activeStaff }}</h4>
                                <span class="text-muted small">Active Staff</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="p-3 bg-dark bg-opacity-50 rounded-4 hover-lift border border-secondary border-opacity-25 mt-2">
                                <h4 class="text-warning fw-bold mb-0">{{ $pendingReservations }}</h4>
                                <span class="text-muted small">Pending Events & Reservations</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
