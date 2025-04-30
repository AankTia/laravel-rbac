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
        <h1 class="display-5 fw-bold">Edit Role</h1>
        <div>
            <a href="show.html" class="btn btn-outline-secondary me-2">
                <i class="bi bi-arrow-left me-1"></i> Back to Details
            </a>
            <a href="index.html" class="btn btn-outline-secondary">
                <i class="bi bi-list me-1"></i> All Roles
            </a>
        </div>
    </div>

    <div class="alert alert-info d-flex align-items-center" role="alert">
        <i class="bi bi-info-circle-fill me-2"></i>
        <div>
            You are editing the <strong>Administrator</strong> role. Changes will affect all users with this role.
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="roleName" class="form-label">Role Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="roleName" value="Administrator" required>
                            <div class="form-text">Use a clear, descriptive name for this role</div>
                        </div>

                        <div class="mb-3">
                            <label for="roleDescription" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="roleDescription" rows="3" required>Full system access and control with ability to manage all resources, users, and configuration settings. This role has the highest level of privileges in the system.</textarea>
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
                                <option value="1" selected>High - System Level</option>
                                <option value="2">Medium - Department Level</option>
                                <option value="3">Normal - Standard User</option>
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
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h3 class="h5 mb-0">Permission Assignment</h3>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="selectAllPermissions" checked>
                            <label class="form-check-label fw-bold" for="selectAllPermissions">
                                Select All Permissions
                            </label>
                        </div>
                    </div>
                    <div class="card-body">
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
                                                <input class="form-check-input" type="checkbox" id="userCreate" checked>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check d-flex justify-content-center">
                                                <input class="form-check-input" type="checkbox" id="userRead" checked>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check d-flex justify-content-center">
                                                <input class="form-check-input" type="checkbox" id="userUpdate" checked>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check d-flex justify-content-center">
                                                <input class="form-check-input" type="checkbox" id="userDelete" checked>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="userResetPwd" checked>
                                                <label class="form-check-label" for="userResetPwd">Reset Passwords</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="userImpersonate" checked>
                                                <label class="form-check-label" for="userImpersonate">Impersonate</label>
                                            </div>
                                        </td>
                                    </tr>
                                    
                                    <!-- Roles Module -->
                                    <tr>
                                        <td class="fw-medium">Roles</td>
                                        <td class="text-center">
                                            <div class="form-check d-flex justify-content-center">
                                                <input class="form-check-input" type="checkbox" id="roleCreate" checked>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check d-flex justify-content-center">
                                                <input class="form-check-input" type="checkbox" id="roleRead" checked>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check d-flex justify-content-center">
                                                <input class="form-check-input" type="checkbox" id="roleUpdate" checked>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check d-flex justify-content-center">
                                                <input class="form-check-input" type="checkbox" id="roleDelete" checked>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="roleAssign" checked>
                                                <label class="form-check-label" for="roleAssign">Assign Roles</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="roleAudit" checked>
                                                <label class="form-check-label" for="roleAudit">View Audit Logs</label>
                                            </div>
                                        </td>
                                    </tr>
                                    
                                    <!-- Reports Module -->
                                    <tr>
                                        <td class="fw-medium">Reports</td>
                                        <td class="text-center">
                                            <div class="form-check d-flex justify-content-center">
                                                <input class="form-check-input" type="checkbox" id="reportCreate" checked>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check d-flex justify-content-center">
                                                <input class="form-check-input" type="checkbox" id="reportRead" checked>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check d-flex justify-content-center">
                                                <input class="form-check-input" type="checkbox" id="reportUpdate" checked>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check d-flex justify-content-center">
                                                <input class="form-check-input" type="checkbox" id="reportDelete" checked>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="reportExport" checked>
                                                <label class="form-check-label" for="reportExport">Export Reports</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="reportSchedule" checked>
                                                <label class="form-check-label" for="reportSchedule">Schedule Reports</label>
                                            </div>
                                        </td>
                                    </tr>
                                    
                                    <!-- Settings Module -->
                                    <tr>
                                        <td class="fw-medium">Settings</td>
                                        <td class="text-center">
                                            <div class="form-check d-flex justify-content-center">
                                                <input class="form-check-input" type="checkbox" id="settingsCreate" checked>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check d-flex justify-content-center">
                                                <input class="form-check-input" type="checkbox" id="settingsRead" checked>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check d-flex justify-content-center">
                                                <input class="form-check-input" type="checkbox" id="settingsUpdate" checked>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check d-flex justify-content-center">
                                                <input class="form-check-input" type="checkbox" id="settingsDelete" checked>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="settingsConfig" checked>
                                                <label class="form-check-label" for="settingsConfig">System Configuration</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="settingsBackup" checked>
                                                <label class="form-check-label" for="settingsBackup">Manage Backups</label>
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
                                    <input type="text" class="form-control" id="ipRestriction">
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
                
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h3 class="h5 mb-0">Audit Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <span class="text-muted">Created By:</span> 
                                    <span>System Administrator</span>
                                </div>
                                <div class="mb-2">
                                    <span class="text-muted">Created On:</span> 
                                    <span>January 15, 2025</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <span class="text-muted">Last Modified By:</span> 
                                    <span>John Smith</span>
                                </div>
                                <div class="mb-2">
                                    <span class="text-muted">Last Modified On:</span> 
                                    <span>April 23, 2025</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-danger">
                        <i class="bi bi-trash me-1"></i> Delete Role
                    </button>
                    <div>
                        <button type="button" class="btn btn-outline-secondary me-2">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Version History Accordion -->
    <div class="accordion mt-4" id="versionHistory">
        <div class="accordion-item">
            <h2 class="accordion-header" id="versionHeading">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#versionContent" aria-expanded="false" aria-controls="versionContent">
                    Version History
                </button>
            </h2>
            <div id="versionContent" class="accordion-collapse collapse" aria-labelledby="versionHeading" data-bs-parent="#versionHistory">
                <div class="accordion-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Version</th>
                                    <th>Modified By</th>
                                    <th>Date</th>
                                    <th>Changes</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>v1.3</td>
                                    <td>John Smith</td>
                                    <td>Apr 23, 2025</td>
                                    <td>Added backup management permissions</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-secondary">View</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>v1.2</td>
                                    <td>Sarah Johnson</td>
                                    <td>Mar 15, 2025</td>
                                    <td>Updated description and added report scheduling</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-secondary">View</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>v1.1</td>
                                    <td>Michael Wong</td>
                                    <td>Feb 12, 2025</td>
                                    <td>Added user impersonation</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-secondary">View</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>v1.0</td>
                                    <td>System Administrator</td>
                                    <td>Jan 15, 2025</td>
                                    <td>Initial role creation</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-secondary">View</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    
<!-- Add simple validation and interactivity -->
<script>
    // Example JavaScript - would be expanded in a real application
    document.addEventListener('DOMContentLoaded', function() {
        // Handle select all permissions checkbox
        const selectAllCheckbox = document.getElementById('selectAllPermissions');
        const permissionCheckboxes = document.querySelectorAll('input[type="checkbox"]:not(#selectAllPermissions):not(#expirationSwitch):not(#availableSwitch)');
        
        selectAllCheckbox.addEventListener('change', function() {
            permissionCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
        });
        
        // Handle expiration date toggle
        const expirationSwitch = document.getElementById('expirationSwitch');
        const expirationDate = expirationSwitch.closest('.input-group').querySelector('input[type="date"]');
        
        expirationSwitch.addEventListener('change', function() {
            expirationDate.disabled = !expirationSwitch.checked;
        });
    });
</script>
@endsection