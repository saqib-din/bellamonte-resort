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
                                <li class="breadcrumb-item"><a href="{{ route('admin.rooms.index') }}">Rooms</a></li>
                                <li class="breadcrumb-item" aria-current="page">Room {{ $room->room_number }}</li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="page-header-title">
                                    <h2 class="mb-0">Room {{ $room->room_number }} — {{ $room->type }}</h2>
                                </div>
                                <div>
                                    <a href="{{ route('admin.rooms.edit', $room->id) }}" class="btn btn-primary me-2">
                                        <i class="ti ti-edit me-1"></i> Edit
                                    </a>
                                    <a href="{{ route('admin.rooms.index') }}" class="btn btn-outline-secondary">
                                        <i class="ti ti-arrow-left me-1"></i> Back
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">

                <!-- Left: Image + Status -->
                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-body text-center p-4">
                            @if ($room->image)
                                <img src="{{ asset('storage/' . $room->image) }}" class="img-fluid rounded mb-3"
                                    style="max-height:220px;width:100%;object-fit:cover;">
                            @else
                                <div class="bg-light-primary rounded d-flex align-items-center justify-content-center mb-3"
                                    style="height:180px;">
                                    <i class="ti ti-bed" style="font-size:5rem;color:#4680ff;opacity:0.3;"></i>
                                </div>
                            @endif

                            <h4 class="mb-1">Room {{ $room->room_number }}</h4>
                            <p class="text-muted mb-3">{{ $room->type }} — Floor {{ $room->floor }}</p>

                            <span class="badge {{ $room->getStatusBadgeClass() }} f-14 px-3 py-2">
                                {{ $room->status }}
                            </span>

                            <hr>

                            <div class="d-flex justify-content-around text-center">
                                <div>
                                    <h5 class="mb-0 text-success">{{ number_format($room->price_per_night) }} Pkr</h5>
                                    <small class="text-muted">Per Night</small>
                                </div>
                                <div>
                                    <h5 class="mb-0">{{ $room->capacity }}</h5>
                                    <small class="text-muted">Max Persons</small>
                                </div>
                                <div>
                                    <h5 class="mb-0">{{ $room->size ?? '—' }}</h5>
                                    <small class="text-muted">Size</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Details -->
                <div class="col-lg-8">

                    <!-- Room Info Table -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="ti ti-info-circle me-2"></i>Room Details</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0">
                                    <tbody>
                                        <tr>
                                            <td class="text-muted fw-500" width="35%"><i class="ti ti-door me-2"></i>Room
                                                Number</td>
                                            <td><strong>{{ $room->room_number }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-500"><i class="ti ti-category me-2"></i>Type</td>
                                            <td><span class="badge bg-light-primary">{{ $room->type }}</span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-500"><i class="ti ti-building me-2"></i>Floor</td>
                                            <td>Floor {{ $room->floor }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-500"><i class="ti ti-currency-rupee me-2"></i>Price Per
                                                Night</td>
                                            <td><strong
                                                    class="text-success">₨{{ number_format($room->price_per_night) }}</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-500"><i class="ti ti-users me-2"></i>Capacity</td>
                                            <td>Max {{ $room->capacity }} persons</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-500"><i class="ti ti-maximize me-2"></i>Room Size</td>
                                            <td>{{ $room->size ?? '—' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-500"><i class="ti ti-bed me-2"></i>Bed Type</td>
                                            <td>{{ $room->bed_type ?? '—' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-500"><i class="ti ti-clock me-2"></i>Check In Time</td>
                                            <td>{{ $room->check_in_time ? \Carbon\Carbon::parse($room->check_in_time)->format('h:i A') : '2:00 PM' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-500"><i class="ti ti-clock me-2"></i>Check Out Time
                                            </td>
                                            <td>{{ $room->check_out_time ? \Carbon\Carbon::parse($room->check_out_time)->format('h:i A') : '12:00 PM' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-500"><i class="ti ti-activity me-2"></i>Status</td>
                                            <td><span
                                                    class="badge {{ $room->getStatusBadgeClass() }}">{{ $room->status }}</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Services -->
                    @if ($room->services)
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="ti ti-star me-2"></i>Services & Amenities</h5>
                            </div>
                            <div class="card-body">
                                @foreach ($room->getServicesArray() as $service)
                                    <span class="badge bg-light-primary me-1 mb-2 p-2">
                                        <i class="ti ti-check me-1"></i>{{ trim($service) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Description -->
                    @if ($room->description)
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="ti ti-file-text me-2"></i>Description</h5>
                            </div>
                            <div class="card-body">
                                <p class="mb-0 text-muted">{{ $room->description }}</p>
                            </div>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
@endsection
