@extends('layouts.dashboard')
@section('title', $user->name . ' Detail | Laravel RBAC')

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
    </style>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h5 class="card-title m-0 me-2">{{ $user->name }} Detail</h5>

                        <div class="text-end">
                            @if (isUserCan('update', 'user'))
                                {!! editButton(route('users.edit', ['user' => $user])) !!}
                            @endif

                            @if (!$user->isSuperAdmin())
                                @if ($user->is_active && isUserCan('deactivate', 'user'))
                                    <form action="{{ route('users.deactivate', $user) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        <button type="submit" onclick="return confirm('Are you sure?')"
                                            class="btn btn-sm btn-secondary">
                                            <i class="bx bx-user-x me-1"></i> Deactivate
                                        </button>
                                    </form>
                                @endif

                                @if (!$user->is_active && isUserCan('activate', 'user'))
                                    <form action="{{ route('users.activate', $user) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        <button type="submit" onclick="return confirm('Are you sure?')"
                                            class="btn btn-sm btn-info">
                                            <i class="bx bx-user-check me-1"></i> Activate
                                        </button>
                                    </form>
                                @endif
                            @endif

                            @if (isUserCan('delete', 'role'))
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

            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="card-title m-0 me-2 mb-2">Activity Timeline</h5>
                    <hr>
                </div>
                <div class="card-body pt-0">
                    <ul class="timeline mb-0">
                        {{-- <li class="timeline-item timeline-item-transparent">
                            <span class="timeline-point timeline-point-primary"></span>
                            <div class="timeline-event">
                                <div class="timeline-header mb-3">
                                    <h6 class="mb-0">12 Invoices have been paid</h6>
                                    <small class="text-muted">12 min ago</small>
                                </div>
                                <p class="mb-2">
                                    Invoices have been paid to the company
                                </p>
                                <div class="d-flex align-items-center mb-2">
                                    <div class="badge bg-lighter rounded d-flex align-items-center">
                                        <img src="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo/assets/img/icons/misc/pdf.png"
                                            alt="img" width="15" class="me-2">
                                        <span class="h6 mb-0 text-body">invoices.pdf</span>
                                    </div>
                                </div>
                            </div>
                        </li> --}}

                        {{-- <hr> --}}
                        @forelse ($userActivityLogs as $log)
                            <li class="timeline-item timeline-item-transparent">
                                <span class="timeline-point {{ actionTimelinePointColor($log->action) }}"></span>
                                <div class="timeline-event">
                                    <div class="timeline-header mb-3">
                                        <h6 class="mb-0">{{ $log->user_description }}</h6>
                                        <small class="text-muted">{{ humanDateTime($log->created_at) }}</small>
                                    </div>

                                    <div class="card shadow text-dark bg-light mb-3">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <h3 class="h6 text-muted mb-2">IP Address</h3>
                                                    <div class="mb-2">
                                                        <small>{{ $log->user_properties['ip_address'] }}</small>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <h3 class="h6 text-muted mb-2">User Agent</h3>
                                                    <div class="mb-2">
                                                        <small>{{ $log->user_properties['user_agent'] }}</small>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                            </li>
                        @empty
                            No Activity History
                        @endforelse
                    </ul>
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
                                <em><i class="{{ getIcon('clock') }}"></i> {{ humanDateTime($user->created_at) }}</em>
                            @endif
                            @if ($user->creatorName())
                                <em><i class="{{ getIcon('user') }}"></i> {{ $user->creatorName() }}</em>
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
                                <em><i class="{{ getIcon('clock') }}"></i> {{ humanDateTime($user->updated_at) }}</em>
                                <em><i class="{{ getIcon('user') }}"></i> {{ $user->lastUpdaterName() }}</em>
                            </div>

                            @if ($lastActivity)
                                <div class="mt-4 mb-4">
                                    {{ $lastActivity->description }}
                                </div>

                                @include('activity_logs.partials._details', ['activity' => $lastActivity])

                                <a href="{{ route('users.activity-logs', $user) }}"
                                    class="btn btn-sm btn-outline-primary mt-4">
                                    <i class="{{ getIcon('history') }}"></i> Show All Histories
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection
