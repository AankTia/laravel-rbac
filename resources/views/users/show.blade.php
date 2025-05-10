@extends('layouts.dashboard')
@section('title', $user->name . " Detail | Laravel RBAC")

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-style1">
        <li class="breadcrumb-item">
            <a href="javascript:void(0);">User Management</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('users.index') }}">User</a>
        </li>
        <li class="breadcrumb-item active">
            {{ $user->name }}
        </li>
    </ol>
</nav>
@endsection

@section('pageAction')
<div class="row mb-4 align-items-center">
    <div class="col-md-12 mt-3 mt-md-0">
        {!! backButton(route('users.index'), 'user.read', 'Back to Users') !!}
        {!! createButton(route('users.create'), 'user.create', 'User') !!}
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h5 class="card-title m-0 me-2">{{ $user->name }} Detail</h5>

                    <div class="text-end">
                        @if(isUserCan('update', 'role'))
                        {!! editButton(route('roles.edit', ['role' => $user])) !!}
                        @endif

                        @if (!$user->isSuperAdmin())
                        @if ($user->is_active && isUserCan('deactivate', 'user'))
                        <form action="{{ route('users.deactivate', $user) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-warning">
                                <i class="bx bx-user-x me-1"></i> Deactivate
                            </button>
                        </form>
                        @endif

                        @if (!$user->is_active && isUserCan('activate', 'user'))
                        <form action="{{ route('users.activate', $user) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-info">
                                <i class="bx bx-user-check me-1"></i> Activate
                            </button>
                        </form>
                        @endif
                        @endif

                        @if(isUserCan('delete', 'role'))
                        {!! deleteButton(route('roles.destroy', $user)) !!}
                        @endif
                    </div>
                </div>
                <hr>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <img src="{{ asset('assets/img/man-avatar.jpg') }}" alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar">
                    </div>
                    <div class="col-md-6">
                        <div class="col-md-6 mb-4">
                            <h3 class="h6 text-muted">Name</h3>
                            <div class="mb-2">{{ $user->name }}</div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <h3 class="h6 text-muted">Email</h3>
                            <div class="mb-2">{{ $user->email }}</div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <h3 class="h6 text-muted">Status</h3>
                            <div class="mb-2">{!! userStatusBadge($user->is_active) !!}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title m-0 me-2">Last Login</h5>
                <hr>
            </div>
            <div class="card-body">
                ...
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title m-0 me-2">Created</h5>
                <hr>
            </div>
            <div class="card-body">
                ...
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title m-0 me-2">Last Updated</h5>
                <hr>
            </div>
            <div class="card-body">
                ...
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- User Profile Card -->
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="text-center">
                    <img src="{{ asset('assets/img/man-avatar.jpg') }}" alt="User Avatar" class="user-image-profile mb-4">
                    <h4>{{ $user->name }}</h4>
                    <p class="text-muted">{{ $user->email }}</p>
                    <div class="d-flex justify-content-center mb-3">
                        <span class="badge bg-primary p-2" id="userRole">{{ $user->getRoleName() }}</span>
                    </div>
                </div>

                <div class="row text-center">
                    <div class="col">
                        <span class="d-block fw-bold">Last Login</span>
                        <small class="text-muted" id="lastLogin">...</small>
                    </div>
                    <div class="col">
                        <span class="d-block fw-bold">Status</span>
                        @if ($user->is_active)
                        <span class="badge rounded-pill bg-success">Active</span>
                        @else
                        <span class="badge rounded-pill bg-danger">Inactive</span>
                        @endif
                    </div>
                </div>

                <div class="card bg-light mt-4">
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="small text-muted">Created By</div>
                            <div>{{ $user->creatorName() }}</div>
                        </div>

                        <div class="mb-3">
                            <div class="small text-muted">Created On</div>
                            <div>{{ $user->createdAt() }}</div>
                        </div>

                        <div class="mb-3">
                            <div class="small text-muted">Last Updated By</div>
                            <div>{{ $user->lastUpdaterName() ?? '-' }}</div>
                        </div>

                        <div class="mb-3">
                            <div class="small text-muted">Last Updated</div>
                            <div>{{ $user->lastUpdate() ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Details -->
    <div class="col-md-8">
        <!-- Activity Log -->
        <div class="mb-4">
            <h4 class="fw-bold">Recent Activity</h4>
        </div>

        <div class="position-relative ps-4 mt-4">
            <div class="timeline-line"></div>
            @foreach ($recentActivities as $activity)
            <div class="mb-4 d-flex align-items-start gap-3">
                <div class="timeline-icon {{ $activity->getActionTextColor() }}">
                    <i class="{{ $activity->getActionIcon() }}"></i>
                </div>
                <div>
                    <strong>{{ $activity->description }}</strong><br>

                    <small class="text-muted">
                        <i class="{{ clockIcon() }}"></i> {{ $activity->created_at }}
                    </small>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection