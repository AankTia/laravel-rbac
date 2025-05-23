@extends('layouts.dashboard')
@section('title', $viewData['title'] . " | Laravel RBAC")
@section('pageTitle', $viewData['title'])

@section('pageAction')
<div class="row mb-4 align-items-center">
    <div class="col-md-12 mt-3 mt-md-0">
        @if(isUserCan('read', 'user'))
        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary me-2">
            <i class="bx bx-left-arrow-alt me-1"></i> Back to List
        </a>
        @endif

        @if(isUserCan('update', 'user'))
        <a href="{{ route('users.edit', ['user' => $user]) }}" class="btn btn-primary">
            <i class="bx bx-pencil me-1"></i> Edit
        </a>
        @endif

        @if (!$user->isSuperAdmin())
        @if ($user->is_active && isUserCan('deactivate', 'use'))
        <form action="{{ route('users.deactivate', $user) }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" onclick="return confirm('Are you sure?')" class="btn btn-warning">
                <i class="bx bx-user-x me-1"></i> Deactivate
            </button>
        </form>
        @endif

        @if (!$user->is_active && isUserCan('activate', 'use'))
        <form action="{{ route('users.activate', $user) }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" onclick="return confirm('Are you sure?')" class="btn btn-info">
                <i class="bx bx-user-check me-1"></i> Activate
            </button>
        </form>
        @endif
        @endif

        @if(isUserCan('delete', 'user'))
        <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline;">
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