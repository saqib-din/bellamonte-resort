<div class="d-flex align-items-center">
    <div class="flex-shrink-0">
        @if ($c->image)
            <img src="{{ asset('uploads/customers/' . $c->image) }}" alt="{{ $c->name }}"
                style="width:40px;height:40px;object-fit:cover;" class="rounded-circle">
        @else
            <div class="avtar avtar-s bg-light-primary rounded-circle d-flex align-items-center justify-content-center"
                style="width:40px;height:40px;">
                <span class="fw-bold text-primary">
                    {{ strtoupper(substr($c->name, 0, 1)) }}
                </span>
            </div>
        @endif
    </div>
    <div class="flex-grow-1 ms-3">
        <h6 class="mb-0">{{ $c->name }}</h6>
        @if ($c->father_name)
            <small class="text-muted d-block">S/O {{ $c->father_name }}</small>
        @endif
        <small class="text-muted">{{ $c->email ?? 'No email' }}</small>
    </div>
</div>
