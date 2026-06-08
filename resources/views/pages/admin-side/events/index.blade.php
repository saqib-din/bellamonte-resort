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
                                <li class="breadcrumb-item" aria-current="page">Events</li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">Events Management</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('components.alerts')

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div>
                                <h5 class="mb-0">All Events</h5>
                                <small class="text-muted">{{ $events->count() }} / 9 events added</small>
                            </div>
                            @if ($events->count() < 9)
                                <a href="{{ route('events.create') }}" class="btn btn-primary d-flex">
                                    <i class="ti ti-plus me-1"></i> Add Event
                                </a>
                            @else
                                <span class="badge bg-light-danger">Max 9 events reached</span>
                            @endif
                        </div>

                        <div class="card-body table-card">
                            @if ($events->count() == 0)
                                <div class="text-center py-5">
                                    <i class="ti ti-calendar-off" style="font-size:48px; color:#ccc;"></i>
                                    <h5 class="mt-3 text-muted">No Events Yet</h5>
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover" id="pc-dt-simple">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Image</th>
                                                <th>Title</th>
                                                <th>Tag</th>
                                                <th>Date</th>
                                                <th>Order</th>
                                                <th>Status</th>
                                                <th class="text-end">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($events as $event)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        <img src="{{ $event->image_url }}" alt="img"
                                                            style="width:60px; height:45px; object-fit:cover; border-radius:6px;">
                                                    </td>
                                                    <td>
                                                        <div style="max-width:220px; font-weight:600; font-size:13px;"
                                                            title="{{ $event->title }}">
                                                            @php
                                                                $words = explode(' ', $event->title);
                                                                echo count($words) > 4
                                                                    ? implode(' ', array_slice($words, 0, 4)) . '...'
                                                                    : $event->title;
                                                            @endphp
                                                        </div>
                                                    </td>
                                                    <td><span class="badge bg-light-primary">{{ $event->tag }}</span></td>
                                                    <td style="font-size:13px;">{{ $event->event_date->format('d M Y') }}
                                                    </td>
                                                    <td>{{ $event->sort_order }}</td>
                                                    <td>
                                                        <span
                                                            class="badge bg-light-{{ $event->is_active ? 'success' : 'danger' }}">
                                                            {{ $event->is_active ? 'Active' : 'Inactive' }}
                                                        </span>
                                                    </td>
                                                    <td class="text-end">
                                                        {{-- <a href="{{ route('event.detail', $event) }}" target="_blank"
                                                            class="avtar avtar-xs btn-link-secondary" title="View">
                                                            <i class="ti ti-eye f-18"></i>
                                                        </a> --}}
                                                        <a href="{{ route('events.edit', $event) }}"
                                                            class="avtar avtar-xs btn-link-secondary" title="Edit">
                                                            <i class="ti ti-edit f-18"></i>
                                                        </a>
                                                        {{-- <a href="#"
                                                            class="avtar avtar-xs btn-link-secondary bs-pass-para"
                                                            data-id="{{ $event->id }}" title="Delete">
                                                            <i class="ti ti-trash f-20"></i>
                                                        </a>
                                                        <form id="delete-form-{{ $event->id }}"
                                                            action="{{ route('events.destroy', $event) }}" method="POST"
                                                            style="display:none;">
                                                            @csrf @method('DELETE')
                                                        </form> --}}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (document.getElementById('pc-dt-simple')) {
                new simpleDatatables.DataTable('#pc-dt-simple', {
                    sortable: true,
                    searchable: true,
                    fixedHeight: true
                });
            }
        });
    </script>
@endpush
