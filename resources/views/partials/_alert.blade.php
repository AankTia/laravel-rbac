@if (session('success'))
<div class="alert alert-primary alert-dismissible" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if (session('info'))
<div class="alert alert-info alert-dismissible" role="alert">
    {{ session('info') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if (session('info'))
<div class="alert alert-danger alert-dismissible" role="alert">
    {{ session('errors') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if (session('error'))
<div class="alert alert-danger alert-dismissible" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

{{-- <div class="alert alert-secondary alert-dismissible" role="alert">
    This is a secondary dismissible alert — check it out!
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div> --}}

{{-- <div class="alert alert-success alert-dismissible" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div> --}}

{{-- <div class="alert alert-warning alert-dismissible" role="alert">
    This is a warning dismissible alert — check it out!
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div> --}}

{{-- <div class="alert alert-dark alert-dismissible" role="alert">
    This is a dark dismissible alert — check it out!
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div> --}}