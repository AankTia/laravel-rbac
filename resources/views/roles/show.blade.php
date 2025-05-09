@extends('layouts.dashboard')

@section('title', $title . " | Laravel RBAC")
@section('pageTitle', $title)

@section('pageAction')
<div class="row mb-4 align-items-center">
    <div class="col-md-12 mt-3 mt-md-0">
        @if(isUserCan('read', 'role'))
        {!! backButton(route('roles.index'), 'Back to Roles') !!}
        @endif

        @if(isUserCan('create', 'role'))
        {!! createButton(route('roles.create'), 'Role') !!}
        @endif
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title m-0 me-2">Details</h5>

                <div class="text-end">
                    @if(isUserCan('update', 'role'))
                    {!! editButton(route('roles.edit', ['role' => $role])) !!}
                    @endif

                    @if(isUserCan('delete', 'role'))
                    {!! deleteButton(route('roles.destroy', $role)) !!}
                    @endif
                </div>
            </div>

            <div class="card-body">
                <hr class="mt-0">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <h3 class="h6 text-muted">{{ $attributeLabels['name'] }}</h3>
                        <div class="mb-2">{{ $role->name }}</div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <h3 class="h6 text-muted">{{ $attributeLabels['slug'] }}</h3>
                        <div class="mb-2">{{ $role->slug }}</div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <h3 class="h6 text-muted">{{ $attributeLabels['description'] }}</h3>
                        <p>{{ $role->description }}</p>
                    </div>

                    <div class="col-md-6">
                        <h3 class="h6 text-muted">{{ $attributeLabels['allow_to_be_assigne'] }}</h3>
                        <p>
                            {!! roleAllowToBeAssigneBadge($role->allow_to_be_assigne) !!}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        @include('roles._assigned_users')
        @include('roles._role_permissions')
    </div>

    <div class="col-md-4">
        @if ($role->creatorName() || $role->createdAt())
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title m-0 me-2">Created</h5>
            </div>
            <div class="card-body">
                <hr class="mt-0">


                <div class="d-flex align-items-center justify-content-between mb-4">
                    @if ($role->createdAt())
                    <em><i class="{{ clockIcon() }}"></i> {{ humanDateTime($role->created_at) }}</em>
                    @endif
                    @if ($role->creatorName())
                    <em><i class="{{ userIcon() }}"></i> {{ $role->creatorName() }}</em>
                    @endif
                </div>
            </div>
        </div>
        @endif

        @if ($role->lastUpdaterName() || $role->lastUpdate())
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title m-0 me-2">Last Updated</h5>
                <a href="{{ route("roles.activity-logs", $role) }}" class="btn btn-sm btn-outline-primary">
                    <i class="{{ historyIcon() }}"></i> Show Histories
                </a>
            </div>

            <div class="card-body">
                <hr class="mt-0">

                <div class="d-flex align-items-center justify-content-between mb-4">
                    <em><i class="{{ clockIcon() }}"></i> {{ humanDateTime($role->updated_at) }}</em>
                    <em><i class="{{ userIcon() }}"></i> {{ $role->lastUpdaterName() }}</em>
                </div>

                <div class="mb-4">
                    {{ $lastActivity->description }}
                </div>

                @include('activity_logs.partials._details', ['activity' => $lastActivity])
            </div>
        </div>
        @endif
    </div>
</div>
@endsection