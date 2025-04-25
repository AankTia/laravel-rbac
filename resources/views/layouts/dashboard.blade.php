<!DOCTYPE html>
<html lang="en">

<head>
    @include('partials._meta')

    <title>@yield('title', 'Laravel RBAC')</title>

    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">

    @include('partials._stylesheet_assets')
    <link rel="stylesheet" href="{{ asset('assets/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap4.min.css') }}">
</head>

<body>
    <div id="global-loader">
        <div class="whirly-loader"> </div>
    </div>

    <div class="main-wrapper">

        @include('partials._header')
        @include('partials._sidebar')

        <div class="page-wrapper">
            <div class="content">
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <h3 class="page-title">@yield('subtitle')</h3>

                            <div class="mt-3 mb-3">
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="#">...</a></li>
                                    <li class="breadcrumb-item active">...</li>
                                </ul>
                            </div>

                            <div class="page-btn">
                                @yield('pageButton')
                            </div>
                        </div>
                    </div>
                </div>

                @include('partials._alert')

                @yield('content')
            </div>
        </div>
    </div>

    @include('partials._script_assets')
    <script src="{{ asset('assets/js/jquery.slimscroll.min.js') }}"></script>
</body>

</html>