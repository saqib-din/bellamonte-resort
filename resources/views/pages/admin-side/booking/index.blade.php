@extends('layouts.admin')

@section('content')
    <div class="pc-container">
        <div class="pc-content">

            <!-- Breadcrumb -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0)">Bookings</a></li>
                                <li class="breadcrumb-item" aria-current="page">List</li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">Bookings Management</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('components.alerts')

            <!-- Stats -->
            <div class="row mb-4">
                <div class="col-md-2 col-sm-4">
                    <div class="card text-center">
                        <div class="card-body py-3">
                            <h4 class="mb-1 text-primary">{{ $bookings->count() }}</h4>
                            <p class="mb-0 text-muted f-12">Total</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4">
                    <div class="card text-center">
                        <div class="card-body py-3">
                            <h4 class="mb-1 text-success">{{ $bookings->where('status', 'Checked In')->count() }}</h4>
                            <p class="mb-0 text-muted f-12">Checked In</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4">
                    <div class="card text-center">
                        <div class="card-body py-3">
                            <h4 class="mb-1 text-primary">{{ $bookings->where('status', 'Confirmed')->count() }}</h4>
                            <p class="mb-0 text-muted f-12">Confirmed</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4">
                    <div class="card text-center">
                        <div class="card-body py-3">
                            <h4 class="mb-1 text-secondary">{{ $bookings->where('status', 'Checked Out')->count() }}</h4>
                            <p class="mb-0 text-muted f-12">Checked Out</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4">
                    <div class="card text-center">
                        <div class="card-body py-3">
                            <h4 class="mb-1 text-danger">{{ $bookings->where('status', 'Cancelled')->count() }}</h4>
                            <p class="mb-0 text-muted f-12">Cancelled</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4">
                    <div class="card text-center">
                        <div class="card-body py-3">
                            <h4 class="mb-1 text-success">
                                {{ number_format($bookings->where('payment_status', 'Paid')->sum('total_amount')) }} Pkr</h4>
                            <p class="mb-0 text-muted f-12">Revenue</p>
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
                                <h5 class="mb-3 mb-sm-0">All Bookings</h5>
                                <a href="{{ route('admin.bookings.create') }}" class="btn btn-primary d-flex">
                                    <i class="ti ti-plus me-1"></i> New Booking
                                </a>
                            </div>
                        </div>
                        <div class="card-body table-card">
                            <div class="table-responsive">
                                <table class="table table-hover" id="pc-dt-simple">
                                    <thead>
                                        <tr>
                                            <th>Booking #</th>
                                            <th>Guest</th>
                                            {{-- <th>Room</th> --}}
                                            <th>Check In</th>
                                            <th>Check Out</th>
                                            {{-- <th>Nights</th> --}}
                                            <th>Advance</th>
                                            <th>Total</th>
                                            <th>Payment</th>
                                            <th>Status</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($bookings as $booking)
                                            <tr>
                                                <td><strong>{{ $booking->booking_number }}</strong></td>

                                                <td>
                                                    <div>
                                                        <h6 class="mb-0">{{ $booking->guest_name }}</h6>
                                                        <small class="text-muted">{{ $booking->guest_phone }}</small>
                                                    </div>
                                                </td>
                                                {{-- 
                                                <td>
                                                    <span class="badge bg-light-primary">
                                                        Room {{ $booking->room->room_number ?? '—' }}
                                                    </span><br>
                                                    <small class="text-muted">{{ $booking->room->type ?? '' }}</small>
                                                </td> --}}

                                                <td>
                                                    <i class="ti ti-calendar-event f-13 text-muted me-1"></i>
                                                    {{ $booking->check_in->format('d M Y') }}
                                                </td>

                                                <td>
                                                    <i class="ti ti-calendar-event f-13 text-muted me-1"></i>
                                                    {{ $booking->check_out->format('d M Y') }}
                                                </td>

                                                {{-- <td>
                                                    <span class="badge bg-light-secondary">{{ $booking->nights }}
                                                        nights</span>
                                                </td> --}}



                                                <td>
                                                    @if ($booking->advance_paid > 0)
                                                        <span
                                                            class="text-info">₨{{ number_format($booking->advance_paid) }}</span>
                                                    @else
                                                        <span class="text-muted">—</span>
                                                    @endif
                                                </td>

                                                <td>
                                                    <strong
                                                        class="text-success">₨{{ number_format($booking->total_amount) }}</strong>
                                                </td>

                                                <td>
                                                    <span class="badge {{ $booking->getPaymentBadgeClass() }}">
                                                        {{ $booking->payment_status }}
                                                    </span>
                                                </td>

                                                <td>
                                                    <span class="badge {{ $booking->getStatusBadgeClass() }}">
                                                        {{ $booking->status }}
                                                    </span>
                                                </td>

                                                <td class="text-end">
                                                    <!-- Check In -->
                                                    @if ($booking->status === 'Confirmed')
                                                        <form action="{{ route('admin.bookings.checkin', $booking->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            <button class="avtar avtar-xs btn-link-success" title="Check In"
                                                                type="submit">
                                                                <i class="ti ti-login f-18"></i>
                                                            </button>
                                                        </form>
                                                    @endif

                                                    <!-- Check Out -->
                                                    @if ($booking->status === 'Checked In')
                                                        <form action="{{ route('admin.bookings.checkout', $booking->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            <button class="avtar avtar-xs btn-link-warning"
                                                                title="Check Out" type="submit">
                                                                <i class="ti ti-logout f-18"></i>
                                                            </button>
                                                        </form>
                                                    @endif

                                                    <!-- View -->
                                                    <a href="{{ route('admin.bookings.show', $booking->id) }}"
                                                        class="avtar avtar-xs btn-link-secondary" title="View">
                                                        <i class="ti ti-eye f-18"></i>
                                                    </a>

                                                    <!-- Edit -->
                                                    <a href="{{ route('admin.bookings.edit', $booking->id) }}"
                                                        class="avtar avtar-xs btn-link-secondary" title="Edit">
                                                        <i class="ti ti-edit f-18"></i>
                                                    </a>

                                                    <!-- Delete -->
                                                    <a href="#" class="avtar avtar-xs btn-link-secondary bs-pass-para"
                                                        data-id="{{ $booking->id }}" title="Delete">
                                                        <i class="ti ti-trash f-18"></i>
                                                    </a>
                                                    <form id="delete-form-{{ $booking->id }}"
                                                        action="{{ route('admin.bookings.destroy', $booking->id) }}"
                                                        method="POST" style="display:none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="11" class="text-center py-5 text-muted">
                                                    <i class="ti ti-calendar-off f-40 d-block mb-2"></i>
                                                    No booking found — please add a booking first!
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
