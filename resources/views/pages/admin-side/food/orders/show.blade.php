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
                                <li class="breadcrumb-item"><a href="{{ route('food.orders.index') }}">Food Orders</a></li>
                                <li class="breadcrumb-item">{{ $foodOrder->order_number }}</li>
                            </ul>
                        </div>
                        <div class="col-md-12 d-flex justify-content-between align-items-center">
                            <h2 class="mb-0">🍽️ {{ $foodOrder->order_number }}</h2>
                            <div class="d-flex gap-2">
                                <a href="{{ route('food.orders.print', $foodOrder) }}" target="_blank"
                                    class="btn btn-outline-secondary">
                                    <i class="ti ti-printer me-1"></i> Print
                                </a>
                                <a href="{{ route('food.orders.edit', $foodOrder) }}" class="btn btn-primary">
                                    <i class="ti ti-edit me-1"></i> Edit
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('components.alerts')

            <div class="row">
                <div class="col-lg-8">
                    {{-- Items --}}
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Order Items</h5>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Item</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end">Unit Price</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($foodOrder->items as $item)
                                        <tr>
                                            <td>{{ $item->item_name }}</td>
                                            <td class="text-center">{{ $item->quantity }}</td>
                                            <td class="text-end">₨{{ number_format($item->unit_price) }}</td>
                                            <td class="text-end fw-500">₨{{ number_format($item->subtotal) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            <div class="row justify-content-end">
                                <div class="col-md-4">
                                    <table class="table table-sm mb-0">
                                        <tr>
                                            <td class="text-muted">Subtotal</td>
                                            <td class="text-end">₨{{ number_format($foodOrder->subtotal) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Discount</td>
                                            <td class="text-end text-success">-₨{{ number_format($foodOrder->discount) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Tax ({{ $foodOrder->tax_percent }}%)</td>
                                            <td class="text-end">₨{{ number_format($foodOrder->tax_amount) }}</td>
                                        </tr>
                                        <tr class="fw-bold">
                                            <td>Total</td>
                                            <td class="text-end text-primary">
                                                ₨{{ number_format($foodOrder->total_amount) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Paid</td>
                                            <td class="text-end text-success">₨{{ number_format($foodOrder->amount_paid) }}
                                            </td>
                                        </tr>
                                        <tr class="fw-bold text-danger">
                                            <td>Balance</td>
                                            <td class="text-end">₨{{ number_format($foodOrder->balance_due) }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    {{-- Order Info --}}
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Order Info</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item px-0 d-flex justify-content-between">
                                    <span class="text-muted">Status</span>
                                    <span
                                        class="badge {{ $foodOrder->status_badge_class }}">{{ $foodOrder->status }}</span>
                                </li>
                                <li class="list-group-item px-0 d-flex justify-content-between">
                                    <span class="text-muted">Type</span>
                                    <span
                                        class="badge {{ $foodOrder->order_type_badge_class }}">{{ $foodOrder->order_type }}</span>
                                </li>
                                <li class="list-group-item px-0 d-flex justify-content-between">
                                    <span class="text-muted">Guest</span>
                                    <span>{{ $foodOrder->guest_name }}</span>
                                </li>
                                @if ($foodOrder->father_name)
                                    <li class="list-group-item px-0 d-flex justify-content-between">
                                        <span class="text-muted">Father Name</span>
                                        <span>{{ $foodOrder->father_name }}</span>
                                    </li>
                                @endif
                                @if ($foodOrder->guest_phone)
                                    <li class="list-group-item px-0 d-flex justify-content-between">
                                        <span class="text-muted">Phone</span>
                                        <span>{{ $foodOrder->guest_phone }}</span>
                                    </li>
                                @endif
                                @if ($foodOrder->room_number)
                                    <li class="list-group-item px-0 d-flex justify-content-between">
                                        <span class="text-muted">Room</span>
                                        <span class="badge bg-light-primary">Room {{ $foodOrder->room_number }}</span>
                                    </li>
                                @endif
                                <li class="list-group-item px-0 d-flex justify-content-between">
                                    <span class="text-muted">Payment</span>
                                    <span>{{ $foodOrder->payment_method }}</span>
                                </li>
                                <li class="list-group-item px-0 d-flex justify-content-between">
                                    <span class="text-muted">Date</span>
                                    <span>{{ $foodOrder->created_at->format('d M Y, h:i A') }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    {{-- Quick Status Update --}}
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Update Status</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                @foreach (['Pending', 'Preparing', 'Served', 'Paid', 'Cancelled'] as $s)
                                    <form action="{{ route('food.orders.status', $foodOrder) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="{{ $s }}">
                                        <button
                                            class="btn w-100 {{ $foodOrder->status === $s ? 'btn-primary' : 'btn-outline-secondary' }}">
                                            {{ $s }}
                                        </button>
                                    </form>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    @if ($foodOrder->notes)
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Notes</h5>
                            </div>
                            <div class="card-body">
                                <p class="mb-0">{{ $foodOrder->notes }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection
