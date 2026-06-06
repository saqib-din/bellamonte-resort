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

                {{-- ══ Dashboard — All Roles ══ --}}
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

                {{-- ══ Billing — Admin + Manager + Accountant ══ --}}
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

                {{-- ══ Foods Menu — Admin + Manager + Receptionist ══ --}}
                @if (in_array($role, ['admin', 'manager', 'receptionist']))
                    <li class="pc-item pc-hasmenu">
                        <a href="#!" class="pc-link">
                            <span class="pc-micon">
                                <i class="ti ti-tools-kitchen-2"></i>
                            </span>
                            <span class="pc-mtext">Foods Menu</span>
                            <span class="pc-arrow">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="feather feather-chevron-right">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </span>
                        </a>
                        <ul class="pc-submenu">
                            @if (in_array($role, ['admin', 'manager']))
                                <li class="pc-item">
                                    <a class="pc-link" href="{{ route('food.categories.index') }}">
                                        Categories
                                    </a>
                                </li>
                                <li class="pc-item">
                                    <a class="pc-link" href="{{ route('food.items.index') }}">
                                        Items
                                    </a>
                                </li>
                            @endif
                            <li class="pc-item">
                                <a class="pc-link" href="{{ route('food.orders.index') }}">
                                    Orders
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                {{-- ══ Events — Admin + Manager ══ --}}
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

                {{-- ══ About Us — Admin + Manager ══ --}}
                @if (in_array($role, ['admin', 'manager']))
                    <li class="pc-item">
                        <a href="{{ route('about.index') }}" class="pc-link">
                            <span class="pc-micon">
                                <svg class="pc-icon">
                                    <use xlink:href="#custom-keyboard"></use>
                                </svg>
                            </span>
                            <span class="pc-mtext">About Us</span>
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

                {{-- ══ Settings — Admin Only ══ --}}
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

                {{-- ══ Users — Admin Only ══ --}}
                @if ($role === 'admin')
                    <li class="pc-item">
                        <a href="{{ route('users.index') }}" class="pc-link">
                            <span class="pc-micon">
                                <svg class="pc-icon">
                                    <use xlink:href="#custom-user-square"></use>
                                </svg>
                            </span>
                            <span class="pc-mtext">Users</span>
                        </a>
                    </li>
                @endif

            </ul>
        </div>
    </div>
</nav>
