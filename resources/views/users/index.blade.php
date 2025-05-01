@extends('layouts.dashboard')

@section('title', $viewData['title'] . " | Laravel RBAC")
@section('pageTitle', $viewData['title'])

@section('pageAction')
<div class="row mb-4 align-items-center">
    <div class="col-md-6 mt-3 mt-md-0">
        @if(auth()->user()->hasPermission('create', 'roles'))
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="bx bx-plus-circle me-2"></i> Add New User
        </a>
        @endif
    </div>
</div>
@endsection

@section('content')
<style>
    .user-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        background-color: #f0f0f0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #666;
        font-weight: 500;
        font-size: 14px;
    }

    .user-details {
        display: flex;
        flex-direction: column;
    }

    .user-name {
        font-weight: 500;
        margin-bottom: 0;
    }

    .user-email {
        font-size: 12px;
        color: #666;
        margin-bottom: 0;
    }
</style>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th>Status</th>
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
                                <div class="user-avatar" style="background-color: #e3f2fd; color: #2196f3;">
                                    {{ $user->initialName() }}
                                </div>
                                <div class="user-details">
                                    <p class="user-name"><a href="{{ route('users.show', $user) }}">{{ $user->name }}</a></p>
                                    <p class="user-email">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td>{{ $user->role->name }}</td>
                        <td></td>
                        <td>
                            <div class="text-end">
                                @if(auth()->user()->hasPermission('read', 'users'))
                                <a href="{{ route('users.show', $user) }}" class="btn btn-icon btn-outline-primary">
                                    <i class="bx bx-show-alt me-1"></i>
                                </a>
                                @endif

                                @if(auth()->user()->hasPermission('update', 'users'))
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-icon btn-outline-warning">
                                    <i class="bx bx-edit-alt me-1"></i>
                                </a>
                                @endif

                                @if(auth()->user()->hasPermission('delete', 'users'))
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