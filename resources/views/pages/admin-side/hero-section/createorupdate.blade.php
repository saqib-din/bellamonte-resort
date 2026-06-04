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
                                <li class="breadcrumb-item">Hero Section</li>
                                <li class="breadcrumb-item">{{ $hero ? 'Edit' : 'Add' }}</li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">{{ $hero ? 'Edit Hero Section' : 'Add Hero Section' }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('components.alerts')

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ $hero ? route('hero.save', $hero->id) : route('hero.save') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Hero Title <span class="text-danger">*</span></label>
                                            <input type="text" name="hero_title" class="form-control"
                                                placeholder="Enter hero title"
                                                value="{{ old('hero_title', $hero->hero_title ?? '') }}" required>
                                            @error('hero_title')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Hero Subtitle <span class="text-danger">*</span></label>
                                            <input type="text" name="hero_subtitle" class="form-control"
                                                placeholder="Enter hero subtitle"
                                                value="{{ old('hero_subtitle', $hero->hero_subtitle ?? '') }}" required>
                                            @error('hero_subtitle')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div> --}}

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Description <span class="text-danger">*</span></label>
                                            <input type="text" name="description" class="form-control"
                                                placeholder="Enter description"
                                                value="{{ old('description', $hero->description ?? '') }}" required>
                                            @error('description')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Status </label>
                                            <select name="status" class="form-select" required>
                                                <option value="Active"
                                                    {{ old('status', $hero->status ?? '') == 'Active' ? 'selected' : '' }}>
                                                    Active</option>
                                                <option value="Inactive"
                                                    {{ old('status', $hero->status ?? '') == 'Inactive' ? 'selected' : '' }}>
                                                    Inactive</option>
                                            </select>
                                            @error('status')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Hero Image</label>
                                            <input class="form-control" type="file" name="image"
                                                accept=".jpg,.jpeg,.png">
                                            @if ($hero && $hero->image)
                                                <img src="{{ asset('uploads/hero/' . $hero->image) }}" width="100"
                                                    class="mt-2">
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-12 text-end">
                                        <button class="btn btn-primary">{{ $hero ? 'Update' : 'Submit' }}</button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
