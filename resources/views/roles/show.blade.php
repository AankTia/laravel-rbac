@extends('layouts.dashboard')

@section('title', $viewData['title'] . " | Laravel RBAC")
@section('pageTitle', $viewData['title'])
@section('pageSubTitle', $viewData['subtitle'] . " Role Details")

@section('pageAction')
<div class="row mb-4 align-items-center">
    <div class="col-md-12 mt-3 mt-md-0">
        <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary me-2">
            <i class="bx bx-left-arrow-alt me-1"></i> Back to List
        </a>
        <a href="{{ route('roles.edit', ['role' => $role]) }}" class="btn btn-primary">
            <i class="bx bx-pencil me-1"></i> Edit Role
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <h2 class="card-title h4 mb-4">{{ $role->name }}</h2>

                <div class="mb-4">
                    <h3 class="h6 text-muted">Description</h3>
                    <p>{{ $role->description }}</p>
                </div>

                {{-- <div class="mb-4">
                    <h3 class="h6 text-muted">Assigned Permissions</h3>
                    <div class="d-flex flex-wrap gap-2 mt-2">
                        <span class="badge bg-success">Create</span>
                        <span class="badge bg-primary">Read</span>
                        <span class="badge bg-warning text-dark">Update</span>
                        <span class="badge bg-danger">Delete</span>
                        <span class="badge bg-info text-dark">Export</span>
                        <span class="badge bg-secondary">Import</span>
                        <span class="badge bg-dark">Configure</span>
                    </div>
                </div> --}}

                <div class="mb-4">
                    <h3 class="h6 text-muted">User Assignment</h3>
                    <p>This role is currently assigned to <strong>{{ $role->users->count() }} users</strong> in the system.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card bg-light">
                    <div class="card-body">
                        <h3 class="h6 text-muted mb-3">Role Information</h3>

                        <div class="mb-3">
                            <div class="small text-muted">Role Identifier</div>
                            <div>{{ $role->slug }}</div>
                        </div>

                        <div class="mb-3">
                            <div class="small text-muted">Created On</div>
                            <div>{{ $role->created_at->format('d M Y, h:i A') }}</div>
                        </div>

                        <div class="mb-3">
                            <div class="small text-muted">Created By</div>
                            <div>-</div>
                        </div>

                        <div class="mb-3">
                            <div class="small text-muted">Last Updated</div>
                            <div>{{ $role->updated_at->format('d M Y, h:i A') }}</div>
                        </div>

                        <div class="mb-3">
                            <div class="small text-muted">Last Updated By</div>
                            <div>-</div>
                        </div>

                        {{-- <div class="mb-3">
                            <div class="small text-muted">Status</div>
                            <div><span class="badge bg-success">Active</span></div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h3 class="h5 mb-0">Permission Details</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Module</th>
                                <th class="text-center">Read</th>
                                <th class="text-center">Create</th>
                                <th class="text-center">Update</th>
                                <th class="text-center">Delete</th>
                                <th class="text-center">Special Privileges</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($modules as $module)
                            <tr>
                                <td class="fw-medium">{{ $module->name }}</td>
                                <th class="text-center">
                                    @if ($role->hasPermission('read', $module->slug))
                                    <i class="bx bxs-check-circle text-success"></i>
                                    @endif
                                </th>
                                <th class="text-center">
                                    @if ($role->hasPermission('create', $module->slug))
                                    <i class="bx bxs-check-circle text-success"></i>
                                    @endif
                                </th>
                                <th class="text-center">
                                    @if ($role->hasPermission('update', $module->slug))
                                    <i class="bx bxs-check-circle text-success"></i>
                                    @endif
                                </th>
                                <th class="text-center">
                                    @if ($role->hasPermission('delete', $module->slug))
                                    <i class="bx bxs-check-circle text-success"></i>
                                    @endif
                                </th>
                                <th class="text-center">
                                    {{-- Special Privileges --}}
                                </th>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h3 class="h5 mb-0">Assigned Users</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">Assigned Date</th>
                                <th class="text-center">Assigned By</th>
                                {{-- <th class="text-center">Email</th> --}}
                                {{-- <th class="text-center">Update</th> --}}
                                {{-- <th class="text-center">Delete</th> --}}
                                {{-- <th class="text-center">Special Privileges</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($role->users as $user)
                            <tr>
                                <td class="fw-medium">{{ $user->name }}</td>
                                <td class="fw-medium">{{ $user->email }}</td>
                                <td class="fw-medium"></td>
                                <td class="fw-medium"></td>
                                {{-- <th class="text-center">
                                    @if ($role->hasPermission('read', $module->slug))
                                        <i class="bx bxs-check-circle text-success"></i>
                                    @endif
                                </th> --}}
                                {{-- <th class="text-center">
                                    @if ($role->hasPermission('create', $module->slug))
                                        <i class="bx bxs-check-circle text-success"></i>
                                    @endif
                                </th> --}}
                                {{-- <th class="text-center">
                                    @if ($role->hasPermission('update', $module->slug))
                                        <i class="bx bxs-check-circle text-success"></i>
                                    @endif
                                </th> --}}
                                {{-- <th class="text-center">
                                    @if ($role->hasPermission('delete', $module->slug))
                                        <i class="bx bxs-check-circle text-success"></i>
                                    @endif
                                </th> --}}
                                {{-- <th class="text-center">
                                    Special Privileges
                                </th> --}}
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection