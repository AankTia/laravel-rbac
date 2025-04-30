@extends('layouts.dashboard')

@section('title', $viewData['title'] . " | Laravel RBAC")
@section('pageTitle', 'Update' . ' ' . $role->name . ' Permissions')

@section('pageAction')
<div class="row mb-4 align-items-center">
    <div class="col-md-12 mt-3 mt-md-0">
        <a href="{{ route('roles.show', $role) }}" class="btn btn-outline-secondary me-2">
            <i class="bx bx-left-arrow-alt me-1"></i> Back to Role
        </a>
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
                                <!-- Users Module -->
                                {{-- <tr>
                                    <td class="fw-medium">Users</td>
                                    <td class="text-center">
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input" type="checkbox" id="userCreate">
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input" type="checkbox" id="userRead" checked>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input" type="checkbox" id="userUpdate">
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input" type="checkbox" id="userDelete">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="userResetPwd">
                                            <label class="form-check-label" for="userResetPwd">Reset Passwords</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="userImpersonate">
                                            <label class="form-check-label" for="userImpersonate">Impersonate</label>
                                        </div>
                                    </td>
                                </tr> --}}

                                <!-- Roles Module -->
                                {{-- <tr>
                                    <td class="fw-medium">Roles</td>
                                    <td class="text-center">
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input" type="checkbox" id="roleCreate">
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input" type="checkbox" id="roleRead" checked>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input" type="checkbox" id="roleUpdate">
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input" type="checkbox" id="roleDelete">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="roleAssign">
                                            <label class="form-check-label" for="roleAssign">Assign Roles</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="roleAudit">
                                            <label class="form-check-label" for="roleAudit">View Audit Logs</label>
                                        </div>
                                    </td>
                                </tr> --}}

                                <!-- Reports Module -->
                                {{-- <tr>
                                    <td class="fw-medium">Reports</td>
                                    <td class="text-center">
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input" type="checkbox" id="reportCreate">
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input" type="checkbox" id="reportRead" checked>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input" type="checkbox" id="reportUpdate">
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input" type="checkbox" id="reportDelete">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="reportExport">
                                            <label class="form-check-label" for="reportExport">Export Reports</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="reportSchedule">
                                            <label class="form-check-label" for="reportSchedule">Schedule Reports</label>
                                        </div>
                                    </td>
                                </tr> --}}
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="text-end">
                    <a href="{{ route('roles.show', $role) }}" class="btn btn-outline-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-warning">
                        <i class="bx bx-save me-1"></i>Update Permissions
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection