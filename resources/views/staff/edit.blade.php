@extends('layouts.app')

@section('title', '| Edit Staff')

@section('content')
<div class="row justify-content-center mb-5">
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-white fw-bold mb-0">Modify Personnel Record</h2>
            <a href="{{ route('staff.index') }}" class="btn btn-outline-light rounded-pill px-4 hover-lift">
                <i class="bi bi-arrow-left me-2"></i> Employee Roster
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
                <form action="{{ route('staff.update', $staff) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <h5 class="text-info mb-4 border-bottom border-secondary pb-2 border-opacity-25">Location Designation</h5>
                    <div class="mb-4">
                        <label class="form-label text-light">Assigned Facility <span class="text-danger">*</span></label>
                        <select name="hotel_id" class="form-select bg-dark text-white border-secondary" required>
                            @foreach($hotels as $hotel)
                                <option value="{{ $hotel->id }}" {{ $staff->hotel_id == $hotel->id ? 'selected' : '' }}>{{ $hotel->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <h5 class="text-info mb-4 border-bottom border-secondary pb-2 border-opacity-25">Employment Status</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-light">Department <span class="text-danger">*</span></label>
                            <input type="text" name="department" class="form-control bg-dark text-white border-secondary" value="{{ $staff->department }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-light">Role Title <span class="text-danger">*</span></label>
                            <input type="text" name="position" class="form-control bg-dark text-white border-secondary" value="{{ $staff->position }}" required>
                        </div>
                    </div>
                    
                    <div class="row g-3 mb-5">
                        <div class="col-md-6">
                            <label class="form-label text-light">Yearly Salary ($) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="salary" class="form-control bg-dark text-white border-secondary" value="{{ $staff->salary }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-light">Shift Rotation <span class="text-danger">*</span></label>
                            <select name="shift" class="form-select bg-dark text-white border-secondary" required>
                                <option value="morning" {{ $staff->shift == 'morning' ? 'selected' : '' }}>Morning Shift</option>
                                <option value="evening" {{ $staff->shift == 'evening' ? 'selected' : '' }}>Evening Shift</option>
                                <option value="night" {{ $staff->shift == 'night' ? 'selected' : '' }}>Night Shift</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-warning text-dark btn-lg rounded-pill px-5 hover-lift shadow-sm w-100 mt-2 fw-bold">
                        Update Personnel Record <i class="bi bi-save ms-2"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
