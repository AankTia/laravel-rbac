<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .user-avatar {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 5px solid #f8f9fa;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .permission-badge {
            margin-right: 5px;
            margin-bottom: 5px;
        }
        
        .card {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border: none;
            border-radius: 15px;
        }
        
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            border-top-left-radius: 15px !important;
            border-top-right-radius: 15px !important;
        }
        
        .section-title {
            border-left: 4px solid #0d6efd;
            padding-left: 10px;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row mb-4">
            <div class="col">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i> Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Users</a></li>
                        <li class="breadcrumb-item active" aria-current="page">User Details</li>
                    </ol>
                </nav>
            </div>
        </div>
        
        <div class="row">
            <!-- User Profile Card -->
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-user me-2"></i>User Profile</h5>
                    </div>
                    <div class="card-body text-center">
                        <img src="/api/placeholder/400/400" alt="User Avatar" class="user-avatar mb-4">
                        <h4 id="userName">John Doe</h4>
                        <p class="text-muted" id="userEmail">john.doe@example.com</p>
                        <div class="d-flex justify-content-center mb-3">
                            <span class="badge bg-primary p-2" id="userRole">Administrator</span>
                        </div>
                        <div class="d-flex justify-content-center">
                            <button class="btn btn-outline-primary me-2"><i class="fas fa-envelope me-1"></i> Message</button>
                            <button class="btn btn-outline-secondary"><i class="fas fa-edit me-1"></i> Edit</button>
                        </div>
                    </div>
                    <div class="card-footer bg-white">
                        <div class="row text-center">
                            <div class="col">
                                <span class="d-block fw-bold">Last Login</span>
                                <small class="text-muted" id="lastLogin">April 28, 2025</small>
                            </div>
                            <div class="col">
                                <span class="d-block fw-bold">Status</span>
                                <small class="text-success" id="userStatus">Active</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- User Details -->
            <div class="col-md-8">
                <!-- Personal Information -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-id-card me-2"></i>Personal Information</h5>
                        <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-pencil-alt"></i></button>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Full Name</label>
                                    <p class="fw-medium" id="fullName">John Robert Doe</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Employee ID</label>
                                    <p class="fw-medium" id="employeeId">EMP-2025-0042</p>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Department</label>
                                    <p class="fw-medium" id="department">Information Technology</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Position</label>
                                    <p class="fw-medium" id="position">System Administrator</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Phone</label>
                                    <p class="fw-medium" id="phone">+1 (555) 123-4567</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Join Date</label>
                                    <p class="fw-medium" id="joinDate">January 15, 2023</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- User Role & Permissions -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-user-shield me-2"></i>Role & Permissions</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <h6 class="section-title mb-3">Role</h6>
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                                    <i class="fas fa-user-tie text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1" id="roleName">Administrator</h6>
                                    <p class="text-muted small mb-0" id="roleDescription">Full system access with user management capabilities</p>
                                </div>
                            </div>
                        </div>
                        
                        <h6 class="section-title mb-3">Assigned Permissions</h6>
                        <div class="row">
                            <!-- User Management -->
                            <div class="col-md-6 mb-3">
                                <div class="card h-100 border-0 bg-light">
                                    <div class="card-body">
                                        <h6 class="mb-3"><i class="fas fa-users me-2 text-primary"></i>User Management</h6>
                                        <div id="userManagementPermissions">
                                            <span class="badge bg-success permission-badge">Create Users</span>
                                            <span class="badge bg-success permission-badge">Edit Users</span>
                                            <span class="badge bg-success permission-badge">Delete Users</span>
                                            <span class="badge bg-success permission-badge">View Users</span>
                                            <span class="badge bg-success permission-badge">Assign Roles</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Content Management -->
                            <div class="col-md-6 mb-3">
                                <div class="card h-100 border-0 bg-light">
                                    <div class="card-body">
                                        <h6 class="mb-3"><i class="fas fa-file-alt me-2 text-primary"></i>Content Management</h6>
                                        <div id="contentManagementPermissions">
                                            <span class="badge bg-success permission-badge">Create Content</span>
                                            <span class="badge bg-success permission-badge">Edit Content</span>
                                            <span class="badge bg-success permission-badge">Delete Content</span>
                                            <span class="badge bg-success permission-badge">Publish Content</span>
                                            <span class="badge bg-warning permission-badge">Archive Content</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- System Settings -->
                            <div class="col-md-6 mb-3">
                                <div class="card h-100 border-0 bg-light">
                                    <div class="card-body">
                                        <h6 class="mb-3"><i class="fas fa-cogs me-2 text-primary"></i>System Settings</h6>
                                        <div id="systemSettingsPermissions">
                                            <span class="badge bg-success permission-badge">View Settings</span>
                                            <span class="badge bg-success permission-badge">Modify Settings</span>
                                            <span class="badge bg-success permission-badge">Backup System</span>
                                            <span class="badge bg-danger permission-badge">Delete Backups</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Reports & Analytics -->
                            <div class="col-md-6 mb-3">
                                <div class="card h-100 border-0 bg-light">
                                    <div class="card-body">
                                        <h6 class="mb-3"><i class="fas fa-chart-bar me-2 text-primary"></i>Reports & Analytics</h6>
                                        <div id="reportsPermissions">
                                            <span class="badge bg-success permission-badge">View Reports</span>
                                            <span class="badge bg-success permission-badge">Create Reports</span>
                                            <span class="badge bg-success permission-badge">Export Data</span>
                                            <span class="badge bg-secondary permission-badge">Delete Reports</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
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
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    
    <!-- Sample JavaScript to load and display user data -->
    <script>
        // This would typically be replaced with an API call to fetch user data
        document.addEventListener('DOMContentLoaded', function() {
            // Sample user data - in a real application, this would come from an API
            const userData = {
                id: "USR-2025-0042",
                name: "John Doe",
                fullName: "John Robert Doe",
                email: "john.doe@example.com",
                employeeId: "EMP-2025-0042",
                department: "Information Technology",
                position: "System Administrator",
                phone: "+1 (555) 123-4567",
                joinDate: "January 15, 2023",
                role: "Administrator",
                roleDescription: "Full system access with user management capabilities",
                lastLogin: "April 28, 2025",
                status: "Active"
            };
            
            // Populate user information
            document.getElementById('userName').textContent = userData.name;
            document.getElementById('userEmail').textContent = userData.email;
            document.getElementById('userRole').textContent = userData.role;
            document.getElementById('fullName').textContent = userData.fullName;
            document.getElementById('employeeId').textContent = userData.employeeId;
            document.getElementById('department').textContent = userData.department;
            document.getElementById('position').textContent = userData.position;
            document.getElementById('phone').textContent = userData.phone;
            document.getElementById('joinDate').textContent = userData.joinDate;
            document.getElementById('lastLogin').textContent = userData.lastLogin;
            document.getElementById('userStatus').textContent = userData.status;
            document.getElementById('roleName').textContent = userData.role;
            document.getElementById('roleDescription').textContent = userData.roleDescription;
            
            // In a real application, you would also load permissions dynamically
        });
    </script>
</body>
</html>