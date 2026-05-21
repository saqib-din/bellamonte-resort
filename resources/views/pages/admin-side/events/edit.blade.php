{{--
    resources/views/admin/events/create.blade.php
    resources/views/admin/events/edit.blade.php
    (Same form — just change @isset($event) for edit mode)
--}}
@extends('layouts.admin')

@section('content')
    <div class="pc-container">
        <div class="pc-content">

            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('events.index') }}">Events</a></li>
                                <li class="breadcrumb-item" aria-current="page">{{ isset($event) ? 'Edit' : 'Create' }}</li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">{{ isset($event) ? 'Edit Event' : 'Add New Event' }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('components.alerts')

            <form action="{{ isset($event) ? route('events.update', $event->id) : route('events.store') }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @if (isset($event))
                    @method('PUT')
                @endif

                <div class="row">

                    {{-- ── Left Column ── --}}
                    <div class="col-lg-8">

                        {{-- Basic Info --}}
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="mb-0">Basic Information</h5>
                            </div>
                            <div class="card-body">

                                <div class="mb-3">
                                    <label class="form-label">Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title"
                                        class="form-control @error('title') is-invalid @enderror"
                                        value="{{ old('title', $event->title ?? '') }}" placeholder="Event title" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tag <span class="text-danger">*</span></label>
                                        <input type="text" name="tag"
                                            class="form-control @error('tag') is-invalid @enderror"
                                            value="{{ old('tag', $event->tag ?? '') }}"
                                            placeholder="e.g. Travel, Nature, Event" required>
                                        @error('tag')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Event Date <span class="text-danger">*</span></label>
                                        <input type="date" name="event_date"
                                            class="form-control @error('event_date') is-invalid @enderror"
                                            value="{{ old('event_date', isset($event) ? $event->event_date->format('Y-m-d') : '') }}"
                                            required>
                                        @error('event_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Short Description</label>
                                    <textarea name="short_description" class="form-control" rows="2" placeholder="Brief summary (shown in listing)">{{ old('short_description', $event->short_description ?? '') }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Full Description</label>
                                    <textarea name="description" class="form-control" rows="4"
                                        placeholder="Detailed description for event detail page">{{ old('description', $event->description ?? '') }}</textarea>
                                </div>

                            </div>
                        </div>

                        {{-- Detail Page Sections --}}
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="mb-0">Detail Page Sections</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Section 1 Title</label>
                                    <input type="text" name="section_1_title" class="form-control"
                                        value="{{ old('section_1_title', $event->section_1_title ?? '') }}"
                                        placeholder="e.g. Luxury & Comfort">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Section 1 Text</label>
                                    <textarea name="section_1_text" class="form-control" rows="3">{{ old('section_1_text', $event->section_1_text ?? '') }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Section 2 Title</label>
                                    <input type="text" name="section_2_title" class="form-control"
                                        value="{{ old('section_2_title', $event->section_2_title ?? '') }}"
                                        placeholder="e.g. Perfect Destination">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Section 2 Text</label>
                                    <textarea name="section_2_text" class="form-control" rows="3">{{ old('section_2_text', $event->section_2_text ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Detail Images --}}
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="mb-0">Detail Page Images (3 images)</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach ([1, 2, 3] as $n)
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Image {{ $n }}</label>
                                            @if (isset($event) && $event->{"detail_image_{$n}"})
                                                <img src="{{ $event->{"detail_image_{$n}_url"} }}"
                                                    class="img-fluid mb-2 rounded"
                                                    style="max-height:100px; object-fit:cover;">
                                            @endif
                                            <input type="file" name="detail_image_{{ $n }}"
                                                class="form-control" accept="image/*">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- ── Right Column ── --}}
                    <div class="col-lg-4">

                        {{-- Main Image --}}
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="mb-0">Main Card Image <span class="text-danger">*</span></h5>
                            </div>
                            <div class="card-body">
                                @if (isset($event) && $event->image)
                                    <img src="{{ $event->image_url }}" class="img-fluid mb-2 rounded"
                                        style="max-height:160px; object-fit:cover; width:100%;">
                                @endif
                                <input type="file" name="image"
                                    class="form-control @error('image') is-invalid @enderror" accept="image/*"
                                    {{ isset($event) ? '' : 'required' }}>
                                <small class="text-muted">JPG, PNG, WEBP — max 2MB</small>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Settings --}}
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="mb-0">Settings</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Sort Order</label>
                                    <input type="number" name="sort_order" class="form-control" min="0"
                                        value="{{ old('sort_order', $event->sort_order ?? 0) }}">
                                    <small class="text-muted">Lower number = shown first</small>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                        {{ old('is_active', $event->is_active ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label">Active (show on website)</label>
                                </div>
                            </div>
                        </div>

                        {{-- Buttons --}}
                        <div class="card">
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-device-floppy me-1"></i>
                                        {{ isset($event) ? 'Update Event' : 'Save Event' }}
                                    </button>
                                    <a href="{{ route('events.index') }}" class="btn btn-light-secondary">
                                        Cancel
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </form>

        </div>
    </div>
@endsection
