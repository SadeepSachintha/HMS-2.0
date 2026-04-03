@extends('layouts.app')

@section('title', '| Manage Rooms')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h2 class="text-white fw-bold mb-0">Room Inventory</h2>
        <p class="text-light opacity-75 mb-0">Allocate and track configurations across all properties.</p>
    </div>
    <div class="col-md-6 text-md-end mt-3 mt-md-0">
        <a href="{{ route('rooms.create') }}" class="btn btn-primary rounded-pill px-4 hover-lift shadow-sm">
            <i class="bi bi-plus-circle me-2"></i> Register New Room
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
                        <th class="ps-4 py-3 text-uppercase text-secondary small fw-bold">Room Identifier</th>
                        <th class="py-3 text-uppercase text-secondary small fw-bold">Property Location</th>
                        <th class="py-3 text-uppercase text-secondary small fw-bold text-center">Config</th>
                        <th class="py-3 text-uppercase text-secondary small fw-bold text-center">Unit Price</th>
                        <th class="py-3 text-uppercase text-secondary small fw-bold">Current Status</th>
                        <th class="pe-4 py-3 text-uppercase text-secondary small fw-bold text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rooms as $room)
                    <tr>
                        <td class="ps-4 py-3">
                            <div class="fw-bold fs-5">#{{ $room->room_number }}</div>
                            <span class="badge bg-secondary rounded-pill fw-light px-2">{{ ucfirst($room->type) }}</span>
                        </td>
                        <td class="py-3">
                            <div class="text-light fw-bold">{{ $room->hotel->name ?? 'Unassigned' }}</div>
                            <div class="small text-muted"><i class="bi bi-geo-alt"></i> {{ $room->hotel->city ?? '' }}</div>
                        </td>
                        <td class="py-3 text-center">
                            <i class="bi bi-people-fill text-info me-1"></i> {{ $room->capacity }} Pax
                        </td>
                        <td class="py-3 text-center text-success fw-bolder">
                            ${{ number_format($room->price_per_night, 2) }}
                        </td>
                        <td class="py-3">
                            @if($room->status === 'available')
                                <span class="badge bg-success rounded-pill px-3 py-2">Available</span>
                            @elseif($room->status === 'occupied')
                                <span class="badge bg-danger rounded-pill px-3 py-2">Occupied</span>
                            @elseif($room->status === 'cleaning')
                                <span class="badge bg-info text-dark rounded-pill px-3 py-2">Cleaning</span>
                            @else
                                <span class="badge bg-warning text-dark rounded-pill px-3 py-2">Maintenance</span>
                            @endif
                        </td>
                        <td class="pe-4 py-3 text-end">
                            <a href="{{ route('rooms.edit', $room) }}" class="btn btn-sm btn-outline-info rounded-circle me-1"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('rooms.destroy', $room) }}" method="POST" class="d-inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" onclick="return confirm('Eradicate this room from inventory?')"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-box-seam fs-1 d-block mb-3"></i>
                            <p>No rooms allocated yet. Time to build!</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
