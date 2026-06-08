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
                                <li class="breadcrumb-item"><a href="{{ route('billing.index') }}">Billing</a></li>
                                <li class="breadcrumb-item" aria-current="page">{{ $bill->invoice_number }}</li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <div class="page-header-title">
                                    <h2 class="mb-0">{{ $bill->invoice_number }}</h2>
                                </div>
                                <div class="d-flex gap-2 flex-wrap">
                                    <a href="{{ route('billing.print', $bill) }}" target="_blank" class="btn btn-success">
                                        <i class="ti ti-printer me-1"></i> Print
                                    </a>
                                    <a href="{{ route('billing.edit', $bill) }}" class="btn btn-primary">
                                        <i class="ti ti-edit me-1"></i> Edit
                                    </a>
                                    <a href="{{ route('billing.index') }}" class="btn btn-outline-secondary">
                                        <i class="ti ti-arrow-left me-1"></i> Back
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">

                <!-- LEFT: Invoice Card -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body p-4 p-md-5">

                            <!-- Header -->
                            <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-3">
                                <div>
                                    <h3 class="mb-1" style="font-family:Georgia,serif;color:#c9a84c;">🏨 White Castle
                                        Resort</h3>
                                    <p class="text-muted mb-0 f-13">Hotel Management System</p>
                                    <p class="text-muted f-12">Shogran, Pakistan | 0329 6777222</p>
                                </div>
                                <div class="text-end">
                                    <h4 class="mb-1 text-primary">INVOICE</h4>
                                    <p class="mb-0 fw-500">{{ $bill->invoice_number }}</p>
                                    <p class="text-muted f-12 mb-0">Date: {{ $bill->issue_date->format('d M Y') }}</p>
                                    <span class="badge {{ $bill->getStatusBadgeClass() }} mt-1">{{ $bill->status }}</span>
                                </div>
                            </div>

                            <hr>

                            <!-- Guest & Room Info -->
                            <div class="row mb-4">
                                <div class="col-sm-6">
                                    <h6 class="text-muted mb-2">Bill To:</h6>
                                    <p class="mb-1 fw-500">{{ $bill->guest_name }}</p>
                                    @if ($bill->father_name)
                                        <p class="text-muted f-13 mb-1">S/O {{ $bill->father_name }}</p>
                                    @endif
                                    @if ($bill->guest_phone)
                                        <p class="text-muted f-13 mb-0">📞 {{ $bill->guest_phone }}</p>
                                    @endif
                                </div>
                                <div class="col-sm-6 text-sm-end">
                                    <h6 class="text-muted mb-2">Room Details:</h6>
                                    @if ($bill->room_number)
                                        <p class="mb-1 fw-500">Room {{ $bill->room_number }}
                                            @if ($bill->room_type)
                                                — {{ $bill->room_type }}
                                            @endif
                                        </p>
                                    @endif
                                    @if ($bill->check_in && $bill->check_out)
                                        <p class="text-muted f-13 mb-0">
                                            {{ $bill->check_in->format('d M Y') }} →
                                            {{ $bill->check_out->format('d M Y') }}
                                            ({{ $bill->nights }} nights)
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <!-- Charges Table -->
                            <table class="table table-bordered mb-4">
                                <thead class="table-light">
                                    <tr>
                                        <th>Description</th>
                                        <th class="text-end">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            Room Charges
                                            @if ($bill->nights > 1)
                                                <small class="text-muted">
                                                    (₨{{ number_format($bill->room_charges / $bill->nights) }} ×
                                                    {{ $bill->nights }} nights)
                                                </small>
                                            @endif
                                        </td>
                                        <td class="text-end">₨{{ number_format($bill->room_charges) }}</td>
                                    </tr>
                                    @if ($bill->extra_charges > 0)
                                        <tr>
                                            <td>Extra Services (Food, Laundry, etc.)</td>
                                            <td class="text-end">₨{{ number_format($bill->extra_charges) }}</td>
                                        </tr>
                                    @endif
                                    @if ($bill->parking_charges > 0)
                                        <tr>
                                            <td>
                                                Parking Charges
                                                @if ($bill->vehicle_number)
                                                    <small class="text-muted">({{ $bill->vehicle_number }})</small>
                                                @endif
                                            </td>
                                            <td class="text-end">₨{{ number_format($bill->parking_charges) }}</td>
                                        </tr>
                                    @endif
                                    @if ($bill->discount > 0)
                                        <tr>
                                            <td class="text-success">Discount</td>
                                            <td class="text-end text-success">-₨{{ number_format($bill->discount) }}</td>
                                        </tr>
                                    @endif
                                    @if ($bill->tax_amount > 0)
                                        <tr>
                                            <td>Tax ({{ $bill->tax_percent }}%)</td>
                                            <td class="text-end">₨{{ number_format($bill->tax_amount) }}</td>
                                        </tr>
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr class="table-light">
                                        <th>Total Amount</th>
                                        <th class="text-end text-primary fs-5">₨{{ number_format($bill->total_amount) }}
                                        </th>
                                    </tr>
                                    <tr>
                                        <td class="text-success">Amount Paid</td>
                                        <td class="text-end text-success">₨{{ number_format($bill->amount_paid) }}</td>
                                    </tr>
                                    @if ($bill->balance_due > 0)
                                        <tr>
                                            <td class="text-danger fw-500">Balance Due</td>
                                            <td class="text-end text-danger fw-bold">
                                                ₨{{ number_format($bill->balance_due) }}</td>
                                        </tr>
                                    @endif
                                </tfoot>
                            </table>

                            <!-- Notes -->
                            @if ($bill->notes)
                                <div class="mb-3 p-3 rounded" style="background:#f8f9fa;border:1px solid #e9ecef;">
                                    <h6 class="text-muted mb-1"><i class="ti ti-notes me-1"></i>Notes</h6>
                                    <p class="mb-0 f-13">{{ $bill->notes }}</p>
                                </div>
                            @endif

                            <hr>

                            <!-- Footer -->
                            <div class="text-center text-muted f-13">
                                <p class="mb-1">Thank you for staying with us. 🙏</p>
                                <p class="mb-0">White Castle Resort — Shogran, Pakistan</p>
                            </div>

                        </div>
                    </div>

                    <!-- Vehicle Details -->
                    @if ($bill->has_vehicle)
                        <div class="card mb-4 border-secondary">
                            <div class="card-header bg-light-secondary">
                                <h5 class="mb-0"><i class="ti ti-car me-2"></i>Vehicle Details</h5>
                            </div>
                            <div class="card-body f-13">
                                <table class="table table-sm mb-0">
                                    <tbody>
                                        @if ($bill->vehicle_number)
                                            <tr>
                                                <td class="text-muted">Vehicle No.</td>
                                                <td class="text-end fw-500">{{ $bill->vehicle_number }}</td>
                                            </tr>
                                        @endif
                                        @if ($bill->vehicle_type)
                                            <tr>
                                                <td class="text-muted">Type</td>
                                                <td class="text-end fw-500">{{ $bill->vehicle_type }}</td>
                                            </tr>
                                        @endif
                                        @if ($bill->vehicle_model)
                                            <tr>
                                                <td class="text-muted">Model</td>
                                                <td class="text-end fw-500">{{ $bill->vehicle_model }}</td>
                                            </tr>
                                        @endif
                                        @if ($bill->vehicle_color)
                                            <tr>
                                                <td class="text-muted">Color</td>
                                                <td class="text-end fw-500">{{ $bill->vehicle_color }}</td>
                                            </tr>
                                        @endif
                                        @if ($bill->driver_name)
                                            <tr>
                                                <td class="text-muted">Driver</td>
                                                <td class="text-end fw-500">{{ $bill->driver_name }}</td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td class="text-muted">Parking Charges</td>
                                            <td class="text-end fw-500">₨{{ number_format($bill->parking_charges) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                </div>

                <!-- RIGHT: Details Sidebar -->
                <div class="col-lg-4">

                    <!-- Payment Summary -->
                    <div class="card mb-4 border-primary">
                        <div class="card-header bg-light-primary">
                            <h5 class="mb-0 text-primary"><i class="ti ti-cash me-2"></i>Payment Summary</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted f-13">Total Amount</span>
                                <span class="fw-bold text-primary">₨{{ number_format($bill->total_amount) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted f-13">Amount Paid</span>
                                <span class="text-success fw-500">₨{{ number_format($bill->amount_paid) }}</span>
                            </div>
                            <hr class="my-2">
                            <div class="d-flex justify-content-between">
                                <span class="fw-500">Balance Due</span>
                                <span class="fw-bold {{ $bill->balance_due > 0 ? 'text-danger' : 'text-success' }}">
                                    ₨{{ number_format($bill->balance_due) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Bill Details -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="ti ti-info-circle me-2"></i>Bill Details</h5>
                        </div>
                        <div class="card-body f-13">
                            <table class="table table-sm mb-0">
                                <tbody>
                                    <tr>
                                        <td class="text-muted">Invoice #</td>
                                        <td class="text-end fw-500">{{ $bill->invoice_number }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Status</td>
                                        <td class="text-end">
                                            <span
                                                class="badge {{ $bill->getStatusBadgeClass() }}">{{ $bill->status }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Payment Method</td>
                                        <td class="text-end fw-500">{{ $bill->payment_method }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Issue Date</td>
                                        <td class="text-end fw-500">{{ $bill->issue_date->format('d M Y') }}</td>
                                    </tr>
                                    @if ($bill->check_in)
                                        <tr>
                                            <td class="text-muted">Check In</td>
                                            <td class="text-end fw-500">{{ $bill->check_in->format('d M Y') }}</td>
                                        </tr>
                                    @endif
                                    @if ($bill->check_out)
                                        <tr>
                                            <td class="text-muted">Check Out</td>
                                            <td class="text-end fw-500">{{ $bill->check_out->format('d M Y') }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td class="text-muted">Nights</td>
                                        <td class="text-end fw-500">{{ $bill->nights }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Created</td>
                                        <td class="text-end fw-500">{{ $bill->created_at->format('d M Y, h:i A') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>



                    <!-- Linked Booking & Customer -->
                    @if ($bill->booking || $bill->customer)
                        <div class="card mb-4 border-info">
                            <div class="card-header bg-light-info">
                                <h5 class="mb-0 text-info"><i class="ti ti-link me-2"></i>Linked Records</h5>
                            </div>
                            <div class="card-body f-13">
                                {{-- @if ($bill->booking)
                                    <div class="mb-3">
                                        <span class="text-muted">Booking</span><br>
                                        <a href="{{ route('bookings.show', $bill->booking) }}" class="fw-500">
                                            <i class="ti ti-calendar me-1"></i>{{ $bill->booking->booking_number }}
                                        </a>
                                    </div>
                                @endif --}}
                                @if ($bill->customer)
                                    <div>
                                        <span class="text-muted">Customer</span><br>
                                        <span class="fw-500"><i
                                                class="ti ti-user me-1"></i>{{ $bill->customer->name }}</span>
                                        @if ($bill->customer->phone)
                                            <div class="text-muted">📞 {{ $bill->customer->phone }}</div>
                                        @endif
                                        @if ($bill->customer->cnic)
                                            <div class="text-muted">CNIC: {{ $bill->customer->cnic }}</div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
@endsection
