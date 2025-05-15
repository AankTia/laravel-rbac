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
            {!! permittedBackButton(route('users.index'), 'user.read', 'Back to Users') !!}
            {!! permittedCreateButton(route('users.create'), 'user.create', 'User') !!}
        </div>
    </div>
@endsection

@section('content')
    <style>
    </style>

    <div class="row">
        <div class="col-md-9">
            <div class="card card-action shadow-sm mb-4">
                <div class="card-header align-items-center flex-wrap gap-2">
                    <h5 class="card-action-title mb-0">{{ $user->name }} Detail</h5>
                    <div class="card-action-element">
                        {!! permittedEditButton(route('users.edit', $user), 'update', 'user') !!}

                        @if (!$user->isSuperAdmin())
                            @if (!$user->is_active)
                                {!! permittedActivateButton(route('users.activate', $user), 'activate', 'user') !!}
                            @endif

                            @if ($user->is_active)
                                {!! permittedDeactivateButton(route('users.deactivate', $user), 'deactivate', 'user') !!}
                            @endif
                        @endif

                        {!! permittedDeleteButton(route('users.destroy', $user), 'delete', 'user') !!}
                    </div>
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
                                <div class="col-xl-12 col-12">
                                    <dl class="row mb-0 gx-2">
                                        <dt class="col-sm-2 mb-sm-2 text-nowrap fw-medium text-heading">Name</dt>
                                        <dd class="col-sm-10">{{ $user->name }}</dd>

                                        <dt class="col-sm-2 mb-sm-2 text-nowrap fw-medium text-heading">Email</dt>
                                        <dd class="col-sm-10">{{ $user->email }}</dd>

                                        <dt class="col-sm-2 mb-sm-2 text-nowrap fw-medium text-heading">Role</dt>
                                        <dd class="col-sm-10">{{ $user->getRoleName() ?? '-' }}</dd>

                                        <dt class="col-sm-2 mb-sm-2 text-nowrap fw-medium text-heading">Status</dt>
                                        <dd class="col-sm-10">{!! userStatusBadge($user->is_active) !!}</dd>

                                        <dt class="col-sm-2 mb-sm-2 text-nowrap fw-medium text-heading">Last Login</dt>
                                        <dd class="col-sm-10">{{ $lastLogin ?? '-' }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="card-title m-0 me-2 mb-2">Activity Timeline</h5>
                </div>
                <div class="card-body pt-0">
                    <ul class="timeline mb-0">
                        @forelse ($userActivityLogs as $log)
                            <li class="timeline-item timeline-item-transparent">
                                <span class="timeline-point {{ actionTimelinePointColor($log->action) }}"></span>
                                <div class="timeline-event">
                                    <div class="timeline-header mb-3">
                                        <small class="text-muted">{{ humanDateTime($log->created_at) }}</small>
                                    </div>

                                    <div class="accordion mt-0 mb-3" id="detailHistoryAccordion{{ $log->id }}">
                                        <div class="card accordion-item">
                                            <h2 class="accordion-header" id="headingOne">
                                                <button type="button" class="accordion-button collapsed"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#accordion{{ $log->id }}" aria-expanded="false"
                                                    aria-controls="accordion{{ $log->id }}">
                                                    {{ $log->user_description }}
                                                </button>
                                            </h2>

                                            <div id="accordion{{ $log->id }}" class="accordion-collapse collapse"
                                                data-bs-parent="#detailHistoryAccordion{{ $log->id }}" style="">
                                                <div class="accordion-body">
                                                    <hr>
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

                                                        <div class="row">
                                                            <div class="col-md-12 mt-0 mb-3">
                                                                <h3 class="h6 text-muted mb-2">IP Address</h3>
                                                                <small>{{ $log->user_properties['ip_address'] }}</small>
                                                            </div>

                                                            <div class="col-md-12 mb-3">
                                                                <h3 class="h6 text-muted mb-2">User Agent</h3>
                                                                <small>{{ $log->user_properties['user_agent'] }}</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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

        <div class="col-md-3">
            @if ($lastActivity)
                <div class="card card-action mb-4">
                    <div class="card-header align-items-center flex-wrap gap-2">
                        <h5 class="card-action-title">Latest History</h5>
                        <div class="card-action-element">
                            {!! permittedReadHistoriesButton('#', 'read-log-history', 'user') !!}
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <dl class="row">
                                <dd>
                                    <i class="{{ getIcon('clock') }}"></i>
                                    {{ humanDateTime($lastActivity->crated_at) }}
                                </dd>

                                <dd>
                                    <i class="{{ getIcon('user') }}"></i>
                                    {{ $lastActivity->user->name }}
                                </dd>
                            </dl>

                            <div class="mb-2">
                                <div class="alert alert-{{ getActionColor($lastActivity->action) }} mt-0" role="alert">
                                    <span>{!! $lastActivity->subject_description !!}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
