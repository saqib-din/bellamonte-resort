@extends('layouts.landing')

@section('hero')
    <div class="breadcrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-text">
                        <h2>Blog & Events</h2>
                        <div class="bt-option">
                            <a href="{{ url('/') }}">Home</a>
                            <span>Blog Grid</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <!-- Blog Section Begin -->
    <section class="blog-section blog-page spad">
        <div class="container">
            <div class="row">

                @foreach ($events as $index => $event)
                    @php
                        // Support both Eloquent model and dummy array/object
                        $isModel = $event instanceof \App\Models\Event;
                        $id = $isModel ? $event->id : $event['id'] ?? null;
                        $title = $isModel ? $event->title : $event['title'] ?? '';
                        $tag = $isModel ? $event->tag : $event['tag'] ?? '';
                        $imageUrl = $isModel
                            ? $event->image_url
                            : $event['image_url'] ?? asset('landing-assets/img/blog/blog-1.jpg');
                        $date = $isModel ? $event->event_date : $event['event_date'] ?? now();
                        $detailUrl = $isModel ? route('event.detail', $id) : route('event.details');
                    @endphp

                    <div class="col-lg-4 col-md-6">
                        <div class="blog-item set-bg" data-setbg="{{ $imageUrl }}">
                            <div class="bi-text">
                                <span class="b-tag">{{ $tag }}</span>
                                <h4>
                                    <a href="{{ $detailUrl }}">{{ $title }}</a>
                                </h4>
                                <div class="b-time">
                                    <i class="icon_clock_alt"></i>
                                    {{ \Carbon\Carbon::parse($date)->format('d M, Y') }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </section>
    <!-- Blog Section End -->
@endsection
