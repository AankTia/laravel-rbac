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
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($roles as $role)
                        <tr>
                            <td>{{ $role->display_name }}</td>
                            <td>{{ $role->description }}</td>
                            <td>
                                <a class="me-3" href="#">
                                    <img src="{{ asset('assets/img/icons/eye.svg') }}" alt="img">
                                </a>
                                <a class="me-3" href="#">
                                    <img src="{{ asset('assets/img/icons/edit.svg') }}" alt="img">
                                </a>
                                <a class="confirm-text" href="javascript:void(0);">
                                    <img src="{{ asset('assets/img/icons/delete.svg') }}" alt="img">
                                </a>
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