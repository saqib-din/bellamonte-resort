<a href="{{ route('customers.show', $c->id) }}"
    class="avtar avtar-xs btn-link-secondary" title="View">
    <i class="ti ti-eye f-20"></i>
</a>
<a href="{{ route('customers.edit', $c->id) }}"
    class="avtar avtar-xs btn-link-secondary" title="Edit">
    <i class="ti ti-edit f-20"></i>
</a>
<a href="#" class="avtar avtar-xs btn-link-secondary bs-pass-para"
    data-id="{{ $c->id }}" title="Delete">
    <i class="ti ti-trash f-20"></i>
</a>
<form id="delete-form-{{ $c->id }}"
    action="{{ route('customers.destroy', $c->id) }}"
    method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>