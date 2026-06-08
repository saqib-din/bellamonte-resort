@extends('layouts.admin')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tom-select/2.3.1/css/tom-select.bootstrap5.min.css">
    <style>
        .ts-wrapper .ts-control {
            border-radius: 6px;
            min-height: 38px;
            padding: 4px 8px;
        }

        .ts-wrapper.focus .ts-control {
            border-color: #4680ff;
            box-shadow: 0 0 0 0.2rem rgba(70, 128, 255, .25);
        }
    </style>
@endpush

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
                                <li class="breadcrumb-item" aria-current="page">Edit — {{ $booking->booking_number }}</li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">Edit Booking — {{ $booking->booking_number }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('components.alerts')

            <form action="{{ route('admin.bookings.update', $booking) }}" method="POST" id="bookingForm">
                @csrf
                @method('PUT')
                <div class="row">

                    <!-- LEFT -->
                    <div class="col-lg-8">

                        <!-- Room & Customer -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="ti ti-home me-2"></i>Room & Customer</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <label class="form-label">Select Room <span class="text-danger">*</span></label>
                                        <select name="room_id" id="roomSelect"
                                            class="form-select @error('room_id') is-invalid @enderror">
                                            <option value="">-- Select Room --</option>
                                            @foreach ($rooms as $room)
                                                <option value="{{ $room->id }}"
                                                    data-price="{{ $room->price_per_night }}"
                                                    data-type="{{ $room->type }}" data-capacity="{{ $room->capacity }}"
                                                    {{ old('room_id', $booking->room_id) == $room->id ? 'selected' : '' }}>
                                                    Room {{ $room->room_number }} — {{ $room->type }}
                                                    (₨{{ number_format($room->price_per_night) }}/night)
                                                    @if ($room->id != $booking->room_id && $room->status != 'Available')
                                                        [Occupied]
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('room_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror

                                        <!-- Room info box -->
                                        <div id="roomInfoBox" class="mt-2 p-2 bg-light rounded" style="display:none;">
                                            <small>
                                                <span class="text-muted">Type:</span> <strong id="rType"></strong>
                                                &nbsp;|&nbsp;
                                                <span class="text-muted">Capacity:</span> <strong id="rCapacity"></strong>
                                                &nbsp;|&nbsp;
                                                <span class="text-muted">Price:</span>
                                                <strong class="text-success" id="rPrice"></strong>/night
                                            </small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Select Customer <span class="text-danger">*</span></label>
                                        <select name="customer_id" id="customerSelect"
                                            class="form-select @error('customer_id') is-invalid @enderror">
                                            <option value="">-- Select Customer --</option>
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}" data-name="{{ $customer->name }}"
                                                    data-phone="{{ $customer->phone }}" data-cnic="{{ $customer->cnic }}"
                                                    data-email="{{ $customer->email }}"
                                                    {{ old('customer_id', $booking->customer_id) == $customer->id ? 'selected' : '' }}>
                                                    {{ $customer->name }} — {{ $customer->phone }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('customer_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Guest Details -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="ti ti-user me-2"></i>Guest Details</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <label class="form-label">Guest Name <span class="text-danger">*</span></label>
                                        <input type="text" name="guest_name" id="guestName"
                                            class="form-control @error('guest_name') is-invalid @enderror"
                                            value="{{ old('guest_name', $booking->guest_name) }}" placeholder="Full name">
                                        @error('guest_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Father Name</label>
                                        <input type="text" name="father_name" id="fatherName"
                                            class="form-control @error('father_name') is-invalid @enderror"
                                            value="{{ old('father_name', $booking->father_name) }}"
                                            placeholder="Father name (optional)">
                                        @error('father_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Phone <span class="text-danger">*</span></label>
                                        <input type="text" name="guest_phone" id="guestPhone"
                                            class="form-control @error('guest_phone') is-invalid @enderror"
                                            value="{{ old('guest_phone', $booking->guest_phone) }}"
                                            placeholder="0300-1234567">
                                        @error('guest_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">CNIC / Passport</label>
                                        <input type="text" name="guest_cnic" id="guestCnic" class="form-control"
                                            value="{{ old('guest_cnic', $booking->guest_cnic) }}"
                                            placeholder="35202-1234567-1">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="guest_email" id="guestEmail" class="form-control"
                                            value="{{ old('guest_email', $booking->guest_email) }}"
                                            placeholder="guest@email.com">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Adults <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="ti ti-users"></i></span>
                                            <input type="number" name="adults" class="form-control"
                                                value="{{ old('adults', $booking->adults) }}" min="1">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Children</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="ti ti-users"></i></span>
                                            <input type="number" name="children" class="form-control"
                                                value="{{ old('children', $booking->children) }}" min="0">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Dates -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="ti ti-calendar me-2"></i>Check In / Check Out</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <label class="form-label">Check In Date <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="ti ti-calendar-event"></i></span>
                                            <input type="date" name="check_in" id="checkIn"
                                                class="form-control @error('check_in') is-invalid @enderror"
                                                value="{{ old('check_in', $booking->check_in->format('Y-m-d')) }}"
                                                onchange="calcTotal()">
                                        </div>
                                        @error('check_in')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Check Out Date <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="ti ti-calendar-event"></i></span>
                                            <input type="date" name="check_out" id="checkOut"
                                                class="form-control @error('check_out') is-invalid @enderror"
                                                value="{{ old('check_out', $booking->check_out->format('Y-m-d')) }}"
                                                onchange="calcTotal()">
                                        </div>
                                        @error('check_out')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Live cost calculator -->
                                    <div class="col-12" id="costBox">
                                        <div class="alert alert-success mb-0 py-2">
                                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                                <span>
                                                    <i class="ti ti-moon me-1"></i> Nights:
                                                    <strong id="nightsCount">{{ $booking->nights }}</strong>
                                                </span>
                                                <span>
                                                    <i class="ti ti-currency-rupee me-1"></i> Per Night:
                                                    <strong
                                                        id="pricePerNight">₨{{ number_format($booking->room_price) }}</strong>
                                                </span>
                                                <span class="fs-5">
                                                    <i class="ti ti-calculator me-1"></i> Total:
                                                    <strong class="text-success" id="totalAmount">
                                                        ₨{{ number_format($booking->total_amount) }}
                                                    </strong>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="ti ti-notes me-2"></i>Notes</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Special Requests (Guest)</label>
                                        <textarea name="special_requests" class="form-control" rows="3" placeholder="Guest ki koi special request...">{{ old('special_requests', $booking->special_requests) }}</textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Admin Notes</label>
                                        <textarea name="notes" class="form-control" rows="3" placeholder="Internal notes...">{{ old('notes', $booking->notes) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- RIGHT -->
                    <div class="col-lg-4">

                        <!-- Current Booking Info -->
                        <div class="card mb-4 border-warning">
                            <div class="card-header bg-light-warning">
                                <h5 class="mb-0 text-warning"><i class="ti ti-info-circle me-2"></i>Current Info</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2">
                                        <small class="text-muted">Booking #</small>
                                        <strong class="d-block">{{ $booking->booking_number }}</strong>
                                    </li>
                                    <li class="mb-2">
                                        <small class="text-muted">Current Room</small>
                                        <strong class="d-block">
                                            Room {{ $booking->room->room_number }} — {{ $booking->room->type }}
                                        </strong>
                                    </li>
                                    <li class="mb-2">
                                        <small class="text-muted">Current Status</small>
                                        <span class="badge {{ $booking->getStatusBadgeClass() }} d-block mt-1">
                                            {{ $booking->status }}
                                        </span>
                                    </li>
                                    <li>
                                        <small class="text-muted">Current Total</small>
                                        <strong class="d-block text-success">
                                            ₨{{ number_format($booking->total_amount) }}
                                        </strong>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Booking Status -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="ti ti-settings me-2"></i>Booking Status</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Booking Status <span class="text-danger">*</span></label>
                                    <select name="status" class="form-select">
                                        @php
                                            $icons = [
                                                'Confirmed' => '✅',
                                                'Checked In' => '🏨',
                                                'Checked Out' => '🚪',
                                                'Cancelled' => '❌',
                                                'No Show' => '👻',
                                            ];
                                        @endphp
                                        @foreach ($icons as $status => $icon)
                                            <option value="{{ $status }}"
                                                {{ old('status', $booking->status) == $status ? 'selected' : '' }}>
                                                {{ $icon }} {{ $status }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Payment -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="ti ti-credit-card me-2"></i>Payment</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Payment Status</label>
                                    <select name="payment_status" class="form-select">
                                        @foreach (['Pending', 'Paid', 'Partial', 'Refunded'] as $ps)
                                            <option value="{{ $ps }}"
                                                {{ old('payment_status', $booking->payment_status) == $ps ? 'selected' : '' }}>
                                                {{ $ps }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Payment Method</label>
                                    <select name="payment_method" class="form-select">
                                        @foreach (['Cash', 'Card', 'Bank Transfer', 'JazzCash', 'EasyPaisa'] as $method)
                                            <option value="{{ $method }}"
                                                {{ old('payment_method', $booking->payment_method) == $method ? 'selected' : '' }}>
                                                {{ $method }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- <div class="mb-3">
                                    <label class="form-label">Advance Paid (₨)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">₨</span>
                                        <input type="number" name="advance_paid" class="form-control"
                                            value="{{ old('advance_paid', $booking->advance_paid) }}" min="0">
                                    </div>
                                </div> --}}

                                <!-- Remaining balance -->
                                <div class="alert alert-info py-2 mb-0">
                                    <small>
                                        <i class="ti ti-wallet me-1"></i>
                                        Remaining:
                                        <strong>₨{{ number_format($booking->getRemainingBalance()) }}</strong>
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Info -->
                        <div class="card mb-4 border-info">
                            <div class="card-header bg-light-info">
                                <h5 class="mb-0 text-info"><i class="ti ti-info-circle me-2"></i>Quick Info</h5>
                            </div>
                            <div class="card-body f-13">
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2">
                                        <i class="ti ti-home text-primary me-1"></i>
                                        <strong>Room & Customer</strong>
                                        <p class="text-muted mb-0 ms-3">Select an available room and customer. Guest
                                            details will be auto-filled from the selected customer.</p>
                                    </li>
                                    <hr class="my-2">
                                    <li class="mb-2">
                                        <i class="ti ti-calendar text-warning me-1"></i>
                                        <strong>Check In / Check Out</strong>
                                        <p class="text-muted mb-0 ms-3">Total amount is calculated automatically based on
                                            selected dates and room price per night.</p>
                                    </li>
                                    <hr class="my-2">
                                    <li class="mb-2">
                                        <i class="ti ti-settings text-success me-1"></i>
                                        <strong>Booking Status</strong>
                                        <p class="text-muted mb-0 ms-3">Set as Confirmed for future bookings or Checked In
                                            if the guest has already arrived.</p>
                                    </li>
                                    <hr class="my-2">
                                    <li class="mb-2">
                                        <i class="ti ti-credit-card text-secondary me-1"></i>
                                        <strong>Payment</strong>
                                        <p class="text-muted mb-0 ms-3">Enter the advance amount received. Full payment can
                                            be recorded at checkout via billing.</p>
                                    </li>
                                    <hr class="my-2">
                                    <li>
                                        <i class="ti ti-notes text-danger me-1"></i>
                                        <strong>Notes</strong>
                                        <p class="text-muted mb-0 ms-3">Special requests are visible to staff. Admin notes
                                            are for internal use only.</p>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="card">
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-device-floppy me-2"></i>Update Booking
                                    </button>
                                    <a href="{{ route('admin.bookings.show', $booking) }}"
                                        class="btn btn-outline-secondary">
                                        <i class="ti ti-eye me-2"></i>View Booking
                                    </a>
                                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary">
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tom-select/2.3.1/js/tom-select.complete.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // ── Room Select with Search ──────────────────────
            new TomSelect('#roomSelect', {
                placeholder: 'Search room...',
                allowEmptyOption: true,
                onChange: function(value) {
                    updateRoomInfo(value);
                }
            });

            // ── Customer Select with Search ──────────────────
            new TomSelect('#customerSelect', {
                placeholder: 'Search customer...',
                allowEmptyOption: true,
                onChange: function(value) {
                    fillCustomerInfo(value);
                }
            });

            // Init on load
            updateRoomInfo(document.getElementById('roomSelect').value);
            calcTotal();
        });

        // ── Room Info Box ────────────────────────────────────
        function updateRoomInfo(value) {
            const sel = document.getElementById('roomSelect');
            const opt = sel.options[sel.selectedIndex];

            if (!value || !opt) {
                document.getElementById('roomInfoBox').style.display = 'none';
                return;
            }

            document.getElementById('rType').textContent = opt.dataset.type || '';
            document.getElementById('rCapacity').textContent = (opt.dataset.capacity || '') + ' persons';
            document.getElementById('rPrice').textContent = '₨' + Number(opt.dataset.price || 0).toLocaleString();
            document.getElementById('roomInfoBox').style.display = 'block';
            calcTotal();
        }

        // ── Customer Autofill ────────────────────────────────
        function fillCustomerInfo(value) {
            const sel = document.getElementById('customerSelect');
            const opt = sel.options[sel.selectedIndex];

            if (!value || !opt) return;

            document.getElementById('guestName').value = opt.dataset.name || '';
            document.getElementById('guestPhone').value = opt.dataset.phone || '';
            document.getElementById('guestCnic').value = opt.dataset.cnic || '';
            document.getElementById('guestEmail').value = opt.dataset.email || '';
        }

        // ── Live Total Calculator ────────────────────────────
        function calcTotal() {
            const cin = document.getElementById('checkIn').value;
            const cout = document.getElementById('checkOut').value;
            const sel = document.getElementById('roomSelect');
            const opt = sel.options[sel.selectedIndex];

            if (!cin || !cout || !opt || !opt.value) return;

            const nights = Math.ceil((new Date(cout) - new Date(cin)) / (1000 * 3600 * 24));
            if (nights <= 0) return;

            const price = Number(opt.dataset.price || 0);
            const total = nights * price;

            document.getElementById('nightsCount').textContent = nights;
            document.getElementById('pricePerNight').textContent = '₨' + price.toLocaleString();
            document.getElementById('totalAmount').textContent = '₨' + total.toLocaleString();
        }
    </script>
@endpush
