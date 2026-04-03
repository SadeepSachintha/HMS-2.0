@extends('layouts.app')

@section('title', '| Edit Room Configuration')

@section('content')
<div class="row justify-content-center mb-5">
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-white fw-bold mb-0">Modify Room: #{{ $room->room_number }}</h2>
            <a href="{{ route('rooms.index') }}" class="btn btn-outline-light rounded-pill px-4 hover-lift">
                <i class="bi bi-arrow-left me-2"></i> Inventory
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
                <form action="{{ route('rooms.update', $room) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <h5 class="text-info mb-4 border-bottom border-secondary pb-2 border-opacity-25">Property Allocation</h5>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-12">
                            <label class="form-label text-light">Master Hotel <span class="text-danger">*</span></label>
                            <select name="hotel_id" class="form-select bg-dark text-white border-secondary" required>
                                @foreach($hotels as $hotel)
                                    <option value="{{ $hotel->id }}" {{ $room->hotel_id == $hotel->id ? 'selected' : '' }}>
                                        {{ $hotel->name }} ({{ $hotel->city }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <h5 class="text-info mb-4 border-bottom border-secondary pb-2 border-opacity-25">Configuration Details</h5>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-light">Room Number / Identifier <span class="text-danger">*</span></label>
                            <input type="text" name="room_number" value="{{ old('room_number', $room->room_number) }}" class="form-control bg-dark text-white border-secondary" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-light">Classification</label>
                            <select name="type" class="form-select bg-dark text-white border-secondary">
                                <option value="single" {{ $room->type == 'single' ? 'selected' : '' }}>Single</option>
                                <option value="double" {{ $room->type == 'double' ? 'selected' : '' }}>Double</option>
                                <option value="deluxe" {{ $room->type == 'deluxe' ? 'selected' : '' }}>Deluxe</option>
                                <option value="suite" {{ $room->type == 'suite' ? 'selected' : '' }}>Suite</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-light">Occupancy Capacity <span class="text-danger">*</span></label>
                            <input type="number" name="capacity" min="1" value="{{ old('capacity', $room->capacity) }}" class="form-control bg-dark text-white border-secondary" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-light">Unit Price per Night ($) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="price_per_night" value="{{ old('price_per_night', $room->price_per_night) }}" class="form-control bg-dark text-white border-secondary" required>
                        </div>
                    </div>

                    <h5 class="text-info mb-4 border-bottom border-secondary pb-2 border-opacity-25">Operation Metadata</h5>

                    <div class="row g-3">
                        <div class="col-md-12 mb-3">
                            <label class="form-label text-light">Marketing Description</label>
                            <textarea name="description" rows="3" class="form-control bg-dark text-white border-secondary">{{ old('description', $room->description) }}</textarea>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label text-light">Current State</label>
                            <select name="status" class="form-select bg-dark text-white border-secondary">
                                <option value="available" {{ $room->status == 'available' ? 'selected' : '' }}>Available (Ready to Book)</option>
                                <option value="occupied" {{ $room->status == 'occupied' ? 'selected' : '' }}>Occupied</option>
                                <option value="cleaning" {{ $room->status == 'cleaning' ? 'selected' : '' }}>Cleaning Phase</option>
                                <option value="maintenance" {{ $room->status == 'maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-warning text-dark fw-bold btn-lg rounded-pill px-5 hover-lift shadow-sm w-100 mt-2">
                        Commit Configurations <i class="bi bi-save ms-2"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
