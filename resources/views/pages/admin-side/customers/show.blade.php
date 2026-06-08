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
                                <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">Customers</a></li>
                                <li class="breadcrumb-item" aria-current="page">{{ $customer->name }}</li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="page-header-title">
                                    <h2 class="mb-0">{{ $customer->name }}</h2>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('customers.edit', $customer) }}" class="btn btn-primary">
                                        <i class="ti ti-edit me-1"></i> Edit
                                    </a>
                                    <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">
                                        <i class="ti ti-arrow-left me-1"></i> Back
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">

                <!-- LEFT: Profile Card -->
                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-body text-center p-4">

                            @if ($customer->image)
                                <img src="{{ asset('uploads/customers/' . $customer->image) }}" class="rounded-circle mb-3"
                                    style="width:110px;height:110px;object-fit:cover;">
                            @else
                                <div class="rounded-circle bg-light-primary d-flex align-items-center justify-content-center mx-auto mb-3"
                                    style="width:110px;height:110px;">
                                    <span class="fw-bold text-primary" style="font-size:2.5rem;">
                                        {{ strtoupper(substr($customer->name, 0, 1)) }}
                                    </span>
                                </div>
                            @endif

                            <h4 class="mb-1">{{ $customer->name }}</h4>
                            @if ($customer->father_name)
                                <p class="text-muted mb-1 f-13">S/O {{ $customer->father_name }}</p>
                            @endif
                            <p class="text-muted mb-2">{{ $customer->email ?? 'No email' }}</p>
                            <span class="badge {{ $customer->getStatusBadgeClass() }} mb-3">{{ $customer->status }}</span>

                            <hr>

                            <!-- Stats -->
                            <div class="row text-center">
                                <div class="col-4">
                                    <h5 class="mb-0 text-primary">{{ $customer->getTotalStays() }}</h5>
                                    <small class="text-muted">Total Stays</small>
                                </div>
                                <div class="col-4">
                                    <h5 class="mb-0 text-success">₨{{ number_format($customer->getTotalSpent()) }}</h5>
                                    <small class="text-muted">Total Spent</small>
                                </div>
                                <div class="col-4">
                                    <h5 class="mb-0 text-info">{{ $customer->getAge() }}</h5>
                                    <small class="text-muted">Age</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Info -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="ti ti-info-circle me-2"></i>Details</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                @if ($customer->father_name)
                                    <li class="mb-3 d-flex gap-3">
                                        <i class="ti ti-user-check text-muted mt-1"></i>
                                        <div>
                                            <small class="text-muted d-block">Father Name</small>
                                            <strong>{{ $customer->father_name }}</strong>
                                        </div>
                                    </li>
                                @endif
                                <li class="mb-3 d-flex gap-3">
                                    <i class="ti ti-id text-muted mt-1"></i>
                                    <div>
                                        <small class="text-muted d-block">CNIC / Passport</small>
                                        <strong>{{ $customer->cnic }}</strong>
                                    </div>
                                </li>
                                <li class="mb-3 d-flex gap-3">
                                    <i class="ti ti-phone text-muted mt-1"></i>
                                    <div>
                                        <small class="text-muted d-block">Phone</small>
                                        <strong>{{ $customer->phone }}</strong>
                                    </div>
                                </li>
                                <li class="mb-3 d-flex gap-3">
                                    <i class="ti ti-user text-muted mt-1"></i>
                                    <div>
                                        <small class="text-muted d-block">Gender</small>
                                        <strong>{{ $customer->gender ?? '—' }}</strong>
                                    </div>
                                </li>
                                <li class="mb-3 d-flex gap-3">
                                    <i class="ti ti-flag text-muted mt-1"></i>
                                    <div>
                                        <small class="text-muted d-block">Nationality</small>
                                        <strong>{{ $customer->nationality }}</strong>
                                    </div>
                                </li>
                                <li class="mb-3 d-flex gap-3">
                                    <i class="ti ti-building text-muted mt-1"></i>
                                    <div>
                                        <small class="text-muted d-block">City</small>
                                        <strong>{{ $customer->city ?? '—' }}</strong>
                                    </div>
                                </li>
                                @if ($customer->address)
                                    <li class="mb-3 d-flex gap-3">
                                        <i class="ti ti-map-pin text-muted mt-1"></i>
                                        <div>
                                            <small class="text-muted d-block">Address</small>
                                            <strong>{{ $customer->address }}</strong>
                                        </div>
                                    </li>
                                @endif
                                @if ($customer->notes)
                                    <li class="d-flex gap-3">
                                        <i class="ti ti-notes text-muted mt-1"></i>
                                        <div>
                                            <small class="text-muted d-block">Notes</small>
                                            <strong>{{ $customer->notes }}</strong>
                                        </div>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- RIGHT: Booking History -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="ti ti-calendar me-2"></i>Booking History</h5>
                            <span class="badge bg-light-primary">{{ $customer->bookings->count() }} bookings</span>
                        </div>
                        <div class="card-body table-card" style="padding:0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Booking #</th>
                                            <th>Room</th>
                                            <th>Check In</th>
                                            <th>Check Out</th>
                                            <th>Nights</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($customer->bookings as $booking)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('admin.bookings.show', $booking) }}"
                                                        class="text-primary fw-500">
                                                        {{ $booking->booking_number }}
                                                    </a>
                                                </td>
                                                <td>
                                                    Room <strong>{{ $booking->room->room_number ?? '—' }}</strong>
                                                    <small
                                                        class="text-muted d-block">{{ $booking->room->type ?? '' }}</small>
                                                </td>
                                                <td>{{ $booking->check_in->format('d M Y') }}</td>
                                                <td>{{ $booking->check_out->format('d M Y') }}</td>
                                                <td><span class="badge bg-light-secondary">{{ $booking->nights }}n</span>
                                                </td>
                                                <td class="text-success fw-500">
                                                    ₨{{ number_format($booking->total_amount) }}</td>
                                                <td>
                                                    <span class="badge {{ $booking->getStatusBadgeClass() }}">
                                                        {{ $booking->status }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-4 text-muted">
                                                    <i class="ti ti-calendar-off f-30 d-block mb-1"></i>
                                                    Koi booking nahi mili
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
