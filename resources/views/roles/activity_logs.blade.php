@extends('layouts.dashboard')

@section('title', $title . " | Laravel RBAC")
@section('pageTitle', $title)

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-style1">
        <li class="breadcrumb-item">
            <a href="javascript:void(0);">User Management</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('roles.index') }}">Role</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('roles.show', $role) }}">{{ $role->name }}</a>
        </li>
        <li class="breadcrumb-item active">
            Activity Histories
        </li>
    </ol>
</nav>
@endsection

@section('pageAction')
<div class="row mb-4 align-items-center">
    <div class="col-md-12 mt-3 mt-md-0">
        {!! backButton(route('roles.show', $role), 'role.read', 'Back to Role Detail') !!}
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="card shadow-sm mb-4">
            <div class="card-header mb-0">
                <h5 class="card-title m-0">Role Data</h5>
                <hr class="mt-3">
            </div>
            <div class="card-body">
                <div class="col-md-12 mb-4">
                    <h3 class="h6 text-muted">{{ $attributeLabels['name'] }}</h3>
                    <div class="mb-2">{{ $role->name }}</div>
                </div>

                <div class="col-md-12 mb-4">
                    <h3 class="h6 text-muted">{{ $attributeLabels['slug'] }}</h3>
                    <div class="mb-2">{{ $role->slug }}</div>
                </div>

                <div class="col-md-12 mb-4">
                    <h3 class="h6 text-muted">{{ $attributeLabels['description'] }}</h3>
                    <p>{{ $role->description }}</p>
                </div>

                <div class="col-md-12">
                    <h3 class="h6 text-muted">{{ $attributeLabels['allow_to_be_assigne'] }}</h3>
                    <p>
                        @if ($role->allow_to_be_assigne)
                        <span class="badge bg-label-primary">Allowed</span>
                        @else
                        <span class="badge bg-label-secondary">Not Allowed</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header mb-0">
                <h5 class="card-title m-0">Info</h5>
                <hr class="mt-3">
            </div>
            <div class="card-body">
                @if ($role->creatorName() || $role->createdAt())
                <div class="col-md-12 mb-4">
                    <h3 class="h6 text-muted">Created</h3>
                    @if ($role->creatorName())
                    <div class="mb-1">
                        <i class="{{ getIcon('user') }}"></i> {{ $role->creatorName() }}
                    </div>
                    @endif

                    @if ($role->createdAt())
                    <div>
                        <i class="{{ getIcon('clock') }}"></i> {{ humanDateTime($role->created_at) }}
                    </div>
                    @endif
                </div>
                @endif

                @if ($role->lastUpdaterName() || $role->lastUpdate())
                <div class="col-md-12 mb-4">
                    <h3 class="h6 text-muted">Last Updated By</h3>
                    <div class="mb-1">
                        <i class="{{ getIcon('user') }}"></i> {{ $role->lastUpdaterName() }}
                    </div>
                    <div>
                        <i class="{{ getIcon('clock') }}"></i> {{ humanDateTime($role->updated_at) }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 class="pb-1 mb-2">Activity Histories</h5>
                        <small class="text-muted">{{ $activityLogs->total() }} Histories</small>
                    </div>

                    <div class="col-md-6 text-md-end mt-3 mt-md-0">
                        <form action="{{ route("roles.activity-logs", $role) }}" method="GET">
                            <div class="input-group">
                                <select class="form-select form-select-sm" id="sort_by" name="sort_by">
                                    <option value="desc" {{ $orderBy === 'desc' ? 'selected' : '' }}>Newest</option>
                                    <option value="asc" {{ $orderBy === 'asc' ? 'selected' : '' }}>Latest</option>
                                </select>
                                <button class="btn btn-sm btn-outline-primary" type="submit">Sort</button>
                            </div>
                        </form>
                    </div>
                </div>

                <hr class="mt-0">
            </div>
            <div class="card-body">
                @forelse ($activityLogs as $activity)
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="avatar flex-shrink-0 rounded me-3">
                                <span class="badge badge-center rounded-pill {{ $activity->getActionBackgroundColor() }}">
                                    <i class="{{ $activity->getActionIcon() }}"></i>
                                </span>
                            </div>

                            <div class="d-flex w-100 flex-wrap adivgn-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">
                                        {{ $activity->description }}
                                    </h6>
                                    <small class="text-muted">
                                        <em><i class="{{ getIcon('clock') }}"></i> {{ humanDateTime($activity->created_at) }}</em>
                                    </small>
                                </div>
                                <div class="d-flex align-item-center">
                                    <em><small class="text-muted"><i class="{{ getIcon('user') }}"></i> {{ $activity->user->name }} </small></em>
                                </div>
                            </div>
                        </div>

                        @if (count($activity->properties) >= 1)
                        <hr>

                        <div class="mt-3">
                            @include('activity_logs.partials._details', ['activity' => $activity])
                        </div>

                        @endif
                    </div>
                </div>
                @empty
                No activity history
                @endforelse

                {{-- Pagination --}}
                {{ $activityLogs->links('vendor.pagination.custom') }}
            </div>
        </div>
    </div>
</div>
@endsection