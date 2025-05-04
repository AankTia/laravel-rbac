@extends('layouts.dashboard')
@section('title', $viewData['title'] . " | Laravel RBAC")
@section('pageTitle', $viewData['title'])

@section('pageAction')
<div class="row mb-4 align-items-center">
    <div class="col-md-12 mt-3 mt-md-0">
        @if(auth()->user()->hasPermission('read', 'users'))
        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary me-2">
            <i class="bx bx-left-arrow-alt me-1"></i> Back to List
        </a>
        @endif

        @if(auth()->user()->hasPermission('update', 'users'))
        <a href="{{ route('users.edit', ['user' => $user]) }}" class="btn btn-primary">
            <i class="bx bx-pencil me-1"></i> Edit
        </a>
        @endif

        @if ($user->is_active)
            <form action="{{ route('users.deactivate', $user) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" onclick="return confirm('Are you sure?')" class="btn btn-warning">
                    <i class="bx bx-user-x me-1"></i> Deactivate
                </button>
            </form>
        @endif

        @if (!$user->is_active)
            <form action="{{ route('users.activate', $user) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" onclick="return confirm('Are you sure?')" class="btn btn-info">
                    <i class="bx bx-user-check me-1"></i> Activate
                </button>
            </form>
        @endif


        @if(auth()->user()->hasPermission('delete', 'users'))
        <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" onclick="return confirm('Are you sure?')" class="btn btn-danger">
                <i class="bx bx-trash me-1"></i> Delete
            </button>
        </form>
        @endif
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <!-- User Profile Card -->
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="text-center">
                    <img src="{{ asset('assets/img/man-avatar.jpg') }}" alt="User Avatar" class="user-image-profile mb-4">
                    <h4>{{ $user->name }}</h4>
                    <p class="text-muted">{{ $user->email }}</p>
                    <div class="d-flex justify-content-center mb-3">
                        <span class="badge bg-primary p-2" id="userRole">{{ $user->role->name }}</span>
                    </div>
                </div>

                <div class="row text-center">
                    <div class="col">
                        <span class="d-block fw-bold">Last Login</span>
                        <small class="text-muted" id="lastLogin">...</small>
                    </div>
                    <div class="col">
                        <span class="d-block fw-bold">Status</span>
                        @if ($user->is_active)
                            <span class="badge rounded-pill bg-success">Active</span>    
                        @else
                            <span class="badge rounded-pill bg-danger">Inactive</span>
                        @endif
                    </div>
                </div>

                <div class="card bg-light mt-4">
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="small text-muted">Created By</div>
                            <div>{{ $user->creatorName() }}</div>
                        </div>

                        <div class="mb-3">
                            <div class="small text-muted">Created On</div>
                            <div>{{ $user->createdAt() }}</div>
                        </div>
    
                        <div class="mb-3">
                            <div class="small text-muted">Last Updated By</div>
                            <div>{{ $user->lastUpdaterName() ?? '-' }}</div>
                        </div>
    
                        <div class="mb-3">
                            <div class="small text-muted">Last Updated</div>
                            <div>{{ $user->lastUpdate() ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Details -->
    <div class="col-md-8">
        <!-- Activity Log -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-history me-2"></i>Recent Activity</h5>
                <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Activity</th>
                                <th>Time</th>
                                <th>IP Address</th>
                            </tr>
                        </thead>
                        <tbody id="activityLog">
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-success bg-opacity-10 p-2 rounded-circle me-3">
                                            <i class="fas fa-sign-in-alt text-success small"></i>
                                        </div>
                                        <span>System Login</span>
                                    </div>
                                </td>
                                <td>Today, 09:32 AM</td>
                                <td>192.168.1.105</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-3">
                                            <i class="fas fa-edit text-primary small"></i>
                                        </div>
                                        <span>Updated User Profile</span>
                                    </div>
                                </td>
                                <td>Yesterday, 03:45 PM</td>
                                <td>192.168.1.105</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-info bg-opacity-10 p-2 rounded-circle me-3">
                                            <i class="fas fa-file-export text-info small"></i>
                                        </div>
                                        <span>Generated Monthly Report</span>
                                    </div>
                                </td>
                                <td>Apr 28, 2025, 10:12 AM</td>
                                <td>192.168.1.105</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-danger bg-opacity-10 p-2 rounded-circle me-3">
                                            <i class="fas fa-trash-alt text-danger small"></i>
                                        </div>
                                        <span>Deleted Outdated Content</span>
                                    </div>
                                </td>
                                <td>Apr 27, 2025, 02:30 PM</td>
                                <td>192.168.1.105</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Personal Information -->
        {{-- <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-id-card me-2"></i>Personal Information</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Full Name</label>
                            <p class="fw-medium" id="fullName">{{ $user->name }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Employee ID</label>
                            <p class="fw-medium" id="employeeId">...</p>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Department</label>
                            <p class="fw-medium" id="department">...</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Position</label>
                            <p class="fw-medium" id="position">...</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Phone</label>
                            <p class="fw-medium" id="phone">...</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Join Date</label>
                            <p class="fw-medium" id="joinDate">...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
</div>
@endsection