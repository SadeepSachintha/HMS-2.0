@extends('layouts.app')

@section('title', '| Financials & Billing')

@section('content')
<div class="row mb-5 align-items-center">
    <div class="col-md-6">
        <h2 class="text-white fw-bold mb-0">Billing & Payments</h2>
        <p class="text-light opacity-75 mb-0">Track revenue and manage collection cycles.</p>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show rounded-4 glass-panel border-success" role="alert">
    <strong>Success!</strong> {{ session('success') }}
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="row g-4 mb-5">
    <div class="col-md-6">
        <div class="card glass-panel border-0 border-start border-4 border-success p-3">
            <div class="card-body py-2">
                <div class="text-success small fw-bold text-uppercase mb-1">Total Realized Revenue</div>
                <h2 class="text-white mb-0">${{ number_format($totalRevenue, 2) }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card glass-panel border-0 border-start border-4 border-warning p-3">
            <div class="card-body py-2">
                <div class="text-warning small fw-bold text-uppercase mb-1">Pending Collections</div>
                <h2 class="text-white mb-0">${{ number_format($pendingRevenue, 2) }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="card glass-panel border-0 mb-5">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-dark table-hover table-borderless text-white mb-0 align-middle">
                <thead class="border-bottom border-light border-opacity-10">
                    <tr>
                        <th class="ps-4 py-3 text-uppercase text-secondary small fw-bold">Invoice ID</th>
                        <th class="py-3 text-uppercase text-secondary small fw-bold">Guest Details</th>
                        <th class="py-3 text-uppercase text-secondary small fw-bold text-center">Amount Due</th>
                        <th class="py-3 text-uppercase text-secondary small fw-bold text-center">Collection Status</th>
                        <th class="pe-4 py-3 text-uppercase text-secondary small fw-bold text-end">Manage Ledger</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations as $res)
                    <tr>
                        <td class="ps-4 py-3">
                            <div class="fw-bold text-info">RES-{{ str_pad($res->id, 5, '0', STR_PAD_LEFT) }}</div>
                            <div class="small text-muted">{{ \Carbon\Carbon::parse($res->created_at)->format('M d, Y') }}</div>
                        </td>
                        <td class="py-3">
                            <div class="fw-bold">{{ $res->user->name ?? 'N/A' }}</div>
                            <div class="small text-muted">{{ $res->user->email ?? '' }}</div>
                        </td>
                        <td class="py-3 text-center text-white fw-bold fs-5">
                            ${{ number_format($res->total_price, 2) }}
                        </td>
                        <td class="py-3 text-center">
                            @if($res->payment_status === 'paid')
                                <span class="badge bg-success rounded-pill px-3 py-2"><i class="bi bi-check-circle me-1"></i> Paid</span>
                            @elseif($res->payment_status === 'partial')
                                <span class="badge bg-warning text-dark rounded-pill px-3 py-2">Partial</span>
                            @elseif($res->payment_status === 'refunded')
                                <span class="badge bg-danger rounded-pill px-3 py-2">Refunded</span>
                            @else
                                <span class="badge bg-secondary rounded-pill px-3 py-2">Unpaid</span>
                            @endif
                        </td>
                        <td class="pe-4 py-3 text-end">
                            <form action="{{ route('billing.update', $res) }}" method="POST" class="d-inline-flex gap-2 align-items-center">
                                @csrf
                                @method('PATCH')
                                <select name="payment_status" class="form-select form-select-sm bg-dark text-white border-secondary" required onchange="this.form.submit()">
                                    <option value="unpaid" {{ $res->payment_status == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                    <option value="partial" {{ $res->payment_status == 'partial' ? 'selected' : '' }}>Partial</option>
                                    <option value="paid" {{ $res->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="refunded" {{ $res->payment_status == 'refunded' ? 'selected' : '' }}>Refunded</option>
                                </select>
                            </form>
                            <a href="{{ route('reservations.invoice', $res) }}" class="btn btn-sm btn-outline-warning ms-2 shadow-sm"><i class="bi bi-receipt"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-wallet2 fs-1 d-block mb-3"></i>
                            <p>No billing records found in the ledger.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
