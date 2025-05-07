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
                                    <th>Special Permissions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($modules as $module)
                                <tr>
                                    <td>{{ $module->name }}</td>
                                    <td class="text-center">
                                        <div class="form-check d-flex justify-content-center">
                                            <input type="checkbox" name="modules[{{ $module->slug }}][]" value="read" class="form-check-input" {{ $role->hasPermission('read', $module->slug) ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check d-flex justify-content-center">
                                            <input type="checkbox" name="modules[{{ $module->slug }}][]" value="create" class="form-check-input" {{ $role->hasPermission('create', $module->slug) ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check d-flex justify-content-center">
                                            <input type="checkbox" name="modules[{{ $module->slug }}][]" value="update" class="form-check-input" {{ $role->hasPermission('update', $module->slug) ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check d-flex justify-content-center">
                                            <input type="checkbox" name="modules[{{ $module->slug }}][]" value="delete" class="form-check-input" {{ $role->hasPermission('delete', $module->slug) ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td></td>
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