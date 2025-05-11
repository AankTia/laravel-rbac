@extends('layouts.dashboard')

@section('title', "Edit ". $user->name ." | Laravel RBAC")

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-style1">
        <li class="breadcrumb-item">
            <a href="javascript:void(0);">User Management</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('users.index') }}">User</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('users.show', $user) }}">{{ $user->name }}</a>
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
        {!! backButton(route('users.show', $user), 'user.read', 'Back to User Detail') !!}
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h5 class="card-title m-0 me-2">Edit {{ $user->name }}</h5>
                <hr>
            </div>
            <div class="card-body">
                <form method="post" action="{{ route('users.update', $user) }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3 col-md-11">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror" autofocus="">
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-11">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror">
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-11">
                                @php
                                $status = $user->is_active ? 'active' : 'inactive';
                                $selectedIsActive = old('is_active', $status ?? '');
                                @endphp

                                <label for="language" class="form-label">Status</label>
                                <select name="is_active" class="form-control @error('is_active') is-invalid @enderror" aria-label="Role select">
                                    <option value="" {{ $selectedIsActive === '' ? 'selected' : '' }}>-- Select Status --</option>
                                    @foreach (['active' => 'Active', 'inactive' => 'Inactive'] as $id => $name)
                                    <option value="{{ $id }}" {{ $selectedIsActive == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('is_active')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-11">
                                @php
                                $selectedRole = old('role_id', $user->getRoleId() ?? '');
                                @endphp

                                <label for="role_id" class="form-label">Role</label>
                                <select name="role_id" class="form-control @error('role_id') is-invalid @enderror" aria-label="Role select">
                                    <option value="" {{ $selectedRole === '' ? 'selected' : '' }}>-- Select Role --</option>
                                    @foreach ($roleOptions as $id => $name)
                                    <option value="{{ $id }}" {{ $selectedRole == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('role_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="d-flex align-items-start align-items-sm-center gap-4">
                                <img src="{{ asset('assets/img/man-avatar.jpg') }}" alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar">
                                <div class="button-wrapper">
                                    <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                        <span class="d-none d-sm-block">Upload new photo</span>
                                        <i class="bx bx-upload d-block d-sm-none"></i>
                                        <input type="file" id="upload" class="account-file-input" hidden="" accept="image/png, image/jpeg">
                                    </label>

                                    <p class="text-muted mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3 col-md-11">
                                <label class="form-label" for="password">{{ __('Password') }}</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-11">
                                <label class="form-label" for="password_confirmation">{{ __('Confirm Password') }}</label>
                                <div class="input-group input-group-merge">
                                    <input id="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" autocomplete="new-password">
                                    @error('password_confirmation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>

                    <div class="text-end">
                        {!! cancelButton(route('users.show', $user)) !!}
                        {!! submitEditButton() !!}
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection