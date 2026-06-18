@extends('layouts.landing')

@section('hero')
    <div class="breadcrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-text">
                        <h2>Our Events Details</h2>
                        <div class="bt-option">
                            <a href="{{ url('/') }}">Home</a>
                            <span>Details</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <!-- Blog Details Hero -->
    <section class="blog-details-hero set-bg" data-setbg="{{ $event->image_url }}"
        style="background-image: url('{{ $event->image_url }}'); background-size: cover; background-position: center;">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="bd-hero-text">
                        <span>{{ $event->tag }}</span>
                        <h2>{{ $event->title }}</h2>
                        <ul>
                            <li class="b-time">
                                <i class="icon_clock_alt"></i>
                                {{ $event->event_date->format('d M, Y') }}
                            </li>
                            <li>
                                <i class="icon_profile"></i>
                                Bellamonte Resort Team
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Blog Details Hero End -->

    <!-- Blog Details Section -->
    <section class="blog-details-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="blog-details-text">

                        <div class="bd-title">
                            <p>{{ $event->description ?? 'Nestled in the breathtaking hills of Shogran, Bellamonte Resort offers a perfect combination of luxury, comfort, and natural beauty. Guests can enjoy peaceful mountain views, fresh air, and a relaxing environment away from the busy city life.' }}
                            </p>
                            <p>{{ $event->short_description ?? 'Whether you are planning a family vacation, honeymoon trip, corporate retreat, or weekend getaway, Bellamonte Resort provides premium rooms, exceptional hospitality, and unforgettable experiences.' }}
                            </p>
                            <p>Visitors can also explore nearby tourist attractions, hiking trails, forests, and beautiful
                                landscapes of Kaghan Valley while enjoying the peaceful atmosphere of the resort.</p>
                        </div>

                        <!-- Detail Images -->
                        <div class="bd-pic">
                            <div class="bp-item">
                                <img src="{{ $event->detail_image_1_url }}" alt="Event Image 1"
                                    style="width: 100%; height: 187px; object-fit: cover;">
                            </div>
                            <div class="bp-item">
                                <img src="{{ $event->detail_image_2_url }}" alt="Event Image 2"
                                    style="width: 100%; height: 187px; object-fit: cover;">
                            </div>
                            <div class="bp-item">
                                <img src="{{ $event->detail_image_3_url }}" alt="Event Image 3"
                                    style="width: 100%; height: 187px; object-fit: cover;">
                            </div>
                        </div>

                        <!-- Sections -->
                        <div class="bd-more-text">
                            <div class="bm-item">
                                <h4>{{ $event->section_1_title ?? 'Luxury & Comfort' }}</h4>
                                <p>{{ $event->section_1_text ?? 'Bellamonte Resort offers spacious and comfortable rooms with premium interiors, mountain-facing balconies, quality room service, and peaceful surroundings. Guests can enjoy a relaxing stay with modern amenities and excellent hospitality.' }}
                                </p>
                            </div>
                            <div class="bm-item">
                                <h4>{{ $event->section_2_title ?? 'Perfect Destination in Northern Pakistan' }}</h4>
                                <p>{{ $event->section_2_text ?? 'Shogran is one of the most beautiful tourist destinations in Pakistan. Staying at Bellamonte Resort allows visitors to experience nature, adventure, and luxury together. From bonfire nights to sightseeing tours, every moment becomes memorable.' }}
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Blog Details Section End -->

    <!-- Recommended -->
    <section class="recommend-blog-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h2>Recommended</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ($related as $rel)
                    <div class="col-md-4">
                        <div class="blog-item set-bg" data-setbg="{{ $rel->image_url }}"
                            style="background-image: url('{{ $rel->image_url }}'); background-size: cover; background-position: center;">
                            <div class="bi-text">
                                <span class="b-tag">{{ $rel->tag }}</span>
                                <h4>
                                    <a href="{{ route('event.detail', $rel) }}">{{ $rel->title }}</a>
                                </h4>
                                <div class="b-time">
                                    <i class="icon_clock_alt"></i>
                                    {{ $rel->event_date->format('d M, Y') }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- Recommend Blog Section End -->
@endsection
