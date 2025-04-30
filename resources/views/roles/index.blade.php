@extends('layouts.dashboard')
{{-- @section('title', $viewData['title']) --}}
{{-- @section('subtitle', $viewData['subtitle']) --}}

{{-- @section('pageButton')
<a href="#" class="btn btn-added"><img src="{{ asset('assets/img/icons/plus.svg') }}" alt="img" class="me-1">Add New {{ $viewData['subtitle'] }}</a>
@endsection --}}

@section('content')

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

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Created On</th>
                        <th><div class="text-end">Action</div></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($roles as $role)
                    <tr>
                        <td>{{ $role->name }}</td>
                        <td>{{ $role->description }}</td>
                        <td>{{ $role->created_at }}</td>
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
</div>

@endsection