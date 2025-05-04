@extends('layouts.dashboard')

@section('title', $viewData['title'] . " | Laravel RBAC")
@section('pageTitle', $viewData['title'])
{{-- @section('pageSubTitle', $viewData['subtitle'] . " Role Details") --}}

@section('pageAction')
<div class="row mb-4 align-items-center">
    <div class="col-md-12 mt-3 mt-md-0">
        @if(auth()->user()->hasPermission('read', 'roles'))
        <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary me-2">
            <i class="bx bx-left-arrow-alt me-1"></i> Back to List
        </a>
        @endif

        @if(auth()->user()->hasPermission('update', 'roles'))
        <a href="{{ route('roles.edit', ['role' => $role]) }}" class="btn btn-primary">
            <i class="bx bx-pencil me-1"></i> Edit
        </a>

        <a href="{{ route('roles.edit-permissions', $role) }}" class="btn btn-warning">
            <i class="bx bx-plus-circle me-2"></i> Update Permissions
        </a>
        @endif

        @if(auth()->user()->hasPermission('delete', 'roles'))
        <form action="{{ route('roles.destroy', $role) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" onclick="return confirm('Are you sure?')" class="btn btn-danger">
                <i class="bx bx-trash me-1"></i> Delete
            </button>
        </form>
        @endif
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
                    <h3 class="h6 text-muted">Identifier</h3>
                    <p>{{ $role->slug }}</p>
                </div>

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
                    <h3 class="h6 text-muted">Allow to be assigned to users</h3>
                    <p>
                        @if ($role->allow_to_be_assigne)
                        <span class="badge bg-label-primary">Allowed</span>
                        @else
                        <span class="badge bg-label-secondary">Not Allowed</span>
                        @endif
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card bg-light">
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="small text-muted">Created By</div>
                            <div>{{ $role->creatorName() }}</div>
                        </div>

                        <div class="mb-3">
                            <div class="small text-muted">Created On</div>
                            <div>{{ $role->createdAt() }}</div>
                        </div>

                        <div class="mb-3">
                            <div class="small text-muted">Last Updated By</div>
                            <div>{{ $role->lastUpdaterName() ?? '-' }}</div>
                        </div>

                        <div class="mb-3">
                            <div class="small text-muted">Last Updated</div>
                            <div>{{ $role->lastUpdate() ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="row">
            <h2 class="card-title h4 mb-4">Assigned Users</h2>
            <div class="mb-4">
                <p>This role is currently assigned to <strong>{{ $role->getTotalUsers() }} users</strong> in the system.</p>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>User</th>
                            <th class="text-center">Assigned At</th>
                            <th class="text-center">Assigned By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($role->roleUsers as $roleUser)
                        <tr>
                            <td class="fw-medium">
                                <div class="user-info">
                                    <div class="user-avatar">
                                        {{ $roleUser->user->initialName() }}
                                    </div>
                                    <div class="user-details">
                                        <p class="user-name"><a href="{{ route('users.show', $roleUser->user) }}">{{ $roleUser->user->name }}</a></p>
                                        <p class="user-email">{{ $roleUser->user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="fw-medium text-center" nowrap>{{ $roleUser->getFormatedAssignedAt() }}</td>
                            <td class="fw-medium text-center" nowrap>{{ $roleUser->getAssignedByName() }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="row">
            <h2 class="card-title h4 mb-4">Allowed Permissions</h2>

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
                        @foreach ($modulePermissions as $moduleName => $permissions)
                        <tr>
                            <td class="fw-medium">{{ $moduleName }}</td>
                            <td class="text-center">
                                @if ($permissions['read'])
                                <i class="bx bxs-check-circle text-success"></i>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($permissions['create'])
                                <i class="bx bxs-check-circle text-success"></i>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($permissions['update'])
                                <i class="bx bxs-check-circle text-success"></i>
                                @endif
                            </td>

                            <td class="text-center">
                                @if ($permissions['delete'])
                                <i class="bx bxs-check-circle text-success"></i>
                                @endif
                            </td>
                            <td nowrap>
                                @foreach ($permissions['others'] as $otherPermission)
                                <div>
                                    <i class="bx bxs-check-circle text-success"></i> {{ $otherPermission }}
                                </div>
                                @endforeach
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection