@extends('layouts.dashboard')

@section('title', $viewData['title'] . " | Laravel RBAC")
@section('pageTitle', $viewData['title'])

@section('pageAction')
<div class="row mb-4 align-items-center">
    <div class="col-md-12 mt-3 mt-md-0">
        @if(auth()->user()->hasPermission('read', 'roles'))
        {!! backButton(route('roles.index'), 'Back to List') !!}
        @endif
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-9">
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                @if(auth()->user()->hasPermission('update', 'roles'))
                {!! editButton(route('roles.edit', ['role' => $role])) !!}
                @endif

                @if(auth()->user()->hasPermission('delete', 'roles'))
                {!! deleteButton(route('roles.destroy', $role)) !!}
                @endif
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <h3 class="h6 text-muted">Role Name</h3>
                        <div class="mb-2">{{ $role->name }}</div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <h3 class="h6 text-muted">Role Identifier</h3>
                        <div class="mb-2">{{ $role->slug }}</div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <h3 class="h6 text-muted">Description</h3>
                        <p>{{ $role->description }}</p>
                    </div>

                    <div class="col-md-6">
                        <h3 class="h6 text-muted">Allow to be assigned to users</h3>
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
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row">
                    <h2 class="card-title h4 mb-4">Assigned Users</h2>
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
            <div class="card-header">
                @if(auth()->user()->hasPermission('update', 'roles'))
                {!! editButton(route('roles.edit-permissions', $role), 'Update Permissions') !!}
                @endif
            </div>

            <div class="card-body">
                <div class="row">
                    <h2 class="card-title h4 mb-4">Allowed Permissions</h2>

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

    <div class="col-md-3">
        @if ($role->creatorName() || $role->createdAt())
        <div>
            <div class="fw-bold mb-3">Created</div>
            @if ($role->creatorName())
            <div class="mb-2"><i class="{{ userIcon() }}"></i> {{ $role->creatorName() }}</div>
            @endif
            @if ($role->createdAt())
            <div><i class="{{ clockIcon() }}"></i> {{ humanDateTime($role->created_at) }}</div>
            @endif
        </div>
        <hr>
        @endif


        @if ($role->lastUpdaterName() || $role->lastUpdate())
        <div class="mt-4">
            <div class="fw-bold mb-3">Last Updated</div>
            <div class="mb-2"><i class="{{ userIcon() }}"></i> {{ $role->lastUpdaterName() }}</div>
            <div><i class="{{ clockIcon() }}"></i> {{ humanDateTime($role->updated_at) }}</div>
        </div>
        <hr>
        @endif

        <div class="mt-4">
            <div class="fw-bold">Activity Logs</div>
            <div class="position-relative ps-4 mt-4">
                <div class="timeline-line"></div>
                @forelse ($activityLogs as $activity)
                <div class="mb-4 d-flex align-items-start gap-3">
                    <div class="timeline-icon {{ $activity->getActionTextColor() }}">
                        <i class="{{ $activity->getActionIcon() }}"></i>
                    </div>

                    <div>
                        <strong>{{ ucwords($activity->action) }}</strong><br>
                        <div class="mb-2">
                            <small class="text-muted">
                                <i class="{{ clockIcon() }}"></i> {{ humanDateTime($activity->created_at) }}
                            </small>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">
                                <i class="{{ userIcon() }}"></i> {{ $activity->user->name }}
                            </small>
                        </div>

                        @if ($activity->isUpdated())
                        <div class="card shadow-none bg-transparent border border-secondary mb-3">
                            <div class="card-body">
                                @foreach ($activity->properties as $propertyName => $data)
                                <div class="mb-3">
                                    <small>{{ $propertyName }}</small><br>
                                    {{ $data['before'] }} <i class="{{ rightArrowIcon() }}"></i> {{ $data['after'] }}
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @empty
                No activity history
                @endforelse
            </div>

            <div class="mt-2">
                <a href="{{ route("roles.activity-logs", $role) }}" class="btn btn-sm btn-outline-primary">
                    <i class="{{ historyIcon() }}"></i> Detail Activities
                </a>
            </div>
            <hr>
        </div>
    </div>
</div>

@endsection