{{-- Quick Status --}}
<div class="dropdown d-inline">
    <a href="#" class="avtar avtar-xs btn-link-secondary" data-bs-toggle="dropdown" title="Change Status">
        <i class="ti ti-refresh f-18"></i>
    </a>
    <ul class="dropdown-menu dropdown-menu-end">
        @foreach (['Pending', 'Preparing', 'Served', 'Paid', 'Cancelled'] as $s)
            <li>
                <form action="{{ route('food.orders.status', $order) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="{{ $s }}">
                    <button
                        class="dropdown-item {{ $order->status === $s ? 'active' : '' }}">{{ $s }}</button>
                </form>
            </li>
        @endforeach
    </ul>
</div>
{{-- Print --}}
<a href="{{ route('food.orders.print', $order) }}" target="_blank" class="avtar avtar-xs btn-link-secondary"
    title="Print">
    <i class="ti ti-printer f-18"></i>
</a>
{{-- View --}}
<a href="{{ route('food.orders.show', $order) }}" class="avtar avtar-xs btn-link-secondary" title="View">
    <i class="ti ti-eye f-18"></i>
</a>
{{-- Edit --}}
<a href="{{ route('food.orders.edit', $order) }}" class="avtar avtar-xs btn-link-secondary" title="Edit">
    <i class="ti ti-edit f-18"></i>
</a>
{{-- Delete --}}
<a href="#" class="avtar avtar-xs btn-link-secondary bs-pass-para" data-id="{{ $order->id }}" title="Delete">
    <i class="ti ti-trash f-18"></i>
</a>
<form id="delete-form-{{ $order->id }}" action="{{ route('food.orders.destroy', $order) }}" method="POST"
    style="display:none;">
    @csrf
    @method('DELETE')
</form>
