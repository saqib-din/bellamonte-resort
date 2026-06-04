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
                            <h4 class="mb-1 text-primary">{{ $customers->count() }}</h4>
                            <p class="mb-0 text-muted f-12">Total Customers</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body py-3">
                            <h4 class="mb-1 text-success">{{ $customers->where('status', 'Active')->count() }}</h4>
                            <p class="mb-0 text-muted f-12">Active</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body py-3">
                            <h4 class="mb-1 text-danger">{{ $customers->where('status', 'Blacklisted')->count() }}</h4>
                            <p class="mb-0 text-muted f-12">Blacklisted</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body py-3">
                            <h4 class="mb-1 text-info">{{ $customers->sum('bookings_count') }}</h4>
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
                                <table class="table table-hover" id="pc-dt-simple">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Customer</th>
                                            <th>CNIC / Passport</th>
                                            <th>Phone</th>
                                            <th>City / Nationality</th>
                                            <th>Gender</th>
                                            {{-- <th>Total Stays</th> --}}
                                            <th>Status</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($customers as $customer)
                                            <tr>
                                                <td>{{ $customer->id }}</td>

                                                <!-- Name + Photo -->
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0">
                                                            @if ($customer->image)
                                                                <img src="{{ asset('uploads/customers/' . $customer->image) }}"
                                                                    alt="{{ $customer->name }}"
                                                                    style="width:40px;height:40px;object-fit:cover;"
                                                                    class="rounded-circle">
                                                            @else
                                                                <div class="avtar avtar-s bg-light-primary rounded-circle d-flex align-items-center justify-content-center"
                                                                    style="width:40px;height:40px;">
                                                                    <span class="fw-bold text-primary">
                                                                        {{ strtoupper(substr($customer->name, 0, 1)) }}
                                                                    </span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="flex-grow-1 ms-3">
                                                            <h6 class="mb-0">{{ $customer->name }}</h6>
                                                            <small
                                                                class="text-muted">{{ $customer->email ?? 'No email' }}</small>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td>{{ $customer->cnic }}</td>
                                                <td>{{ $customer->phone }}</td>
                                                <td>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h6 class="mb-0">{{ $customer->city ?? '—' }}</h6>
                                                        <small class="text-muted">{{ $customer->nationality }}</small>
                                                    </div>
                                                </td>
                                                <td>{{ $customer->gender ?? '—' }}</td>

                                                {{-- <td>
                                            <span class="badge bg-light-info">
                                                {{ $customer->bookings_count }} stays
                                            </span>
                                        </td> --}}

                                                <td>
                                                    <span class="badge {{ $customer->getStatusBadgeClass() }}">
                                                        {{ $customer->status }}
                                                    </span>
                                                </td>

                                                <td class="text-end">
                                                    <a href="{{ route('customers.show', $customer->id) }}"
                                                        class="avtar avtar-xs btn-link-secondary" title="View">
                                                        <i class="ti ti-eye f-20"></i>
                                                    </a>
                                                    <a href="{{ route('customers.edit', $customer->id) }}"
                                                        class="avtar avtar-xs btn-link-secondary" title="Edit">
                                                        <i class="ti ti-edit f-20"></i>
                                                    </a>
                                                    <a href="#" class="avtar avtar-xs btn-link-secondary bs-pass-para"
                                                        data-id="{{ $customer->id }}" title="Delete">
                                                        <i class="ti ti-trash f-20"></i>
                                                    </a>
                                                    <form id="delete-form-{{ $customer->id }}"
                                                        action="{{ route('customers.destroy', $customer->id) }}"
                                                        method="POST" style="display:none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center py-5 text-muted">
                                                    <i class="ti ti-users f-40 d-block mb-2"></i>
                                                    No customer found — please add a customer first!
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
