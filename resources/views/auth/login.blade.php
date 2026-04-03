@extends('layouts.app')

@section('title', '| Authentication')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="col-md-5 position-relative">
        <!-- Decorative Glow -->
        <div class="position-absolute bg-primary rounded-circle" style="width: 250px; height: 250px; top: -30px; right: -30px; filter: blur(70px); opacity: 0.4;"></div>
        <div class="position-absolute bg-info rounded-circle" style="width: 200px; height: 200px; bottom: -20px; left: -20px; filter: blur(60px); opacity: 0.3;"></div>
        
        <div class="card glass-panel border-0 p-5 shadow-lg">
            <div class="card-body">
                <div class="text-center mb-4">
                    <h3 class="text-white fw-bold mb-1">Welcome Back</h3>
                    <p class="text-light opacity-75 small">Enter your credentials to access the system.</p>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger bg-danger bg-opacity-25 border-0 text-white rounded-3 py-2 px-3 small mb-4">
                        <i class="bi bi-exclamation-circle me-1"></i> Invalid credentials provided.
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="email" class="form-label text-light small fw-bold text-uppercase">Email Address</label>
                        <input id="email" type="email" class="form-control bg-dark text-white border-secondary border-opacity-50 py-2 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="admin@hms.com">
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label text-light small fw-bold text-uppercase d-flex justify-content-between">
                            <span>Password</span>
                        </label>
                        <input id="password" type="password" class="form-control bg-dark text-white border-secondary border-opacity-50 py-2 @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="••••••••">
                    </div>

                    <div class="mb-4 form-check">
                        <input class="form-check-input bg-dark border-secondary" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label text-light opacity-75 small" for="remember">
                            {{ __('Keep me signed in') }}
                        </label>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill hover-lift shadow fw-bold">
                            Authenticate <i class="bi bi-box-arrow-in-right ms-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
