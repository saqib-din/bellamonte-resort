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
                                <li class="breadcrumb-item"><a href="javascript:void(0)">Customers</a></li>
                                <li class="breadcrumb-item" aria-current="page">List</li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">Customers Management</h2>
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
                            <p class="mb-0 text-muted f-12">Total Customers</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body py-3">
                            <h4 class="mb-1 text-success">{{ $stats['active'] }}</h4>
                            <p class="mb-0 text-muted f-12">Active</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body py-3">
                            <h4 class="mb-1 text-danger">{{ $stats['blacklisted'] }}</h4>
                            <p class="mb-0 text-muted f-12">Blacklisted</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body py-3">
                            <h4 class="mb-1 text-info">{{ $stats['bookings'] }}</h4>
                            <p class="mb-0 text-muted f-12">Total Bookings</p>
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
                                <h5 class="mb-3 mb-sm-0">All Customers</h5>
                                <a href="{{ route('customers.create') }}" class="btn btn-primary d-flex">
                                    <i class="ti ti-plus me-1"></i> Add Customer
                                </a>
                            </div>
                        </div>

                        <div class="card-body table-card">
                            <div class="table-responsive">
                                <table class="table table-hover" id="customers-table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Customer</th>
                                            <th>CNIC / Passport</th>
                                            <th>Phone</th>
                                            <th>City / Nationality</th>
                                            <th>Gender</th>
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
            const table = $('#customers-table').DataTable({
                processing: true,
                serverSide: true, // <-- lakhs records ke liye sabse zaroori
                responsive: true,
                order: [
                    [0, 'desc']
                ],
                ajax: "{{ route('customers.index') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'customer',
                        name: 'name'
                    },
                    {
                        data: 'cnic',
                        name: 'cnic'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'location',
                        name: 'city',
                        orderable: false
                    },
                    {
                        data: 'gender',
                        name: 'gender'
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
                    emptyTable: 'No customer found — please add a customer first!',
                }
            });

            // Delete — event DELEGATION zaroori hai (rows har page pe naye render hote hain)
            $('#customers-table tbody').on('click', '.bs-pass-para', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This customer will be deleted permanently!',
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
