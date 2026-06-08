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
                                    </tbody>
                                </table>

                                <a href="{{ route('rooms.details', $room) }}" class="primary-btn">
                                    More Details
                                </a>
                            </div>

                        </div>
                    </div>

                @empty

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

                                </div>

                            </div>
                        </div>
                    @endforeach
                @endforelse

            </div>

            {{-- Pagination (only shown when real rooms exist) --}}
            @if ($rooms->hasPages())
                <div class="room-pagination-wrapper mt-4">
                    <div class="room-pagination">
                        {{ $rooms->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif

        </div>
    </section>
    <!-- Rooms Section End -->

    <style>
        .room-pagination .pagination-info,
        .room-pagination nav p,
        .room-pagination nav>div:first-child {
            display: none !important;
        }

        .room-pagination-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 2rem;
        }

        .room-pagination .pagination {
            gap: 10px;
            margin: 0;
            padding: 12px;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        }

        .room-pagination .page-item {
            list-style: none;
        }

        .room-pagination .page-link {
            width: 44px;
            height: 44px;
            border: 0;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 600;
            color: #495057;
            background: #f8f9fa;
            transition: all .25s ease;
            box-shadow: none;
        }

        .room-pagination .page-link svg {
            width: 14px;
            height: 14px;
            display: inline-block;
            vertical-align: middle;
        }

        .room-pagination .page-link:hover {
            background: #dfa974;
            color: #fff;
            transform: translateY(-2px);
        }

        .room-pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #dfa974, #dfa974);
            color: #fff;
            border: none;
            box-shadow: 0 6px 18px rgba(162, 188, 120, 0.35);
        }

        .room-pagination .page-item.disabled .page-link {
            background: #f1f3f5;
            color: #adb5bd;
            cursor: not-allowed;
            opacity: 0.8;
        }

        .room-pagination .page-item:first-child .page-link,
        .room-pagination .page-item:last-child .page-link {
            width: auto;
            min-width: 90px;
            padding: 0 16px;
        }

        @media (max-width: 576px) {
            .room-pagination .pagination {
                gap: 6px;
                padding: 8px;
            }

            .room-pagination .page-link {
                width: 38px;
                height: 38px;
                font-size: 13px;
            }

            .room-pagination .page-item:first-child .page-link,
            .room-pagination .page-item:last-child .page-link {
                min-width: 70px;
                padding: 0 10px;
            }
        }
    </style>
@endsection
