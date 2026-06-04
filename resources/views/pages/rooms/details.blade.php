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
                </div>
            </div>
        </div>
    </section>
    <!-- Room Details Section End -->
@endsection
