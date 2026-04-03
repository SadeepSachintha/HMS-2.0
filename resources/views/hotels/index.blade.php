@extends('layouts.app')

@section('title', '| Manage Hotels')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h2 class="text-white fw-bold mb-0">Hotel Inventory</h2>
        <p class="text-light opacity-75 mb-0">Manage and track your properties globally.</p>
    </div>
    <div class="col-md-6 text-md-end mt-3 mt-md-0">
        <a href="{{ route('hotels.create') }}" class="btn btn-primary rounded-pill px-4 hover-lift shadow-sm">
            <i class="bi bi-plus-circle me-2"></i> Register New Hotel
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
                        <th class="ps-4 py-3 text-uppercase text-secondary small fw-bold">Property Name</th>
                        <th class="py-3 text-uppercase text-secondary small fw-bold">Location</th>
                        <th class="py-3 text-uppercase text-secondary small fw-bold text-center">Rating</th>
                        <th class="py-3 text-uppercase text-secondary small fw-bold text-center">Rooms</th>
                        <th class="py-3 text-uppercase text-secondary small fw-bold">Status</th>
                        <th class="pe-4 py-3 text-uppercase text-secondary small fw-bold text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($hotels as $hotel)
                    <tr>
                        <td class="ps-4 py-3">
                            <div class="fw-bold">{{ $hotel->name }}</div>
                            <div class="small text-muted">{{ $hotel->email }}</div>
                        </td>
                        <td class="py-3">
                            <div>{{ $hotel->city ?: 'N/A' }}, {{ $hotel->country ?: 'N/A' }}</div>
                        </td>
                        <td class="py-3 text-center">
                            <span class="badge bg-warning text-dark"><i class="bi bi-star-fill"></i> {{ $hotel->stars }}</span>
                        </td>
                        <td class="py-3 text-center">
                            {{ $hotel->rooms_count }}
                        </td>
                        <td class="py-3">
                            @if($hotel->status === 'active')
                                <span class="badge bg-success rounded-pill px-3 py-2">Active</span>
                            @elseif($hotel->status === 'maintenance')
                                <span class="badge bg-warning text-dark rounded-pill px-3 py-2">Maintenance</span>
                            @else
                                <span class="badge bg-danger rounded-pill px-3 py-2">Inactive</span>
                            @endif
                        </td>
                        <td class="pe-4 py-3 text-end">
                            <a href="{{ route('hotels.edit', $hotel) }}" class="btn btn-sm btn-outline-info rounded-circle me-1"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('hotels.destroy', $hotel) }}" method="POST" class="d-inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" onclick="return confirm('Delete this property?')"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-building fs-1 d-block mb-3"></i>
                            <p>No hotels found in the database. Get started by adding a property!</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
