@extends('layouts.landing')

@section('hero')
    <div class="breadcrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-text">
                        <h2>About Us</h2>
                        <div class="bt-option">
                            <a href="{{ url('/') }}">Home</a>
                            <span>About Us</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <section class="aboutus-page-section spad">
        <div class="container">

            <div class="about-page-text">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="ap-title">
                            <h2>{{ $data['welcome_title'] ?? 'Welcome To Bellamonte Resort.' }}</h2>
                            <p>{{ $data['welcome_description'] ?? 'Perched in the beautiful hills of Shogran, Pakistan, Bellamonte Resort offers luxury accommodation, breathtaking mountain views, peaceful surroundings, and comfortable rooms for a memorable stay with family and friends.' }}
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-5 offset-lg-1">
                        <ul class="ap-services">
                            @foreach ([1, 2, 3, 4, 5] as $n)
                                @php
                                    $defaults = [
                                        1 => '20% Off On Accommodation.',
                                        2 => 'Complimentary Daily Breakfast',
                                        3 => '3 Pcs Laundry Per Day',
                                        4 => 'Free Wifi.',
                                        5 => 'Discount 20% On F&B',
                                    ];
                                    $offer = $data["offer_{$n}"] ?? $defaults[$n];
                                @endphp
                                @if ($offer)
                                    <li><i class="icon_check"></i> {{ $offer }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Services -->
            <div class="about-page-services">
                <div class="row">
                    @php
                        $serviceDefaults = ['Restaurants Services', 'Travel & Camping', 'Event & Party'];
                    @endphp
                    @foreach ([1, 2, 3] as $n)
                        @php
                            $title = $data["service_{$n}_title"] ?? $serviceDefaults[$n - 1];
                            $img = \App\Models\AboutSetting::serviceImageUrl($data, $n);
                        @endphp
                        <div class="col-md-4">
                            <div class="ap-service-item set-bg" data-setbg="{{ $img }}"
                                style="background-image: url('{{ $img }}'); background-size: cover; background-position: center;">
                                <div class="api-text">
                                    <h3>{{ $title }}</h3>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </section>

    <!-- Video Section -->
    @php
        $videoBg = \App\Models\AboutSetting::videoBgUrl($data);
        $videoUrl = $data['video_url'] ?? 'https://www.youtube.com/watch?v=EzKkl64rRbM';
    @endphp
    <section class="video-section set-bg" data-setbg="{{ $videoBg }}"
        style="background-image: url('{{ $videoBg }}'); background-size: cover; background-position: center;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="video-text">
                        <h2>{{ $data['video_title'] ?? 'Discover Our Resort & Services.' }}</h2>
                        <p>{{ $data['video_subtitle'] ?? 'Experience comfort, fine dining, and breathtaking mountain views at Bellamonte Resort, Shogran.' }}
                        </p>
                        <a href="{{ $videoUrl }}" class="play-btn video-popup">
                            <img src="{{ asset('landing-assets/img/play.png') }}" alt="">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="gallery-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <span>Our Gallery</span>
                        <h2>Discover Our Work</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    @php $g1 = \App\Models\AboutSetting::galleryImageUrl($data, 1); @endphp
                    <div class="gallery-item set-bg" data-setbg="{{ $g1 }}"
                        style="background-image: url('{{ $g1 }}'); background-size: cover; background-position: center;">
                        <div class="gi-text">
                            <h3>{{ $data['gallery_1_title'] ?? 'Room Luxury' }}</h3>
                        </div>
                    </div>
                    <div class="row">
                        @foreach ([3, 4] as $n)
                            @php $gi = \App\Models\AboutSetting::galleryImageUrl($data, $n); @endphp
                            <div class="col-sm-6">
                                <div class="gallery-item set-bg" data-setbg="{{ $gi }}"
                                    style="background-image: url('{{ $gi }}'); background-size: cover; background-position: center;">
                                    <div class="gi-text">
                                        <h3>{{ $data["gallery_{$n}_title"] ?? 'Room Luxury' }}</h3>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-6">
                    @php $g2 = \App\Models\AboutSetting::galleryImageUrl($data, 2); @endphp
                    <div class="gallery-item large-item set-bg" data-setbg="{{ $g2 }}"
                        style="background-image: url('{{ $g2 }}'); background-size: cover; background-position: center;">
                        <div class="gi-text">
                            <h3>{{ $data['gallery_2_title'] ?? 'Room Luxury' }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
