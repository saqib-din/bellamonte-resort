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
                                <li class="breadcrumb-item"><a href="{{ route('admin.rooms.index') }}">Rooms</a></li>
                                <li class="breadcrumb-item" aria-current="page">Add New Room</li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">Add New Room</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('components.alerts')

            <form action="{{ route('admin.rooms.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
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
                                            value="{{ old('room_number') }}" placeholder="e.g. 101">
                                        @error('room_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Room Type <span class="text-danger">*</span></label>
                                        <select name="type" class="form-select @error('type') is-invalid @enderror">
                                            <option value="">-- Select Type --</option>
                                            @foreach (['Deluxe Suite Room', 'Duplex Suite Room', 'VIP Honeymoon Room', 'Deluxe VIP Room', 'VIP Excutive Room'] as $type)
                                                <option value="{{ $type }}"
                                                    {{ old('type') == $type ? 'selected' : '' }}>
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
                                        <input type="number" name="floor"
                                            class="form-control @error('floor') is-invalid @enderror"
                                            value="{{ old('floor', 1) }}" min="1">
                                        @error('floor')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Price Per Night (₨) <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">₨</span>
                                            <input type="number" name="price_per_night"
                                                class="form-control @error('price_per_night') is-invalid @enderror"
                                                value="{{ old('price_per_night') }}" placeholder="5000" min="1">
                                        </div>
                                        @error('price_per_night')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Max Capacity <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="ti ti-users"></i></span>
                                            <input type="number" name="capacity"
                                                class="form-control @error('capacity') is-invalid @enderror"
                                                value="{{ old('capacity', 2) }}" min="1" max="20">
                                            <span class="input-group-text">persons</span>
                                        </div>
                                        @error('capacity')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Room Size</label>
                                        <div class="input-group">
                                            <input type="text" name="size"
                                                class="form-control @error('size') is-invalid @enderror"
                                                value="{{ old('size') }}" placeholder="30">
                                            <span class="input-group-text">sq ft</span>
                                        </div>
                                        @error('size')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Bed & Check In/Out -->
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
                                                    {{ old('bed_type') == $bed ? 'selected' : '' }}>
                                                    {{ $bed }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Check In Time</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="ti ti-clock"></i></span>
                                            <input type="time" name="check_in_time" class="form-control"
                                                value="{{ old('check_in_time', '14:00') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Check Out Time</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="ti ti-clock"></i></span>
                                            <input type="time" name="check_out_time" class="form-control"
                                                value="{{ old('check_out_time', '12:00') }}">
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
                                <label class="form-label">Services <small class="text-muted">(👉 “Separate the values with commas”)</small></label>

                                <!-- Checkboxes for quick select -->
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
                                    value="{{ old('services') }}" placeholder="WiFi, AC, TV, Geyser ...">
                                <small class="text-muted">Check Above or Enter Manually</small>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="ti ti-file-text me-2"></i>Description</h5>
                            </div>
                            <div class="card-body">
                                <textarea name="description" class="form-control" rows="5" placeholder="Enter Room Description...">{{ old('description') }}</textarea>
                            </div>
                        </div>

                    </div>

                    <!-- Right Column -->
                    <div class="col-lg-4">

                        <!-- Status & Image -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="ti ti-settings me-2"></i>Status & Image</h5>
                            </div>
                            <div class="card-body">

                                <div class="mb-3">
                                    <label class="form-label">Room Status <span class="text-danger">*</span></label>
                                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                                        <option value="Available" {{ old('status') == 'Available' ? 'selected' : '' }}>✅
                                            Available</option>
                                        <option value="Occupied" {{ old('status') == 'Occupied' ? 'selected' : '' }}>🔴
                                            Occupied</option>
                                        <option value="Maintenance"
                                            {{ old('status') == 'Maintenance' ? 'selected' : '' }}>🔧 Maintenance</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Room Image</label>
                                    <input type="file" name="image" class="form-control" accept="image/*"
                                        onchange="previewImage(this)">
                                    @error('image')
                                        <div class="text-danger mt-1 f-12">{{ $message }}</div>
                                    @enderror
                                    <div class="mt-3 text-center" id="imagePreviewBox" style="display:none">
                                        <img id="imagePreview" src="#" class="img-fluid rounded"
                                            style="max-height:180px;">
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Summary Card -->
                        <div class="card mb-4 border-primary">
                            <div class="card-header bg-light-primary">
                                <h5 class="mb-0 text-primary"><i class="ti ti-info-circle me-2"></i>Quick Info</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2"><i class="ti ti-door text-muted me-2"></i>Room Number required</li>
                                    <li class="mb-2"><i class="ti ti-currency-rupee text-muted me-2"></i>Price per night
                                        required</li>
                                    <li class="mb-2"><i class="ti ti-users text-muted me-2"></i>Capacity in persons</li>
                                    <li class="mb-2"><i class="ti ti-clock text-muted me-2"></i>Check-in: 2:00 PM
                                        default</li>
                                    <li class="mb-2"><i class="ti ti-clock text-muted me-2"></i>Check-out: 12:00 PM
                                        default</li>
                                    <li><i class="ti ti-photo text-muted me-2"></i>Image optional (max 2MB)</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="card">
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-device-floppy me-2"></i>Save Room
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
        // Image preview
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    document.getElementById('imagePreview').src = e.target.result;
                    document.getElementById('imagePreviewBox').style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Services checkboxes → input field
        document.querySelectorAll('.service-check').forEach(function(cb) {
            cb.addEventListener('change', function() {
                const checked = Array.from(document.querySelectorAll('.service-check:checked'))
                    .map(c => c.value);
                document.getElementById('servicesInput').value = checked.join(', ');
            });
        });

        // Pre-check services if old value exists
        const existingServices = document.getElementById('servicesInput').value;
        if (existingServices) {
            const list = existingServices.split(',').map(s => s.trim().toLowerCase());
            document.querySelectorAll('.service-check').forEach(cb => {
                if (list.includes(cb.value.toLowerCase())) cb.checked = true;
            });
        }
    </script>
@endpush
