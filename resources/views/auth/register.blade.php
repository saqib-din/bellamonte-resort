{{-- @extends('layouts.admin')

@section('content')
    <div class="auth-main">
        <div class="auth-wrapper v1">
            <div class="auth-form">
                <div class="card my-5">
                    <div class="card-body">
                        <h4 class="text-center f-w-500 mb-3">Sign up with your work email.</h4>

                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <input type="text" class="form-control" name="first_name"
                                            placeholder="First Name" value="{{ old('first_name') }}" required />
                                        @error('first_name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror

                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-3">
                                        <input type="text" class="form-control" name="last_name" placeholder="Last Name"
                                            value="{{ old('last_name') }}" required />
                                        @error('last_name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror

                                    </div>
                                </div>

                            </div>

                            <div class="mb-3">
                                <input type="email" class="form-control" name="email" placeholder="Email Address"
                                    value="{{ old('email') }}" required />
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <input type="password" class="form-control" name="password" placeholder="Password"
                                    required />
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <input type="password" class="form-control" name="password_confirmation"
                                    placeholder="Confirm Password" required />
                                @error('password_confirmation')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="d-flex mt-1 justify-content-between">
                                <div class="form-check">
                                    <input class="form-check-input input-primary" type="checkbox" id="terms"
                                        name="terms" required />
                                    <label class="form-check-label text-muted" for="terms">I agree to all the Terms &
                                        Conditions</label>
                                </div>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary">Sign up</button>
                            </div>
                        </form>

                        <div class="d-flex justify-content-between align-items-end mt-4">
                            <h6 class="f-w-500 mb-0">Already have an Account?</h6>
                            <a href="{{ route('login') }}" class="link-primary">Login here</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection --}}
