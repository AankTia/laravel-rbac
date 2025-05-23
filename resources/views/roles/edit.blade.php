@extends('layouts.dashboard')

@section('title', "Edit " . $role->name . " Role | Laravel RBAC")

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-style1">
        <li class="breadcrumb-item">
            <a href="javascript:void(0);">User Management</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('roles.index') }}">Role</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('roles.show', $role) }}">{{ $role->name }}</a>
        </li>
        <li class="breadcrumb-item active">
            Edit
        </li>
    </ol>
</nav>
@endsection

@section('pageAction')
<div class="row mb-4 align-items-center">
    <div class="col-md-12 mt-3 mt-md-0">
        @if(isUserCan('read', 'role'))
        {!! backButton(route('roles.show', $role), 'Back to Role Detail') !!}
        @endif
    </div>
</div>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h5 class="card-title m-0 me-2">Edit {{ $role->name }}</h5>
        <hr>
    </div>
    <div class="card-body">
        <form method="post" action="{{ route('roles.update', $role) }}">
            @csrf
            @method('PUT')

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">{{ $attributeLabels['name'] }} <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Enter role name" value="{{ old('name', $role->name) }}" required>
                        @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">{{ $attributeLabels['description'] }} <span class="text-danger">*</span></label>
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
                        <label class="form-label">{{ $attributeLabels['allow_to_be_assigne'] }}</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="allow_to_be_assigne" id="allow_to_be_assigne" {{ old('allow_to_be_assigne', $role->allow_to_be_assigne) ? 'checked' : '' }}>
                            <label class="form-check-label" for="allow_to_be_assigne">Allow this role to be assigned to users</label>
                        </div>
                    </div>
                </div>
            </div>
            <hr>

            <div class="text-end">
                {!! cancelButton(route('roles.show', $role)) !!}
                {!! submitEditButton() !!}
            </div>
        </form>
    </div>
</div>
@endsection