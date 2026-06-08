@extends('layouts.admin')

@section('content')
    <div class="pc-container">
        <div class="pc-content">

            {{-- ── Breadcrumb ── --}}
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="#">Contacts</a></li>
                                <li class="breadcrumb-item" aria-current="page">List</li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">Contacts List</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Alerts ── --}}
            @include('components.alerts')

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Contacts List</h5>
                        </div>
                        <div class="card-body table-card">

                            {{-- ── Empty State ── --}}
                            @if ($contacts->count() == 0)
                                <div class="text-center"
                                    style="min-height: 300px; display:flex; flex-direction:column; align-items:center; justify-content:center;">
                                    <img src="{{ asset('admin/assets/images/application/img-empty-mail.png') }}"
                                        alt="No mail" class="img-fluid mb-4" style="max-width:200px;">
                                    <h2><b>There is No Mail</b></h2>
                                </div>

                                {{-- ── Contacts Table ── --}}
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover" id="pc-dt-simple">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Subject</th>
                                                <th>Phone</th>
                                                <th>Replied</th>
                                                <th class="text-end">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($contacts as $contact)
                                                <tr>
                                                    <td>{{ $contact->id }}</td>
                                                    <td>{{ $contact->name }}</td>
                                                    <td>{{ $contact->email }}</td>
                                                    <td>{{ $contact->subject ?? '—' }}</td>
                                                    <td>{{ $contact->phone ?? '—' }}</td>
                                                    <td>
                                                        <span
                                                            class="badge bg-light-{{ $contact->is_replied ? 'success' : 'danger' }}">
                                                            {{ $contact->is_replied ? 'Yes' : 'No' }}
                                                        </span>
                                                    </td>
                                                    <td class="text-end">

                                                        {{-- View Button --}}
                                                        <a href="#" class="avtar avtar-xs btn-link-secondary"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#viewModal{{ $contact->id }}" title="View">
                                                            <i class="ti ti-eye f-20"></i>
                                                        </a>

                                                        {{-- Reply Button (only if not replied) --}}
                                                        @if (!$contact->is_replied)
                                                            <a href="#" class="avtar avtar-xs btn-link-secondary"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#replyModal{{ $contact->id }}"
                                                                title="Reply">
                                                                <i
                                                                    class="align-text-bottom me-1 ti ti-arrow-back-up f-20"></i>
                                                            </a>
                                                        @endif

                                                        {{-- Delete Button --}}
                                                        <a href="#"
                                                            class="avtar avtar-xs btn-link-secondary bs-pass-para"
                                                            data-id="{{ $contact->id }}" title="Delete">
                                                            <i class="ti ti-trash f-20"></i>
                                                        </a>

                                                        {{-- Hidden Delete Form --}}
                                                        <form id="delete-form-{{ $contact->id }}"
                                                            action="{{ route('contacts.delete', $contact->id) }}"
                                                            method="POST" style="display: none;">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>

                                                    </td>
                                                </tr>

                                                {{-- ════════════════════════════════════
                                                     VIEW MODAL
                                                ════════════════════════════════════ --}}
                                                <div class="modal fade" id="viewModal{{ $contact->id }}" tabindex="-1"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                                        <div class="modal-content border-0 shadow-lg rounded-4">

                                                            <div class="modal-header">
                                                                <h5 class="modal-title fw-bold">
                                                                    <i class="ti ti-mail me-2 text-primary"></i>
                                                                    Contact Details
                                                                </h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal"></button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <dl class="row mb-0">

                                                                    <dt class="col-sm-4 text-muted">ID</dt>
                                                                    <dd class="col-sm-8">{{ $contact->id }}</dd>

                                                                    <dt class="col-sm-4 text-muted">Name</dt>
                                                                    <dd class="col-sm-8">{{ $contact->name }}</dd>

                                                                    <dt class="col-sm-4 text-muted">Email</dt>
                                                                    <dd class="col-sm-8">
                                                                        <a
                                                                            href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                                                                    </dd>

                                                                    <dt class="col-sm-4 text-muted">Phone</dt>
                                                                    <dd class="col-sm-8">{{ $contact->phone ?? '—' }}</dd>

                                                                    <dt class="col-sm-4 text-muted">Subject</dt>
                                                                    <dd class="col-sm-8">{{ $contact->subject ?? '—' }}
                                                                    </dd>

                                                                    <dt class="col-sm-4 text-muted">Message</dt>
                                                                    <dd class="col-sm-8">{{ $contact->message }}</dd>

                                                                    <dt class="col-sm-4 text-muted">Terms Accepted At</dt>
                                                                    <dd class="col-sm-8">
                                                                        {{ $contact->terms_accepted_time ?? '—' }}</dd>

                                                                    <dt class="col-sm-4 text-muted">IP Address</dt>
                                                                    <dd class="col-sm-8">{{ $contact->ip_address ?? '—' }}
                                                                    </dd>

                                                                    <dt class="col-sm-4 text-muted">User Agent</dt>
                                                                    <dd class="col-sm-8"
                                                                        style="word-break:break-word; font-size:12px;">
                                                                        {{ $contact->user_agent ?? '—' }}
                                                                    </dd>

                                                                    <dt class="col-sm-4 text-muted">Admin Reply</dt>
                                                                    <dd class="col-sm-8">
                                                                        @if ($contact->reply_message)
                                                                            <span
                                                                                class="text-success">{{ $contact->reply_message }}</span>
                                                                        @else
                                                                            <span class="text-muted fst-italic">No reply
                                                                                yet</span>
                                                                        @endif
                                                                    </dd>

                                                                    <dt class="col-sm-4 text-muted">Replied At</dt>
                                                                    <dd class="col-sm-8">
                                                                        {{ $contact->replied_at ?? 'No reply yet' }}
                                                                    </dd>

                                                                </dl>
                                                            </div>

                                                            <div class="modal-footer border-top-0">
                                                                @if (!$contact->is_replied)
                                                                    <button type="button" class="btn btn-light-info"
                                                                        data-bs-dismiss="modal" data-bs-toggle="modal"
                                                                        data-bs-target="#replyModal{{ $contact->id }}">
                                                                        <i class="ti ti-arrow-back-up me-1"></i> Reply Now
                                                                    </button>
                                                                @endif
                                                                <button type="button"
                                                                    class="btn btn-outline-secondary rounded-pill"
                                                                    data-bs-dismiss="modal">Close</button>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- END VIEW MODAL --}}


                                                {{-- ════════════════════════════════════
                                                     REPLY MODAL
                                                ════════════════════════════════════ --}}
                                                @if (!$contact->is_replied)
                                                    <div class="modal fade" id="replyModal{{ $contact->id }}"
                                                        tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog modal-md modal-dialog-centered">
                                                            <div class="modal-content border-0 shadow">

                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">
                                                                        <i class="ti ti-send me-2 text-info"></i>
                                                                        Reply to <strong>{{ $contact->name }}</strong>
                                                                    </h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"></button>
                                                                </div>

                                                                <form action="{{ route('contacts.reply', $contact->id) }}"
                                                                    method="POST">
                                                                    @csrf

                                                                    <div class="modal-body">

                                                                        {{-- Contact Info Summary --}}
                                                                        <div class="alert alert-light-secondary mb-3 py-2">
                                                                            <small>
                                                                                <strong>To:</strong>
                                                                                {{ $contact->email }}<br>
                                                                                <strong>Subject:</strong>
                                                                                {{ $contact->subject ?? 'Contact Inquiry' }}
                                                                            </small>
                                                                        </div>

                                                                        {{-- Original Message --}}
                                                                        <div class="mb-3">
                                                                            <label class="form-label text-muted"
                                                                                style="font-size:12px;">
                                                                                ORIGINAL MESSAGE
                                                                            </label>
                                                                            <div class="p-3 bg-light rounded"
                                                                                style="font-size:13px; color:#666; border-left:3px solid #6c757d;">
                                                                                {{ $contact->message }}
                                                                            </div>
                                                                        </div>

                                                                        {{-- Reply Textarea --}}
                                                                        <div class="mb-3">
                                                                            <label class="form-label">
                                                                                Your Reply <span
                                                                                    class="text-danger">*</span>
                                                                            </label>
                                                                            <textarea name="reply_message" class="form-control" rows="5" placeholder="Type your reply here..." required>{{ old('reply_message') }}</textarea>
                                                                        </div>

                                                                    </div>

                                                                    <div class="modal-footer">
                                                                        <button type="button"
                                                                            class="btn btn-light-secondary"
                                                                            data-bs-dismiss="modal">Cancel</button>
                                                                        <button type="submit" class="btn btn-light-info">
                                                                            <i class="ti ti-send me-1"></i> Send Reply
                                                                        </button>
                                                                    </div>

                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                                {{-- END REPLY MODAL --}}
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
        // ── DataTable Init ──────────────────────────────────────
        document.addEventListener('DOMContentLoaded', function() {
            if (document.getElementById('pc-dt-simple')) {
                window.dt = new simpleDatatables.DataTable('#pc-dt-simple', {
                    sortable: true,
                    searchable: true,
                    fixedHeight: true,
                });
            }
        });
    </script>
@endpush
