@extends('layouts.dashboard')

{{-- @section('title', 'Home') --}}

@section('content')
{{-- <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">{{ __('Dashboard') }} /</span></h4> --}}

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <h5 class="card-header">{{ __('Dashboard') }}</h5>
            <!-- Account -->
            <div class="card-body">
                <div class="d-flex align-items-start align-items-sm-center gap-4">
                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
