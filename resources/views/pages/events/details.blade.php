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
    @php
        $isModel = $event instanceof \App\Models\Event;
        $title = $isModel ? $event->title : $event->title;
        $tag = $isModel ? $event->tag : $event->tag;
        $date = $isModel ? $event->event_date : $event->event_date;
        $description = $isModel ? $event->description : $event->description;
        $shortDesc = $isModel ? $event->short_description : $event->short_description;
        $img1 = $isModel ? $event->detail_image_1_url : $event->detail_image_1_url;
        $img2 = $isModel ? $event->detail_image_2_url : $event->detail_image_2_url;
        $img3 = $isModel ? $event->detail_image_3_url : $event->detail_image_3_url;
        $sec1Title = $isModel ? $event->section_1_title : $event->section_1_title;
        $sec1Text = $isModel ? $event->section_1_text : $event->section_1_text;
        $sec2Title = $isModel ? $event->section_2_title : $event->section_2_title;
        $sec2Text = $isModel ? $event->section_2_text : $event->section_2_text;
        $heroImage = $isModel
            ? $event->image_url ?? asset('landing-assets/img/blog/blog-details/blog-details-hero.jpg')
            : asset('landing-assets/img/blog/blog-details/blog-details-hero.jpg');
    @endphp

    <!-- Blog Details Hero -->
    <section class="blog-details-hero set-bg" data-setbg="{{ $heroImage }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="bd-hero-text">
                        <span>{{ $tag }}</span>
                        <h2>{{ $title }}</h2>
                        <ul>
                            <li class="b-time">
                                <i class="icon_clock_alt"></i>
                                {{ \Carbon\Carbon::parse($date)->format('d M, Y') }}
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
                            @if ($description)
                                <p>{{ $description }}</p>
                            @else
                                <p>Nestled in the breathtaking hills of Shogran, Bellamonte Resort offers a perfect
                                    combination of luxury, comfort, and natural beauty. Guests can enjoy peaceful mountain
                                    views, fresh air, and a relaxing environment away from the busy city life.</p>
                            @endif

                            @if ($shortDesc)
                                <p>{{ $shortDesc }}</p>
                            @else
                                <p>Whether you are planning a family vacation, honeymoon trip, corporate retreat, or weekend
                                    getaway, Bellamonte Resort provides premium rooms, exceptional hospitality, and
                                    unforgettable experiences.</p>
                            @endif

                            <p>Visitors can also explore nearby tourist attractions, hiking trails, forests, and beautiful
                                landscapes of Kaghan Valley while enjoying the peaceful atmosphere of the resort.</p>
                        </div>

                        <!-- Detail Images -->
                        <div class="bd-pic">
                            <div class="bp-item">
                                <img src="{{ $img1 }}" alt="Event Image 1">
                            </div>
                            <div class="bp-item">
                                <img src="{{ $img2 }}" alt="Event Image 2">
                            </div>
                            <div class="bp-item">
                                <img src="{{ $img3 }}" alt="Event Image 3">
                            </div>
                        </div>

                        <!-- Sections -->
                        <div class="bd-more-text">
                            <div class="bm-item">
                                <h4>{{ $sec1Title ?? 'Luxury & Comfort' }}</h4>
                                <p>{{ $sec1Text ?? 'Bellamonte Resort offers spacious and comfortable rooms with premium interiors, mountain-facing balconies, quality room service, and peaceful surroundings. Guests can enjoy a relaxing stay with modern amenities and excellent hospitality.' }}
                                </p>
                            </div>
                            <div class="bm-item">
                                <h4>{{ $sec2Title ?? 'Perfect Destination in Northern Pakistan' }}</h4>
                                <p>{{ $sec2Text ?? 'Shogran is one of the most beautiful tourist destinations in Pakistan. Staying at Bellamonte Resort allows visitors to experience nature, adventure, and luxury together. From bonfire nights to sightseeing tours, every moment becomes memorable.' }}
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
                    @php
                        $relIsModel = $rel instanceof \App\Models\Event;
                        $relId = $relIsModel ? $rel->id : $rel->id ?? null;
                        $relTitle = $relIsModel ? $rel->title : $rel->title ?? '';
                        $relTag = $relIsModel ? $rel->tag : $rel->tag ?? '';
                        $relImageUrl = $relIsModel
                            ? $rel->image_url
                            : $rel->image_url ?? asset('landing-assets/img/blog/blog-1.jpg');
                        $relDate = $relIsModel ? $rel->event_date : $rel->event_date ?? now();
                        $relUrl = $relIsModel ? route('event.detail', $relId) : route('event.details');
                    @endphp
                    <div class="col-md-4">
                        <div class="blog-item set-bg" data-setbg="{{ $relImageUrl }}">
                            <div class="bi-text">
                                <span class="b-tag">{{ $relTag }}</span>
                                <h4><a href="{{ $relUrl }}">{{ $relTitle }}</a></h4>
                                <div class="b-time">
                                    <i class="icon_clock_alt"></i>
                                    {{ \Carbon\Carbon::parse($relDate)->format('d M, Y') }}
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
