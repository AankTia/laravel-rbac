@extends('layouts.dashboard')

@section('title', "Roles | Laravel RBAC")

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-style1">
        <li class="breadcrumb-item">
            <a href="javascript:void(0);">User Management</a>
        </li>
        <li class="breadcrumb-item active">
            Role
        </li>
    </ol>
</nav>
@endsection

@section('pageAction')
<div class="row mb-4 align-items-center">
    <div class="col-md-12 mt-3 mt-md-0">
        @if(isUserCan('create', 'role'))
        {!! createButton(route('roles.create'), 'role.create', 'Role') !!}
        @endif
    </div>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row mb-4">
            <div class="col-md-9">
                <h5 class="pb-1">Roles</h5>
            </div>

            <div class="col-md-3 text-md-end mt-3 mt-md-0">
                @if(isUserCan('read', 'role'))
                <form action="{{ route('roles.index') }}" method="GET">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white">
                            <i class="bx bx-search-alt"></i>
                        </span>
                        <input type="text" name="search_keyword" value="{{ request('search_keyword') }}" class="form-control" placeholder="Search roles...">
                    </div>
                </form>
                @endif
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ $attributeLabels['name'] }}</th>
                        <th>{{ $attributeLabels['description'] }}</th>
                        <th class="text-center">Total Users</th>
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
                        <td class="text-center">{{ $role->getTotalUsers() }}</td>
                        <td class="text-end">

                            {!! buttonIconForShow(route('roles.show', $role), 'role.read') !!}
                            {!! buttonIconForEdit(route('roles.edit', $role), 'role.update') !!}

                            @if(isUserCan('delete', 'role'))
                            <form action="{{ route('roles.destroy', $role) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                {!! submitButtonIconForDelete() !!}
                            </form>
                            @endif
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