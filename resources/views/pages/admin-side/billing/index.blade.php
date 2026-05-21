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
                                <li class="breadcrumb-item">Billing</li>
                                <li class="breadcrumb-item" aria-current="page">Invoices</li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">Invoices / Billing</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('components.alerts')

            <!-- Stats -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body py-3">
                            <h4 class="mb-1 text-primary">{{ $bills->count() }} Pkr</h4>
                            <p class="mb-0 text-muted f-12">Total Invoices</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body py-3">
                            <h4 class="mb-1 text-success">
                                {{ number_format($bills->where('status', 'Paid')->sum('total_amount')) }} Pkr</h4>
                            <p class="mb-0 text-muted f-12">Total Collected</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body py-3">
                            <h4 class="mb-1 text-danger">
                                {{ number_format($bills->whereIn('status', ['Unpaid', 'Partial'])->sum('balance_due')) }} Pkr
                            </h4>
                            <p class="mb-0 text-muted f-12">Total Pending</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body py-3">
                            <h4 class="mb-1 text-warning">{{ $bills->where('status', 'Partial')->count() }} Pkr</h4>
                            <p class="mb-0 text-muted f-12">Partial Paid</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-sm-flex align-items-center justify-content-between">
                                <h5 class="mb-3 mb-sm-0">All Invoices</h5>
                                <a href="{{ route('billing.create') }}" class="btn btn-primary d-flex">
                                    <i class="ti ti-plus me-1"></i> New Invoice
                                </a>
                            </div>
                        </div>
                        <div class="card-body table-card">
                            <div class="table-responsive">
                                <table class="table table-hover" id="pc-dt-simple">
                                    <thead>
                                        <tr>
                                            <th>Invoice #</th>
                                            <th>Guest</th>
                                            <th>Room</th>
                                            <th>Check In</th>
                                            <th>Check Out</th>
                                            {{-- <th>Nights</th> --}}
                                            <th>Method</th>
                                            <th>Total</th>
                                            {{-- <th>Paid</th> --}}
                                            {{-- <th>Balance</th> --}}

                                            <th>Status</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($bills as $bill)
                                            <tr>
                                                <td><strong class="text-primary">{{ $bill->invoice_number }}</strong></td>

                                                <td>
                                                    <h6 class="mb-0">{{ $bill->guest_name }}</h6>
                                                    <small class="text-muted">{{ $bill->guest_phone }}</small>
                                                </td>

                                                <td>
                                                    @if ($bill->room_number)
                                                        <span class="badge bg-light-primary">Room
                                                            {{ $bill->room_number }}</span>
                                                        <small class="text-muted d-block">{{ $bill->room_type }}</small>
                                                    @else
                                                        <span class="text-muted">—</span>
                                                    @endif
                                                </td>

                                                <td>{{ $bill->check_in ? $bill->check_in->format('d M Y') : '—' }}</td>
                                                <td>{{ $bill->check_out ? $bill->check_out->format('d M Y') : '—' }}</td>
                                                {{-- <td><span class="badge bg-light-secondary">{{ $bill->nights }}n</span></td> --}}
                                                <td><small>{{ $bill->payment_method }}</small></td>
                                                <td><strong
                                                        class="text-dark">₨{{ number_format($bill->total_amount) }}</strong>
                                                </td>
                                                {{-- <td><span
                                                        class="text-success">₨{{ number_format($bill->amount_paid) }}</span>
                                                </td> --}}
                                                {{-- <td>
                                                    @if ($bill->balance_due > 0)
                                                        <span
                                                            class="text-danger fw-500">₨{{ number_format($bill->balance_due) }}</span>
                                                    @else
                                                        <span class="text-muted">—</span>
                                                    @endif
                                                </td> --}}



                                                <td>
                                                    <span class="badge {{ $bill->getStatusBadgeClass() }}">
                                                        {{ $bill->status }}
                                                    </span>
                                                </td>

                                                <td class="text-end">
                                                    <!-- Print -->
                                                    <a href="{{ route('billing.print', $bill) }}" target="_blank"
                                                        class="avtar avtar-xs btn-link-secondary" title="Print">
                                                        <i class="ti ti-printer f-18"></i>
                                                    </a>
                                                    <!-- View -->
                                                    <a href="{{ route('billing.show', $bill->id) }}"
                                                        class="avtar avtar-xs btn-link-secondary" title="View">
                                                        <i class="ti ti-eye f-18"></i>
                                                    </a>
                                                    <!-- Edit -->
                                                    <a href="{{ route('billing.edit', $bill->id) }}"
                                                        class="avtar avtar-xs btn-link-secondary" title="Edit">
                                                        <i class="ti ti-edit f-18"></i>
                                                    </a>
                                                    <!-- Delete -->
                                                    <a href="#" class="avtar avtar-xs btn-link-secondary bs-pass-para"
                                                        data-id="{{ $bill->id }}" title="Delete">
                                                        <i class="ti ti-trash f-18"></i>
                                                    </a>
                                                    <form id="delete-form-{{ $bill->id }}"
                                                        action="{{ route('billing.destroy', $bill->id) }}" method="POST"
                                                        style="display:none;">
                                                        @csrf @method('DELETE')
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="12" class="text-center py-5 text-muted">
                                                    <i class="ti ti-file-invoice f-40 d-block mb-2"></i>
                                                    No invoices found — please create your first invoice!
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
        document.addEventListener('DOMContentLoaded', function() {
            window.dt = new simpleDatatables.DataTable('#pc-dt-simple', {
                sortable: true,
                searchable: true,
                fixedHeight: true
            });
        });
    </script>
@endpush
