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
                                <li class="breadcrumb-item"><a href="{{ route('billing.index') }}">Billing</a></li>
                                <li class="breadcrumb-item" aria-current="page">New Invoice</li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">Create Invoice</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('components.alerts')

            <form action="{{ route('billing.store') }}" method="POST" id="billForm">
                @csrf
                <div class="row">

                    <!-- LEFT -->
                    <div class="col-lg-8">

                        <!-- Select Booking -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="ti ti-calendar me-2"></i>Auto-Fill from Booking (Optional)</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Booking Select</label>
                                        <select name="booking_id" id="bookingSelect" class="form-select">
                                            <option value="">-- Booking Select --</option>
                                            @foreach ($bookings as $booking)
                                                <option value="{{ $booking->id }}" data-guest="{{ $booking->guest_name }}"
                                                    data-father="{{ $booking->father_name }}"
                                                    data-phone="{{ $booking->guest_phone }}"
                                                    data-room="{{ $booking->room->room_number ?? '' }}"
                                                    data-type="{{ $booking->room->type ?? '' }}"
                                                    data-checkin="{{ $booking->check_in->format('Y-m-d') }}"
                                                    data-checkout="{{ $booking->check_out->format('Y-m-d') }}"
                                                    data-nights="{{ $booking->nights }}"
                                                    data-amount="{{ $booking->total_amount }}"
                                                    data-customer="{{ $booking->customer_id }}">
                                                    {{ $booking->booking_number }} —
                                                    {{ $booking->guest_name }} |
                                                    Room {{ $booking->room->room_number ?? '?' }} |
                                                    {{ $booking->check_in->format('d M') }} →
                                                    {{ $booking->check_out->format('d M Y') }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Search & select a booking to auto-fill all
                                            details.</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Customer (Optional)</label>
                                        <select name="customer_id" id="customerSelect" class="form-select">
                                            <option value="">-- Customer --</option>
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}"
                                                    {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                                    {{ $customer->name }} — {{ $customer->phone }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Guest & Room Info -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="ti ti-user me-2"></i>Guest & Room Info</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Guest Name <span class="text-danger">*</span></label>
                                        <input type="text" name="guest_name" id="guestName"
                                            class="form-control @error('guest_name') is-invalid @enderror"
                                            value="{{ old('guest_name') }}" placeholder="Ahmed Ali">
                                        @error('guest_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Father Name</label>
                                        <input type="text" name="father_name" id="fatherName" class="form-control"
                                            value="{{ old('father_name') }}" placeholder="Father name (optional)">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Phone</label>
                                        <input type="text" name="guest_phone" id="guestPhone" class="form-control"
                                            value="{{ old('guest_phone') }}" placeholder="0300-1234567">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Room Number</label>
                                        <input type="text" name="room_number" id="roomNumber" class="form-control"
                                            value="{{ old('room_number') }}" placeholder="101">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Room Type</label>
                                        <input type="text" name="room_type" id="roomType" class="form-control"
                                            value="{{ old('room_type') }}" placeholder="Deluxe">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Nights</label>
                                        <input type="number" name="nights" id="nights" class="form-control"
                                            value="{{ old('nights', 1) }}" min="1" onchange="calcTotal()">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Check In</label>
                                        <input type="date" name="check_in" id="checkIn" class="form-control"
                                            value="{{ old('check_in') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Check Out</label>
                                        <input type="date" name="check_out" id="checkOut" class="form-control"
                                            value="{{ old('check_out') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Vehicle Details -->
                        <div class="card mb-4">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h5 class="mb-0"><i class="ti ti-car me-2"></i>Vehicle Details</h5>
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" name="has_vehicle" value="1"
                                        id="hasVehicle" {{ old('has_vehicle') ? 'checked' : '' }}
                                        onchange="toggleVehicle()">
                                    <label class="form-check-label f-13" for="hasVehicle">Guest has a vehicle</label>
                                </div>
                            </div>
                            <div class="card-body" id="vehicleFields" style="display:none;">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Vehicle Number</label>
                                        <input type="text" name="vehicle_number" class="form-control text-uppercase"
                                            value="{{ old('vehicle_number') }}" placeholder="LEA-1234">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Vehicle Type</label>
                                        <select name="vehicle_type" class="form-select">
                                            <option value="">-- Select --</option>
                                            @foreach (['Car', 'SUV', 'Van', 'Bike', 'Jeep', 'Other'] as $vt)
                                                <option value="{{ $vt }}"
                                                    {{ old('vehicle_type') == $vt ? 'selected' : '' }}>{{ $vt }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Vehicle Model</label>
                                        <input type="text" name="vehicle_model" class="form-control"
                                            value="{{ old('vehicle_model') }}" placeholder="Corolla 2022">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Vehicle Color</label>
                                        <input type="text" name="vehicle_color" class="form-control"
                                            value="{{ old('vehicle_color') }}" placeholder="White">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Driver Name</label>
                                        <input type="text" name="driver_name" class="form-control"
                                            value="{{ old('driver_name') }}" placeholder="Optional">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Parking Charges (₨)</label>
                                        <div class="input-group">
                                            <span class="input-group-text">₨</span>
                                            <input type="number" name="parking_charges" id="parkingCharges"
                                                class="form-control" value="{{ old('parking_charges', 0) }}"
                                                min="0" oninput="calcTotal()">
                                        </div>
                                        <small class="text-muted">Total mein add ho jayega.</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Charges -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="ti ti-calculator me-2"></i>Charges & Payment</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Room Charges (₨) <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">₨</span>
                                            <input type="number" name="room_charges" id="roomCharges"
                                                class="form-control @error('room_charges') is-invalid @enderror"
                                                value="{{ old('room_charges', 0) }}" min="0"
                                                oninput="calcTotal()">
                                        </div>
                                        @error('room_charges')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Extra Charges (₨)
                                            <small class="text-muted">Food, Laundry, etc.</small>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">₨</span>
                                            <input type="number" name="extra_charges" id="extraCharges"
                                                class="form-control" value="{{ old('extra_charges', 0) }}"
                                                min="0" oninput="calcTotal()">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Discount (₨)</label>
                                        <div class="input-group">
                                            <span class="input-group-text">₨</span>
                                            <input type="number" name="discount" id="discount" class="form-control"
                                                value="{{ old('discount', 0) }}" min="0" oninput="calcTotal()">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Tax (%)</label>
                                        <div class="input-group">
                                            <input type="number" name="tax_percent" id="taxPercent"
                                                class="form-control" value="{{ old('tax_percent', 0) }}" min="0"
                                                max="100" oninput="calcTotal()">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Amount Paid (₨)</label>
                                        <div class="input-group">
                                            <span class="input-group-text">₨</span>
                                            <input type="number" name="amount_paid" id="amountPaid"
                                                class="form-control" value="{{ old('amount_paid', 0) }}" min="0"
                                                oninput="calcTotal()">
                                        </div>
                                    </div>
                                </div>

                                <!-- Live Total Box -->
                                <div class="mt-4 p-3 rounded"
                                    style="background:var(--bs-gray-100,#f8f9fa);border:1px solid #e0e0e0;">
                                    <div class="row text-center">
                                        <div class="col-3">
                                            <div class="text-muted f-12">Room + Extra + Parking</div>
                                            <div class="fw-500" id="calcSubtotal">₨0</div>
                                        </div>
                                        <div class="col-2">
                                            <div class="text-muted f-12">Discount</div>
                                            <div class="fw-500 text-success" id="calcDiscount">-₨0</div>
                                        </div>
                                        <div class="col-2">
                                            <div class="text-muted f-12">Tax</div>
                                            <div class="fw-500 text-warning" id="calcTax">₨0</div>
                                        </div>
                                        <div class="col-3">
                                            <div class="text-muted f-12">Total</div>
                                            <div class="fw-bold text-primary fs-5" id="calcTotal">₨0</div>
                                        </div>
                                        <div class="col-2">
                                            <div class="text-muted f-12">Balance</div>
                                            <div class="fw-bold text-danger" id="calcBalance">₨0</div>
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
                                <textarea name="notes" class="form-control" rows="3" placeholder="Enter notes ...">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                    </div>

                    <!-- RIGHT -->
                    <div class="col-lg-4">

                        <div class="card mb-4 border-info">
                            <div class="card-header bg-light-info">
                                <h5 class="mb-0 text-info"><i class="ti ti-info-circle me-2"></i>Quick Info</h5>
                            </div>
                            <div class="card-body f-13">
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2">
                                        <i class="ti ti-calendar text-primary me-1"></i>
                                        <strong>Auto-Fill from Booking</strong>
                                        <p class="text-muted mb-0 ms-3">Select an existing booking to automatically fill
                                            guest name, room, dates and charges.</p>
                                    </li>
                                    <hr class="my-2">
                                    <li class="mb-2">
                                        <i class="ti ti-car text-info me-1"></i>
                                        <strong>Vehicle Details</strong>
                                        <p class="text-muted mb-0 ms-3">Toggle on if the guest has a vehicle. Parking
                                            charges are added automatically to the total.</p>
                                    </li>
                                    <hr class="my-2">
                                    <li class="mb-2">
                                        <i class="ti ti-calculator text-warning me-1"></i>
                                        <strong>Live Calculator</strong>
                                        <p class="text-muted mb-0 ms-3">Total amount is calculated in real-time as you
                                            enter room charges, extra charges, discount and tax.</p>
                                    </li>
                                    <hr class="my-2">
                                    <li class="mb-2">
                                        <i class="ti ti-credit-card text-secondary me-1"></i>
                                        <strong>Payment Status</strong>
                                        <p class="text-muted mb-0 ms-3">Set status as Paid, Unpaid, or Partial based on the
                                            amount received from the guest.</p>
                                    </li>
                                    <hr class="my-2">
                                    <li>
                                        <i class="ti ti-file-invoice text-danger me-1"></i>
                                        <strong>Invoice Preview</strong>
                                        <p class="text-muted mb-0 ms-3">The right panel shows a live breakdown of all
                                            charges before generating the invoice.</p>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Payment Settings -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="ti ti-credit-card me-2"></i>Payment</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                                    <select name="payment_method" class="form-select">
                                        @foreach (['Cash', 'Card', 'Bank Transfer', 'JazzCash', 'EasyPaisa'] as $m)
                                            <option value="{{ $m }}"
                                                {{ old('payment_method', 'Cash') == $m ? 'selected' : '' }}>
                                                {{ $m }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Issue Date <span class="text-danger">*</span></label>
                                    <input type="date" name="issue_date" class="form-control"
                                        value="{{ old('issue_date', date('Y-m-d')) }}">
                                </div>
                            </div>
                        </div>

                        <!-- Invoice Preview Card -->
                        <div class="card mb-4 border-primary">
                            <div class="card-header bg-light-primary">
                                <h5 class="mb-0 text-primary"><i class="ti ti-file-invoice me-2"></i>Invoice Preview</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted f-13">Room Charges</span>
                                    <span id="prev-room">₨0</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted f-13">Extra Charges</span>
                                    <span id="prev-extra">₨0</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2" id="prev-parking-row"
                                    style="display:none;">
                                    <span class="text-muted f-13">Parking Charges</span>
                                    <span id="prev-parking">₨0</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted f-13">Discount</span>
                                    <span id="prev-dis" class="text-success">-₨0</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted f-13">Tax</span>
                                    <span id="prev-tax">₨0</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="fw-500">Total</span>
                                    <span class="fw-bold text-primary" id="prev-total">₨0</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted f-13">Amount Paid</span>
                                    <span id="prev-paid" class="text-success">₨0</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="fw-500">Balance Due</span>
                                    <span class="fw-bold text-danger" id="prev-bal">₨0</span>
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="card">
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-file-invoice me-2"></i>Generate Invoice
                                    </button>
                                    <a href="{{ route('billing.index') }}" class="btn btn-outline-secondary">
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

            // ── Booking Select with Search ──────────────────
            new TomSelect('#bookingSelect', {
                placeholder: 'Search booking...',
                allowEmptyOption: true,
                onChange: function(value) {
                    fillFromBooking();
                }
            });

            // ── Customer Select with Search ─────────────────
            new TomSelect('#customerSelect', {
                placeholder: 'Search customer...',
                allowEmptyOption: true
            });

            toggleVehicle(); 
            calcTotal();
        });

        // Vehicle section show / hide
        function toggleVehicle() {
            const on = document.getElementById('hasVehicle').checked;
            document.getElementById('vehicleFields').style.display = on ? 'block' : 'none';
            if (!on) {
                document.getElementById('parkingCharges').value = 0;
            }
            calcTotal();
        }

        // Auto fill from booking
        function fillFromBooking() {
            const sel = document.getElementById('bookingSelect');
            const opt = sel.options[sel.selectedIndex];
            if (!opt || !opt.value) return;

            document.getElementById('guestName').value = opt.dataset.guest || '';
            document.getElementById('fatherName').value = opt.dataset.father || '';
            document.getElementById('guestPhone').value = opt.dataset.phone || '';
            document.getElementById('roomNumber').value = opt.dataset.room || '';
            document.getElementById('roomType').value = opt.dataset.type || '';
            document.getElementById('checkIn').value = opt.dataset.checkin || '';
            document.getElementById('checkOut').value = opt.dataset.checkout || '';
            document.getElementById('nights').value = opt.dataset.nights || 1;
            document.getElementById('roomCharges').value = opt.dataset.amount || 0;

            // Customer select sync (TomSelect ke through)
            const custTS = document.getElementById('customerSelect').tomselect;
            if (custTS && opt.dataset.customer) {
                custTS.setValue(opt.dataset.customer);
            }

            calcTotal();
        }

        // Live calculator
        function calcTotal() {
            const room = parseFloat(document.getElementById('roomCharges').value) || 0;
            const extra = parseFloat(document.getElementById('extraCharges').value) || 0;
            const parking = parseFloat(document.getElementById('parkingCharges').value) || 0;
            const dis = parseFloat(document.getElementById('discount').value) || 0;
            const taxPct = parseFloat(document.getElementById('taxPercent').value) || 0;
            const paid = parseFloat(document.getElementById('amountPaid').value) || 0;

            const subtotal = room + extra + parking; 
            const afterDis = subtotal - dis;
            const tax = Math.round(afterDis * (taxPct / 100) * 100) / 100;
            const total = Math.round((afterDis + tax) * 100) / 100;
            const balance = Math.round((total - paid) * 100) / 100;

            const fmt = n => '₨' + n.toLocaleString();

            document.getElementById('calcSubtotal').textContent = fmt(subtotal);
            document.getElementById('calcDiscount').textContent = '-' + fmt(dis);
            document.getElementById('calcTax').textContent = fmt(tax);
            document.getElementById('calcTotal').textContent = fmt(total);
            document.getElementById('calcBalance').textContent = fmt(balance > 0 ? balance : 0);

            document.getElementById('prev-room').textContent = fmt(room);
            document.getElementById('prev-extra').textContent = fmt(extra);
            document.getElementById('prev-dis').textContent = '-' + fmt(dis);
            document.getElementById('prev-tax').textContent = fmt(tax);
            document.getElementById('prev-total').textContent = fmt(total);
            document.getElementById('prev-paid').textContent = fmt(paid);
            document.getElementById('prev-bal').textContent = fmt(balance > 0 ? balance : 0);

            const parkingRow = document.getElementById('prev-parking-row');
            if (parking > 0) {
                parkingRow.style.display = 'flex';
                document.getElementById('prev-parking').textContent = fmt(parking);
            } else {
                parkingRow.style.display = 'none';
            }
        }
    </script>
@endpush
