@extends('layouts.landing')

@section('hero')
    <section class="hero-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="hero-text">
                        <h1>Bellamonte Resort A Luxury Hotel</h1>
                        <p>
                            Experience luxury, comfort, and breathtaking mountain views at Bellamonte
                            Resort Shogran, Pakistan, offering premium rooms, peaceful stays, and
                            unforgettable hospitality for families, couples, and travelers.
                        </p>
                        {{-- <a href="#" class="primary-btn">Discover Now</a> --}}
                    </div>
                </div>
                {{-- <div class="col-xl-4 col-lg-5 offset-xl-2 offset-lg-1">
                    <div class="booking-form">
                        <h3>Booking Your Hotel</h3>
                        <form action="#">
                            <div class="check-date">
                                <label for="date-in">Check In:</label>
                                <input type="text" class="date-input" id="date-in" />
                                <i class="icon_calendar"></i>
                            </div>
                            <div class="check-date">
                                <label for="date-out">Check Out:</label>
                                <input type="text" class="date-input" id="date-out" />
                                <i class="icon_calendar"></i>
                            </div>
                            <div class="select-option">
                                <label for="guest">Guests:</label>
                                <select id="guest">
                                    <option value="">2 Adults</option>
                                    <option value="">3 Adults</option>
                                </select>
                            </div>
                            <div class="select-option">
                                <label for="room">Room:</label>
                                <select id="room">
                                    <option value="">1 Room</option>
                                    <option value="">2 Room</option>
                                </select>
                            </div>
                            <button type="submit">Check Availability</button>
                        </form>
                    </div>
                </div> --}}
            </div>
        </div>
        <div class="hero-slider owl-carousel">
            <div class="hs-item set-bg" data-setbg="{{ asset('landing-assets/img/hero/hero-1.jpg') }}">
            </div>

            <div class="hs-item set-bg" data-setbg="{{ asset('landing-assets/img/hero/hero-2.jpg') }}">
            </div>

            <div class="hs-item set-bg" data-setbg="{{ asset('landing-assets/img/hero/hero-3.jpg') }}">
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
                            <h2>Bellamonte Resort <br />Shogran Pakistan</h2>
                        </div>

                        <p class="f-para">
                            Bellamonte Resort is a premium hotel located in the beautiful hills of
                            Shogran, Pakistan. We provide luxury accommodation, peaceful surroundings,
                            and quality hospitality for travelers and families.
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

                                                <tr>
                                                    <td class="r-o">Services:</td>
                                                    <td>{{ $room->services ?? 'Wifi, Television, Bathroom' }}</td>
                                                </tr>
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
                                ['title' => 'Luxury Suite', 'price' => 299, 'img' => 'room-b4.jpg'],
                                ['title' => 'Premium King Room', 'price' => 259, 'img' => 'room-b2.jpg'],
                                ['title' => 'Deluxe Room', 'price' => 199, 'img' => 'room-b3.jpg'],
                                ['title' => 'Double Room', 'price' => 159, 'img' => 'room-b1.jpg'],
                            ];
                        @endphp

                        @foreach ($dummyRooms as $d)
                            <div class="col-lg-3 col-md-6">
                                <div class="hp-room-item set-bg"
                                    data-setbg="{{ asset('landing-assets/img/room/' . $d['img']) }}">

                                    <div class="hr-text">
                                        <h3>{{ $d['title'] }}</h3>
                                        <h2>${{ $d['price'] }}<span>/Per Night</span></h2>

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

                                        <a href="#" class="primary-btn">More Details</a>
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
                        ? asset('storage/' . $event->image)
                        : asset('landing-assets/img/blog/blog-1.jpg'))
                    : $event->image_url;

                $getLink = fn($event) => $useDb
                    ? route('event.detail', $event->id) // DB → ID pass
                : route('event.details'); // Dummy → no ID, controller fallback chalega @endphp

            <div class="row">

                {{-- ── Top 3 cards ── --}}
                @foreach ($top3 as $event)
                    <div class="col-lg-4 col-md-6">
                        <div class="blog-item set-bg" data-setbg="{{ $getImage($event) }}">
                            <div class="bi-text">
                                <span class="b-tag">{{ $event->tag }}</span>
                                <h4>
                                    <a href="{{ $getLink($event) }}">
                                        {{ $event->title }}
                                    </a>
                                </h4>
                                <div class="b-time">
                                    <i class="icon_clock_alt"></i>
                                    {{ \Carbon\Carbon::parse($event->event_date)->format('d M, Y') }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- ── Bottom row: wide + small ── --}}
                @php
                    $item4 = $bottom2->values()->get(0);
                    $item5 = $bottom2->values()->get(1);
                @endphp

                @if ($item4)
                    <div class="col-lg-8 col-md-12">
                        <div class="blog-item small-size set-bg" data-setbg="{{ $getImage($item4) }}">
                            <div class="bi-text">
                                <span class="b-tag">{{ $item4->tag }}</span>
                                <h4>
                                    <a href="{{ $getLink($item4) }}">
                                        {{ $item4->title }}
                                    </a>
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
                        <div class="blog-item small-size set-bg" data-setbg="{{ $getImage($item5) }}">
                            <div class="bi-text">
                                <span class="b-tag">{{ $item5->tag }}</span>
                                <h4>
                                    <a href="{{ $getLink($item5) }}">
                                        {{ $item5->title }}
                                    </a>
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
