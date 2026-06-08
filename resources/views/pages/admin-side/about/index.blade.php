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
                                <li class="breadcrumb-item">About Page</li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">About Page Settings</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('components.alerts')

            <form action="{{ route('about.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">

                    <!-- LEFT -->
                    <div class="col-lg-8">

                        <!-- Welcome Section -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="ti ti-home me-2"></i>Welcome Section</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Title</label>
                                    <input type="text" name="welcome_title" class="form-control"
                                        value="{{ old('welcome_title', $data['welcome_title'] ?? 'Welcome To White Castle Resort.') }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="welcome_description" class="form-control" rows="4" placeholder="Enter a description ">{{ old('welcome_description', $data['welcome_description'] ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Offers -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="ti ti-list me-2"></i>Offers / Services List</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    @php
                                        $offerDefaults = [
                                            1 => '20% Off On Accommodation.',
                                            2 => 'Complimentary Daily Breakfast',
                                            3 => '3 Pcs Laundry Per Day',
                                            4 => 'Free Wifi.',
                                            5 => 'Discount 20% On F&B',
                                        ];
                                    @endphp
                                    @foreach ([1, 2, 3, 4, 5] as $n)
                                        <div class="col-md-6">
                                            <label class="form-label">Offer {{ $n }}</label>
                                            <input type="text" name="offer_{{ $n }}" class="form-control"
                                                value="{{ old('offer_' . $n, $data['offer_' . $n] ?? $offerDefaults[$n]) }}">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Service Cards -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="ti ti-photo me-2"></i>Service Cards</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    @php
                                        $serviceTitleDefaults = [
                                            'Restaurants Services',
                                            'Travel & Camping',
                                            'Event & Party',
                                        ];
                                    @endphp
                                    @foreach ([1, 2, 3] as $n)
                                        <div class="col-md-4">
                                            <label class="form-label">Service {{ $n }} Title</label>
                                            <input type="text" name="service_{{ $n }}_title"
                                                class="form-control mb-2"
                                                value="{{ old('service_' . $n . '_title', $data['service_' . $n . '_title'] ?? $serviceTitleDefaults[$n - 1]) }}">
                                            <label class="form-label">Image</label>
                                            <input type="file" name="service_{{ $n }}_image"
                                                class="form-control" accept="image/*">
                                            @if (!empty($data["service_{$n}_image"]))
                                                <img src="{{ \App\Models\AboutSetting::serviceImageUrl($data, $n) }}"
                                                    class="mt-2 rounded" style="height:80px; object-fit:cover; width:100%;">
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Video Section -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="ti ti-video me-2"></i>Video Section</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Title</label>
                                        <input type="text" name="video_title" class="form-control"
                                            value="{{ old('video_title', $data['video_title'] ?? 'Discover Our Hotel & Services.') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Subtitle</label>
                                        <input type="text" name="video_subtitle" class="form-control" placeholder="Enter a subtitle"
                                            value="{{ old('video_subtitle', $data['video_subtitle'] ?? '') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">YouTube URL</label>
                                        <input type="url" name="video_url" class="form-control"
                                            value="{{ old('video_url', $data['video_url'] ?? 'https://www.youtube.com/watch?v=EzKkl64rRbM') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Background Image</label>
                                        <input type="file" name="video_bg_image" class="form-control" accept="image/*">
                                        @if (!empty($data['video_bg_image']))
                                            <img src="{{ \App\Models\AboutSetting::videoBgUrl($data) }}"
                                                class="mt-2 rounded" style="height:80px; object-fit:cover; width:100%;">
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Gallery -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="ti ti-layout me-2"></i>Gallery</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    @foreach ([1, 2, 3, 4, 5] as $n)
                                        <div class="col-md-4">
                                            <label class="form-label">Gallery {{ $n }} Title</label>
                                            <input type="text" name="gallery_{{ $n }}_title"
                                                class="form-control mb-2"
                                                value="{{ old('gallery_' . $n . '_title', $data['gallery_' . $n . '_title'] ?? 'Room Luxury') }}">
                                            <label class="form-label">Image</label>
                                            <input type="file" name="gallery_{{ $n }}_image"
                                                class="form-control" accept="image/*">
                                            @if (!empty($data["gallery_{$n}_image"]))
                                                <img src="{{ \App\Models\AboutSetting::galleryImageUrl($data, $n) }}"
                                                    class="mt-2 rounded"
                                                    style="height:80px; object-fit:cover; width:100%;">
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- RIGHT -->
                    <div class="col-lg-4">

                        <!-- Quick Info Card -->
                        <div class="card mb-4 border-info">
                            <div class="card-header bg-light-info">
                                <h5 class="mb-0 text-info"><i class="ti ti-info-circle me-2"></i>Quick Info</h5>
                            </div>
                            <div class="card-body f-13">
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2">
                                        <i class="ti ti-home text-primary me-1"></i>
                                        <strong>Welcome Section</strong>
                                        <p class="text-muted mb-0 ms-3">Update the resort title and description.</p>
                                    </li>
                                    <hr class="my-2">
                                    <li class="mb-2">
                                        <i class="ti ti-list text-success me-1"></i>
                                        <strong>Offers List</strong>
                                        <p class="text-muted mb-0 ms-3">5 offers/services displayed as bullet points on the
                                            page.</p>
                                    </li>
                                    <hr class="my-2">
                                    <li class="mb-2">
                                        <i class="ti ti-photo text-warning me-1"></i>
                                        <strong>Service Cards</strong>
                                        <p class="text-muted mb-0 ms-3">Set title and background image for 3 service cards.
                                        </p>
                                    </li>
                                    <hr class="my-2">
                                    <li class="mb-2">
                                        <i class="ti ti-video text-danger me-1"></i>
                                        <strong>Video Section</strong>
                                        <p class="text-muted mb-0 ms-3">Update the YouTube link, title and background
                                            image.</p>
                                    </li>
                                    <hr class="my-2">
                                    <li class="mb-2">
                                        <i class="ti ti-layout text-secondary me-1"></i>
                                        <strong>Gallery</strong>
                                        <p class="text-muted mb-0 ms-3">Set titles and images for 5 gallery items.</p>
                                    </li>
                                    <hr class="my-2">
                                    <li>
                                        <i class="ti ti-photo-off text-muted me-1"></i>
                                        <strong>Image Upload</strong>
                                        <p class="text-muted mb-0 ms-3">If no image is uploaded, the default image will be
                                            shown automatically.</p>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Actions Card -->
                        <div class="card sticky-top" style="top: 20px;">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="ti ti-settings me-2"></i>Actions</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-device-floppy me-2"></i>Save Changes
                                    </button>
                                    <a href="{{ route('about.us') }}" target="_blank" class="btn btn-outline-secondary">
                                        <i class="ti ti-eye me-2"></i>Preview Page
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- <div class="col-lg-4">
                        <div class="card sticky-top" style="top: 20px;">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="ti ti-settings me-2"></i>Actions</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-device-floppy me-2"></i>Save Changes
                                    </button>
                                    <a href="{{ route('about.us') }}" target="_blank" class="btn btn-outline-secondary">
                                        <i class="ti ti-eye me-2"></i>Preview Page
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div> --}}

                </div>
            </form>

        </div>
    </div>
@endsection
