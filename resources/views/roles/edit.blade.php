@extends('layouts.dashboard')

@section('title', $viewData['title'] . " | Laravel RBAC")
@section('pageTitle', $viewData['title'])

@section('pageAction')
<div class="row mb-4 align-items-center">
    <div class="col-md-12 mt-3 mt-md-0">
        <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary me-2">
            <i class="bx bx-left-arrow-alt me-1"></i> Back to List
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-body">
        <form method="post" action="{{ route('roles.update', $role) }}">
            @csrf
            @method('PUT')

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Role Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Enter role name" value="{{ old('name', $role->name) }}" required>
                        @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="description" rows="3" placeholder="Describe the purpose of this role" required>{{ old('description', $role->description) }}</textarea>
                        @error('description')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Available for Assignment</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="allow_to_be_assigne" id="allow_to_be_assigne" {{ old('allow_to_be_assigne', $role->allow_to_be_assigne) ? 'checked' : '' }}>
                            <label class="form-check-label" for="allow_to_be_assigne">Allow this role to be assigned to users</label>
                        </div>
                    </div>

                    {{-- <div class="mb-3">
                        <label class="form-label d-block">Status</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="roleStatus" id="statusActive" value="active" checked>
                            <label class="form-check-label" for="statusActive">Active</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="roleStatus" id="statusInactive" value="inactive">
                            <label class="form-check-label" for="statusInactive">Inactive</label>
                        </div>
                    </div> --}}
                </div>
            </div>

            <div class="text-end">
                <a href="{{ route('roles.show', $role) }}" class="btn btn-outline-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-warning">Update Role</button>
            </div>
        </form>
    </div>
</div>
@endsection