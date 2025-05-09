@extends('layouts.dashboard')

@section('title', $title . " | Laravel RBAC")
@section('pageTitle', $title)

@section('pageAction')
<div class="row mb-4 align-items-center">
    <div class="col-md-12 mt-3 mt-md-0">
        @if(auth()->user()->hasPermission('read', 'role'))
        {!! backButton(route('roles.show', $role), 'Back to Role') !!}
        @endif
    </div>
</div>
@endsection

@section('content')
<div class="card shadow-sm mb-4">
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
                                            <input type="checkbox" name="modules[{{ $moduleSlug }}][]" value="read" class="form-check-input" {{ $permissions['read'] ? 'checked' : '' }}>
                                        </div>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($permissions['create'] !== null)
                                        <div class="form-check d-flex justify-content-center">
                                            <input type="checkbox" name="modules[{{ $moduleSlug }}][]" value="create" class="form-check-input" {{ $permissions['create'] ? 'checked' : '' }}>
                                        </div>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($permissions['update'] !== null)
                                        <div class="form-check d-flex justify-content-center">
                                            <input type="checkbox" name="modules[{{ $moduleSlug }}][]" value="update" class="form-check-input" {{ $permissions['update'] ? 'checked' : '' }}>
                                        </div>
                                        @endif
                                    </td>
                                    <td>@if ($permissions['delete'] !== null)
                                        <div class="form-check d-flex justify-content-center">
                                            <input type="checkbox" name="modules[{{ $moduleSlug }}][]" value="delete" class="form-check-input" {{ $permissions['delete'] ? 'checked' : '' }}>
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
                    {!! cancelButton(route('roles.show', $role)) !!}
                    {!! submitEditButton() !!}
                </div>
            </form>
        </div>
    </div>
</div>

@endsection