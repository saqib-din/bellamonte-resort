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
    <section class="blog-section blog-page spad">
        <div class="container">
            <div class="row">

                @forelse ($events as $event)
                    @php
                        $img = $event->image
                            ? asset('uploads/events/' . $event->image)
                            : asset('landing-assets/img/blog/blog-1.jpg');
                    @endphp
                    <div class="col-lg-4 col-md-6">
                        <div class="blog-item set-bg" data-setbg="{{ $img }}"
                            style="background-image: url('{{ $img }}'); background-size: cover; background-position: center;">
                            <div class="bi-text">
                                <span class="b-tag">{{ $event->tag }}</span>
                                <h4>
                                    <a href="{{ route('event.detail', $event->id) }}">{{ $event->title }}</a>
                                </h4>
                                <div class="b-time">
                                    <i class="icon_clock_alt"></i>
                                    {{ $event->event_date->format('d M, Y') }}
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    {{-- ... --}}
                @endforelse

            </div>
        </div>
    </section>
@endsection
