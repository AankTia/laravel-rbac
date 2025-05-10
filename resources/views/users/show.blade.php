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
<style>
    .profile-image {
        width: 100px;
        height: 100px;
        border-radius: 10px;
        overflow: hidden;
        margin-right: 20px;
        background-color: #c2beff;
    }

    .profile-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>

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
                    <div class="col-md-2">
                        <div class="profile-image">
                            <img src="{{ asset('assets/img/man-avatar.jpg') }}" alt="Profile Photo">
                        </div>
                    </div>
                    <div class="col-md-10">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <h3 class="h6 text-muted">Name</h3>
                                <div class="mb-2">{{ $user->name }}</div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <h3 class="h6 text-muted">Email</h3>
                                <div class="mb-2">{{ $user->email }}</div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <h3 class="h6 text-muted">Role</h3>
                                <div class="mb-2">{{ $user->getRoleName() }}</div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <h3 class="h6 text-muted">Status</h3>
                                <div class="mb-2">{!! userStatusBadge($user->is_active) !!}</div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <h3 class="h6 text-muted">Last Login</h3>
                                <div class="mb-2">...</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">

        @if ($user->creatorName() || $user->createdAt())
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title m-0 me-2 mb-2">Created</h5>
                <hr>

                <div class="d-flex align-items-center justify-content-between">
                    @if ($user->createdAt())
                    <em><i class="{{ clockIcon() }}"></i> {{ humanDateTime($user->created_at) }}</em>
                    @endif
                    @if ($user->creatorName())
                    <em><i class="{{ userIcon() }}"></i> {{ $user->creatorName() }}</em>
                    @endif
                </div>
            </div>
        </div>


        @if ($user->lastUpdaterName() || $user->lastUpdate())
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title m-0 me-2 mb-2">Last Updated</h5>
                <hr>
                <div class="d-flex align-items-center justify-content-between">
                    <em><i class="{{ clockIcon() }}"></i> {{ humanDateTime($user->updated_at) }}</em>
                    <em><i class="{{ userIcon() }}"></i> {{ $user->lastUpdaterName() }}</em>
                </div>

                {{-- <hr /> --}}

                <div class="mt-4 mb-4">
                    {{ $lastActivity->description }}
                </div>

                @include('activity_logs.partials._details', ['activity' => $lastActivity])

                <a href="{{ route("users.activity-logs", $user) }}" class="btn btn-sm btn-outline-primary mt-4">
                    <i class="{{ historyIcon() }}"></i> Show All Histories
                </a>
            </div>
        </div>
        @endif
        @endif
    </div>
</div>
@endsection