@extends('layouts.dashboard')
@section('title', $viewData['title'])
@section('subtitle', $viewData['subtitle'])

@section('pageButton')
<a class="btn btn btn-primary">
    Add New {{ $viewData['subtitle'] }}
</a>
<a class="btn btn btn-warning">
    Update {{ $viewData['subtitle'] }}
</a>
<a class="btn btn btn-danger">
    Delete {{ $viewData['subtitle'] }}
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="productdetails">
            <ul class="product-bar">
                <li>
                    <h4>Nama</h4>
                    <h6>{{ $role->display_name }}</h6>
                </li>
                <li>
                    <h4>Description</h4>
                    <h6>{{ $role->description }}</h6>
                </li>
                <li>
                    <h4>Created</h4>
                    <h6>{{ $role->created_at }}</h6>
                </li>
                <li>
                    <h4>Last Updated</h4>
                    <h6>{{ $role->updated_at }}</h6>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection