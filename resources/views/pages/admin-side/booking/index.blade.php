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
                                <table class="table table-hover" id="bookings-table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Booking #</th>
                                            <th>Guest</th>
                                            <th>Room</th>
                                            <th>Check In</th>
                                            <th>Check Out</th>
                                            {{-- <th>Nights</th> --}}
                                            {{-- <th>Advance</th> --}}
                                            <th>Total</th>
                                            {{-- <th>Payment</th> --}}
                                            <th>Status</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- DataTables server-side AJAX --}}
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

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
@endpush

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(function() {
            const table = $('#bookings-table').DataTable({
                processing: true,
                serverSide: true, // <-- lakhs records 
                responsive: true,
                order: [
                    [0, 'desc']
                ],
                ajax: "{{ route('admin.bookings.index') }}",
                columns: [{
                        data: 'booking_number',
                        name: 'booking_number'
                    },
                    {
                        data: 'guest',
                        name: 'guest_name'
                    },
                    {
                        data: 'room',
                        name: 'room.room_number',
                        orderable: false
                    },
                    {
                        data: 'check_in',
                        name: 'check_in'
                    },
                    {
                        data: 'check_out',
                        name: 'check_out'
                    },
                    // {
                    //     data: 'nights',
                    //     name: 'nights'
                    // },
                    // {
                    //     data: 'advance',
                    //     name: 'advance_paid'
                    // },
                    {
                        data: 'total',
                        name: 'total_amount'
                    },
                    // {
                    //     data: 'payment_badge',
                    //     name: 'payment_status'
                    // },
                    {
                        data: 'status_badge',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-end'
                    },
                ],
                language: {
                    processing: '<div class="spinner-border text-primary" role="status"></div>',
                    emptyTable: 'No booking found — please add a booking first!',
                }
            });

            $('#bookings-table tbody').on('click', '.bs-pass-para', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This booking will be deleted permanently!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-form-' + id).submit();
                    }
                });
            });
        });
    </script>
@endpush
