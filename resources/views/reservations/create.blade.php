@extends('layouts.app')

@section('title', '| Create Reservation')

@section('content')
<div class="row justify-content-center mb-5">
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-white fw-bold mb-0">Book a Room</h2>
            <a href="{{ route('reservations.index') }}" class="btn btn-outline-light rounded-pill px-4 hover-lift">
                <i class="bi bi-arrow-left me-2"></i> Ledger
            </a>
        </div>

        @if($errors->any())
        <div class="alert alert-danger glass-panel border-danger rounded-4 py-3 mb-4">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="card glass-panel border-0">
            <div class="card-body p-5">
                <form action="{{ route('reservations.store') }}" method="POST">
                    @csrf
                    
                    <h5 class="text-info mb-4 border-bottom border-secondary pb-2 border-opacity-25">Select Accommodations</h5>
                    
                    <div class="mb-4">
                        <label class="form-label text-light">Available Rooms <span class="text-danger">*</span></label>
                        <select name="room_id" class="form-select bg-dark text-white border-secondary" required>
                            <option value="" selected disabled>Choose a configuration...</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}">Room {{ $room->room_number }} - {{ ucfirst($room->type) }} (${{ $room->price_per_night }} / night)</option>
                            @endforeach
                        </select>
                    </div>

                    <h5 class="text-info mb-4 border-bottom border-secondary pb-2 border-opacity-25">Duration of Stay</h5>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-light">Check-In <span class="text-danger">*</span></label>
                            <input type="date" name="check_in_date" class="form-control bg-dark text-white border-secondary" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-light">Check-Out <span class="text-danger">*</span></label>
                            <input type="date" name="check_out_date" class="form-control bg-dark text-white border-secondary" required>
                        </div>
                    </div>

                    <h5 class="text-info mb-4 border-bottom border-secondary pb-2 border-opacity-25">Additional Context</h5>

                    <div class="mb-4">
                        <label class="form-label text-light">Special Requests (Optional)</label>
                        <textarea name="special_requests" rows="3" class="form-control bg-dark text-white border-secondary" placeholder="Let us know if you need anything specific..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 hover-lift shadow-sm w-100 mt-2">
                        Confirm Reservation <i class="bi bi-calendar-check ms-2"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
