@extends('layouts.admin')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item" aria-current="page">User</li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">Account Profile</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body py-0">
                            <ul class="nav nav-tabs profile-tabs" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link {{ session('active_tab', 'profile-1') == 'profile-1' ? 'active' : '' }}"
                                        id="profile-tab-1" data-bs-toggle="tab" href="#profile-1" role="tab"
                                        aria-selected="{{ session('active_tab', 'profile-1') == 'profile-1' ? 'true' : 'false' }}">
                                        <i class="ti ti-user me-2"></i>Personal Details
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="tab-content">
                        <div class="tab-pane fade {{ session('active_tab', 'profile-1') == 'profile-1' ? 'show active' : '' }}"
                            id="profile-1" role="tabpanel" aria-labelledby="profile-tab-1">
                            {{-- <div class="col-12 card mt-4">
                                <div class="card-header text-Danger">
                                    <h5 class="mb-0">Delete Account</h5>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted">
                                        Once your account is deleted, all of its resources and data will be permanently
                                        removed.
                                        Please download any important data before continuing.
                                    </p>
                                    <button class="btn btn-light-danger d-flex align-items-center" data-bs-toggle="modal"
                                        data-bs-target="#deleteAccountModal">
                                        <i class="ti ti-trash me-1"></i> Delete Account
                                    </button>
                                </div>
                            </div> --}}
                            <div class="row">

                                {{-- Personal Information --}}
                                <div class="col-lg-6">
                                    <form method="POST" action="{{ route('profile.update') }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('patch')
                                        <div class="card">
                                            <div class="card-header">
                                                <h5>Personal Information</h5>
                                                <p class="text-muted mb-0">Update your account's profile information and
                                                    email address.</p>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <x-input-label for="name" :value="__('Name')" />
                                                    <x-text-input id="name" name="name" type="text"
                                                        placeholder="Enter your name" class="form-control mt-1"
                                                        :value="old('name', $user->name)" required />
                                                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                                </div>
                                                <div class="mb-3">
                                                    <x-input-label for="email" :value="__('Email')" />
                                                    <x-text-input id="email" name="email" type="email"
                                                        placeholder="Enter your email" class="form-control mt-1"
                                                        :value="old('email', $user->email)" required />
                                                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
                                                </div>
                                            </div>
                                            <div class="card-footer text-end">
                                                <x-primary-button>{{ __('Save') }}</x-primary-button>

                                                @if (session('status') === 'profile-updated')
                                                    <span class="btn btn-light-success">Saved.</span>
                                                @endif
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                {{-- Update Password --}}
                                <div class="col-lg-6">
                                    <form method="POST" action="{{ route('password.update') }}">
                                        @csrf
                                        @method('put')
                                        <div class="card">
                                            <div class="card-header">
                                                <h5>Change Password</h5>
                                                <p class="text-muted mb-0">Ensure your account is secure with a strong
                                                    password.</p>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <x-input-label for="current_password" :value="__('Current Password')" />
                                                    <x-text-input id="current_password" name="current_password"
                                                        placeholder="Enter current password" type="password"
                                                        class="form-control mt-1" autocomplete="current-password" />
                                                    <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                                                </div>
                                                <div class="mb-3">
                                                    <x-input-label for="password" :value="__('New Password')" />
                                                    <x-text-input id="password" name="password" type="password"
                                                        placeholder="Enter new password" class="form-control mt-1"
                                                        autocomplete="new-password" />
                                                    <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                                                </div>
                                                <div class="mb-3">
                                                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                                                    <x-text-input id="password_confirmation" name="password_confirmation"
                                                        placeholder="Confirm new password" type="password"
                                                        class="form-control mt-1" autocomplete="new-password" />
                                                    <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                                                </div>
                                            </div>
                                            <div class="card-footer text-end">
                                                <x-primary-button>{{ __('Update Password') }}</x-primary-button>

                                                @if (session('status') === 'password-updated')
                                                    <span class="btn btn-light-success">Saved.</span>
                                                @endif
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                {{-- Delete Confirmation Modal --}}
                                <div class="modal fade" id="deleteAccountModal" tabindex="-1"
                                    aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <form method="POST" action="{{ route('profile.destroy') }}">
                                                @csrf
                                                @method('delete')
                                                <div class="modal-header text-danger">
                                                    <h5 class="modal-title" id="deleteAccountModalLabel">
                                                        Confirm Account Deletion
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white"
                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>
                                                        Once your account is deleted, all of its resources and data will be
                                                        permanently deleted.
                                                        Please enter your password to confirm this action.
                                                    </p>
                                                    <div class="mb-3">
                                                        <label class="form-label">Password</label>
                                                        <input type="password" name="password" class="form-control"
                                                            placeholder="Enter your password to confirm" required>

                                                        @if ($errors->userDeletion->has('password'))
                                                            <small class="text-danger">
                                                                {{ $errors->userDeletion->first('password') }}
                                                            </small>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light-secondary"
                                                        data-bs-dismiss="modal">Cancel</button>

                                                    <button type="submit" class="btn btn-light-danger">
                                                        Delete Account
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
{{-- <section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)"
                required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)"
                required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification"
                            class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section> --}}
