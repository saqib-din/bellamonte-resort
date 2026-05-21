@extends('layouts.admin')

@section('content')
    <div class="auth-main">
        <div class="auth-wrapper v1">
            <div class="auth-form">
                <div class="card my-5">
                    <div class="card-body">
                        <div class="pt-0 pb-4 d-flex justify-content-center">
                            <img src="{{ asset('admin/assets/images/bella.png') }}" class="img-fluid"
                                alt="Bellamonte Resort App Logo" />
                        </div>

                        <h4 class="text-center f-w-500 mb-4">Login with your email</h4>

                        @include('components.alerts')

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-3">
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="Email Address" value="{{ old('email') }}" required autofocus
                                    autocomplete="username" />
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Password" required autocomplete="current-password" />
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="d-flex mt-1 justify-content-between align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input input-primary" type="checkbox" id="remember_me"
                                        name="remember" />
                                    <label class="form-check-label text-muted" for="remember_me">Remember me?</label>
                                </div>
                                @if (Route::has('password.request'))
                                    <h6 class="text-secondary f-w-400 mb-0">
                                        <a href="{{ route('password.request') }}">Forgot Password?</a>
                                    </h6>
                                @endif
                            </div>

                            <!-- Submit -->
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-light-primary">Login</button>
                            </div>
                        </form>

                        {{-- <div class="d-flex justify-content-between align-items-end mt-4">
                            <h6 class="f-w-500 mb-0">Don't have an Account?</h6>
                            <a href="{{ route('register') }}" class="link-primary">Create Account</a>
                            @auth
                                @if (auth()->user()->role === 'admin')
                                    <a href="{{ route('register') }}" class="link-primary">Create Account</a>
                                @endif
                            @endauth
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
