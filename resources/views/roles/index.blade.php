@extends('layouts.dashboard')

@section('title', $title . " | Laravel RBAC")
@section('pageTitle', $title)

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6 d-flex align-items-center">
                @if(auth()->user()->hasPermission('create', 'role'))
                {!! createButton(route('roles.create'), 'Role') !!}
                @endif
            </div>

            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                @if(auth()->user()->hasPermission('read', 'role'))
                <form action="{{ route('roles.index') }}" method="GET">
                    <div class="input-group">
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
        <hr class="mt-0">
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
                        <td>
                            <div class="row">
                                <div class="text-end">
                                    @if(auth()->user()->hasPermission('read', 'role'))
                                    <a href="{{ route('roles.show', $role) }}" class="btn btn-icon btn-outline-primary mt-2">
                                        <i class="bx bx-show-alt me-1"></i>
                                    </a>
                                    @endif

                                    @if(auth()->user()->hasPermission('update', 'role'))
                                    <a href="{{ route('roles.edit', $role) }}" class="btn btn-icon btn-outline-warning mt-2">
                                        <i class="bx bx-edit-alt me-1"></i>
                                    </a>
                                    @endif

                                    @if(auth()->user()->hasPermission('delete', 'role'))
                                    <form action="{{ route('roles.destroy', $role) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Are you sure?')" class="btn btn-icon btn-outline-danger mt-2">
                                            <i class="bx bx-trash me-1"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
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