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
                                <li class="breadcrumb-item">Food Categories</li>
                            </ul>
                        </div>
                        <div class="col-md-12 d-flex justify-content-between align-items-center">
                            <h2 class="mb-0">🍽️ Food Categories</h2>
                            <a href="{{ route('food.items.index') }}" class="btn btn-outline-secondary d-flex">
                                <i class="ti ti-tools-kitchen-2 me-1"></i> Manage Items
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            @include('components.alerts')

            <div class="row">
                {{-- Add Category --}}
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Add Category</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('food.categories.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Icon (Emoji)</label>
                                    <input type="text" name="icon" class="form-control" placeholder="🍛"
                                        maxlength="5">
                                    <small class="text-muted">Here are some emojis you can copy and paste:</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Category Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" required
                                        placeholder="Main Course">
                                </div>
                                <div class="mb-3 form-check">
                                    <input type="checkbox" name="is_active" id="is_active" class="form-check-input" checked>
                                    <label class="form-check-label" for="is_active">Active</label>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ti ti-plus me-1"></i> Add Category
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Common Emojis --}}
                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="mb-0">Common Emojis</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-wrap gap-2">
                                @foreach (['🍛', '🍳', '☕', '🍟', '🥗', '🍰', '🥤', '🍜', '🍕', '🥩', '🍱', '🍣'] as $emoji)
                                    <span class="badge bg-light text-dark fs-5" style="cursor:pointer"
                                        onclick="document.querySelector('[name=icon]').value='{{ $emoji }}'">
                                        {{ $emoji }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Categories Table --}}
                <div class="col-lg-8">

                    <div class="card mb-4 border-info">
                        <div class="card-header bg-light-info">
                            <h5 class="mb-0 text-info"><i class="ti ti-info-circle me-2"></i>Quick Info</h5>
                        </div>
                        <div class="card-body f-13">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2">
                                    <i class="ti ti-category text-primary me-1"></i>
                                    <strong>Food Categories</strong>
                                    <p class="text-muted mb-0 ms-3">Categories are used to organize food items (e.g. Main
                                        Course, Drinks, Desserts).</p>
                                </li>
                                <hr class="my-2">
                                <li class="mb-2">
                                    <i class="ti ti-mood-smile text-warning me-1"></i>
                                    <strong>Emoji Icon</strong>
                                    <p class="text-muted mb-0 ms-3">Each category can have an emoji icon. Click any emoji
                                        from the panel on the left to auto-fill it.</p>
                                </li>
                                <hr class="my-2">
                                <li class="mb-2">
                                    <i class="ti ti-toggle-right text-success me-1"></i>
                                    <strong>Active / Inactive</strong>
                                    <p class="text-muted mb-0 ms-3">Inactive categories will not appear in the food order
                                        form.</p>
                                </li>
                                <hr class="my-2">
                                <li class="mb-2">
                                    <i class="ti ti-tools-kitchen-2 text-secondary me-1"></i>
                                    <strong>Items Count</strong>
                                    <p class="text-muted mb-0 ms-3">Shows how many food items belong to each category.</p>
                                </li>
                                <hr class="my-2">
                                <li>
                                    <i class="ti ti-trash text-danger me-1"></i>
                                    <strong>Delete</strong>
                                    <p class="text-muted mb-0 ms-3">You can only delete a category if it has no food items
                                        assigned to it.</p>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">All Categories</h5>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Icon</th>
                                        <th>Name</th>
                                        <th>Items</th>
                                        <th>Status</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($categories as $cat)
                                        <tr>
                                            <td class="fs-4">{{ $cat->icon }}</td>
                                            <td>
                                                <h6 class="mb-0">{{ $cat->name }}</h6>
                                            </td>
                                            <td><span class="badge bg-light-primary">{{ $cat->items_count }} items</span>
                                            </td>
                                            <td>
                                                @if ($cat->is_active)
                                                    <span class="badge bg-light-success text-success">Active</span>
                                                @else
                                                    <span class="badge bg-light-danger text-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                {{-- Edit --}}
                                                <a href="javascript:void(0)"
                                                    onclick="editCat({{ $cat->id }}, '{{ $cat->icon }}', '{{ addslashes($cat->name) }}', {{ $cat->is_active ? 1 : 0 }})"
                                                    class="avtar avtar-xs btn-link-secondary" title="Edit">
                                                    <i class="ti ti-edit f-18"></i>
                                                </a>

                                                {{-- Delete --}}
                                                <a href="#" class="avtar avtar-xs btn-link-secondary bs-pass-para"
                                                    data-id="{{ $cat->id }}" title="Delete">
                                                    <i class="ti ti-trash f-18"></i>
                                                </a>

                                                <form id="delete-form-{{ $cat->id }}"
                                                    action="{{ route('food.categories.destroy', $cat) }}" method="POST"
                                                    style="display:none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">"No category is
                                                currently displayed. Please add the category section at the top."</td>
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

    {{-- Edit Modal --}}
    <div class="modal fade" id="editCatModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Category</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editCatForm" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Icon</label>
                            <input type="text" name="icon" id="edit_icon" class="form-control" maxlength="5">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" id="edit_cat_name" class="form-control" required>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="is_active" id="edit_cat_active" class="form-check-input">
                            <label class="form-check-label">Active</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {{-- <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button> --}}
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function editCat(id, icon, name, active) {
            document.getElementById('editCatForm').action = `/food/categories/${id}`;
            document.getElementById('edit_icon').value = icon;
            document.getElementById('edit_cat_name').value = name;
            document.getElementById('edit_cat_active').checked = active == 1;
            new bootstrap.Modal(document.getElementById('editCatModal')).show();
        }
    </script>
@endpush
