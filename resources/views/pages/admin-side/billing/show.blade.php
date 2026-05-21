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
                                    <a href="{{ route('billing.print', $bill) }}" target="_blank"
                                        class="btn btn-success">
                                        <i class="ti ti-printer me-1"></i> Print
                                    </a>
                                    <a href="{{ route('billing.edit', $bill->id) }}" class="btn btn-primary">
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
                <div class="col-lg-8 offset-lg-2">

                    <!-- Invoice Card -->
                    <div class="card">
                        <div class="card-body p-4 p-md-5">

                            <!-- Header -->
                            <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-3">
                                <div>
                                    <h3 class="mb-1" style="font-family:Georgia,serif;color:#c9a84c;">🏨 BM Resort</h3>
                                    <p class="text-muted mb-0 f-13">Hotel Management System</p>
                                    <p class="text-muted f-12">Lahore, Pakistan | 0300-1234567</p>
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

                            <!-- Payment Info -->
                            <div class="row mb-4">
                                <div class="col-sm-6">
                                    <p class="mb-1"><strong>Payment Method:</strong> {{ $bill->payment_method }}</p>
                                    <p class="mb-0"><strong>Status:</strong>
                                        <span class="badge {{ $bill->getStatusBadgeClass() }}">{{ $bill->status }}</span>
                                    </p>
                                </div>
                                @if ($bill->notes)
                                    <div class="col-sm-6">
                                        <p class="text-muted f-13 mb-0"><strong>Notes:</strong> {{ $bill->notes }}</p>
                                    </div>
                                @endif
                            </div>

                            <hr>

                            <!-- Footer -->
                            <div class="text-center text-muted f-13">
                                <p class="mb-1">Shukriya! Thank you for staying with us. 🙏</p>
                                <p class="mb-0">BM Resort — Lahore, Pakistan</p>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection
