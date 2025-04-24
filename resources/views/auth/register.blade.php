@extends('layouts.auth')
@section('content')
<div class="account-content">
    <div class="login-wrapper">
        <div class="login-content">
            <div class="login-userset">
                <div class="login-logo">
                    {{-- <img src="assets/img/logo.png" alt="img"> --}}
                </div>
                <div class="login-userheading">
                    <h3>Create an Account</h3>
                    <h4>Continue where you left off</h4>
                </div>
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="form-login">
                        <label>{{ __('Name') }}</label>
                        <div class="form-addons">
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" placeholder="Enter your name" autocomplete="name" autofocus>
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-login">
                        <label>{{ __('Email Address') }}</label>
                        <div class="form-addons">
                            <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="Enter your email address" autocomplete="email">
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-login">
                        <label>{{ __('Password') }}</label>
                        <div class="pass-group">
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter your password" autocomplete="new-password">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-login">
                        <label>{{ __('Confirm Password') }}</label>
                        <div class="pass-group">
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Enter your password confirmation" autocomplete="new-password">
                        </div>
                    </div>

                    <div class="form-login">
                        <button type="submit" class="btn btn-login">{{ __('Register') }}</button>
                    </div>
                </form>

                <div class="signinform text-center">
                    <h4>Already a user? <a href="{{ route('login') }}" class="hover-a">{{ __('Login') }}</a></h4>
                </div>
            </div>
        </div>
        {{-- <div class="login-img"><img src="assets/img/login.jpg" alt="img"></div> --}}
    </div>
</div>
@endsection