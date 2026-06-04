@extends('layouts.admin')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/hero') }}">Home</a></li>
                                <li class="breadcrumb-item" aria-current="page">Hero</li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">Hero Section</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('components.alerts')

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-sm-flex align-items-center justify-content-between">
                                <h5 class="mb-3 mb-sm-0">Hero Section</h5>
                                <div>
                                    <a href="{{ route('hero.create') }}" class="btn btn-primary">Add Hero</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body table-card">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Title</th>
                                            {{-- <th>Subtitle</th> --}}
                                            <th>Description</th>
                                            <th>Status</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($heroSections as $hero)
                                            <tr>
                                                <td>{{ $hero->id }}</td>
                                                <td>
                                                    <div class="row">
                                                        <div class="col-auto pe-0">
                                                            @if ($hero->image)
                                                                <img src="{{ asset('uploads/hero/' . $hero->image) }}"
                                                                    style="width: 40px; height: 40px;"
                                                                    class="rounded-circle" alt="Hero Image">
                                                            @else
                                                                <img src="{{ asset('admin/assets/images/user/sms.png') }}"
                                                                    alt="img" class="rounded-circle"
                                                                    style="width: 40px; height: 40px;" />
                                                            @endif
                                                        </div>
                                                        <div class="col justify-content-center">
                                                            @php
                                                                $titleWords = explode(' ', $hero->hero_title ?? '');
                                                                $titleChunks = array_chunk($titleWords, 5);
                                                            @endphp

                                                            @foreach ($titleChunks as $chunk)
                                                                {{ implode(' ', $chunk) }}<br>
                                                            @endforeach

                                                        </div>
                                                    </div>
                                                </td>
                                                {{-- <td>
                                                    @php
                                                        $subtitleWords = explode(' ', $hero->hero_subtitle ?? '-');
                                                        $subtitleChunks = array_chunk($subtitleWords, 5);
                                                    @endphp

                                                    @foreach ($subtitleChunks as $chunk)
                                                        {{ implode(' ', $chunk) }}<br>
                                                    @endforeach
                                                </td> --}}
                                                <td>
                                                    @php
                                                        $words = explode(' ', $hero->description ?? '');
                                                        $chunks = array_chunk($words, 5); // Split into arrays of 5 words each
                                                    @endphp

                                                    @foreach ($chunks as $chunk)
                                                        {{ implode(' ', $chunk) }}<br>
                                                    @endforeach
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $hero->status == 'Active' ? 'light-success' : 'light-danger' }}">
                                                        {{ $hero->status }}
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    {{-- <a href="{{ route('hero.show', $hero->id) }}"
                                                        class="avtar avtar-xs btn-link-secondary" data-bs-hover="tooltip"
                                                        title="Edit">
                                                        <i class="ti ti-edit f-20"></i>
                                                    </a> --}}
                                                    <a href="{{ route('hero.edit', $hero->id) }}"
                                                        class="avtar avtar-xs btn-link-secondary" data-bs-hover="tooltip"
                                                        title="Edit">
                                                        <i class="ti ti-edit f-20"></i>
                                                    </a>
                                                    <a href="#" class="avtar avtar-xs btn-link-secondary bs-pass-para"
                                                        data-id="{{ $hero->id }}" title="Delete">
                                                        <i class="ti ti-trash f-20"></i>
                                                    </a>

                                                    <!-- Hidden form -->
                                                    <form id="delete-form-{{ $hero->id }}"
                                                        action="{{ route('hero.delete', $hero->id) }}" method="POST"
                                                        style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
