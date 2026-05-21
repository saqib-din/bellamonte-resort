<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="navbar-content">
            <div class="card pc-user-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <a href="{{ route('dashboard') }}">
                                <img src="{{ asset('admin/assets/images/user/avatar-1.jpg') }}" alt="user"
                                    class="user-avtar rounded-circle" style="width: 50px; height: 50px;" />
                            </a>
                        </div>
                        <div class="flex-grow-1 ms-3 me-2">
                            <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                            <small class="text-muted text-capitalize">{{ Auth::user()->role }}</small>
                        </div>
                    </div>
                </div>
            </div>

            @php $role = Auth::user()->role; @endphp

            <ul class="pc-navbar">

                <li class="pc-item pc-caption">
                    <label>Navigation</label>
                </li>

                {{-- ══ Dashboard — sab ko ══ --}}
                <li class="pc-item">
                    <a href="{{ route('dashboard') }}" class="pc-link">
                        <span class="pc-micon">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-status-up"></use>
                            </svg>
                        </span>
                        <span class="pc-mtext">Dashboard</span>
                    </a>
                </li>

                {{-- ══ Rooms — Admin + Manager ══ --}}
                @if (in_array($role, ['admin', 'manager']))
                    <li class="pc-item">
                        <a href="{{ route('admin.rooms.index') }}" class="pc-link">
                            <span class="pc-micon">
                                <svg class="pc-icon">
                                    <use xlink:href="#custom-element-plus"></use>
                                </svg>
                            </span>
                            <span class="pc-mtext">Rooms</span>
                        </a>
                    </li>
                @endif


                {{-- ══ Bookings — Admin + Manager + Receptionist ══ --}}
                @if (in_array($role, ['admin', 'manager', 'receptionist']))
                    <li class="pc-item">
                        <a href="{{ route('admin.bookings.index') }}" class="pc-link">
                            <span class="pc-micon">
                                <svg class="pc-icon">
                                    <use xlink:href="#custom-link"></use>
                                </svg>
                            </span>
                            <span class="pc-mtext">Bookings</span>
                        </a>
                    </li>
                @endif

                {{-- ══ Customers — Admin + Manager + Receptionist ══ --}}
                @if (in_array($role, ['admin', 'manager', 'receptionist']))
                    <li class="pc-item">
                        <a href="{{ route('customers.index') }}" class="pc-link">
                            <span class="pc-micon">
                                <i class="ti ti-users"></i>
                            </span>
                            <span class="pc-mtext">Customers</span>
                        </a>
                    </li>
                @endif

                {{-- ══ Billing/Invoices — Admin + Manager + Accountant ══ --}}
                @if (in_array($role, ['admin', 'manager', 'accountant']))
                    <li class="pc-item">
                        <a href="{{ route('billing.index') }}" class="pc-link">
                            <span class="pc-micon">
                                <svg class="pc-icon">
                                    <use xlink:href="#custom-mouse-circle"></use>
                                </svg>
                            </span>
                            <span class="pc-mtext">Invoices</span>
                        </a>
                    </li>
                @endif

                @if (in_array($role, ['admin', 'manager']))
                    <li class="pc-item">
                        <a href="{{ route('events.index') }}" class="pc-link">
                            <span class="pc-micon">
                                <i class="ti ti-calendar-event"></i>
                            </span>
                            <span class="pc-mtext">Events</span>
                        </a>
                    </li>
                @endif

                {{-- ══ Contacts — Admin + Manager + Receptionist ══ --}}
                @if (in_array($role, ['admin', 'manager', 'receptionist']))
                    <li class="pc-item">
                        <a href="{{ route('contacts.index') }}" class="pc-link">
                            <span class="pc-micon">
                                <svg class="pc-icon">
                                    <use xlink:href="#custom-24-support"></use>
                                </svg>
                            </span>
                            <span class="pc-mtext">Contacts</span>
                        </a>
                    </li>
                @endif

                {{-- ══ Settings — Sirf Admin ══ --}}
                @if ($role === 'admin')
                    <li class="pc-item">
                        <a href="{{ route('settings.index') }}" class="pc-link">
                            <span class="pc-micon">
                                <svg class="pc-icon">
                                    <use xlink:href="#custom-setting-2"></use>
                                </svg>
                            </span>
                            <span class="pc-mtext">Settings</span>
                        </a>
                    </li>
                @endif

                {{-- ══ Users — Sirf Admin ══ --}}
                @if ($role === 'admin')
                    <li class="pc-item">
                        <a href="{{ route('users.index') }}" class="pc-link">
                            <span class="pc-micon">
                                <svg class="pc-icon">
                                    <use xlink:href="#custom-user-square"></use>
                                </svg>
                            </span>
                            <span class="pc-mtext">User Profile</span>
                        </a>
                    </li>
                @endif

            </ul>
        </div>
    </div>
</nav>
