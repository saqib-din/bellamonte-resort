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
                            <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">Customers</a></li>
                            <li class="breadcrumb-item" aria-current="page">Add Customer</li>
                        </ul>
                    </div>
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h2 class="mb-0">Add New Customer</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('components.alerts')

        <form action="{{ route('customers.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">

                <!-- LEFT -->
                <div class="col-lg-8">

                    <!-- Personal Info -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="ti ti-user me-2"></i>Personal Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name') }}" placeholder="Ahmed Ali">
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">CNIC / Passport <span class="text-danger">*</span></label>
                                    <input type="text" name="cnic"
                                        class="form-control @error('cnic') is-invalid @enderror"
                                        value="{{ old('cnic') }}" placeholder="35202-1234567-1">
                                    @error('cnic') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Phone <span class="text-danger">*</span></label>
                                    <input type="text" name="phone"
                                        class="form-control @error('phone') is-invalid @enderror"
                                        value="{{ old('phone') }}" placeholder="0300-1234567">
                                    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email') }}" placeholder="ahmed@email.com">
                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Gender</label>
                                    <select name="gender" class="form-select">
                                        <option value="">-- Select --</option>
                                        @foreach(['Male','Female','Other'] as $g)
                                            <option value="{{ $g }}" {{ old('gender') == $g ? 'selected' : '' }}>{{ $g }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Date of Birth</label>
                                    <input type="date" name="dob" class="form-control"
                                        value="{{ old('dob') }}">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Nationality</label>
                                    <input type="text" name="nationality" class="form-control"
                                        value="{{ old('nationality', 'Pakistani') }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">City</label>
                                    <input type="text" name="city" class="form-control"
                                        value="{{ old('city') }}" placeholder="Lahore">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="Active"      {{ old('status','Active') == 'Active'      ? 'selected' : '' }}>✅ Active</option>
                                        <option value="Blacklisted" {{ old('status') == 'Blacklisted' ? 'selected' : '' }}>🚫 Blacklisted</option>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Address</label>
                                    <textarea name="address" class="form-control" rows="2"
                                        placeholder="House #, Street, Area, City...">{{ old('address') }}</textarea>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Notes <small class="text-muted">(Admin only)</small></label>
                                    <textarea name="notes" class="form-control" rows="2"
                                        placeholder="Koi khas baat...">{{ old('notes') }}</textarea>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

                <!-- RIGHT -->
                <div class="col-lg-4">

                    <!-- Photo -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="ti ti-photo me-2"></i>Photo</h5>
                        </div>
                        <div class="card-body text-center">
                            <div id="imagePreviewBox" class="mb-3">
                                <div class="rounded-circle bg-light-primary d-flex align-items-center justify-content-center mx-auto"
                                    style="width:110px;height:110px;" id="avatarPlaceholder">
                                    <i class="ti ti-user" style="font-size:3rem;color:#4680ff;opacity:0.4;"></i>
                                </div>
                                <img id="imagePreview" src="#"
                                    class="rounded-circle mx-auto d-none"
                                    style="width:110px;height:110px;object-fit:cover;">
                            </div>
                            <input type="file" name="image" class="form-control" accept="image/*"
                                onchange="previewImage(this)">
                            <small class="text-muted d-block mt-1">JPG, PNG — max 2MB</small>
                        </div>
                    </div>

                    <!-- Quick Info -->
                    <div class="card mb-4 border-primary">
                        <div class="card-header bg-light-primary">
                            <h5 class="mb-0 text-primary"><i class="ti ti-info-circle me-2"></i>Required Fields</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2"><i class="ti ti-user text-muted me-2"></i>Full Name</li>
                                <li class="mb-2"><i class="ti ti-id text-muted me-2"></i>CNIC / Passport (unique)</li>
                                <li><i class="ti ti-phone text-muted me-2"></i>Phone Number</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="card">
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-device-floppy me-2"></i>Save Customer
                                </button>
                                <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">
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
            document.getElementById('avatarPlaceholder').classList.add('d-none');
            const img = document.getElementById('imagePreview');
            img.src = e.target.result;
            img.classList.remove('d-none');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
