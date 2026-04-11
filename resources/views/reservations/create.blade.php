@extends('layouts.app')

@section('title', '| Create Reservation')

@section('content')
<div class="row justify-content-center mb-5 mt-5 pt-4">
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
                    
                    @isset($guests)
                    <h5 class="text-warning mb-4 border-bottom border-secondary pb-2 border-opacity-25"><i class="bi bi-person-badge me-2"></i> Guest Allocation</h5>
                    <div class="mb-4">
                        <label class="form-label text-light">Register to Guest Profile <span class="text-danger">*</span></label>
                        <select name="user_id" class="form-select bg-dark text-white border-secondary" required>
                            <option value="" selected disabled>Select a guest profile...</option>
                            @foreach($guests as $guest)
                                <option value="{{ $guest->id }}">{{ $guest->name }} ({{ $guest->email }})</option>
                            @endforeach
                        </select>
                        <div class="form-text text-secondary mt-2">
                            Guest not listed? <a href="{{ route('customers.create') }}" class="text-info text-decoration-none">Initialize New Walk-In Guest</a>
                        </div>
                    </div>
                    @endisset

                    <h5 class="text-info mb-4 border-bottom border-secondary pb-2 border-opacity-25">Duration of Stay</h5>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-light">Check-In <span class="text-danger">*</span></label>
                            <input type="date" id="check_in_date" name="check_in_date" class="form-control bg-dark text-white border-secondary" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-light">Check-Out <span class="text-danger">*</span></label>
                            <input type="date" id="check_out_date" name="check_out_date" class="form-control bg-dark text-white border-secondary" required>
                        </div>
                    </div>

                    <h5 class="text-info mb-4 border-bottom border-secondary pb-2 border-opacity-25">Select Accommodations</h5>
                    
                    <div class="mb-4">
                        <label class="form-label text-light">Available Rooms <span class="text-danger">*</span></label>
                        <select name="room_id" id="room_id" class="form-select bg-dark text-white border-secondary" required disabled>
                            <option value="" selected disabled>Please select dates first...</option>
                        </select>
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

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkInInput = document.getElementById('check_in_date');
    const checkOutInput = document.getElementById('check_out_date');
    const roomSelect = document.getElementById('room_id');

    function fetchAvailableRooms() {
        const checkIn = checkInInput.value;
        const checkOut = checkOutInput.value;

        if (checkIn && checkOut) {
            
            // Basic date check before sending to server
            if (new Date(checkOut) <= new Date(checkIn)) {
                roomSelect.innerHTML = '<option value="" selected disabled>Check-Out must be after Check-In...</option>';
                roomSelect.disabled = true;
                return;
            }

            roomSelect.innerHTML = '<option value="" selected disabled>Loading available rooms...</option>';
            roomSelect.disabled = true;

            fetch(`/reservations/check-availability?check_in_date=${checkIn}&check_out_date=${checkOut}`)
                .then(response => response.json())
                .then(data => {
                    roomSelect.innerHTML = '<option value="" selected disabled>Choose a configuration...</option>';
                    if (data.available_rooms && data.available_rooms.length > 0) {
                        data.available_rooms.forEach(room => {
                            const option = document.createElement('option');
                            option.value = room.id;
                            option.textContent = `Room ${room.room_number} - ${room.type.charAt(0).toUpperCase() + room.type.slice(1)} ($${room.price_per_night} / night)`;
                            roomSelect.appendChild(option);
                        });
                        roomSelect.disabled = false;
                    } else {
                        roomSelect.innerHTML = '<option value="" selected disabled>No rooms available for these dates.</option>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching rooms:', error);
                    roomSelect.innerHTML = '<option value="" selected disabled>Error loading rooms. Try again.</option>';
                });
        } else {
            roomSelect.innerHTML = '<option value="" selected disabled>Please select dates first...</option>';
            roomSelect.disabled = true;
        }
    }

    checkInInput.addEventListener('change', fetchAvailableRooms);
    checkOutInput.addEventListener('change', fetchAvailableRooms);
});
</script>
@endsection
