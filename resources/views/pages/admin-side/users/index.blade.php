@extends('layouts.admin')

@section('content')
    <div class="pc-container">
        <div class="pc-content">

            <!-- Breadcrumb -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item" aria-current="page">Users & Roles</li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">👥 Users & Roles Management</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('components.alerts')

            <!-- Stats -->
            <div class="row mb-4">
                <div class="col-md-2 col-sm-4">
                    <div class="card text-center">
                        <div class="card-body py-3">
                            <h4 class="mb-1 text-primary">{{ $users->count() }}</h4>
                            <p class="mb-0 text-muted f-12">Total Users</p>
                        </div>
                    </div>
                </div>
                @foreach ([['role' => 'admin', 'label' => 'Admins', 'color' => 'danger'], ['role' => 'manager', 'label' => 'Managers', 'color' => 'primary'], ['role' => 'receptionist', 'label' => 'Receptionists', 'color' => 'success'], ['role' => 'accountant', 'label' => 'Accountants', 'color' => 'warning'], ['role' => 'staff', 'label' => 'Staff', 'color' => 'secondary']] as $r)
                    <div class="col-md-2 col-sm-4">
                        <div class="card text-center">
                            <div class="card-body py-3">
                                <h4 class="mb-1 text-{{ $r['color'] }}">{{ $users->where('role', $r['role'])->count() }}
                                </h4>
                                <p class="mb-0 text-muted f-12">{{ $r['label'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Table Card -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-sm-flex align-items-center justify-content-between">
                                <h5 class="mb-3 mb-sm-0">All Users</h5>
                                @if (auth()->user()->isAdmin())
                                    <a href="{{ route('users.create') }}" class="btn btn-primary d-flex">
                                        <i class="ti ti-user-plus me-1"></i> Add User
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="card-body table-card">
                            <div class="table-responsive">
                                <table class="table table-hover" id="pc-dt-simple">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>User</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th>Joined</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($users as $user)
                                            <tr
                                                @if ($user->id === auth()->id()) style="background:rgba(70,128,255,0.05);" @endif>
                                                <td>{{ $loop->iteration }}</td>

                                                <!-- Name + Avatar -->
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avtar avtar-s me-3 {{ $user->getRoleBadgeClass() }} rounded-circle d-flex align-items-center justify-content-center"
                                                            style="width:40px;height:40px;font-weight:700;font-size:1rem;">
                                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0">
                                                                {{ $user->name }}
                                                                @if ($user->id === auth()->id())
                                                                    <span class="badge bg-light-info ms-1">You</span>
                                                                @endif
                                                            </h6>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->phone ?? '—' }}</td>

                                                <!-- Role -->
                                                <td>
                                                    <span class="badge {{ $user->getRoleBadgeClass() }}">
                                                        {{ $user->getRoleLabel() }}
                                                    </span>
                                                </td>

                                                <!-- Status -->
                                                <td>
                                                    <span class="badge {{ $user->getStatusBadgeClass() }}">
                                                        {{ ucfirst($user->status) }}
                                                    </span>
                                                </td>

                                                <td>{{ $user->created_at->format('d M Y') }}</td>

                                                <!-- Actions -->
                                                <td class="text-end">
                                                    @if (auth()->user()->isAdmin())
                                                        {{-- Toggle Status --}}
                                                        @if ($user->id !== auth()->id())
                                                            <form action="{{ route('admin.users.toggle', $user->id) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="avtar avtar-xs {{ $user->status === 'active' ? 'btn-link-warning' : 'btn-link-success' }}"
                                                                    title="{{ $user->status === 'active' ? 'Deactivate' : 'Activate' }}">
                                                                    <i
                                                                        class="ti ti-{{ $user->status === 'active' ? 'user-off' : 'user-check' }} f-18"></i>
                                                                </button>
                                                            </form>
                                                        @endif

                                                        {{-- Edit --}}
                                                        <a href="{{ route('users.edit', $user->id) }}"
                                                            class="avtar avtar-xs btn-link-secondary" title="Edit">
                                                            <i class="ti ti-edit f-18"></i>
                                                        </a>

                                                        {{-- Delete — Admin cannot delete their own account --}}
                                                        @if ($user->id !== auth()->id())
                                                            <a href="#"
                                                                class="avtar avtar-xs btn-link-secondary bs-pass-para"
                                                                data-id="{{ $user->id }}"
                                                                data-name="{{ $user->name }}" title="Delete">
                                                                <i class="ti ti-trash f-18"></i>
                                                            </a>
                                                            <form id="delete-form-{{ $user->id }}"
                                                                action="{{ route('users.destroy', $user->id) }}"
                                                                method="POST" style="display:none;">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                        {{-- @else
                                                            <span class="avtar avtar-xs text-muted"
                                                                title="Aap khud ko delete nahi kar sakte"
                                                                style="cursor:not-allowed;opacity:0.3;">
                                                                <i class="ti ti-trash f-18"></i>
                                                            </span> --}}
                                                        @endif
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-5 text-muted">
                                                    <i class="ti ti-users f-40 d-block mb-2"></i>
                                                    No users found.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            new simpleDatatables.DataTable('#pc-dt-simple', {
                sortable: true,
                searchable: true,
                fixedHeight: true
            });

            document.querySelectorAll('.bs-pass-para').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const name = this.dataset.name;
                    if (confirm(name +
                            ' ka account delete karna chahte hain?\nYeh action wapas nahi ho sakta!'
                            )) {
                        document.getElementById('delete-form-' + this.dataset.id).submit();
                    }
                });
            });
        });
    </script> --}}
@endpush
