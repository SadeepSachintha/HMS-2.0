@extends('layouts.app')

@section('title', '| Create Account')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="col-md-6 position-relative">
        <div class="position-absolute bg-primary rounded-circle" style="width: 250px; height: 250px; top: -30px; left: -30px; filter: blur(70px); opacity: 0.3;"></div>
        <div class="position-absolute bg-success rounded-circle" style="width: 200px; height: 200px; bottom: -20px; right: -20px; filter: blur(60px); opacity: 0.2;"></div>
        
        <div class="card glass-panel border-0 p-5 shadow-lg">
            <div class="card-body">
                <div class="text-center mb-4">
                    <h3 class="text-white fw-bold mb-1">Join the System</h3>
                    <p class="text-light opacity-75 small">Create an account to handle properties.</p>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger bg-danger bg-opacity-25 border-0 text-white rounded-3 py-2 px-3 small mb-4">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label text-light small fw-bold text-uppercase">Full Identity</label>
                        <input id="name" type="text" class="form-control bg-dark text-white border-secondary border-opacity-50 py-2" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label text-light small fw-bold text-uppercase">Email Address</label>
                        <input id="email" type="email" class="form-control bg-dark text-white border-secondary border-opacity-50 py-2" name="email" value="{{ old('email') }}" required autocomplete="email">
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="password" class="form-label text-light small fw-bold text-uppercase">Password</label>
                            <input id="password" type="password" class="form-control bg-dark text-white border-secondary border-opacity-50 py-2" name="password" required autocomplete="new-password">
                        </div>
                        <div class="col-md-6">
                            <label for="password-confirm" class="form-label text-light small fw-bold text-uppercase">Confirm</label>
                            <input id="password-confirm" type="password" class="form-control bg-dark text-white border-secondary border-opacity-50 py-2" name="password_confirmation" required autocomplete="new-password">
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-outline-light btn-lg rounded-pill hover-lift shadow fw-bold">
                            Initialize Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
