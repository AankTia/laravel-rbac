@extends('layouts.dashboard')

@section('title', $viewData['title'] . " | Laravel RBAC")
@section('pageTitle', $viewData['title'])

@section('pageAction')
<div class="row mb-4 align-items-center">
    <div class="col-md-12 mt-3 mt-md-0">
        @if(auth()->user()->hasPermission('read', 'roles'))
        {!! backButton(route('roles.show', $role), 'Back to Role') !!}
        @endif
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
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

        <div class="card shadow-sm mb-4">
            <div class="card-body">
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
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="timeline-cards">
            @forelse ($activityLogs as $activity)
            <div class="timeline-card">
                <div class="timeline-info">
                    {{ humanDateTime($activity->created_at) }}<br>
                    {{ $activity->user->name }}
                </div>
                <div class="timeline-icon">
                    <div class="timeline-icon-circle {{ $activity->getActionBackgroundColor() }}">
                        <i class="{{ $activity->getActionIcon() }}"></i>
                    </div>
                </div>
                <div class="timeline-content">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ ucwords($activity->action) }} Data</h5>
                            <div class="mt-3">
                                <div class="table-responsive">
                                    @if ($activity->isCreated())
                                    <table class="table table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="text-center">Attribute</th>
                                                <th class="text-center">Value</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($activity->properties as $propertyName => $value)
                                            <tr>
                                                <td>{{ $propertyName }}</td>
                                                <td>{{ $value }}
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @endif
        
                                    @if ($activity->isUpdated())
                                    <table class="table table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="text-center">Attribute</th>
                                                <th class="text-center">Old Value</th>
                                                <th class="text-center">New Value</th>
                                            </tr>
                                        </thead>
        
                                        <tbody>
                                            @foreach ($activity->properties as $propertyName => $data)
                                            <tr>
                                                <td>{{ $propertyName }}</td>
                                                <td>{{ $data['before'] }}</td>
                                                <td>{{ $data['after'] }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            No activity history
            @endforelse
        </div>
    </div>
</div>
@endsection