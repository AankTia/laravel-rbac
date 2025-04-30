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
        <h1 class="display-5 fw-bold">Role Details</h1>
        <div>
            <a href="index.html" class="btn btn-outline-secondary me-2">
                <i class="bi bi-arrow-left me-1"></i> Back to List
            </a>
            <a href="edit.html" class="btn btn-primary">
                <i class="bi bi-pencil me-1"></i> Edit Role
            </a>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <h2 class="card-title h4 mb-4">Administrator</h2>

                    <div class="mb-4">
                        <h3 class="h6 text-muted">Description</h3>
                        <p>Full system access and control with ability to manage all resources, users, and configuration settings. This role has the highest level of privileges in the system.</p>
                    </div>

                    <div class="mb-4">
                        <h3 class="h6 text-muted">Assigned Permissions</h3>
                        <div class="d-flex flex-wrap gap-2 mt-2">
                            <span class="badge bg-success">Create</span>
                            <span class="badge bg-primary">Read</span>
                            <span class="badge bg-warning text-dark">Update</span>
                            <span class="badge bg-danger">Delete</span>
                            <span class="badge bg-info text-dark">Export</span>
                            <span class="badge bg-secondary">Import</span>
                            <span class="badge bg-dark">Configure</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h3 class="h6 text-muted">User Assignment</h3>
                        <p>This role is currently assigned to <strong>15 users</strong> in the system.</p>
                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#usersModal">
                            View Assigned Users
                        </button>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h3 class="h6 text-muted mb-3">Role Information</h3>

                            <div class="mb-3">
                                <div class="small text-muted">Role ID</div>
                                <div>ROLE-001</div>
                            </div>

                            <div class="mb-3">
                                <div class="small text-muted">Created On</div>
                                <div>January 15, 2025</div>
                            </div>

                            <div class="mb-3">
                                <div class="small text-muted">Last Updated</div>
                                <div>April 23, 2025</div>
                            </div>

                            <div class="mb-3">
                                <div class="small text-muted">Created By</div>
                                <div>System Administrator</div>
                            </div>

                            <div class="mb-3">
                                <div class="small text-muted">Status</div>
                                <div><span class="badge bg-success">Active</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h3 class="h5 mb-0">Permission Details</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Module</th>
                            <th class="text-center">Create</th>
                            <th class="text-center">Read</th>
                            <th class="text-center">Update</th>
                            <th class="text-center">Delete</th>
                            <th class="text-center">Special Privileges</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="fw-medium">Users</td>
                            <td class="text-center"><i class="bi bi-check-circle-fill text-success"></i></td>
                            <td class="text-center"><i class="bi bi-check-circle-fill text-success"></i></td>
                            <td class="text-center"><i class="bi bi-check-circle-fill text-success"></i></td>
                            <td class="text-center"><i class="bi bi-check-circle-fill text-success"></i></td>
                            <td>Reset passwords, Impersonate</td>
                        </tr>
                        <tr>
                            <td class="fw-medium">Roles</td>
                            <td class="text-center"><i class="bi bi-check-circle-fill text-success"></i></td>
                            <td class="text-center"><i class="bi bi-check-circle-fill text-success"></i></td>
                            <td class="text-center"><i class="bi bi-check-circle-fill text-success"></i></td>
                            <td class="text-center"><i class="bi bi-check-circle-fill text-success"></i></td>
                            <td>Assign, Audit</td>
                        </tr>
                        <tr>
                            <td class="fw-medium">Reports</td>
                            <td class="text-center"><i class="bi bi-check-circle-fill text-success"></i></td>
                            <td class="text-center"><i class="bi bi-check-circle-fill text-success"></i></td>
                            <td class="text-center"><i class="bi bi-check-circle-fill text-success"></i></td>
                            <td class="text-center"><i class="bi bi-check-circle-fill text-success"></i></td>
                            <td>Export, Schedule</td>
                        </tr>
                        <tr>
                            <td class="fw-medium">Settings</td>
                            <td class="text-center"><i class="bi bi-check-circle-fill text-success"></i></td>
                            <td class="text-center"><i class="bi bi-check-circle-fill text-success"></i></td>
                            <td class="text-center"><i class="bi bi-check-circle-fill text-success"></i></td>
                            <td class="text-center"><i class="bi bi-check-circle-fill text-success"></i></td>
                            <td>System configuration, Backups</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Users Modal -->
<div class="modal fade" id="usersModal" tabindex="-1" aria-labelledby="usersModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="usersModalLabel">Users with Administrator Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Department</th>
                                <th>Assigned Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>John Smith</td>
                                <td>john.smith@example.com</td>
                                <td>IT</td>
                                <td>Jan 15, 2025</td>
                            </tr>
                            <tr>
                                <td>Sarah Johnson</td>
                                <td>sarah.j@example.com</td>
                                <td>Operations</td>
                                <td>Feb 3, 2025</td>
                            </tr>
                            <tr>
                                <td>Michael Wong</td>
                                <td>m.wong@example.com</td>
                                <td>Security</td>
                                <td>Mar 12, 2025</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
@endsection