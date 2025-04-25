<!DOCTYPE html>
<html lang="en">

<head>
    @include('partials._meta')

    <title>Laravel RBAC | @yield('title')</title>

    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">

    @include('partials._stylesheet_assets')
    <link rel="stylesheet" href="{{ asset('assets/css/animate.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap4.min.css') }}"> --}}
</head>

<body>
    <div id="global-loader">
        <div class="whirly-loader"> </div>
    </div>

    <div class="main-wrapper">

        @include('partials._header')
        @include('partials._sidebar')

        <div class="page-wrapper pagehead">
            <div class="content">
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <h3 class="page-title">@yield('title')</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                                <li class="breadcrumb-item active">Blank Page</li>
                            </ul>
                        </div>
                    </div>
                </div>

                @include('partials._alert')

                <div class="row">
                    <div class="col-sm-12">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials._script_assets')
    <script src="{{ asset('assets/js/jquery.slimscroll.min.js') }}"></script>
</body>

</html>