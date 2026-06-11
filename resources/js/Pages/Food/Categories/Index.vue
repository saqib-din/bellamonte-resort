<template>
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><Link href="/dashboard">Home</Link></li>
                        <li class="breadcrumb-item">Food Categories</li>
                    </ul>
                </div>
                <div class="col-md-12 d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">🍽️ Food Categories</h2>
                    <Link href="/food/items" class="btn btn-outline-secondary d-flex">
                        <i class="ti ti-tools-kitchen-2 me-1"></i> Manage Items
                    </Link>
                </div>
            </div>
        </div>
    </div>

    <AppModal :show="!!editTarget" title="Edit Category" :max-width="440" @close="editTarget = null">
        <div class="mb-3">
            <label class="form-label">Icon</label>
            <input type="text" v-model="editForm.icon" class="form-control" maxlength="5">
        </div>
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" v-model="editForm.name" class="form-control" :class="{ 'is-invalid': editForm.errors.name }">
            <div v-if="editForm.errors.name" class="invalid-feedback">{{ editForm.errors.name }}</div>
        </div>
        <div class="form-check mb-4">
            <input type="checkbox" v-model="editForm.is_active" id="edit_cat_active" class="form-check-input">
            <label class="form-check-label" for="edit_cat_active">Active</label>
        </div>
        <button class="btn btn-primary w-100" :disabled="editForm.processing" @click="saveEdit">Update</button>
    </AppModal>

    <div class="row">
        <!-- Add Category -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Add Category</h5></div>
                <div class="card-body">
                    <form @submit.prevent="addCategory">
                        <div class="mb-3">
                            <label class="form-label">Icon (Emoji)</label>
                            <input type="text" v-model="addForm.icon" class="form-control" placeholder="🍛" maxlength="5">
                            <small class="text-muted">Pick from the panel below or paste an emoji.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category Name <span class="text-danger">*</span></label>
                            <input type="text" v-model="addForm.name" class="form-control" :class="{ 'is-invalid': addForm.errors.name }" placeholder="Main Course">
                            <div v-if="addForm.errors.name" class="invalid-feedback">{{ addForm.errors.name }}</div>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" v-model="addForm.is_active" id="is_active" class="form-check-input">
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100" :disabled="addForm.processing">
                            <i class="ti ti-plus me-1"></i> Add Category
                        </button>
                    </form>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header"><h6 class="mb-0">Common Emojis</h6></div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        <span v-for="e in emojis" :key="e" class="badge bg-light text-dark fs-5" style="cursor:pointer" @click="addForm.icon = e">{{ e }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories Table -->
        <div class="col-lg-8">
            <!-- Quick Info -->
            <div class="card mb-4 border-info">
                <div class="card-header bg-light-info"><h5 class="mb-0 text-info"><i class="ti ti-info-circle me-2"></i>Quick Info</h5></div>
                <div class="card-body f-13">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><i class="ti ti-category text-primary me-1"></i><strong>Food Categories</strong><p class="text-muted mb-0 ms-3">Categories are used to organize food items (e.g. Main Course, Drinks, Desserts).</p></li>
                        <hr class="my-2">
                        <li class="mb-2"><i class="ti ti-mood-smile text-warning me-1"></i><strong>Emoji Icon</strong><p class="text-muted mb-0 ms-3">Each category can have an emoji icon. Click any emoji from the panel on the left to auto-fill it.</p></li>
                        <hr class="my-2">
                        <li class="mb-2"><i class="ti ti-toggle-right text-success me-1"></i><strong>Active / Inactive</strong><p class="text-muted mb-0 ms-3">Inactive categories will not appear in the food order form.</p></li>
                        <hr class="my-2">
                        <li class="mb-2"><i class="ti ti-tools-kitchen-2 text-secondary me-1"></i><strong>Items Count</strong><p class="text-muted mb-0 ms-3">Shows how many food items belong to each category.</p></li>
                        <hr class="my-2">
                        <li><i class="ti ti-trash text-danger me-1"></i><strong>Delete</strong><p class="text-muted mb-0 ms-3">You can only delete a category if it has no food items assigned to it.</p></li>
                    </ul>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h5 class="mb-0">All Categories</h5></div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr><th>Icon</th><th>Name</th><th>Items</th><th>Status</th><th class="text-end">Action</th></tr>
                        </thead>
                        <tbody>
                            <tr v-for="cat in categories" :key="cat.id">
                                <td class="fs-4">{{ cat.icon }}</td>
                                <td><h6 class="mb-0">{{ cat.name }}</h6></td>
                                <td><span class="badge bg-light-primary">{{ cat.items_count }} items</span></td>
                                <td>
                                    <span v-if="cat.is_active" class="badge bg-light-success text-success">Active</span>
                                    <span v-else class="badge bg-light-danger text-danger">Inactive</span>
                                </td>
                                <td class="text-end">
                                    <a href="javascript:void(0)" class="avtar avtar-xs btn-link-secondary" title="Edit" @click="openEdit(cat)"><i class="ti ti-edit f-18"></i></a>
                                    <a href="javascript:void(0)" class="avtar avtar-xs btn-link-secondary" title="Delete" @click="askDelete(cat)"><i class="ti ti-trash f-18"></i></a>
                                </td>
                            </tr>
                            <tr v-if="!categories.length">
                                <td colspan="5" class="text-center py-4 text-muted">No category yet — add one from the left.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link, useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import AppModal from '@/Components/AppModal.vue';
import { swalDelete } from '@/lib/swalDelete';

defineOptions({ layout: AppLayout });

defineProps({
    categories: { type: Array, default: () => [] },
});

const emojis = ['🍛', '🍳', '☕', '🍟', '🥗', '🍰', '🥤', '🍜', '🍕', '🥩', '🍱', '🍣'];

const addForm = useForm({ icon: '', name: '', is_active: true });
function addCategory() {
    addForm.post('/food/categories', { preserveScroll: true, onSuccess: () => addForm.reset() });
}

function askDelete(cat) {
    swalDelete(() => router.delete(`/food/categories/${cat.id}`, { preserveScroll: true }));
}

const editTarget = ref(null);
const editForm = useForm({ icon: '', name: '', is_active: true });
function openEdit(cat) {
    editTarget.value = cat;
    editForm.icon = cat.icon;
    editForm.name = cat.name;
    editForm.is_active = cat.is_active;
    editForm.clearErrors();
}
function saveEdit() {
    editForm.put(`/food/categories/${editTarget.value.id}`, { preserveScroll: true, onSuccess: () => { editTarget.value = null; } });
}
</script>
