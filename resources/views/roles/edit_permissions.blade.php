@extends('layouts.dashboard')

@section('title', "Edit ". $role->name . " Role Permissions | Laravel RBAC")

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
            Edit Permissions
        </li>
    </ol>
</nav>
@endsection

@section('pageAction')
<div class="row mb-4 align-items-center">
    <div class="col-md-12 mt-3 mt-md-0">
        {!! permittedBackButton(route('roles.show', $role), 'read', 'role', 'Back to Role Detail') !!}
    </div>
</div>
@endsection

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-header">
        <h5 class="card-title m-0 me-2">Edit {{ $role->name }} Permissions</h5>
        <hr>
    </div>
    <div class="card-body">
        <div class="row">
            <form method="post" action="{{ route('roles.update-permissions', $role) }}">
                @csrf
                @method('PUT')

                <div class="row mb-4">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">Module</th>
                                    <th class="text-center">Read</th>
                                    <th class="text-center">Create</th>
                                    <th class="text-center">Update</th>
                                    <th class="text-center">Delete</th>
                                    <th class="text-center">Special Permissions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($modulePermissions as $moduleSlug => $permissions)
                                <tr>
                                    <td>{{ $moduleNamebySlug[$moduleSlug] }}</td>
                                    <td>
                                        @if ($permissions['read'] !== null)
                                        <div class="form-check d-flex justify-content-center">
                                            <input type="checkbox" name="modules[{{ $moduleSlug }}][]" value="read" class="form-check-input" {{ $permissions['read'] == 'checked' ? 'checked' : '' }}>
                                        </div>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($permissions['create'] !== null)
                                        <div class="form-check d-flex justify-content-center">
                                            <input type="checkbox" name="modules[{{ $moduleSlug }}][]" value="create" class="form-check-input" {{ $permissions['create'] == 'checked' ? 'checked' : '' }}>
                                        </div>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($permissions['update'] !== null)
                                        <div class="form-check d-flex justify-content-center">
                                            <input type="checkbox" name="modules[{{ $moduleSlug }}][]" value="update" class="form-check-input" {{ $permissions['update'] == 'checked' ? 'checked' : '' }}>
                                        </div>
                                        @endif
                                    </td>
                                    <td>@if ($permissions['delete'] !== null)
                                        <div class="form-check d-flex justify-content-center">
                                            <input type="checkbox" name="modules[{{ $moduleSlug }}][]" value="delete" class="form-check-input" {{ $permissions['delete'] == 'checked' ? 'checked' : '' }}>
                                        </div>
                                        @endif
                                    </td>
                                    <td nowrap>
                                        @foreach ($permissions['others'] as $otherPermissionSlug => $otherPermissionData)
                                        <div>
                                            <div class="form-check">
                                                <input type="checkbox" name="modules[{{ $moduleSlug }}][]" value="{{ $otherPermissionSlug }}" class="form-check-input" {{ $otherPermissionData['checked'] ? 'checked' : '' }}>
                                                <label class="form-check-label"> {{ $otherPermissionData['label'] }} </label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="text-end">
                    {!! permittedBackButton(route('roles.show', $role), 'read', 'role') !!}
                    {!! submitEditButton() !!}
                </div>
            </form>
        </div>
    </div>
</div>

@endsection