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
                    <h3>Sign In</h3>
                    <h4>Please login to your account</h4>
                </div>
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-login">
                        <label>Email</label>
                        <div class="form-addons">
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="Enter your email address" value="{{ old('email') }}" autofocus>
                            @error('email')
                            <span class="invalid-feedback">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-login">
                        <label>Password</label>
                        <div class="pass-group">
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter your password" autocomplete="current-password">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                        <label class="form-check-label" for="remember">
                            {{ __('Remember Me') }}
                        </label>
                    </div>

                    <div class="form-login">
                        <button type="submit" class="btn btn-login">Sign In</button>
                    </div>


                    @if (Route::has('password.request'))
                    <div class="form-login">
                        <div class="alreadyuser">
                            <h4><a href="{{ route('password.request') }}" class="hover-a">Forgot Password?</a></h4>
                        </div>
                    </div>
                    @endif

                    @if (Route::has('register'))
                    <div class="signinform text-center">
                        <h4>Don’t have an account? <a href="{{ route('register') }}" class="hover-a">Sign Up</a></h4>
                    </div>
                    @endif
                </form>
            </div>
        </div>
        {{-- <div class="login-img"><img src="assets/img/login.jpg" alt="img"></div> --}}
    </div>
</div>
@endsection