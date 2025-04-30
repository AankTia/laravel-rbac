@extends('layouts.dashboard')

@section('title', $viewData['title'] . " | Laravel RBAC")
@section('pageTitle', $viewData['title'])
@section('pageSubTitle', $viewData['subtitle'])

@section('pageAction')
<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <form action="{{ route('roles.index') }}" method="GET">
            <div class="input-group">
                <span class="input-group-text bg-white">
                    <i class="bx bx-search-alt"></i>
                </span>
                <input type="text" name="search_keyword" value="{{ request('search_keyword') }}" class="form-control" placeholder="Search roles...">
            </div>
        </form>
    </div>
    <div class="col-md-6 text-md-end mt-3 mt-md-0">
        <a href="{{ route('roles.create') }}" class="btn btn-primary">
            <i class="bx bx-plus-circle me-2"></i> Add New Role
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Role Name</th>
                        <th>Description</th>
                        <th>Created On</th>
                        <th>
                            <div class="text-end">Action</div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($roles as $role)
                    <tr>
                        <td>
                            <a href="{{ route('roles.show', $role) }}">{{ $role->name }}</a>
                        </td>
                        <td>{{ $role->description }}</td>
                        <td>{{ $role->created_at->format('d M Y, h:i A') }}</td>
                        <td>
                            <div class="text-end">
                                <a href="{{ route('roles.show', $role) }}" class="btn btn-icon btn-outline-primary">
                                    <i class="bx bx-show-alt me-1"></i>
                                </a>
                                <a href="{{ route('roles.edit', $role) }}" class="btn btn-icon btn-outline-warning">
                                    <i class="bx bx-edit-alt me-1"></i>
                                </a>
                                <form action="{{ route('roles.destroy', $role) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Are you sure?')" class="btn btn-icon btn-outline-danger">
                                        <i class="bx bx-trash me-1"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4">
                            <div class="text-center">No data to show</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        {{ $roles->links('vendor.pagination.custom') }}
    </div>
</div>

@endsection