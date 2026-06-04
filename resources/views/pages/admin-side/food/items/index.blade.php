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
                                <li class="breadcrumb-item">Menu Items</li>
                            </ul>
                        </div>
                        <div class="col-md-12 d-flex justify-content-between align-items-center">
                            <h2 class="mb-0">🍴 Manage Menu</h2>
                            <a href="{{ route('food.orders.index') }}" class="btn btn-outline-secondary d-flex">
                                <i class="ti ti-arrow-left me-1"></i> Back to Orders
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            @include('components.alerts')

            <div class="row">
                {{-- Add Item Form --}}
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Add New Item</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('food.items.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Category <span class="text-danger">*</span></label>
                                    <select name="food_category_id" class="form-select" required>
                                        <option value="">-- Select --</option>
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->icon }} {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Item Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" required
                                        placeholder="Chicken Rise">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control" rows="2" placeholder="Optional..."></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Price (₨) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">₨</span>
                                        <input type="number" name="price" class="form-control" required min="0"
                                            placeholder="350">
                                    </div>
                                </div>
                                <div class="mb-3 form-check">
                                    <input type="checkbox" name="is_available" id="is_available" class="form-check-input"
                                        checked>
                                    <label class="form-check-label" for="is_available">Available</label>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ti ti-plus me-1"></i> Add Item
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Items Table --}}
                <div class="col-lg-8">

                    {{-- Quick Info --}}
                    <div class="card mb-4 border-info">
                        <div class="card-header bg-light-info">
                            <h5 class="mb-0 text-info"><i class="ti ti-info-circle me-2"></i>Quick Info</h5>
                        </div>
                        <div class="card-body f-13">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2">
                                    <i class="ti ti-tools-kitchen-2 text-primary me-1"></i>
                                    <strong>Menu Items</strong>
                                    <p class="text-muted mb-0 ms-3">Add and manage all food items available for ordering.
                                        Each item belongs to a category.</p>
                                </li>
                                <hr class="my-2">
                                <li class="mb-2">
                                    <i class="ti ti-category text-warning me-1"></i>
                                    <strong>Category</strong>
                                    <p class="text-muted mb-0 ms-3">Every item must be assigned to a category. Manage
                                        categories from the Categories page.</p>
                                </li>
                                <hr class="my-2">
                                <li class="mb-2">
                                    <i class="ti ti-currency-rupee text-success me-1"></i>
                                    <strong>Price</strong>
                                    <p class="text-muted mb-0 ms-3">Set the price in PKR. This will be used automatically
                                        when creating food orders.</p>
                                </li>
                                <hr class="my-2">
                                <li class="mb-2">
                                    <i class="ti ti-toggle-right text-info me-1"></i>
                                    <strong>Available</strong>
                                    <p class="text-muted mb-0 ms-3">Unavailable items will not appear in the food order
                                        form.</p>
                                </li>
                                <hr class="my-2">
                                <li>
                                    <i class="ti ti-edit text-secondary me-1"></i>
                                    <strong>Edit / Delete</strong>
                                    <p class="text-muted mb-0 ms-3">Click the edit button to update item details. Deleted
                                        items cannot be recovered.</p>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">All Menu Items</h5>
                        </div>
                        <div class="card-body table-card p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Name</th>
                                            <th>Category</th>
                                            <th>Price</th>
                                            <th>Available</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($items as $item)
                                            <tr>
                                                <td>
                                                    <h6 class="mb-0">{{ $item->name }}</h6>
                                                    @if ($item->description)
                                                        <small
                                                            class="text-muted">{{ Str::limit($item->description, 40) }}</small>
                                                    @endif
                                                </td>
                                                <td>{{ $item->category->icon ?? '' }} {{ $item->category->name ?? '—' }}
                                                </td>
                                                <td class="fw-500 text-primary">₨{{ number_format($item->price) }}</td>
                                                <td>
                                                    @if ($item->is_available)
                                                        <span class="badge bg-light-success text-success">Yes</span>
                                                    @else
                                                        <span class="badge bg-light-danger text-danger">No</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    {{-- Edit --}}
                                                    <a href="javascript:void(0)"
                                                        onclick="editItem({{ $item->id }}, '{{ addslashes($item->name) }}', '{{ addslashes($item->description) }}', {{ $item->price }}, {{ $item->food_category_id }}, {{ $item->is_available ? 1 : 0 }})"
                                                        class="avtar avtar-xs btn-link-secondary" title="Edit">
                                                        <i class="ti ti-edit f-18"></i>
                                                    </a>

                                                    {{-- Delete --}}
                                                    <a href="#"
                                                        class="avtar avtar-xs btn-link-secondary bs-pass-para"
                                                        data-id="{{ $item->id }}" title="Delete">
                                                        <i class="ti ti-trash f-18"></i>
                                                    </a>

                                                    <form id="delete-form-{{ $item->id }}"
                                                        action="{{ route('food.items.destroy', $item) }}" method="POST"
                                                        style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4 text-muted">No items yet.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Item</h5><button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editForm" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select name="food_category_id" id="edit_category" class="form-select">
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->icon }} {{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" id="edit_desc" class="form-control" rows="2" placeholder="Enter a description"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Price (₨)</label>
                            <input type="number" name="price" id="edit_price" class="form-control" required
                                min="0">
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="is_available" id="edit_available" class="form-check-input">
                            <label class="form-check-label">Available</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function editItem(id, name, desc, price, catId, available) {
            document.getElementById('editForm').action = `/food/items/${id}`;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_desc').value = desc;
            document.getElementById('edit_price').value = price;
            document.getElementById('edit_category').value = catId;
            document.getElementById('edit_available').checked = available == 1;
            new bootstrap.Modal(document.getElementById('editModal')).show();
        }
    </script>
@endpush
