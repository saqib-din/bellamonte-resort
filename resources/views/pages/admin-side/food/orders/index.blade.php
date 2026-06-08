@extends('layouts.admin')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
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
                                <li class="breadcrumb-item" aria-current="page">Food Orders</li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">Food Orders</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('components.alerts')

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
                                <table class="table table-hover" id="orders-table" style="width:100%">
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
    {{-- DataTables (jQuery) --}}
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#orders-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('food.orders.index') }}",
                order: [],
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                dom: "<'row mb-3'<'col-sm-6'l><'col-sm-6'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row mt-3'<'col-sm-5'i><'col-sm-7'p>>",
                columns: [{
                        data: 'order_no',
                        name: 'order_no'
                    },
                    {
                        data: 'guest',
                        name: 'guest'
                    },
                    {
                        data: 'room',
                        name: 'room',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'type',
                        name: 'type',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'items_count',
                        name: 'items_count',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'total',
                        name: 'total_amount'
                    },
                    {
                        data: 'balance',
                        name: 'balance_due'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false
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
                    emptyTable: "No food orders yet — place the first order!",
                    paginate: {
                        previous: "Prev",
                        next: "Next"
                    }
                }
            });
        });
    </script>
@endpush
