@extends('layouts.dashboard')
@section('title', $viewData['title'])
@section('subtitle', $viewData['subtitle'])

@section('pageButton')
<a href="#" class="btn btn-added"><img src="{{ asset('assets/img/icons/plus.svg') }}" alt="img" class="me-1">Add New {{ $viewData['subtitle'] }}</a>
@endsection

@section('content')

<div class="container">
    <header class="mb-4">
        <h1 class="display-5 fw-bold">Role Management</h1>
        <p class="text-muted">Manage all roles in the system</p>
    </header>

    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text bg-white">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" class="form-control" placeholder="Search roles...">
            </div>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <a href="create.html" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i> Add New Role
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Role Name</th>
                            <th scope="col">Description</th>
                            <th scope="col">Permissions</th>
                            <th scope="col">Created On</th>
                            <th scope="col" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Row 1 -->
                        <tr>
                            <td class="fw-medium">Administrator</td>
                            <td>Full system access and control</td>
                            <td>
                                <span class="badge bg-success me-1">Create</span>
                                <span class="badge bg-primary me-1">Read</span>
                                <span class="badge bg-warning text-dark me-1">Update</span>
                                <span class="badge bg-danger">Delete</span>
                            </td>
                            <td>Jan 15, 2025</td>
                            <td class="text-end">
                                <a href="show.html" class="btn btn-sm btn-outline-primary me-1">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="edit.html" class="btn btn-sm btn-outline-secondary me-1">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <!-- Row 2 -->
                        <tr>
                            <td class="fw-medium">Manager</td>
                            <td>Department level management access</td>
                            <td>
                                <span class="badge bg-success me-1">Create</span>
                                <span class="badge bg-primary me-1">Read</span>
                                <span class="badge bg-warning text-dark">Update</span>
                            </td>
                            <td>Feb 3, 2025</td>
                            <td class="text-end">
                                <a href="show.html" class="btn btn-sm btn-outline-primary me-1">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="edit.html" class="btn btn-sm btn-outline-secondary me-1">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <!-- Row 3 -->
                        <tr>
                            <td class="fw-medium">Staff</td>
                            <td>Regular employee access</td>
                            <td>
                                <span class="badge bg-primary me-1">Read</span>
                                <span class="badge bg-warning text-dark">Update (limited)</span>
                            </td>
                            <td>Mar 10, 2025</td>
                            <td class="text-end">
                                <a href="show.html" class="btn btn-sm btn-outline-primary me-1">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="edit.html" class="btn btn-sm btn-outline-secondary me-1">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <nav aria-label="Page navigation" class="mt-4">
        <ul class="pagination justify-content-center">
            <li class="page-item disabled">
                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
            </li>
            <li class="page-item active"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item">
                <a class="page-link" href="#">Next</a>
            </li>
        </ul>
    </nav>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th><div class="text-end">Action</div></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($roles as $role)
                    <tr>
                        <td>{{ $role->name }}</td>
                        <td>{{ $role->description }}</td>
                        <td>
                            <div class="text-end">
                                <a href="{{ route('roles.show', $role) }}" class="btn btn-icon btn-outline-primary">
                                    <i class="bx bx-show-alt"></i>
                                </a>
                                <a href="#" class="btn btn-icon btn-outline-warning">
                                    <i class="bx bx-edit-alt"></i>
                                </a>
                                <a href="#" class="btn btn-icon btn-outline-danger">
                                    <i class="bx bx-trash-alt"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty

                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection