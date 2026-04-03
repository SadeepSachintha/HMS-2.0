@extends('layouts.app')

@section('title', '| Staff Management')

@section('content')
<div class="row align-items-center mb-4">
    <div class="col-md-6">
        <h2 class="text-white fw-bold mb-0">Personnel & Shifts</h2>
        <p class="text-light opacity-75 mb-0">Manage staff access, departments, and active shift rotations.</p>
    </div>
    <div class="col-md-6 text-md-end mt-3 mt-md-0">
        <a href="{{ route('staff.create') }}" class="btn btn-primary rounded-pill px-4 hover-lift shadow-sm">
            <i class="bi bi-person-plus-fill me-2"></i> Onboard New Staff
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
                        <th class="ps-4 py-3 text-uppercase text-secondary small fw-bold">Employee</th>
                        <th class="py-3 text-uppercase text-secondary small fw-bold">Facility & Dept</th>
                        <th class="py-3 text-uppercase text-secondary small fw-bold">Role & Salary</th>
                        <th class="py-3 text-uppercase text-secondary small fw-bold">Current Shift</th>
                        <th class="pe-4 py-3 text-uppercase text-secondary small fw-bold text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($staff as $member)
                    <tr>
                        <td class="ps-4 py-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-25 rounded-circle me-3 d-flex justify-content-center align-items-center text-info shadow p-2" style="width:40px; height:40px;">
                                    <i class="bi bi-person-badge"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $member->user->name ?? 'System User Removed' }}</div>
                                    <div class="small text-muted">{{ \Carbon\Carbon::parse($member->hire_date)->format('M Y') }} Start</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-3">
                            <div class="text-white">{{ $member->hotel->name ?? 'Unassigned' }}</div>
                            <div class="text-info small">{{ $member->department }}</div>
                        </td>
                        <td class="py-3">
                            <div class="text-white fw-bold">{{ $member->position }}</div>
                            <div class="small text-warning">${{ number_format($member->salary, 2) }} /yr</div>
                        </td>
                        <td class="py-3">
                            @if($member->shift == 'morning')
                                <span class="badge bg-info text-dark rounded-pill px-3 py-2"><i class="bi bi-sunrise me-1"></i> Morning</span>
                            @elseif($member->shift == 'evening')
                                <span class="badge bg-warning text-dark rounded-pill px-3 py-2"><i class="bi bi-sunset me-1"></i> Evening</span>
                            @else
                                <span class="badge bg-secondary rounded-pill px-3 py-2"><i class="bi bi-moon-stars me-1"></i> Night</span>
                            @endif
                        </td>
                        <td class="pe-4 py-3 text-end">
                            <a href="{{ route('staff.edit', $member) }}" class="btn btn-sm btn-outline-info rounded-circle me-1"><i class="bi bi-pencil-square"></i></a>
                            
                            <form action="{{ route('staff.destroy', $member) }}" method="POST" class="d-inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" onclick="return confirm('Terminate this staff record? This action cannot be reversed.')"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-5 text-center text-muted">
                            <i class="bi bi-people-fill fs-1 d-block mb-3"></i>
                            <p class="mb-0">No active personnel assigned to shifts.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
