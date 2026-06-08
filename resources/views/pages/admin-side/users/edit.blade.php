@extends('layouts.admin')

@section('content')
    <div class="pc-container">
        <div class="pc-content">

            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
                                <li class="breadcrumb-item" aria-current="page">Edit — {{ $user->name }}</li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">Edit User — {{ $user->name }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('components.alerts')

            <form action="{{ route('users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">

                    <!-- LEFT -->
                    <div class="col-lg-8">

                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="ti ti-user me-2"></i>User Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            value="{{ old('name', $user->name) }}">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" name="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            value="{{ old('email', $user->email) }}">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">
                                            New Password
                                            <small class="text-muted">(Leave blank if you do not want to change it)</small>
                                        </label>
                                        <div class="input-group">
                                            <input type="password" name="password" id="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                placeholder="New password">
                                            <button class="btn btn-outline-secondary" type="button"
                                                onclick="togglePass('password')">
                                                <i class="ti ti-eye" id="eye-password"></i>
                                            </button>
                                        </div>
                                        @error('password')
                                            <div class="text-danger f-12 mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Confirm New Password</label>
                                        <div class="input-group">
                                            <input type="password" name="password_confirmation" id="password_confirmation"
                                                class="form-control" placeholder="Please re-enter your password">
                                            <button class="btn btn-outline-secondary" type="button"
                                                onclick="togglePass('password_confirmation')">
                                                <i class="ti ti-eye" id="eye-password_confirmation"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Phone</label>
                                        <input type="text" name="phone" class="form-control"
                                            value="{{ old('phone', $user->phone) }}" placeholder="Enter a phone number">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-select"
                                            {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                            <option value="active"
                                                {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>✅ Active
                                            </option>
                                            <option value="inactive"
                                                {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>❌
                                                Inactive
                                            </option>
                                        </select>
                                        @if ($user->id === auth()->id())
                                            <input type="hidden" name="status" value="{{ $user->status }}">
                                            <small class="text-muted">You cannot change your own status.</small>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- RIGHT -->
                    <div class="col-lg-4">

                        {{-- Role section is visible only to Admin --}}
                        @if (auth()->user()->isAdmin())

                            @php
                                $roles = [
                                    [
                                        'value' => 'manager',
                                        'label' => 'Manager',
                                        'icon' => '🏢',
                                        'desc' => 'Can manage rooms, bookings, and reports.',
                                        'color' => 'primary',
                                    ],
                                    [
                                        'value' => 'receptionist',
                                        'label' => 'Receptionist',
                                        'icon' => '🛎️',
                                        'desc' => 'Handles bookings and customers.',
                                        'color' => 'success',
                                    ],
                                    [
                                        'value' => 'accountant',
                                        'label' => 'Accountant',
                                        'icon' => '💼',
                                        'desc' => 'Can view billing and invoices.',
                                        'color' => 'warning',
                                    ],
                                    [
                                        'value' => 'staff',
                                        'label' => 'Staff',
                                        'icon' => '👤',
                                        'desc' => 'Basic view only',
                                        'color' => 'secondary',
                                    ],
                                ];
                            @endphp

                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="ti ti-shield me-2"></i>Role / Permission</h5>
                                </div>
                                <div class="card-body">

                                    {{-- If admin is editing their own profile --}}
                                    @if (isset($user) && $user->id === auth()->id())
                                        <div class="alert alert-warning py-2 f-13">
                                            <i class="ti ti-info-circle me-1"></i>
                                            You cannot change your own role.
                                        </div>
                                        <input type="hidden" name="role" value="{{ $user->role }}">

                                        {{-- Admin role cannot be changed. --}}
                                    @elseif(isset($user) && $user->isAdmin())
                                        <div class="alert alert-danger py-2 f-13">
                                            <i class="ti ti-lock me-1"></i>
                                            Admin role cannot be changed.
                                        </div>
                                        <input type="hidden" name="role" value="admin">

                                        {{-- Normal user edit — show roles --}}
                                    @else
                                        @foreach ($roles as $role)
                                            <div class="mb-2">
                                                <input type="radio" class="btn-check" name="role"
                                                    id="role_{{ $role['value'] }}" value="{{ $role['value'] }}"
                                                    {{ old('role', isset($user) ? $user->role : 'staff') == $role['value'] ? 'checked' : '' }}>

                                                <label
                                                    class="btn btn-outline-{{ $role['color'] }} w-100 text-start d-flex align-items-center gap-2"
                                                    for="role_{{ $role['value'] }}" style="padding:10px 14px;">
                                                    <span style="font-size:1.2rem;">{{ $role['icon'] }}</span>
                                                    <div>
                                                        <div class="fw-500">{{ $role['label'] }}</div>
                                                        <small class="text-muted">{{ $role['desc'] }}</small>
                                                    </div>
                                                </label>
                                            </div>
                                        @endforeach

                                        @error('role')
                                            <div class="text-danger f-12 mt-1">{{ $message }}</div>
                                        @enderror
                                    @endif

                                </div>
                            </div>
                        @else
                            @if (isset($user))
                                <input type="hidden" name="role" value="{{ $user->role }}">
                            @endif

                        @endif

                        <!-- Current Info -->
                        <div class="card mb-4 border-warning">
                            <div class="card-header bg-light-warning">
                                <h5 class="mb-0 text-warning"><i class="ti ti-info-circle me-2"></i>Current Info</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled mb-0 f-13">
                                    <li class="mb-2">
                                        <span class="text-muted">Current Role:</span>
                                        <span
                                            class="badge {{ $user->getRoleBadgeClass() }} ms-1">{{ $user->getRoleLabel() }}</span>
                                    </li>
                                    <li class="mb-2">
                                        <span class="text-muted">Status:</span>
                                        <span
                                            class="badge {{ $user->getStatusBadgeClass() }} ms-1">{{ ucfirst($user->status) }}</span>
                                    </li>
                                    <li>
                                        <span class="text-muted">Joined:</span>
                                        <strong class="ms-1">{{ $user->created_at->format('d M Y') }}</strong>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="card">
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-device-floppy me-2"></i>Update User
                                    </button>
                                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                                        <i class="ti ti-arrow-left me-2"></i>Cancel
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </form>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function togglePass(id) {
            const input = document.getElementById(id);
            const icon = document.getElementById('eye-' + id);
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'ti ti-eye-off';
            } else {
                input.type = 'password';
                icon.className = 'ti ti-eye';
            }
        }
    </script>
@endpush
