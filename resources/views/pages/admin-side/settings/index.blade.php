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
                                <li class="breadcrumb-item" aria-current="page">Settings</li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">⚙️ Hotel Settings</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('components.alerts')

            <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- LEFT -->
                    <div class="col-lg-8">

                        <!-- Hotel Info -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="ti ti-building me-2"></i>Hotel Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Hotel Name <span class="text-danger">*</span></label>
                                        <input type="text" name="hotel_name" class="form-control"
                                            value="{{ $settings['hotel_name'] ?? '' }}" placeholder="BM Resort">
                                    </div>
                                    {{-- <div class="col-md-6">
                                    <label class="form-label">Tagline</label>
                                    <input type="text" name="hotel_tagline" class="form-control"
                                        value="{{ $settings['hotel_tagline'] ?? '' }}"
                                        placeholder="Luxury · Elegance · Comfort">
                                </div> --}}
                                    <div class="col-md-6">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="hotel_email" class="form-control"
                                            value="{{ $settings['hotel_email'] ?? '' }}" placeholder="info@bmresort.com">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Phone</label>
                                        <input type="text" name="hotel_phone" class="form-control"
                                            value="{{ $settings['hotel_phone'] ?? '' }}" placeholder="0300-1234567">
                                    </div>
                                    {{-- <div class="col-md-6">
                                    <label class="form-label">City</label>
                                    <input type="text" name="hotel_city" class="form-control"
                                        value="{{ $settings['hotel_city'] ?? '' }}"
                                        placeholder="Lahore">
                                </div> --}}
                                    <div class="col-md-6">
                                        <label class="form-label">Country</label>
                                        <input type="text" name="hotel_country" class="form-control"
                                            value="{{ $settings['hotel_country'] ?? '' }}" placeholder="Pakistan">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Full Address</label>
                                        <input type="text" name="hotel_address" class="form-control"
                                            value="{{ $settings['hotel_address'] ?? '' }}"
                                            placeholder="Street, Area, City, Pakistan">
                                    </div>
                                    {{-- <div class="col-md-6">
                                    <label class="form-label">Website</label>
                                    <input type="text" name="hotel_website" class="form-control"
                                        value="{{ $settings['hotel_website'] ?? '' }}"
                                        placeholder="www.bmresort.com">
                                </div> --}}
                                    {{-- <div class="col-md-6">
                                    <label class="form-label">Footer Text (Invoice par)</label>
                                    <input type="text" name="footer_text" class="form-control"
                                        value="{{ $settings['footer_text'] ?? '' }}"
                                        placeholder="Thank you for staying with us!">
                                </div> --}}
                                </div>
                            </div>
                        </div>

                        {{-- <!-- Check-in / Check-out & Invoice -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="ti ti-clock me-2"></i>Timing & Invoice Settings</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">Default Check-in Time</label>
                                    <input type="time" name="checkin_time" class="form-control"
                                        value="{{ $settings['checkin_time'] ?? '14:00' }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Default Check-out Time</label>
                                    <input type="time" name="checkout_time" class="form-control"
                                        value="{{ $settings['checkout_time'] ?? '12:00' }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Invoice Prefix</label>
                                    <input type="text" name="invoice_prefix" class="form-control"
                                        value="{{ $settings['invoice_prefix'] ?? 'INV' }}"
                                        placeholder="INV">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Default Tax (%)</label>
                                    <div class="input-group">
                                        <input type="number" name="default_tax" class="form-control"
                                            value="{{ $settings['default_tax'] ?? '0' }}"
                                            min="0" max="100">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Currency Symbol</label>
                                    <input type="text" name="currency" class="form-control"
                                        value="{{ $settings['currency'] ?? '₨' }}"
                                        placeholder="₨">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Currency Code</label>
                                    <input type="text" name="currency_code" class="form-control"
                                        value="{{ $settings['currency_code'] ?? 'PKR' }}"
                                        placeholder="PKR">
                                </div>
                            </div>
                        </div>
                    </div> --}}

                        <!-- Social Media -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="ti ti-share me-2"></i>Social Media</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label"><i
                                                class="ti ti-brand-facebook text-primary me-1"></i>Facebook</label>
                                        <input type="text" name="facebook" class="form-control"
                                            value="{{ $settings['facebook'] ?? '' }}"
                                            placeholder="https://facebook.com/...">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label"><i
                                                class="ti ti-brand-instagram text-danger me-1"></i>Instagram</label>
                                        <input type="text" name="instagram" class="form-control"
                                            value="{{ $settings['instagram'] ?? '' }}"
                                            placeholder="https://instagram.com/...">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label"><i class="ti ti-brand-twitter text-info me-1"></i>Twitter
                                            / X</label>
                                        <input type="text" name="twitter" class="form-control"
                                            value="{{ $settings['twitter'] ?? '' }}" placeholder="https://twitter.com/...">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- RIGHT -->
                    <div class="col-lg-4">

                        <!-- Logo -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="ti ti-photo me-2"></i>Hotel Logo</h5>
                            </div>
                            <div class="card-body text-center">
                                @if (!empty($settings['hotel_logo']))
                                    <img src="{{ asset('uploads/settings/' . $settings['hotel_logo']) }}"
                                        class="img-fluid mb-3 rounded" style="max-height:120px;" id="logoPreview">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center mb-3 mx-auto"
                                        style="height:100px;width:180px;" id="logoPlaceholder">
                                        <i class="ti ti-building f-40 text-muted opacity-50"></i>
                                    </div>
                                    <img id="logoPreview" src="#" class="img-fluid mb-3 rounded d-none"
                                        style="max-height:120px;">
                                @endif
                                <input type="file" name="hotel_logo" class="form-control" accept="image/*"
                                    onchange="previewLogo(this)">
                                <small class="text-muted d-block mt-1">PNG, JPG, SVG — max 2MB</small>
                                <small class="text-muted d-block">Will appear on invoices and headers</small>
                            </div>
                        </div>

                        <!-- Info Box -->
                        <div class="card mb-4 border-primary">
                            <div class="card-header bg-light-primary">
                                <h5 class="mb-0 text-primary"><i class="ti ti-info-circle me-2"></i>Settings Info</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled mb-0 f-13">
                                    <li class="mb-2"><i class="ti ti-check text-success me-2"></i>Hotel name appears on
                                        invoices</li>
                                    <li class="mb-2"><i class="ti ti-check text-success me-2"></i>Logo appears on the
                                        print page</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Save Button -->
                        <div class="card">
                            <div class="card-body">
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="ti ti-device-floppy me-2"></i>Save Settings
                                    </button>
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
        function previewLogo(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    const placeholder = document.getElementById('logoPlaceholder');
                    if (placeholder) placeholder.classList.add('d-none');
                    const img = document.getElementById('logoPreview');
                    img.src = e.target.result;
                    img.classList.remove('d-none');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush
