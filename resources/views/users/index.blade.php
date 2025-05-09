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
        <div class="row">
            <h5 class="pb-1 mb-2">Users</h5>
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
                        <td>
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