@extends('layouts.dashboard')

@section('title', "Users | Laravel RBAC")

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-style1">
        <li class="breadcrumb-item">
            <a href="javascript:void(0);">User Management</a>
        </li>
        <li class="breadcrumb-item active">
            User
        </li>
    </ol>
</nav>
@endsection

@section('pageAction')
<div class="row mb-4 align-items-center">
    <div class="col-md-6 mt-3 mt-md-0">
        {!! createButton(route('users.create'), 'user.create', 'User') !!}
    </div>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Users</h5>
        <hr>

        <div class="accordion mt-3" id="accordionExample">
            <div class="card accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#accordionOne" aria-expanded="false" aria-controls="accordionOne">
                        Search Filters
                    </button>
                </h2>

                <div id="accordionOne" class="accordion-collapse collapse" data-bs-parent="#accordionExample" style="">
                    <div class="accordion-body">
                        <form action="{{ route('users.index') }}" method="GET">
                            <div class="row">
                                <div class="col-md-4">
                                    <input class="form-control form-control-sm mb-3" type="text" name="search_name" value="{{ request('search_name') }}" placeholder="Name">
                                </div>

                                <div class="col-md-3">
                                    <select name="search_status" class="form-select form-select-sm mb-3">
                                        <option value="">-- Status --</option>
                                        <option value="active" {{ request('search_status') === 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ request('search_status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <select name="search_role" class="form-select form-select-sm mb-3">
                                        <option value="">-- Role --</option>
                                        @foreach ($roleOptions as $slug => $name)
                                        <option value="{{ $slug }}" {{ request('search_status') == $slug ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-sm btn-primary mb-3">Search</button>
                                    <button type="button" class="btn btn-sm btn-secondary mb-3">Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Status</th>
                        <th>Role</th>
                        <th>
                            <div class="text-end">Action</div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                    <tr>
                        <td>
                            <div class="user-info">
                                <div class="user-avatar">
                                    {{ $user->initialName() }}
                                </div>
                                <div class="user-details">
                                    <p class="user-name"><a href="{{ route('users.show', $user) }}">{{ $user->name }}</a></p>
                                    <p class="user-email">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            {!! userStatusBadge($user->is_active) !!}
                        </td>
                        <td>{{ $user->getRoleName() }}</td>
                        <td>
                            <div class="text-end">
                                @if(isUserCan('read', 'user'))
                                <a href="{{ route('users.show', $user) }}" class="btn btn-icon btn-outline-primary">
                                    <i class="bx bx-show-alt me-1"></i>
                                </a>
                                @endif

                                @if(isUserCan('update', 'user'))
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-icon btn-outline-warning">
                                    <i class="bx bx-edit-alt me-1"></i>
                                </a>
                                @endif

                                @if(isUserCan('delete', 'user'))
                                <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Are you sure?')" class="btn btn-icon btn-outline-danger">
                                        <i class="bx bx-trash me-1"></i>
                                    </button>
                                </form>
                                @endif
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
        {{ $users->links('vendor.pagination.custom') }}
    </div>
</div>
@endsection