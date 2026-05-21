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
                                <li class="breadcrumb-item"><a href="{{ route('admin.bookings.index') }}">Bookings</a></li>
                                <li class="breadcrumb-item">{{ $booking->booking_number }}</li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="page-header-title">
                                    <h2 class="mb-0">Booking — {{ $booking->booking_number }}</h2>
                                </div>
                                <div class="d-flex gap-2">
                                    @if ($booking->status === 'Confirmed')
                                        <form action="{{ route('admin.bookings.checkin', $booking->id) }}" method="POST">
                                            @csrf
                                            <button class="btn btn-success">
                                                <i class="ti ti-login me-1"></i> Check In
                                            </button>
                                        </form>
                                    @endif
                                    @if ($booking->status === 'Checked In')
                                        <form action="{{ route('admin.bookings.checkout', $booking->id) }}" method="POST">
                                            @csrf
                                            <button class="btn btn-warning">
                                                <i class="ti ti-logout me-1"></i> Check Out
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('admin.bookings.edit', $booking->id) }}" class="btn btn-primary">
                                        <i class="ti ti-edit me-1"></i> Edit
                                    </a>
                                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary">
                                        <i class="ti ti-arrow-left me-1"></i> Back
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('components.alerts')

            <div class="row">

                <!-- Left -->
                <div class="col-lg-8">

                    <!-- Booking Info -->
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="ti ti-clipboard me-2"></i>Booking Details</h5>
                            <span class="badge {{ $booking->getStatusBadgeClass() }} f-14">{{ $booking->status }}</span>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0">
                                    <tbody>
                                        <tr>
                                            <td class="text-muted fw-500" width="35%">Booking Number</td>
                                            <td><strong>{{ $booking->booking_number }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-500">Room</td>
                                            <td>
                                                Room <strong>{{ $booking->room->room_number }}</strong>
                                                — {{ $booking->room->type }}
                                                (Floor {{ $booking->room->floor }})
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-500">Check In</td>
                                            <td>
                                                <i class="ti ti-calendar-event text-success me-1"></i>
                                                <strong>{{ $booking->check_in->format('d M Y') }}</strong>
                                                @if ($booking->room->check_in_time)
                                                    <small class="text-muted ms-2">after
                                                        {{ \Carbon\Carbon::parse($booking->room->check_in_time)->format('h:i A') }}</small>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-500">Check Out</td>
                                            <td>
                                                <i class="ti ti-calendar-event text-danger me-1"></i>
                                                <strong>{{ $booking->check_out->format('d M Y') }}</strong>
                                                @if ($booking->room->check_out_time)
                                                    <small class="text-muted ms-2">before
                                                        {{ \Carbon\Carbon::parse($booking->room->check_out_time)->format('h:i A') }}</small>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-500">Duration</td>
                                            <td><span class="badge bg-light-secondary">{{ $booking->nights }} nights</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-500">Guests</td>
                                            <td>{{ $booking->adults }} Adults, {{ $booking->children }} Children</td>
                                        </tr>
                                        @if ($booking->special_requests)
                                            <tr>
                                                <td class="text-muted fw-500">Special Requests</td>
                                                <td>{{ $booking->special_requests }}</td>
                                            </tr>
                                        @endif
                                        @if ($booking->notes)
                                            <tr>
                                                <td class="text-muted fw-500">Admin Notes</td>
                                                <td>{{ $booking->notes }}</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Guest Info -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="ti ti-user me-2"></i>Guest Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0">
                                    <tbody>
                                        <tr>
                                            <td class="text-muted fw-500" width="35%">Name</td>
                                            <td><strong>{{ $booking->guest_name }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-500">Phone</td>
                                            <td>{{ $booking->guest_phone }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-500">CNIC / Passport</td>
                                            <td>{{ $booking->guest_cnic ?? '—' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-500">Email</td>
                                            <td>{{ $booking->guest_email ?? '—' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Right -->
                <div class="col-lg-4">

                    <!-- Payment -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="ti ti-credit-card me-2"></i>Payment Summary</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Room Price/Night</span>
                                <span>₨{{ number_format($booking->room_price) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Nights</span>
                                <span>× {{ $booking->nights }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="fw-500">Total Amount</span>
                                <span class="text-success fw-bold fs-5">₨{{ number_format($booking->total_amount) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Advance Paid</span>
                                <span class="text-info">₨{{ number_format($booking->advance_paid) }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="fw-500">Remaining</span>
                                <span
                                    class="text-danger fw-bold">₨{{ number_format($booking->getRemainingBalance()) }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Payment Method</span>
                                <span>{{ $booking->payment_method }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Payment Status</span>
                                <span
                                    class="badge {{ $booking->getPaymentBadgeClass() }}">{{ $booking->payment_status }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="ti ti-bolt me-2"></i>Quick Actions</h5>
                        </div>
                        <div class="card-body d-grid gap-2">
                            <a href="{{ route('admin.bookings.edit', $booking->id) }}" class="btn btn-outline-primary">
                                <i class="ti ti-edit me-1"></i> Edit Booking
                            </a>
                            <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary">
                                <i class="ti ti-list me-1"></i> All Bookings
                            </a>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection
