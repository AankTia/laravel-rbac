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

                            @if (isUserCan('delete', 'user'))
                                {!! deleteButton(route('users.destroy', $user)) !!}
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
                                    <div class="mb-2">{{ $user->getRoleName() ?? '-' }}</div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <h3 class="h6 text-muted">Status</h3>
                                    <div class="mb-2">{!! userStatusBadge($user->is_active) !!}</div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <h3 class="h6 text-muted">Last Login</h3>
                                    <div class="mb-2">{{ humanDateTime($lastLogin) }}</div>
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
                        @forelse ($userActivityLogs as $log)
                            <li class="timeline-item timeline-item-transparent">
                                <span class="timeline-point {{ actionTimelinePointColor($log->action) }}"></span>
                                <div class="timeline-event">
                                    <div class="timeline-header mb-3">
                                        <h6 class="mb-0">{{ $log->user_description }}</h6>
                                        <small class="text-muted">{{ humanDateTime($log->created_at) }}</small>
                                    </div>

                                    <div class="accordion mt-3 mb-3" id="detailHistoryAccordion{{ $log->id }}">
                                        <div class="card accordion-item">
                                            <h2 class="accordion-header" id="headingOne">
                                                <button type="button" class="accordion-button collapsed"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#accordion{{ $log->id }}" aria-expanded="false"
                                                    aria-controls="accordion{{ $log->id }}">
                                                    Details
                                                </button>
                                            </h2>

                                            <div id="accordion{{ $log->id }}" class="accordion-collapse collapse"
                                                data-bs-parent="#detailHistoryAccordion{{ $log->id }}" style="">
                                                <div class="accordion-body">
                                                    <div class="row">
                                                        @if (in_array($log->action, ['create', 'delete']))
                                                            @include(
                                                                'activity_log_histories._create_details',
                                                                [
                                                                    'log_name' => $log->log_name,
                                                                    'attributes' =>
                                                                        $log->subject_properties['attributes'],
                                                                ]
                                                            )
                                                        @elseif (in_array($log->action, ['update', 'update-user-role', 'update-role-permission']))
                                                            @include(
                                                                'activity_log_histories._update_details',
                                                                [
                                                                    'log_name' => $log->log_name,
                                                                    'attributes' =>
                                                                        $log->subject_properties['attributes'],
                                                                ]
                                                            )
                                                        @endif
                                                        </row>

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
                                            </div>
                                        </div>
                                        <hr>
                                    </div>
                            </li>
                        @empty
                            No Activity History
                        @endforelse
                    </ul>

                    {{-- Pagination --}}
                    {{ $userActivityLogs->links('vendor.pagination.custom') }}
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
                                    {{ $lastActivity->subject_description }}
                                </div>

                                {{-- @include('activity_logs.partials._details', ['activity' => $lastActivity]) --}}

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
