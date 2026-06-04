@extends('layouts.landing')

@section('hero')
    <section class="hero-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="hero-text">
                        <h1>{{ $hero->hero_title ?? 'Bellamonte Resort A Luxury Hotel' }}</h1>

                        <p>
                            {{ $hero->description ?? 'Experience luxury, comfort, and breathtaking mountain views at Bellamonte Resort Shogran, Pakistan, offering premium rooms, peaceful stays, and unforgettable hospitality for families, couples, and travelers.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="hero-slider owl-carousel">
            <div class="hs-item set-bg"
                style="height:770px;background-size:cover;background-position:center center;background-repeat:no-repeat;"
                data-setbg="{{ !empty($hero?->image) ? asset('uploads/hero/' . $hero->image) : asset('landing-assets/img/hero/hero-1.jpg') }}">
            </div>
        </div>
    </section>
@endsection

@section('content')
    <!-- About Us Section Begin -->
    <section class="aboutus-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="about-text">
                        <div class="section-title">
                            <span>About Us</span>
                            <h2>{{ $data['welcome_title'] ?? 'Welcome To Bellamonte Resort.' }}</h2>

                        </div>

                        <p class="f-para">
                            {{ $data['welcome_description'] ?? 'Located in the beautiful hills of Shogran, Pakistan, Bellamonte Resort offers luxury accommodation, breathtaking mountain views, peaceful surroundings, and comfortable rooms for a memorable stay with family and friends.' }}
                        </p>

                        <p class="s-para">
                            Whether you are planning a relaxing vacation, honeymoon, or family trip,
                            our resort offers comfortable rooms, mountain views, delicious dining,
                            and a memorable stay in nature.
                        </p>
                        <a href="{{ route('about.us') }}" class="primary-btn about-btn">Read More</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about-pic">
                        <div class="row">
                            <div class="col-sm-6">
                                <img src="{{ asset('landing-assets/img/shogran.webp') }}" alt="Bellamonte Resort"
                                    style="
            height: 23em;
            width: 100%;
            object-fit: cover;
            border-radius: 5px;
            display: block;
        " />
                            </div>

                            <div class="col-sm-6">
                                <img src="{{ asset('landing-assets/img/shogran2.jpg') }}" alt=""
                                    style="
            height: 23em;
            width: 100%;
            object-fit: cover;
            border-radius: 5px;
            display: block;
        " />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- About Us Section End -->

    <!-- Services Section End -->
    <section class="services-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <span>What We Offer</span>
                        <h2>Discover Our Premium Services</h2>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-lg-4 col-sm-6">
                    <div class="service-item">
                        <i class="flaticon-036-parking"></i>
                        <h4>Free Parking</h4>
                        <p>
                            Secure and spacious parking area available for all guests to ensure
                            a comfortable and stress-free stay at Bellamonte Resort.
                        </p>
                    </div>
                </div>

                <div class="col-lg-4 col-sm-6">
                    <div class="service-item">
                        <i class="flaticon-033-dinner"></i>
                        <h4>Restaurant & Dining</h4>
                        <p>
                            Enjoy delicious Pakistani and continental meals prepared fresh with
                            quality ingredients while overlooking the beautiful mountains of Shogran.
                        </p>
                    </div>
                </div>

                <div class="col-lg-4 col-sm-6">
                    <div class="service-item">
                        <i class="flaticon-026-bed"></i>
                        <h4>Luxury Rooms</h4>
                        <p>
                            Experience comfortable and elegant rooms designed with modern
                            facilities, cozy interiors, and breathtaking valley views.
                        </p>
                    </div>
                </div>

                <div class="col-lg-4 col-sm-6">
                    <div class="service-item">
                        <i class="flaticon-024-towel"></i>
                        <h4>Laundry Service</h4>
                        <p>
                            Professional laundry and housekeeping services are available to
                            keep your stay clean, fresh, and convenient.
                        </p>
                    </div>
                </div>

                <div class="col-lg-4 col-sm-6">
                    <div class="service-item">
                        <i class="flaticon-044-clock-1"></i>
                        <h4>24/7 Room Service</h4>
                        <p>
                            Our dedicated staff is available around the clock to provide quick
                            assistance and excellent hospitality for every guest.
                        </p>
                    </div>
                </div>

                <div class="col-lg-4 col-sm-6">
                    <div class="service-item">
                        <i class="flaticon-012-cocktail"></i>
                        <h4>Family & Tourist Stay</h4>
                        <p>
                            Whether you are visiting with family, friends, or as a tourist,
                            Bellamonte Resort offers a peaceful and memorable mountain getaway.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- Services Section End -->

    <!-- Home Room Section Begin -->
    <section class="hp-room-section">
        <div class="container-fluid">
            <div class="hp-room-items">
                <div class="row">

                    @php
                        $roomsData = $rooms->sortByDesc('price_per_night')->take(4);
                    @endphp

                    @if ($roomsData->count() > 0)
                        @foreach ($roomsData as $room)
                            <div class="col-lg-3 col-md-6">
                                <div class="hp-room-item set-bg"
                                    data-setbg="{{ $room->image ? asset('uploads/rooms/' . $room->image) : asset('landing-assets/img/room/room-b1.jpg') }}">

                                    <div class="hr-text">
                                        <h3>{{ $room->type }}</h3>
                                        <h2>{{ number_format($room->price_per_night) }} Pkr<span>/Per Night</span></h2>

                                        <table>
                                            <tbody>
                                                <tr>
                                                    <td class="r-o">Size:</td>
                                                    <td>{{ $room->size ?? '30 ft' }}</td>
                                                </tr>

                                                <tr>
                                                    <td class="r-o">Capacity:</td>
                                                    <td>Max person {{ $room->capacity }}</td>
                                                </tr>

                                                <tr>
                                                    <td class="r-o">Bed:</td>
                                                    <td>{{ $room->bed_type ?? 'King Beds' }}</td>
                                                </tr>

                                                {{-- <tr>
                                                    <td class="r-o">Services:</td>
                                                    <td>{{ $room->services ?? 'Wifi, Television, Bathroom' }}</td>
                                                </tr> --}}
                                            </tbody>
                                        </table>

                                        <a href="{{ route('rooms.details', $room->id) }}" class="primary-btn">
                                            More Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        {{-- 🔥 Dummy fallback (same design) --}}
                        @php
                            $dummyRooms = [
                                ['title' => 'Luxury Suite', 'price' => 24499, 'img' => 'room-b4.jpg'],
                                ['title' => 'Premium King Room', 'price' => 25559, 'img' => 'room-b2.jpg'],
                                ['title' => 'Deluxe Room', 'price' => 14499, 'img' => 'room-b3.jpg'],
                                ['title' => 'Double Room', 'price' => 18859, 'img' => 'room-b1.jpg'],
                            ];
                        @endphp

                        @foreach ($dummyRooms as $d)
                            <div class="col-lg-3 col-md-6">
                                <div class="hp-room-item set-bg"
                                    data-setbg="{{ asset('landing-assets/img/room/' . $d['img']) }}">

                                    <div class="hr-text">
                                        <h3>{{ $d['title'] }}</h3>
                                        <h2>Pkr {{ $d['price'] }}<span>/Per Night</span></h2>

                                        <table>
                                            <tbody>
                                                <tr>
                                                    <td class="r-o">Size:</td>
                                                    <td>30 ft</td>
                                                </tr>

                                                <tr>
                                                    <td class="r-o">Capacity:</td>
                                                    <td>Max person 5</td>
                                                </tr>

                                                <tr>
                                                    <td class="r-o">Bed:</td>
                                                    <td>King Beds</td>
                                                </tr>

                                                <tr>
                                                    <td class="r-o">Services:</td>
                                                    <td>Wifi, Television, Bathroom...</td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <a href="{{ route('rooms.list') }}" class="primary-btn">More Details</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif

                </div>
            </div>
        </div>
    </section>
    <!-- Home Room Section End -->

    <!-- Testimonial Section Begin -->
    <section class="testimonial-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <span>Testimonials</span>
                        <h2>What Our Guests Say?</h2>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="testimonial-slider owl-carousel">

                        <div class="ts-item">
                            <p>
                                Our stay at Bellamonte Resort was truly unforgettable. The
                                mountain views, comfortable rooms, and excellent hospitality
                                made our family trip to Shogran peaceful and memorable.
                            </p>

                            <div class="ti-author">
                                <div class="rating">
                                    <i class="icon_star"></i>
                                    <i class="icon_star"></i>
                                    <i class="icon_star"></i>
                                    <i class="icon_star"></i>
                                    <i class="icon_star-half_alt"></i>
                                </div>
                                <h5>- Ahmed Khan</h5>
                            </div>

                            <img src="{{ asset('landing-assets/img/testimonial-logo.png') }}" alt="" />
                        </div>

                        <div class="ts-item">
                            <p>
                                Bellamonte Resort offers the perfect combination of luxury and
                                nature. The staff was friendly, the food was delicious, and the
                                beautiful environment made our vacation amazing.
                            </p>

                            <div class="ti-author">
                                <div class="rating">
                                    <i class="icon_star"></i>
                                    <i class="icon_star"></i>
                                    <i class="icon_star"></i>
                                    <i class="icon_star"></i>
                                    <i class="icon_star-half_alt"></i>
                                </div>
                                <h5>- Sarah Ali</h5>
                            </div>

                            <img src="{{ asset('landing-assets/img/testimonial-logo.png') }}" alt="" />
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Testimonial Section End -->

    <!-- Blog Section Begin -->
    <section class="blog-section spad">
        <div class="container">

            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <span>Bellamonte Resort News</span>
                        <h2>Our Blog & Events</h2>
                    </div>
                </div>
            </div>

            @php
                $blogItems = $events->take(5);
                $top3 = $blogItems->take(3);
                $bottom2 = $blogItems->slice(3);

                $getImage = fn($event) => $useDb
                    ? ($event->image
                        ? asset('uploads/events/' . $event->image)
                        : asset('landing-assets/img/blog/blog-1.jpg'))
                    : $event->image_url;

                $getLink = fn($event) => $useDb ? route('event.detail', $event->id) : route('event.details');
            @endphp

            <div class="row">

                {{-- ── Top 3 cards ── --}}
                @foreach ($top3 as $event)
                    <div class="col-lg-4 col-md-6">
                        <div class="blog-item set-bg" data-setbg="{{ $getImage($event) }}"
                            style="background-image: url('{{ $getImage($event) }}'); background-size: cover; background-position: center;">
                            <div class="bi-text">
                                <span class="b-tag">{{ $event->tag }}</span>
                                <h4>
                                    <a href="{{ $getLink($event) }}">{{ $event->title }}</a>
                                </h4>
                                <div class="b-time">
                                    <i class="icon_clock_alt"></i>
                                    {{ \Carbon\Carbon::parse($event->event_date)->format('d M, Y') }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- ── Bottom row ── --}}
                @php
                    $item4 = $bottom2->values()->get(0);
                    $item5 = $bottom2->values()->get(1);
                @endphp

                @if ($item4)
                    <div class="col-lg-8 col-md-12">
                        <div class="blog-item small-size set-bg" data-setbg="{{ $getImage($item4) }}"
                            style="background-image: url('{{ $getImage($item4) }}'); background-size: cover; background-position: center;">
                            <div class="bi-text">
                                <span class="b-tag">{{ $item4->tag }}</span>
                                <h4>
                                    <a href="{{ $getLink($item4) }}">{{ $item4->title }}</a>
                                </h4>
                                <div class="b-time">
                                    <i class="icon_clock_alt"></i>
                                    {{ \Carbon\Carbon::parse($item4->event_date)->format('d M, Y') }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($item5)
                    <div class="col-lg-4 col-md-12">
                        <div class="blog-item small-size set-bg" data-setbg="{{ $getImage($item5) }}"
                            style="background-image: url('{{ $getImage($item5) }}'); background-size: cover; background-position: center;">
                            <div class="bi-text">
                                <span class="b-tag">{{ $item5->tag }}</span>
                                <h4>
                                    <a href="{{ $getLink($item5) }}">{{ $item5->title }}</a>
                                </h4>
                                <div class="b-time">
                                    <i class="icon_clock_alt"></i>
                                    {{ \Carbon\Carbon::parse($item5->event_date)->format('d M, Y') }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </section>
    <!-- Blog Section End -->
@endsection
