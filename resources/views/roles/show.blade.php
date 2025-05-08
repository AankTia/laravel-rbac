@extends('layouts.dashboard')

@section('title', $title . " | Laravel RBAC")
@section('pageTitle', $title)

@section('pageAction')
<div class="row mb-4 align-items-center">
    <div class="col-md-12 mt-3 mt-md-0">
        @if(auth()->user()->hasPermission('read', 'role'))
        {!! backButton(route('roles.index'), 'Back to Roles') !!}
        @endif

        @if(auth()->user()->hasPermission('create', 'role'))
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
                    @if(auth()->user()->hasPermission('update', 'role'))
                    {!! editButton(route('roles.edit', ['role' => $role])) !!}
                    @endif

                    @if(auth()->user()->hasPermission('delete', 'role'))
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

        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h5 class="card-title m-0 me-2">Assigned Users</h5>
            </div>

            <div class="card-body">
                <hr class="mt-0">
                <div class="row">
                    <div class="mb-2">
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
                                @forelse ($role->roleUsers as $roleUser)
                                <tr>
                                    <td class="fw-medium">
                                        <div class="user-info">
                                            <button type="button" class="btn btn-sm btn-icon btn-outline-danger">
                                                <span class="tf-icons {{ deleteIcon() }}"></span>
                                            </button>

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
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center"> No data to show</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title m-0 me-2">Allowed Permissions</h5>
                @if(auth()->user()->hasPermission('update-role-permissions', 'role'))
                {!! editButton(route('roles.edit-permissions', $role), 'Update Permissions') !!}
                @endif
            </div>

            <div class="card-body">
                <hr class="mt-0">
                <div class="row">
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
    </div>

    <div class="col-md-4">
        @if ($role->creatorName() || $role->createdAt())
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title m-0 me-2">Created</h5>
            </div>
            <div class="card-body">
                <hr class="mt-0">
                <div>
                    @if ($role->creatorName())
                    <div class="mb-2">
                        <em><i class="{{ userIcon() }}"></i> {{ $role->creatorName() }}</em>
                    </div>
                    @endif
                    @if ($role->createdAt())
                    <div>
                        <em><i class="{{ clockIcon() }}"></i> {{ humanDateTime($role->created_at) }}</em>
                    </div>
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
                <div class="mb-2">
                    <em><i class="{{ userIcon() }}"></i> {{ $role->lastUpdaterName() }}</em>
                </div>
                <div class="mb-4">
                    <em><i class="{{ clockIcon() }}"></i> {{ humanDateTime($role->updated_at) }}</em>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" nowrap>Attribute</th>
                                <th class="text-center" nowrap>Old Value</th>
                                <th class="text-center" nowrap>New Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lastActivity->properties['attributes'] as $attribute => $data)
                            <tr>
                                <td>{{ $attributeLabels[$attribute] ?? $attribute}}</td>
                                @if ($attribute == 'allow_to_be_assigne')
                                <td nowrap>{!! roleAllowToBeAssigneBadge($data['old']) !!}</td>
                                <td nowrap>{!! roleAllowToBeAssigneBadge($data['new']) !!}</td>
                                @else
                                <td>{{ $data['old'] }}</td>
                                <td>{{ $data['new'] }}</td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection