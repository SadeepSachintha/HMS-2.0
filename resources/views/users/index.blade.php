@extends('layouts.app')

@section('title', '| Global User Governance')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col-md-6">
        <h2 class="text-white fw-bold mb-0">System Identity Governance</h2>
        <p class="text-light opacity-75 mb-0">Absolute control module over platform hierarchies, passwords, and permissions.</p>
    </div>
    <div class="col-md-6 text-md-end mt-3 mt-md-0">
        <a href="{{ route('users.create') }}" class="btn btn-danger rounded-pill px-4 hover-lift shadow-sm">
            <i class="bi bi-person-plus-fill me-2"></i> Register Native Identity
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show rounded-4 glass-panel border-success" role="alert">
    <strong>Success!</strong> {{ session('success') }}
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show rounded-4 glass-panel border-danger" role="alert">
    <strong>Error:</strong> {{ session('error') }}
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card glass-panel border-0 border-top border-4 border-danger mb-5 shadow-lg">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-dark table-hover table-borderless text-white mb-0 align-middle">
                <thead class="border-bottom border-light border-opacity-10">
                    <tr>
                        <th class="ps-4 py-3 text-uppercase text-secondary small fw-bold">Internal ID</th>
                        <th class="py-3 text-uppercase text-secondary small fw-bold">Identity Array</th>
                        <th class="py-3 text-uppercase text-secondary small fw-bold">Clearance Level</th>
                        <th class="py-3 text-uppercase text-secondary small fw-bold">Platform Inception</th>
                        <th class="pe-4 py-3 text-uppercase text-secondary small fw-bold text-end">Execution</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td class="ps-4 py-3 text-muted">ID: {{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td class="py-3">
                            <div class="fw-bold">{{ $user->name }}</div>
                            <div class="small">
                                <i class="bi bi-envelope text-info me-1"></i>{{ $user->email }}<br>
                                <i class="bi bi-telephone text-info me-1"></i>{{ $user->phone ?? 'Unallocated' }}
                            </div>
                        </td>
                        <td class="py-3">
                            @if(($user->role->slug ?? 'guest') === 'admin')
                                <span class="badge bg-danger rounded-pill px-3 py-2 shadow-sm"><i class="bi bi-shield-check me-1"></i> Admin</span>
                            @elseif(($user->role->slug ?? 'guest') === 'staff')
                                <span class="badge bg-primary rounded-pill px-3 py-2 shadow-sm"><i class="bi bi-briefcase-fill me-1"></i> Staff</span>
                            @else
                                <span class="badge bg-success rounded-pill px-3 py-2 shadow-sm"><i class="bi bi-person-fill"></i> Guest</span>
                            @endif
                        </td>
                        <td class="py-3">
                            <div class="mb-1">
                                @if($user->status === 'active')
                                    <span class="badge bg-success bg-opacity-25 text-success rounded-pill px-2 border border-success"><i class="bi bi-circle-fill small me-1"></i> Active Session</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-25 text-danger rounded-pill px-2 border border-danger"><i class="bi bi-x-circle-fill small me-1"></i> Suspended</span>
                                @endif
                            </div>
                            <span class="small text-muted">{{ \Carbon\Carbon::parse($user->created_at)->diffForHumans() }}</span>
                        </td>
                        <td class="pe-4 py-3 text-end">
                            <div class="d-inline-flex gap-1">
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-warning rounded-pill px-3" title="Modify Paradigm">
                                    <i class="bi bi-pencil-square"></i> Mutate
                                </a>
                                @if(Auth::id() !== $user->id)
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" onclick="return confirm('Absolute Eradication: Delete this entity from the platform permanently?');"><i class="bi bi-trash"></i></button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-5 text-center text-muted">Core database corrupted. No users found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
