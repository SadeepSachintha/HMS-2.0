@extends('layouts.app')

@section('title', '| Edit Property')

@section('content')
<div class="row justify-content-center mb-5">
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-white fw-bold mb-0">Edit Property: {{ $hotel->name }}</h2>
            <a href="{{ route('hotels.index') }}" class="btn btn-outline-light rounded-pill px-4 hover-lift">
                <i class="bi bi-arrow-left me-2"></i> Inventory
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
                <form action="{{ route('hotels.update', $hotel) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <h5 class="text-info mb-4 border-bottom border-secondary pb-2 border-opacity-25">General Information</h5>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-light">Property Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $hotel->name) }}" class="form-control bg-dark text-white border-secondary" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-light">Official Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" value="{{ old('email', $hotel->email) }}" class="form-control bg-dark text-white border-secondary" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-light">Contact Phone</label>
                            <input type="text" name="phone" value="{{ old('phone', $hotel->phone) }}" class="form-control bg-dark text-white border-secondary">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-light">Star Rating</label>
                            <select name="stars" class="form-select bg-dark text-white border-secondary">
                                @for($i=1; $i<=5; $i++)
                                    <option value="{{ $i }}" {{ $hotel->stars == $i ? 'selected' : '' }}>{{ $i }} Stars</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <h5 class="text-info mb-4 border-bottom border-secondary pb-2 border-opacity-25">Location Details</h5>

                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="form-label text-light">Street Address</label>
                            <input type="text" name="address" value="{{ old('address', $hotel->address) }}" class="form-control bg-dark text-white border-secondary">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-light">City</label>
                            <input type="text" name="city" value="{{ old('city', $hotel->city) }}" class="form-control bg-dark text-white border-secondary">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-light">Country</label>
                            <input type="text" name="country" value="{{ old('country', $hotel->country) }}" class="form-control bg-dark text-white border-secondary">
                        </div>
                    </div>

                    <h5 class="text-info mb-4 border-bottom border-secondary pb-2 border-opacity-25">Operation Metadata</h5>

                    <div class="row g-3">
                        <div class="col-md-12 mb-3">
                            <label class="form-label text-light">Description</label>
                            <textarea name="description" rows="3" class="form-control bg-dark text-white border-secondary">{{ old('description', $hotel->description) }}</textarea>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label text-light">System Status</label>
                            <select name="status" class="form-select bg-dark text-white border-secondary">
                                <option value="active" {{ $hotel->status == 'active' ? 'selected' : '' }}>Active (Visible)</option>
                                <option value="maintenance" {{ $hotel->status == 'maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                                <option value="inactive" {{ $hotel->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-warning text-dark fw-bold btn-lg rounded-pill px-5 hover-lift shadow-sm w-100 mt-2">
                        Commit Changes <i class="bi bi-save ms-2"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
