@extends('layouts.dashboard')

@section('title', $role->name . " Role Detail | Laravel RBAC")

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-style1">
        <li class="breadcrumb-item">
            <a href="javascript:void(0);">User Management</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('roles.index') }}">Role</a>
        </li>
        <li class="breadcrumb-item active">
            {{ $role->name }}
        </li>
    </ol>
</nav>
@endsection

@section('pageAction')
<div class="row mb-4 align-items-center">
    <div class="col-md-12 mt-3 mt-md-0">
        @if(isUserCan('read', 'role'))
        {!! backButton(route('roles.index'), 'role.read', 'Back to Roles') !!}
        @endif

        @if(isUserCan('create', 'role'))
        {!! createButton(route('roles.create'), 'role.create', 'Role') !!}
        @endif
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h5 class="card-title m-0 me-2">{{ $role->name }} Detail</h5>

                    <div class="text-end">
                        @if(isUserCan('update', 'role'))
                        {!! editButton(route('roles.edit', ['role' => $role])) !!}
                        @endif

                        @if(isUserCan('delete', 'role'))
                        {!! deleteButton(route('roles.destroy', $role)) !!}
                        @endif
                    </div>
                </div>
                <hr>
            </div>

            <div class="card-body">
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
            <div class="card-body">
                <h5 class="card-title m-0 me-2 mb-2">Created</h5>

                <div class="d-flex align-items-center justify-content-between">
                    @if ($role->createdAt())
                    <em><i class="{{ getIcon('clock') }}"></i> {{ humanDateTime($role->created_at) }}</em>
                    @endif
                    @if ($role->creatorName())
                    <em><i class="{{ getIcon('user') }}"></i> {{ $role->creatorName() }}</em>
                    @endif
                </div>
            </div>
        </div>
        @endif

        @if ($role->lastUpdaterName() || $role->lastUpdate())
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title m-0 me-2 mb-2">Last Updated</h5>
                <div class="d-flex align-items-center justify-content-between">
                    <em><i class="{{ getIcon('clock') }}"></i> {{ humanDateTime($role->updated_at) }}</em>
                    <em><i class="{{ getIcon('user') }}"></i> {{ $role->lastUpdaterName() }}</em>
                </div>

                <hr />

                <div class="mb-4">
                    {{ $lastActivity->description }}
                </div>

                @include('activity_logs.partials._details', ['activity' => $lastActivity])

                <a href="{{ route("roles.activity-logs", $role) }}" class="btn btn-sm btn-outline-primary mt-4">
                    <i class="{{ getIcon('history') }}"></i> Show All Histories
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection