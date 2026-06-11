<template>
    <form @submit.prevent="submit">
        <div class="row">
            <!-- LEFT -->
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0"><i class="ti ti-user me-2"></i>User Information</h5></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" v-model="form.name" class="form-control" :class="{ 'is-invalid': form.errors.name }" placeholder="Enter a full name">
                                <div v-if="form.errors.name" class="invalid-feedback">{{ form.errors.name }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" v-model="form.email" class="form-control" :class="{ 'is-invalid': form.errors.email }" placeholder="example@bmresort.com">
                                <div v-if="form.errors.email" class="invalid-feedback">{{ form.errors.email }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Password <span v-if="mode === 'create'" class="text-danger">*</span><small v-else class="text-muted">(leave blank to keep)</small></label>
                                <div class="input-group">
                                    <input :type="showPass ? 'text' : 'password'" v-model="form.password" class="form-control" :class="{ 'is-invalid': form.errors.password }" placeholder="Min 6 characters">
                                    <button class="btn btn-outline-secondary" type="button" @click="showPass = !showPass"><i class="ti" :class="showPass ? 'ti-eye-off' : 'ti-eye'"></i></button>
                                </div>
                                <div v-if="form.errors.password" class="text-danger f-12 mt-1">{{ form.errors.password }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Confirm Password <span v-if="mode === 'create'" class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input :type="showPass2 ? 'text' : 'password'" v-model="form.password_confirmation" class="form-control" placeholder="Re-enter password">
                                    <button class="btn btn-outline-secondary" type="button" @click="showPass2 = !showPass2"><i class="ti" :class="showPass2 ? 'ti-eye-off' : 'ti-eye'"></i></button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="text" v-model="form.phone" class="form-control" placeholder="Enter a phone number">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select v-model="form.status" class="form-select">
                                    <option value="active">✅ Active</option>
                                    <option value="inactive">❌ Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT -->
            <div class="col-lg-4">
                <div v-if="isAdminViewer" class="card mb-4">
                    <div class="card-header"><h5 class="mb-0"><i class="ti ti-shield me-2"></i>Role / Permission</h5></div>
                    <div class="card-body">
                        <div v-if="isSelf" class="alert alert-warning py-2 f-13"><i class="ti ti-info-circle me-1"></i> You cannot change your own role.</div>
                        <div v-else-if="isEditingAdmin" class="alert alert-danger py-2 f-13"><i class="ti ti-lock me-1"></i> Admin role cannot be changed.</div>
                        <template v-else>
                            <div class="mb-2" v-for="role in roles" :key="role.value">
                                <input type="radio" class="btn-check" name="role" :id="`role_${role.value}`" :value="role.value" v-model="form.role">
                                <label class="btn w-100 text-start d-flex align-items-center gap-2" :class="`btn-outline-${role.color}`" :for="`role_${role.value}`" style="padding:10px 14px;">
                                    <span style="font-size:1.2rem;">{{ role.icon }}</span>
                                    <div>
                                        <div class="fw-500">{{ role.label }}</div>
                                        <small class="text-muted">{{ role.desc }}</small>
                                    </div>
                                </label>
                            </div>
                            <div v-if="form.errors.role" class="text-danger f-12 mt-1">{{ form.errors.role }}</div>
                        </template>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary" :disabled="form.processing">
                                <i class="ti ti-user-plus me-2"></i>{{ mode === 'edit' ? 'Update User' : 'Create User' }}
                            </button>
                            <Link href="/users" class="btn btn-outline-secondary"><i class="ti ti-arrow-left me-2"></i>Cancel</Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

const props = defineProps({
    mode: { type: String, default: 'create' },
    user: { type: Object, default: null },
});

const page = usePage();
const isAdminViewer = computed(() => page.props.auth?.user?.isAdmin === true);
const isSelf        = computed(() => props.mode === 'edit' && props.user && props.user.id === page.props.auth?.user?.id);
const isEditingAdmin = computed(() => props.mode === 'edit' && props.user?.role === 'admin');

const roles = [
    { value: 'manager',      label: 'Manager',      icon: '🏢', desc: 'Can manage rooms, bookings, and reports.', color: 'primary' },
    { value: 'receptionist', label: 'Receptionist', icon: '🛎️', desc: 'Handles bookings and customers.',          color: 'success' },
    { value: 'accountant',   label: 'Accountant',   icon: '💼', desc: 'Can view billing and invoices.',          color: 'warning' },
    { value: 'staff',        label: 'Staff',        icon: '👤', desc: 'Basic view only',                          color: 'secondary' },
];

const u = props.user || {};
const form = useForm({
    name:                  u.name ?? '',
    email:                 u.email ?? '',
    password:              '',
    password_confirmation: '',
    phone:                 u.phone ?? '',
    status:                u.status ?? 'active',
    role:                  u.role ?? 'staff',
});

const showPass  = ref(false);
const showPass2 = ref(false);

function submit() {
    if (props.mode === 'edit') {
        form.put(`/users/${props.user.uuid}`);
    } else {
        form.post('/users');
    }
}
</script>
