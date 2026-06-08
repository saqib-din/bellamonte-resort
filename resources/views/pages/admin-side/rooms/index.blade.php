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
                                <li class="breadcrumb-item"><a href="javascript:void(0)">Rooms</a></li>
                                <li class="breadcrumb-item" aria-current="page">List</li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">Rooms Management</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('components.alerts')

            <!-- Main Table -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-sm-flex align-items-center justify-content-between">
                                <h5 class="mb-3 mb-sm-0">All Rooms</h5>
                                <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary d-flex">
                                    <i class="ti ti-plus me-1"></i> Add New Room
                                </a>
                            </div>
                        </div>

                        <div class="card-body table-card">
                            <div class="table-responsive">
                                <table class="table table-hover" id="pc-dt-simple">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Room</th>
                                            <th>Type</th>
                                            <th>Check In</th>
                                            <th>Check Out</th>
                                            <th>Price</th>

                                            <th>Status</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($rooms as $room)
                                            <tr>
                                                <td>{{ $room->id }}</td>

                                                <!-- Room Image + Number -->
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0">
                                                            @if ($room->image)
                                                                <img src="{{ $room->image ? asset('uploads/rooms/' . $room->image) : asset('landing-assets/img/room/room-1.jpg') }}"
                                                                    alt="Room {{ $room->room_number }}"
                                                                    style="width:45px;height:45px;object-fit:cover;"
                                                                    class="rounded">
                                                            @else
                                                                <div class="avtar avtar-s bg-light-primary rounded">
                                                                    <i class="ti ti-bed f-20"></i>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="flex-grow-1 ms-3">
                                                            <h6 class="mb-0">Room {{ $room->room_number }}</h6>
                                                            <small class="text-muted">Floor {{ $room->floor }}</small>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td>
                                                    <span class="badge bg-light-primary">{{ $room->type }}</span>
                                                </td>

                                                <td>
                                                    <i class="ti ti-clock f-13 text-muted"></i>
                                                    {{ $room->check_in_time ? \Carbon\Carbon::parse($room->check_in_time)->format('h:i A') : '—' }}
                                                </td>

                                                <td>
                                                    <i class="ti ti-clock f-13 text-muted"></i>
                                                    {{ $room->check_out_time ? \Carbon\Carbon::parse($room->check_out_time)->format('h:i A') : '—' }}
                                                </td>

                                                <td>
                                                    <strong
                                                        class="text-success">₨ {{ number_format($room->price_per_night) }}
                                                        </strong>
                                                </td>

                                                <!-- Status -->
                                                <td>
                                                    <span class="badge {{ $room->getStatusBadgeClass() }}">
                                                        {{ $room->status }}
                                                    </span>
                                                </td>

                                                <!-- Actions -->
                                                <td class="text-end">
                                                    <a href="{{ route('admin.rooms.show', $room) }}"
                                                        class="avtar avtar-xs btn-link-secondary" title="View">
                                                        <i class="ti ti-eye f-20"></i>
                                                    </a>
                                                    <a href="{{ route('admin.rooms.edit', $room) }}"
                                                        class="avtar avtar-xs btn-link-secondary" title="Edit">
                                                        <i class="ti ti-edit f-20"></i>
                                                    </a>
                                                    <a href="#" class="avtar avtar-xs btn-link-secondary bs-pass-para"
                                                        data-id="{{ $room->id }}" title="Delete">
                                                        <i class="ti ti-trash f-20"></i>
                                                    </a>
                                                    <form id="delete-form-{{ $room->id }}"
                                                        action="{{ route('admin.rooms.destroy', $room) }}" method="POST"
                                                        style="display:none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="13" class="text-center py-4 text-muted">
                                                    <i class="ti ti-bed f-40 d-block mb-2"></i>
                                                    No rooms found — please add your first room!
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
            // DataTable
            window.dt = new simpleDatatables.DataTable('#pc-dt-simple', {
                sortable: true,
                searchable: true,
                fixedHeight: true
            });

        });
    </script>
@endpush
