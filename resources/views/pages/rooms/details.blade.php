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
    <!-- Room Details Section Begin -->
    <section class="room-details-section spad">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-lg-8">
                    <div class="room-details-item">
                        <img src="{{ $room->image ? asset('uploads/rooms/' . $room->image) : asset('landing-assets/img/room/room-details.jpg') }}"
                            alt="">

                        <div class="rd-text">
                            <div class="rd-title">
                                <h3>{{ $room->type ?? 'Premium King Room' }}</h3>

                                <div class="rdt-right">
                                    <div class="rating">
                                        <i class="icon_star"></i>
                                        <i class="icon_star"></i>
                                        <i class="icon_star"></i>
                                        <i class="icon_star"></i>
                                        <i class="icon_star-half_alt"></i>
                                    </div>
                                </div>
                            </div>

                            <h2>
                                ₨ {{ number_format($room->price_per_night ?? 159) }}
                                <span>/Per Night</span>
                            </h2>

                            <table>
                                <tbody>
                                    <tr>
                                        <td class="r-o">Size:</td>
                                        <td>{{ $room->size ?? '30 ft' }}</td>
                                    </tr>

                                    <tr>
                                        <td class="r-o">Capacity:</td>
                                        <td>Max person {{ $room->capacity ?? 5 }}</td>
                                    </tr>

                                    <tr>
                                        <td class="r-o">Bed:</td>
                                        <td>{{ $room->bed_type ?? 'King Beds' }}</td>
                                    </tr>

                                    <tr>
                                        <td class="r-o">Services:</td>
                                        <td>{{ $room->services ?? 'Wifi, Television, Bathroom' }}</td>
                                    </tr>

                                    <tr>
                                        <td class="r-o">Room Status:</td>
                                        <td>{{ $room->status ?? 'Wifi, Television, Bathroom' }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <p class="f-para">
                                {{ $room->description ?? 'Comfortable and well-furnished room with all basic amenities for a relaxing stay in Shogran, Pakistan.' }}
                            </p>
                        </div>
                    </div>
                    <div class="rd-reviews">
                        <h4>Reviews</h4>
                        <div class="review-item">
                            <div class="ri-pic">
                                <img src="{{ asset('landing-assets/img/room/avatar/avatar-2.jpg') }}" alt="Avatar">
                            </div>

                            <div class="ri-text">
                                <span>10 May 2026</span>

                                <div class="rating">
                                    <i class="icon_star"></i>
                                    <i class="icon_star"></i>
                                    <i class="icon_star"></i>
                                    <i class="icon_star"></i>
                                    <i class="icon_star-half_alt"></i>
                                </div>

                                <h5>Sarah Williams</h5>

                                <p>
                                    Bellamonte Resort is a beautiful hotel in Shogran with breathtaking mountain views.
                                    The environment is calm and perfect for families. Food quality was good and staff
                                    was friendly. We enjoyed every moment of our stay.
                                </p>
                            </div>
                        </div>
                        <div class="review-item">
                            <div class="ri-pic">
                                <img src="{{ asset('landing-assets/img/room/avatar/avatar-1.jpg') }}" alt="Avatar">
                            </div>

                            <div class="ri-text">
                                <span>12 May 2026</span>

                                <div class="rating">
                                    <i class="icon_star"></i>
                                    <i class="icon_star"></i>
                                    <i class="icon_star"></i>
                                    <i class="icon_star"></i>
                                    <i class="icon_star"></i>
                                </div>

                                <h5>Ahmed Khan</h5>

                                <p>
                                    Our stay at Bellamonte Resort in Shogran, Pakistan was absolutely amazing.
                                    The location is peaceful, surrounded by beautiful mountains and nature.
                                    Rooms were clean, comfortable, and well maintained. Staff was very cooperative
                                    and service was excellent. Highly recommended for a relaxing vacation.
                                </p>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="review-add">
                        <h4>Add Review</h4>
                        <form action="#" class="ra-form">
                            <div class="row">
                                <div class="col-lg-6">
                                    <input type="text" placeholder="Name*">
                                </div>
                                <div class="col-lg-6">
                                    <input type="text" placeholder="Email*">
                                </div>
                                <div class="col-lg-12">
                                    <div>
                                        <h5>You Rating:</h5>
                                        <div class="rating">
                                            <i class="icon_star"></i>
                                            <i class="icon_star"></i>
                                            <i class="icon_star"></i>
                                            <i class="icon_star"></i>
                                            <i class="icon_star-half_alt"></i>
                                        </div>
                                    </div>
                                    <textarea placeholder="Your Review"></textarea>
                                    <button type="submit">Submit Now</button>
                                </div>
                            </div>
                        </form>
                    </div> --}}
                </div>
                {{-- <div class="col-lg-4">
                    <div class="room-booking">
                        <h3>Your Reservation</h3>
                        <form action="#">
                            <div class="check-date">
                                <label for="date-in">Check In:</label>
                                <input type="text" class="date-input" id="date-in">
                                <i class="icon_calendar"></i>
                            </div>
                            <div class="check-date">
                                <label for="date-out">Check Out:</label>
                                <input type="text" class="date-input" id="date-out">
                                <i class="icon_calendar"></i>
                            </div>
                            <div class="select-option">
                                <label for="guest">Guests:</label>
                                <select id="guest">
                                    <option value="">3 Adults</option>
                                </select>
                            </div>
                            <div class="select-option">
                                <label for="room">Room:</label>
                                <select id="room">
                                    <option value="">1 Room</option>
                                </select>
                            </div>
                            <button type="submit">Check Availability</button>
                        </form>
                    </div>
                </div> --}}
            </div>
        </div>
    </section>
    <!-- Room Details Section End -->
@endsection
