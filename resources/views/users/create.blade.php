@extends('layouts.dashboard')

@section('title', $viewData['title'] . " | Laravel RBAC")
@section('pageTitle', $viewData['title'])

@section('pageAction')
<div class="row mb-4 align-items-center">
    <div class="col-md-12 mt-3 mt-md-0">
        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary me-2">
            <i class="bx bx-left-arrow-alt me-1"></i> Back to List
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3 col-md-11">
                                <label for="firstName" class="form-label">First Name</label>
                                <input class="form-control" type="text" id="firstName" name="firstName" value="John" autofocus="">
                            </div>
    
                            <div class="mb-3 col-md-11">
                                <label for="email" class="form-label">E-mail</label>
                                <input class="form-control" type="text" id="email" name="email" value="john.doe@example.com" placeholder="john.doe@example.com">
                            </div>
    
                            <div class="mb-3 col-md-11">
                                <label for="language" class="form-label">Role</label>
                                <select id="language" class="select2 form-select">
                                    <option value=""></option>
                                    <option value="en">Admin</option>
                                    <option value="fr">User</option>
                                </select>
                            </div>
    
                            <div class="mb-3 col-md-11">
                                <label for="language" class="form-label">Status</label>
                                <select id="language" class="select2 form-select">
                                    <option value=""></option>
                                    <option value="en">Active</option>
                                    <option value="fr">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 mt-3">
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
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary me-2">Save</button>
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection