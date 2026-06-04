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
                                <li class="breadcrumb-item" aria-current="page">Food Orders</li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">🍽️ Food Orders</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('components.alerts')

            {{-- Stats --}}
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body py-3">
                            <h4 class="mb-1 text-primary">{{ $orders->count() }}</h4>
                            <p class="mb-0 text-muted f-12">Total Orders</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body py-3">
                            <h4 class="mb-1 text-warning">{{ $orders->where('status', 'Pending')->count() }}</h4>
                            <p class="mb-0 text-muted f-12">Pending</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body py-3">
                            <h4 class="mb-1 text-success">
                                ₨{{ number_format($orders->where('status', 'Paid')->sum('total_amount')) }}
                            </h4>
                            <p class="mb-0 text-muted f-12">Total Collected</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body py-3">
                            <h4 class="mb-1 text-danger">
                                ₨{{ number_format($orders->whereIn('status', ['Pending', 'Preparing', 'Served'])->sum('balance_due')) }}
                            </h4>
                            <p class="mb-0 text-muted f-12">Pending Amount</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-sm-flex align-items-center justify-content-between">
                                <h5 class="mb-3 mb-sm-0">All Food Orders</h5>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('food.items.index') }}" class="btn btn-outline-secondary d-flex">
                                        <i class="ti ti-tools-kitchen-2 me-1"></i> Manage Menu
                                    </a>
                                    <a href="{{ route('food.orders.create') }}" class="btn btn-primary d-flex">
                                        <i class="ti ti-plus me-1"></i> New Order
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body table-card">
                            <div class="table-responsive">
                                <table class="table table-hover" id="pc-dt-simple">
                                    <thead>
                                        <tr>
                                            <th>Order #</th>
                                            <th>Guest</th>
                                            <th>Room</th>
                                            <th>Type</th>
                                            <th>Items</th>
                                            <th>Total</th>
                                            <th>Balance</th>
                                            <th>Status</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($orders as $order)
                                            <tr>
                                                <td><strong class="text-primary">{{ $order->order_number }}</strong>
                                                    <small class="text-muted d-block">{{ $order->created_at->format('d M, h:i A') }}</small>
                                                </td>

                                                <td>
                                                    <h6 class="mb-0">{{ $order->guest_name }}</h6>
                                                    <small class="text-muted">{{ $order->guest_phone }}</small>
                                                </td>

                                                <td>
                                                    @if ($order->room_number)
                                                        <span class="badge bg-light-primary">Room {{ $order->room_number }}</span>
                                                    @else
                                                        <span class="text-muted">—</span>
                                                    @endif
                                                </td>

                                                <td>
                                                    <span class="badge {{ $order->order_type_badge_class }}">
                                                        {{ $order->order_type }}
                                                    </span>
                                                </td>

                                                <td>
                                                    <small class="text-muted">{{ $order->items->count() }} item(s)</small>
                                                </td>

                                                <td><strong class="text-dark">₨{{ number_format($order->total_amount) }}</strong></td>

                                                <td>
                                                    @if ($order->balance_due > 0)
                                                        <span class="text-danger fw-500">₨{{ number_format($order->balance_due) }}</span>
                                                    @else
                                                        <span class="text-muted">—</span>
                                                    @endif
                                                </td>

                                                <td>
                                                    <span class="badge {{ $order->status_badge_class }}">
                                                        {{ $order->status }}
                                                    </span>
                                                </td>

                                                <td class="text-end">
                                                    {{-- Quick Status --}}
                                                    <div class="dropdown d-inline">
                                                        <a href="#" class="avtar avtar-xs btn-link-secondary"
                                                            data-bs-toggle="dropdown" title="Change Status">
                                                            <i class="ti ti-refresh f-18"></i>
                                                        </a>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            @foreach (['Pending', 'Preparing', 'Served', 'Paid', 'Cancelled'] as $s)
                                                                <li>
                                                                    <form action="{{ route('food.orders.status', $order) }}" method="POST">
                                                                        @csrf
                                                                        <input type="hidden" name="status" value="{{ $s }}">
                                                                        <button class="dropdown-item {{ $order->status === $s ? 'active' : '' }}">
                                                                            {{ $s }}
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                    {{-- Print --}}
                                                    <a href="{{ route('food.orders.print', $order) }}" target="_blank"
                                                        class="avtar avtar-xs btn-link-secondary" title="Print">
                                                        <i class="ti ti-printer f-18"></i>
                                                    </a>
                                                    {{-- View --}}
                                                    <a href="{{ route('food.orders.show', $order) }}"
                                                        class="avtar avtar-xs btn-link-secondary" title="View">
                                                        <i class="ti ti-eye f-18"></i>
                                                    </a>
                                                    {{-- Edit --}}
                                                    <a href="{{ route('food.orders.edit', $order) }}"
                                                        class="avtar avtar-xs btn-link-secondary" title="Edit">
                                                        <i class="ti ti-edit f-18"></i>
                                                    </a>
                                                    {{-- Delete --}}
                                                    <a href="#" class="avtar avtar-xs btn-link-secondary bs-pass-para"
                                                        data-id="{{ $order->id }}" title="Delete">
                                                        <i class="ti ti-trash f-18"></i>
                                                    </a>
                                                    <form id="delete-form-{{ $order->id }}"
                                                        action="{{ route('food.orders.destroy', $order) }}" method="POST"
                                                        style="display:none;">
                                                        @csrf @method('DELETE')
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center py-5 text-muted">
                                                    <i class="ti ti-tools-kitchen-2 f-40 d-block mb-2"></i>
                                                    No food orders yet — place the first order!
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            window.dt = new simpleDatatables.DataTable('#pc-dt-simple', {
                sortable: true, searchable: true, fixedHeight: true
            });
        });
    </script>
@endpush