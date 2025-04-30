@extends('layouts.dashboard')
@section('title', $viewData['title'])
@section('subtitle', $viewData['subtitle'])

@section('pageButton')
<a href="#" class="btn btn-added"><img src="{{ asset('assets/img/icons/plus.svg') }}" alt="img" class="me-1">Add New {{ $viewData['subtitle'] }}</a>
@endsection

@section('content')
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