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
            <h5 class="card-header">Role Data</h5>
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
            <h5 class="card-header">Info</h5>
            <div class="card-body">
                @if ($role->creatorName() || $role->createdAt())
                <div class="col-md-12 mb-4">
                    <h3 class="h6 text-muted">Created</h3>
                    @if ($role->creatorName())
                    <div class="mb-1">
                        <i class="{{ userIcon() }}"></i> {{ $role->creatorName() }}
                    </div>
                    @endif

                    @if ($role->createdAt())
                    <div>
                        <i class="{{ clockIcon() }}"></i> {{ humanDateTime($role->created_at) }}
                    </div>
                    @endif
                </div>
                @endif

                @if ($role->lastUpdaterName() || $role->lastUpdate())
                <div class="col-md-12 mb-4">
                    <h3 class="h6 text-muted">Last Updated By</h3>
                    <div class="mb-1">
                        <i class="{{ userIcon() }}"></i> {{ $role->lastUpdaterName() }}
                    </div>
                    <div>
                        <i class="{{ clockIcon() }}"></i> {{ humanDateTime($role->updated_at) }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <div class="row mb-4 align-items-center">
                    <div class="col-md-6">
                        <h5 class="pb-1 mb-2">Activity Histories</h5>
                    </div>

                    <div class="col-md-6 text-md-end mt-3 mt-md-0">
                        <form action="{{ route("roles.activity-logs", $role) }}" method="GET">
                            <div class="input-group">
                                <select class="form-select" id="sort_by" name="sort_by">
                                    <option value="desc" {{ $orderBy === 'desc' ? 'selected' : '' }}>Newest</option>
                                    <option value="asc" {{ $orderBy === 'asc' ? 'selected' : '' }}>Latest</option>
                                </select>
                                <button class="btn btn-outline-primary" type="submit">Sort</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @forelse ($activityLogs as $activity)
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="avatar flex-shrink-0 rounded me-3">
                                <span class="avatar-initial rounded {{ $activity->getActionBackgroundColor() }}">
                                    <i class="{{ $activity->getActionIcon() }}"></i>
                                </span>
                            </div>

                            <div class="d-flex w-100 flex-wrap adivgn-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">{{ ucwords($activity->action) }} Data</h6>
                                    <small class="text-muted">{{ $activity->user->name }} </small><br>
                                </div>
                                <div>
                                    <small class="fw-semibold">{{ humanDateTime($activity->created_at) }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center">Attribute</th>
                                            <th class="text-center">Old Value</th>
                                            <th class="text-center">New Value</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($activity->properties as $attribute => $data)
                                        <tr>
                                            <td>{{ $attributeLabels[$attribute] }}</td>
                                            <td>{{ $data['old_value'] }}</td>
                                            <td>{{ $data['new_value'] }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
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