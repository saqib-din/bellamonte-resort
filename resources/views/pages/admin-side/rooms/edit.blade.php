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
                                <li class="breadcrumb-item" aria-current="page">Edit Room {{ $room->room_number }}</li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">Edit Room — {{ $room->room_number }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('components.alerts')

            <form action="{{ route('admin.rooms.update', $room->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">

                    <!-- Left Column -->
                    <div class="col-lg-8">

                        <!-- Basic Info -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="ti ti-door me-2"></i>Basic Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">

                                    <div class="col-md-4">
                                        <label class="form-label">Room Number <span class="text-danger">*</span></label>
                                        <input type="text" name="room_number"
                                            class="form-control @error('room_number') is-invalid @enderror"
                                            value="{{ old('room_number', $room->room_number) }}">
                                        @error('room_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Room Type <span class="text-danger">*</span></label>
                                        <select name="type" class="form-select @error('type') is-invalid @enderror">
                                            @foreach (['Deluxe Suite Room', 'Duplex Suite Room', 'VIP Honeymoon Room', 'Deluxe VIP Room', 'VIP Excutive Room'] as $type)
                                                <option value="{{ $type }}"
                                                    {{ old('type', $room->type) == $type ? 'selected' : '' }}>
                                                    {{ $type }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Floor <span class="text-danger">*</span></label>
                                        <input type="number" name="floor" class="form-control"
                                            value="{{ old('floor', $room->floor) }}" min="1">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Price Per Night (₨) <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">₨</span>
                                            <input type="number" name="price_per_night" class="form-control"
                                                value="{{ old('price_per_night', $room->price_per_night) }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Max Capacity <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="ti ti-users"></i></span>
                                            <input type="number" name="capacity" class="form-control"
                                                value="{{ old('capacity', $room->capacity) }}" min="1">
                                            <span class="input-group-text">persons</span>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Room Size</label>
                                        <div class="input-group">
                                            <input type="text" name="size" class="form-control"
                                                value="{{ old('size', $room->size) }}" placeholder="30">
                                            <span class="input-group-text">sq ft</span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Bed & Timing -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="ti ti-bed me-2"></i>Bed & Timing</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Bed Type</label>
                                        <select name="bed_type" class="form-select">
                                            <option value="">-- Select Bed --</option>
                                            @foreach (['Single Bed', 'Double Bed', 'Queen Bed', 'King Bed', 'Twin Beds', 'King Beds x2'] as $bed)
                                                <option value="{{ $bed }}"
                                                    {{ old('bed_type', $room->bed_type) == $bed ? 'selected' : '' }}>
                                                    {{ $bed }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Check In Time</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="ti ti-clock"></i></span>
                                            <input type="time" name="check_in_time" class="form-control"
                                                value="{{ old('check_in_time', $room->check_in_time ? \Carbon\Carbon::parse($room->check_in_time)->format('H:i') : '14:00') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Check Out Time</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="ti ti-clock"></i></span>
                                            <input type="time" name="check_out_time" class="form-control"
                                                value="{{ old('check_out_time', $room->check_out_time ? \Carbon\Carbon::parse($room->check_out_time)->format('H:i') : '12:00') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Services -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="ti ti-star me-2"></i>Services & Amenities</h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    @foreach (['WiFi', 'AC', 'TV', 'Geyser', 'Mini Bar', 'Jacuzzi', 'Parking', 'Room Service', 'Laundry', 'Breakfast', 'Swimming Pool', 'Gym'] as $service)
                                        <div class="col-md-3 col-6 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input service-check" type="checkbox"
                                                    value="{{ $service }}" id="svc_{{ $loop->index }}">
                                                <label class="form-check-label"
                                                    for="svc_{{ $loop->index }}">{{ $service }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <input type="text" name="services" id="servicesInput" class="form-control"
                                    value="{{ old('services', $room->services) }}" placeholder="WiFi, AC, TV ...">
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="ti ti-file-text me-2"></i>Description</h5>
                            </div>
                            <div class="card-body">
                                <textarea name="description" class="form-control" rows="5">{{ old('description', $room->description) }}</textarea>
                            </div>
                        </div>

                    </div>

                    <!-- Right Column -->
                    <div class="col-lg-4">

                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="ti ti-settings me-2"></i>Status & Image</h5>
                            </div>
                            <div class="card-body">

                                <div class="mb-3">
                                    <label class="form-label">Room Status <span class="text-danger">*</span></label>
                                    <select name="status" class="form-select">
                                        <option value="Available"
                                            {{ old('status', $room->status) == 'Available' ? 'selected' : '' }}>✅
                                            Available</option>
                                        <option value="Occupied"
                                            {{ old('status', $room->status) == 'Occupied' ? 'selected' : '' }}>🔴
                                            Occupied</option>
                                        <option value="Maintenance"
                                            {{ old('status', $room->status) == 'Maintenance' ? 'selected' : '' }}>🔧
                                            Maintenance</option>
                                    </select>
                                </div>

                                <!-- Current Image -->
                                @if ($room->image)
                                    <div class="mb-3 text-center">
                                        <p class="text-muted f-12 mb-1">Current Image:</p>
                                        <img src="{{ asset('uploads/rooms/' . $room->image) }}" class="img-fluid rounded"
                                            style="max-height:160px;" id="imagePreview">
                                    </div>
                                @else
                                    <div class="mb-3 text-center" id="imagePreviewBox" style="display:none">
                                        <img id="imagePreview" src="#" class="img-fluid rounded"
                                            style="max-height:160px;">
                                    </div>
                                @endif

                                <div class="mb-3">
                                    <label class="form-label">Change Image</label>
                                    <input type="file" name="image" class="form-control" accept="image/*"
                                        onchange="previewImage(this)">
                                    <small class="text-muted">Leave empty to keep current image</small>
                                </div>

                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-device-floppy me-2"></i>Update Room
                                    </button>
                                    <a href="{{ route('admin.rooms.index') }}" class="btn btn-outline-secondary">
                                        <i class="ti ti-arrow-left me-2"></i>Cancel
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </form>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    document.getElementById('imagePreview').src = e.target.result;
                    const box = document.getElementById('imagePreviewBox');
                    if (box) box.style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Pre-check existing services
        const existingServices = document.getElementById('servicesInput').value;
        if (existingServices) {
            const list = existingServices.split(',').map(s => s.trim().toLowerCase());
            document.querySelectorAll('.service-check').forEach(cb => {
                if (list.includes(cb.value.toLowerCase())) cb.checked = true;
            });
        }

        // Sync checkboxes with input
        document.querySelectorAll('.service-check').forEach(function(cb) {
            cb.addEventListener('change', function() {
                const checked = Array.from(document.querySelectorAll('.service-check:checked')).map(c => c
                    .value);
                document.getElementById('servicesInput').value = checked.join(', ');
            });
        });
    </script>
@endpush
