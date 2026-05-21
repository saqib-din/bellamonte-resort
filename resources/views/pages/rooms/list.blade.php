@extends('layouts.landing')

@section('hero')
    <!-- Breadcrumb Section Begin -->
    <div class="breadcrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-text">
                        <h2>Our Rooms</h2>
                        <div class="bt-option">
                            <a href="{{ route('welcomepage') }}">Home</a>
                            <span>Rooms</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Section End -->
@endsection

@section('content')
    <!-- Rooms Section Begin -->
    <section class="rooms-section spad">
        <div class="container">

            <!-- 🔎 FILTER SECTION -->
            {{-- <form method="GET" class="row mb-4">

                <div class="col-md-4">
                    <input type="number" name="price" class="form-control" placeholder="Max Price"
                        value="{{ request('price') }}">
                </div>

                <div class="col-md-4">
                    <input type="number" name="capacity" class="form-control" placeholder="Min Capacity"
                        value="{{ request('capacity') }}">
                </div>

                <div class="col-md-4">
                    <button class="btn btn-primary w-100">Filter</button>
                </div>

            </form> --}}

            <div class="row">

                @forelse($rooms as $room)
                    <div class="col-lg-4 col-md-6">
                        <div class="room-item">

                            <img src="{{ $room->image ? asset('uploads/rooms/' . $room->image) : asset('landing-assets/img/room/room-1.jpg') }}"
                                alt="Room">

                            <div class="ri-text">
                                <h4>{{ $room->type }}</h4>

                                <h3>
                                  ₨ {{ $room->price_per_night }}
                                    <span>/Per night</span>
                                </h3>

                                <table>
                                    <tbody>
                                        <tr>
                                            <td class="r-o">Size:</td>
                                            <td>{{ $room->size ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="r-o">Capacity:</td>
                                            <td>{{ $room->capacity }} Persons</td>
                                        </tr>
                                        <tr>
                                            <td class="r-o">Bed:</td>
                                            <td>{{ $room->bed_type ?? 'Standard' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="r-o">Services:</td>
                                            <td>{{ $room->services ?? 'Wifi, TV, Bathroom' }}</td>
                                        </tr>
                                    </tbody>
                                </table>

                                <a href="{{ route('rooms.details', $room->id) }}" class="primary-btn">
                                    More Details
                                </a>
                            </div>

                        </div>
                    </div>

                @empty

                    {{-- 🔥 DUMMY DATA --}}
                    @php
                        $dummyRooms = [
                            ['type' => 'Premium King Room', 'price' => 159],
                            ['type' => 'Deluxe Room', 'price' => 139],
                            ['type' => 'Double Room', 'price' => 119],
                            ['type' => 'Luxury Room', 'price' => 199],
                            ['type' => 'View Room', 'price' => 179],
                            ['type' => 'Small Room', 'price' => 99],
                        ];
                    @endphp

                    @foreach ($dummyRooms as $dummy)
                        <div class="col-lg-4 col-md-6">
                            <div class="room-item">

                                <img src="{{ asset('landing-assets/img/room/room-1.jpg') }}" alt="Room">

                                <div class="ri-text">
                                    <h4>{{ $dummy['type'] }}</h4>

                                    <h3>
                                      ₨ {{ $dummy['price'] }}
                                        <span>/Per night</span>
                                    </h3>

                                    <table>
                                        <tbody>
                                            <tr>
                                                <td class="r-o">Size:</td>
                                                <td>30 ft</td>
                                            </tr>
                                            <tr>
                                                <td class="r-o">Capacity:</td>
                                                <td>2-3 Persons</td>
                                            </tr>
                                            <tr>
                                                <td class="r-o">Bed:</td>
                                                <td>King Bed</td>
                                            </tr>
                                            <tr>
                                                <td class="r-o">Services:</td>
                                                <td>Wifi, TV, Bathroom</td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <a href="{{ route('rooms.details') }}" class="primary-btn">More Details</a>
                                </div>

                            </div>
                        </div>
                    @endforeach
                @endforelse

            </div>

            <!-- 📄 PAGINATION -->
            <div class="room-pagination mt-4">
                {{ $rooms->links() }}
            </div>

        </div>
    </section>
    <!-- Rooms Section End -->
@endsection
