@extends('layouts.app')

@section('title', '| Edit System User')

@section('content')
<div class="row justify-content-center mb-5">
    <div class="col-md-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-white fw-bold mb-0">System Identity Mutation</h2>
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

        @if(session('error'))
        <div class="alert alert-warning alert-dismissible fade show rounded-4 glass-panel border-warning mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> <strong>Restriction:</strong> {{ session('error') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 glass-panel border-success mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> <strong>Success:</strong> {{ session('success') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="row g-4">
            <div class="col-md-7">
                <div class="card glass-panel border-0 border-top border-4 border-info">
                    <div class="card-body p-5">
                        <h5 class="text-info mb-4 border-bottom border-secondary pb-2 border-opacity-25"><i class="bi bi-braces-asterisk me-2"></i> Identity Configuration</h5>
                        <form action="{{ route('users.update', $user) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-3">
                                <label class="form-label text-light">Clearance Level (Role) <span class="text-danger">*</span></label>
                                <select name="role_id" class="form-select bg-dark text-white border-secondary" required {{ Auth::id() === $user->id ? 'disabled' : '' }}>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                @if(Auth::id() === $user->id)
                                    <small class="text-warning"><i class="bi bi-lock"></i> Anti-Lockout protocol prevents muting your own clearance.</small>
                                    <input type="hidden" name="role_id" value="{{ $user->role_id }}"> <!-- Preserve array binding -->
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-light">Entity Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control bg-dark text-white border-secondary" value="{{ $user->name }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-light">Primary Contact Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control bg-dark text-white border-secondary" value="{{ $user->email }}" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-light">Telecommunication <span class="text-secondary">(Optional)</span></label>
                                <input type="text" name="phone" class="form-control bg-dark text-white border-secondary" value="{{ $user->phone }}">
                            </div>

                            <button type="submit" class="btn btn-info text-dark fw-bold btn-lg rounded-pill px-5 hover-lift shadow-sm w-100">
                                Mutate Parameters <i class="bi bi-cloud-upload ms-2"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Force Password Reset Matrix -->
            <div class="col-md-5">
                <div class="card glass-panel border-0 border-top border-4 border-danger h-100">
                    <div class="card-body p-4">
                        <h5 class="text-danger mb-4 border-bottom border-secondary pb-2 border-opacity-25"><i class="bi bi-shield-lock me-2"></i> Force Authentication Reset</h5>
                        <p class="text-muted small mb-4">Administrators maintain the authority to aggressively override user credentials if an absolute system lock is required, or a user has lost access vectors.</p>
                        
                        <form action="{{ route('users.password.force', $user) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label text-light">Inject New Password</label>
                                <input type="password" name="password" class="form-control bg-dark text-white border-danger" placeholder="Minimum 8 sequence elements" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label text-light">Confirm Password Vector</label>
                                <input type="password" name="password_confirmation" class="form-control bg-dark text-white border-danger" placeholder="Repeat sequence" required>
                            </div>
                            <button type="submit" class="btn btn-outline-danger btn-lg rounded-pill px-4 hover-lift shadow-sm w-100">
                                Enforce Override
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
