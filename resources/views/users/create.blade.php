@extends('layouts.app')

@section('title', '| Create Native User')

@section('content')
<div class="row justify-content-center mb-5">
    <div class="col-md-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-white fw-bold mb-0">Register Native Identity</h2>
            <a href="{{ route('users.index') }}" class="btn btn-outline-light rounded-pill px-4 hover-lift">
                <i class="bi bi-arrow-left me-2"></i> Global Governance
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

        <div class="card glass-panel border-0 border-top border-4 border-danger">
            <div class="card-body p-5">
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    
                    <h5 class="text-danger mb-4 border-bottom border-secondary pb-2 border-opacity-25">Operational Parameters</h5>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-light">Clearance Level (Role) <span class="text-danger">*</span></label>
                            <select name="role_id" class="form-select bg-dark text-white border-secondary" required>
                                <option value="" selected disabled>Select hierarchy tier...</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-light">Account Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select bg-dark text-white border-secondary" required>
                                <option value="active" selected>Active - Standard Connectivity</option>
                                <option value="suspended">Suspended - Hard Lockout Protocol</option>
                            </select>
                        </div>
                    </div>

                    <h5 class="text-danger mb-4 border-bottom border-secondary pb-2 border-opacity-25 mt-4">Personal Identifiers</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-light">Entity Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control bg-dark text-white border-secondary" value="{{ old('name') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-light">Primary Contact Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control bg-dark text-white border-secondary" value="{{ old('email') }}" required>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label text-light">Telecommunication <span class="text-secondary">(Optional)</span></label>
                        <input type="text" name="phone" class="form-control bg-dark text-white border-secondary" value="{{ old('phone') }}">
                    </div>

                    <h5 class="text-danger mb-4 border-bottom border-secondary pb-2 border-opacity-25 mt-4">Security Envelope</h5>
                    <div class="row g-3 mb-5">
                        <div class="col-md-6">
                            <label class="form-label text-light">Original Password Vector <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control bg-dark text-white border-secondary" placeholder="Minimum 8 sequence elements" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-light">Confirm Password Vector <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control bg-dark text-white border-secondary" placeholder="Repeat sequence" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-danger text-white fw-bold btn-lg rounded-pill px-5 hover-lift shadow-sm w-100 mt-2">
                        Execute Identity Inception <i class="bi bi-person-check-fill ms-2"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
