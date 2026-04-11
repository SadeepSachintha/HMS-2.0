@extends('layouts.app')

@section('title', '| Register Walk-In Guest')

@section('content')
<div class="row justify-content-center mb-5 mt-5 pt-4">
    <div class="col-md-7">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-white fw-bold mb-0">Initialize New Guest</h2>
            <a href="{{ route('customers.index') }}" class="btn btn-outline-light rounded-pill px-4 hover-lift">
                <i class="bi bi-arrow-left me-2"></i> Intelligence Matrix
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
                <form action="{{ route('customers.store') }}" method="POST">
                    @csrf
                    
                    <div class="text-center mb-4">
                        <div class="bg-success bg-opacity-25 rounded-circle d-inline-flex justify-content-center align-items-center text-success mb-3 shadow" style="width:70px; height:70px;">
                            <i class="bi bi-person-plus fs-2"></i>
                        </div>
                        <p class="text-light opacity-75">Silently provision a new guest account for immediate reservation alignment. Authentication is bypassed.</p>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-light">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control bg-dark text-white border-secondary" required placeholder="John Doe" value="{{ old('name') }}">
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-light">Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control bg-dark text-white border-secondary" required placeholder="name@example.com" value="{{ old('email') }}">
                        <div class="form-text text-secondary mt-2"><i class="bi bi-info-circle me-1"></i> Unique identifier. Guests can use this later to trigger a password reset and gain online access.</div>
                    </div>

                    <div class="mb-5">
                        <label class="form-label text-light">Phone Number (Optional)</label>
                        <input type="text" name="phone" class="form-control bg-dark text-white border-secondary" placeholder="+1 (555) 000-0000" value="{{ old('phone') }}">
                    </div>

                    <button type="submit" class="btn btn-success btn-lg rounded-pill px-5 hover-lift shadow-sm w-100">
                        Create Guest Identity <i class="bi bi-person-check ms-2"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
