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
                            <h4 class="mb-1 text-primary">{{ $stats['total'] }}</h4>
                            <p class="mb-0 text-muted f-12">Total Invoices</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body py-3">
                            <h4 class="mb-1 text-success">{{ number_format($stats['collected']) }} Pkr</h4>
                            <p class="mb-0 text-muted f-12">Total Collected</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body py-3">
                            <h4 class="mb-1 text-danger">{{ number_format($stats['pending']) }} Pkr</h4>
                            <p class="mb-0 text-muted f-12">Total Pending</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body py-3">
                            <h4 class="mb-1 text-warning">{{ $stats['partial'] }}</h4>
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
                                <table class="table table-hover" id="bills-table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Invoice #</th>
                                            <th>Guest</th>
                                            <th>Room</th>
                                            <th>Check In</th>
                                            <th>Check Out</th>
                                            <th>Method</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- DataTables server-side AJAX se bharega --}}
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
            const table = $('#bills-table').DataTable({
                processing: true,
                serverSide: true, // <-- lakhs records ke liye sabse zaroori
                responsive: true,
                order: [
                    [0, 'desc']
                ],
                ajax: "{{ route('billing.index') }}",
                columns: [{
                        data: 'invoice_number',
                        name: 'invoice_number'
                    },
                    {
                        data: 'guest',
                        name: 'guest_name'
                    },
                    {
                        data: 'room',
                        name: 'room_number'
                    },
                    {
                        data: 'check_in',
                        name: 'check_in'
                    },
                    {
                        data: 'check_out',
                        name: 'check_out'
                    },
                    {
                        data: 'method',
                        name: 'payment_method'
                    },
                    {
                        data: 'total',
                        name: 'total_amount'
                    },
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
                    emptyTable: 'No invoices found — please create your first invoice!',
                }
            });

            // Delete — event DELEGATION zaroori hai
            $('#bills-table tbody').on('click', '.bs-pass-para', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This invoice will be deleted permanently!',
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
