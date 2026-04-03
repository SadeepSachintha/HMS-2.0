@extends('layouts.app')

@section('title', '| Customer CRM')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col-md-6">
        <h2 class="text-white fw-bold mb-0">Guest Intelligence Matrix</h2>
        <p class="text-light opacity-75 mb-0">Monitor distinct customer entities and track global reservation engagement.</p>
    </div>
</div>

<div class="card glass-panel border-0 mb-5">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-dark table-hover table-borderless text-white mb-0 align-middle">
                <thead class="border-bottom border-light border-opacity-10">
                    <tr>
                        <th class="ps-4 py-3 text-uppercase text-secondary small fw-bold">Guest Identity</th>
                        <th class="py-3 text-uppercase text-secondary small fw-bold">Secure Contact</th>
                        <th class="py-3 text-uppercase text-secondary small fw-bold">Platform Registration</th>
                        <th class="py-3 text-uppercase text-secondary small fw-bold text-center">Total Bookings</th>
                        <th class="pe-4 py-3 text-uppercase text-secondary small fw-bold text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                    <tr>
                        <td class="ps-4 py-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-success bg-opacity-25 rounded-circle me-3 d-flex justify-content-center align-items-center text-success shadow p-2" style="width:40px; height:40px;">
                                    <i class="bi bi-person-heart"></i>
                                </div>
                                <div class="fw-bold">{{ $customer->name }}</div>
                            </div>
                        </td>
                        <td class="py-3">
                            <div class="text-white small"><i class="bi bi-envelope text-info me-1"></i> {{ $customer->email }}</div>
                            <div class="text-white small"><i class="bi bi-telephone text-info me-1"></i> {{ $customer->phone ?? 'Unlisted' }}</div>
                        </td>
                        <td class="py-3 text-muted small">
                            {{ \Carbon\Carbon::parse($customer->created_at)->format('F d, Y') }}
                        </td>
                        <td class="py-3 text-center">
                            @if($customer->reservations_count > 0)
                                <span class="badge bg-primary rounded-pill px-3 py-2 fs-6">{{ $customer->reservations_count }} Active</span>
                            @else
                                <span class="badge bg-secondary rounded-pill px-3 py-2 fs-6">0 Bookings</span>
                            @endif
                        </td>
                        <td class="pe-4 py-3 text-end">
                            <a href="{{ route('customers.show', $customer) }}" class="btn btn-sm btn-outline-info rounded-pill px-3">
                                <i class="bi bi-eye me-1"></i> Profile History
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-5 text-center text-muted">
                            <i class="bi bi-person-x fs-1 d-block mb-3"></i>
                            <p class="mb-0">No registered guests traversing the system array right now.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
