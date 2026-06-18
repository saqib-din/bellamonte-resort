<template>
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><Link href="/dashboard">Home</Link></li>
                        <li class="breadcrumb-item">Menu Items</li>
                    </ul>
                </div>
                <div class="col-md-12 d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">🍴 Manage Menu</h2>
                    <Link href="/food/orders" class="btn btn-outline-secondary d-flex"><i class="ti ti-arrow-left me-1"></i> Back to Orders</Link>
                </div>
            </div>
        </div>
    </div>

    <AppModal :show="!!editTarget" title="Edit Item" :max-width="460" @close="editTarget = null">
        <div class="mb-3">
            <label class="form-label">Category</label>
            <select v-model="editForm.food_category_id" class="form-select">
                <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.icon }} {{ c.name }}</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" v-model="editForm.name" class="form-control" :class="{ 'is-invalid': editForm.errors.name }">
            <div v-if="editForm.errors.name" class="invalid-feedback">{{ editForm.errors.name }}</div>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea v-model="editForm.description" class="form-control" :class="{ 'is-invalid': editForm.errors.description }" rows="2"></textarea>
            <div v-if="editForm.errors.description" class="invalid-feedback">{{ editForm.errors.description }}</div>
        </div>
        <div class="mb-3">
            <label class="form-label">Price (₨)</label>
            <input type="number" v-model="editForm.price" class="form-control" :class="{ 'is-invalid': editForm.errors.price }" min="0">
            <div v-if="editForm.errors.price" class="invalid-feedback">{{ editForm.errors.price }}</div>
        </div>
        <div class="form-check mb-4">
            <input type="checkbox" v-model="editForm.is_available" id="edit_available" class="form-check-input">
            <label class="form-check-label" for="edit_available">Available</label>
        </div>
        <button class="btn btn-primary w-100" :disabled="editForm.processing" @click="saveEdit">Update</button>
    </AppModal>

    <div class="row">
        <!-- Add Item -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Add New Item</h5></div>
                <div class="card-body">
                    <form @submit.prevent="addItem">
                        <div class="mb-3">
                            <label class="form-label">Category <span class="text-danger">*</span></label>
                            <select v-model="addForm.food_category_id" class="form-select" :class="{ 'is-invalid': addForm.errors.food_category_id }">
                                <option value="">-- Select --</option>
                                <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.icon }} {{ c.name }}</option>
                            </select>
                            <div v-if="addForm.errors.food_category_id" class="invalid-feedback">{{ addForm.errors.food_category_id }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Item Name <span class="text-danger">*</span></label>
                            <input type="text" v-model="addForm.name" class="form-control" :class="{ 'is-invalid': addForm.errors.name }" placeholder="Chicken Rice">
                            <div v-if="addForm.errors.name" class="invalid-feedback">{{ addForm.errors.name }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea v-model="addForm.description" class="form-control" :class="{ 'is-invalid': addForm.errors.description }" rows="2" placeholder="Optional..."></textarea>
                            <div v-if="addForm.errors.description" class="invalid-feedback">{{ addForm.errors.description }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Price (₨) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">₨</span>
                                <input type="number" v-model="addForm.price" class="form-control" :class="{ 'is-invalid': addForm.errors.price }" min="0" placeholder="350">
                            </div>
                            <div v-if="addForm.errors.price" class="text-danger f-12 mt-1">{{ addForm.errors.price }}</div>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" v-model="addForm.is_available" id="is_available" class="form-check-input">
                            <label class="form-check-label" for="is_available">Available</label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100" :disabled="addForm.processing"><i class="ti ti-plus me-1"></i> Add Item</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="col-lg-8">
            <!-- Quick Info -->
            <div class="card mb-4 border-info">
                <div class="card-header bg-light-info"><h5 class="mb-0 text-info"><i class="ti ti-info-circle me-2"></i>Quick Info</h5></div>
                <div class="card-body f-13">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><i class="ti ti-tools-kitchen-2 text-primary me-1"></i><strong>Menu Items</strong><p class="text-muted mb-0 ms-3">Add and manage all food items available for ordering. Each item belongs to a category.</p></li>
                        <hr class="my-2">
                        <li class="mb-2"><i class="ti ti-category text-warning me-1"></i><strong>Category</strong><p class="text-muted mb-0 ms-3">Every item must be assigned to a category. Manage categories from the Categories page.</p></li>
                        <hr class="my-2">
                        <li class="mb-2"><i class="ti ti-currency-rupee text-success me-1"></i><strong>Price</strong><p class="text-muted mb-0 ms-3">Set the price in PKR. This will be used automatically when creating food orders.</p></li>
                        <hr class="my-2">
                        <li class="mb-2"><i class="ti ti-toggle-right text-info me-1"></i><strong>Available</strong><p class="text-muted mb-0 ms-3">Unavailable items will not appear in the food order form.</p></li>
                        <hr class="my-2">
                        <li><i class="ti ti-edit text-secondary me-1"></i><strong>Edit / Delete</strong><p class="text-muted mb-0 ms-3">Click the edit button to update item details. Deleted items cannot be recovered.</p></li>
                    </ul>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h5 class="mb-0">All Menu Items</h5></div>
                <div class="card-body table-card">
                    <TableToolbar v-model:perPage="filters.per_page" v-model:search="filters.search" :per-page-options="[10, 15, 25, 50, 100]" />
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th role="button" @click="sortBy('name')">Name <SortIcon col="name" :active="filters.sort" :dir="filters.dir" /></th>
                                    <th>Category</th>
                                    <th role="button" @click="sortBy('price')">Price <SortIcon col="price" :active="filters.sort" :dir="filters.dir" /></th>
                                    <th role="button" @click="sortBy('is_available')">Available <SortIcon col="is_available" :active="filters.sort" :dir="filters.dir" /></th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in items.data" :key="item.id">
                                    <td>
                                        <h6 class="mb-0">{{ item.name }}</h6>
                                        <small v-if="item.description" class="text-muted">{{ truncate(item.description, 40) }}</small>
                                    </td>
                                    <td>{{ item.category_icon }} {{ item.category_name }}</td>
                                    <td class="fw-500 text-primary">₨{{ n(item.price) }}</td>
                                    <td>
                                        <span v-if="item.is_available" class="badge bg-light-success text-success">Yes</span>
                                        <span v-else class="badge bg-light-danger text-danger">No</span>
                                    </td>
                                    <td class="text-end">
                                        <a href="javascript:void(0)" class="avtar avtar-xs btn-link-secondary" title="Edit" @click="openEdit(item)"><i class="ti ti-edit f-18"></i></a>
                                        <a href="javascript:void(0)" class="avtar avtar-xs btn-link-secondary" title="Delete" @click="askDelete(item)"><i class="ti ti-trash f-18"></i></a>
                                    </td>
                                </tr>
                                <tr v-if="!items.data.length">
                                    <td colspan="5" class="text-center py-4 text-muted">No items found.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <TableFooter :from="items.from" :to="items.to" :total="items.total"
                        :can-prev="!!items.prev_page_url" :can-next="!!items.next_page_url"
                        @prev="go(items.prev_page_url)" @next="go(items.next_page_url)" />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, watch } from 'vue';
import { Link, useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import AppModal from '@/Components/AppModal.vue';
import { swalDelete } from '@/lib/swalDelete';
import TableToolbar from '@/Components/TableToolbar.vue';
import TableFooter from '@/Components/TableFooter.vue';
import SortIcon from '@/Components/SortIcon.vue';

defineOptions({ layout: AppLayout });

const props = defineProps({
    items:      { type: Object, required: true },
    categories: { type: Array, default: () => [] },
    filters:    { type: Object, default: () => ({}) },
});

const filters = reactive({
    search:   props.filters.search   ?? '',
    sort:     props.filters.sort     ?? 'created_at',
    dir:      props.filters.dir      ?? 'desc',
    per_page: props.filters.per_page ?? 15,
});
function reload() {
    router.get('/food/items', {
        search: filters.search || undefined, sort: filters.sort, dir: filters.dir, per_page: filters.per_page,
    }, { preserveState: true, preserveScroll: true, replace: true });
}
let st = null;
watch(() => filters.search, () => { clearTimeout(st); st = setTimeout(reload, 350); });
watch(() => filters.per_page, reload);
function sortBy(col) {
    if (filters.sort === col) filters.dir = filters.dir === 'asc' ? 'desc' : 'asc';
    else { filters.sort = col; filters.dir = 'asc'; }
    reload();
}
function go(url) { if (url) router.get(url, {}, { preserveState: true, preserveScroll: true }); }

const n = (v) => Number(v || 0).toLocaleString('en-US');
const truncate = (s, len) => (s && s.length > len ? s.slice(0, len) + '…' : s);

const addForm = useForm({ food_category_id: '', name: '', description: '', price: '', is_available: true });
function addItem() {
    addForm.post('/food/items', { preserveScroll: true, onSuccess: () => addForm.reset() });
}

function askDelete(item) {
    swalDelete(() => router.delete(`/food/items/${item.id}`, { preserveScroll: true }));
}

const editTarget = ref(null);
const editForm = useForm({ food_category_id: '', name: '', description: '', price: '', is_available: true });
function openEdit(item) {
    editTarget.value = item;
    editForm.food_category_id = item.food_category_id;
    editForm.name = item.name;
    editForm.description = item.description || '';
    editForm.price = item.price;
    editForm.is_available = item.is_available;
    editForm.clearErrors();
}
function saveEdit() {
    editForm.put(`/food/items/${editTarget.value.id}`, { preserveScroll: true, onSuccess: () => { editTarget.value = null; } });
}
</script>
