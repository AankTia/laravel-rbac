@extends('layouts.dashboard')
{{-- @section('title', $viewData['title'])
@section('subtitle', $viewData['subtitle']) --}}

{{-- @section('pageButton')
<a class="btn btn btn-primary">
    Add New {{ $viewData['subtitle'] }}
</a>
<a class="btn btn btn-warning">
    Update {{ $viewData['subtitle'] }}
</a>
<a class="btn btn btn-danger">
    Delete {{ $viewData['subtitle'] }}
</a>
@endsection --}}

@section('content')
<!-- Bootstrap CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap Icons -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css" rel="stylesheet">


<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-5 fw-bold">Create New Role</h1>
        <div>
            <a href="index.html" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="roleName" class="form-label">Role Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="roleName" placeholder="Enter role name" required>
                            <div class="form-text">Use a clear, descriptive name for this role</div>
                        </div>

                        <div class="mb-3">
                            <label for="roleDescription" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="roleDescription" rows="3" placeholder="Describe the purpose of this role" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label d-block">Status</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="roleStatus" id="statusActive" value="active" checked>
                                <label class="form-check-label" for="statusActive">Active</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="roleStatus" id="statusInactive" value="inactive">
                                <label class="form-check-label" for="statusInactive">Inactive</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="rolePriority" class="form-label">Priority Level</label>
                            <select class="form-select" id="rolePriority">
                                <option value="1">High - System Level</option>
                                <option value="2">Medium - Department Level</option>
                                <option value="3" selected>Normal - Standard User</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Role Expiration</label>
                            <div class="input-group">
                                <div class="form-check form-switch mt-2 me-2">
                                    <input class="form-check-input" type="checkbox" id="expirationSwitch">
                                    <label class="form-check-label" for="expirationSwitch">Set expiration</label>
                                </div>
                                <input type="date" class="form-control" disabled>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Available for Assignment</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="availableSwitch" checked>
                                <label class="form-check-label" for="availableSwitch">Allow this role to be assigned to users</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h3 class="h5 mb-0">Permission Assignment</h3>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAllPermissions">
                                    <label class="form-check-label fw-bold" for="selectAllPermissions">
                                        Select All Permissions
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th width="200">Module</th>
                                        <th class="text-center">Create</th>
                                        <th class="text-center">Read</th>
                                        <th class="text-center">Update</th>
                                        <th class="text-center">Delete</th>
                                        <th>Special Permissions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Users Module -->
                                    <tr>
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
                                    </tr>
                                    
                                    <!-- Roles Module -->
                                    <tr>
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
                                    </tr>
                                    
                                    <!-- Reports Module -->
                                    <tr>
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
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h3 class="h5 mb-0">Access Restrictions</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="ipRestriction" class="form-label">IP Address Restrictions</label>
                                    <input type="text" class="form-control" id="ipRestriction" placeholder="e.g., 192.168.1.0/24">
                                    <div class="form-text">Restrict access to specific IP addresses or ranges</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="timeRestriction" class="form-label">Time Restrictions</label>
                                    <select class="form-select" id="timeRestriction">
                                        <option value="none" selected>No time restrictions</option>
                                        <option value="business">Business hours only (9 AM - 5 PM)</option>
                                        <option value="custom">Custom hours</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="button" class="btn btn-outline-secondary me-2">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Role</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection