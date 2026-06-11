<template>
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><Link href="/dashboard">Home</Link></li>
                        <li class="breadcrumb-item" aria-current="page">Users & Roles</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title"><h2 class="mb-0">👥 Users & Roles Management</h2></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="row mb-4">
        <div class="col-md-2 col-sm-4">
            <div class="card text-center"><div class="card-body py-3"><h4 class="mb-1 text-primary">{{ counts.total }}</h4><p class="mb-0 text-muted f-12">Total Users</p></div></div>
        </div>
        <div class="col-md-2 col-sm-4" v-for="r in roleStats" :key="r.role">
            <div class="card text-center"><div class="card-body py-3"><h4 class="mb-1" :class="`text-${r.color}`">{{ counts[r.role] }}</h4><p class="mb-0 text-muted f-12">{{ r.label }}</p></div></div>
        </div>
    </div>

    <!-- Table -->
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-sm-flex align-items-center justify-content-between">
                        <h5 class="mb-3 mb-sm-0">All Users</h5>
                        <Link v-if="isAdmin" href="/users/create" class="btn btn-primary d-flex"><i class="ti ti-user-plus me-1"></i> Add User</Link>
                    </div>
                </div>
                <div class="card-body table-card">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr><th>#</th><th>User</th><th>Email</th><th>Phone</th><th>Role</th><th>Status</th><th>Joined</th><th class="text-end">Action</th></tr>
                            </thead>
                            <tbody>
                                <tr v-for="(u, i) in users" :key="u.uuid" :style="u.is_me ? 'background:rgba(70,128,255,0.05);' : ''">
                                    <td>{{ i + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avtar avtar-s me-3 rounded-circle d-flex align-items-center justify-content-center" :class="u.role_badge" style="width:40px;height:40px;font-weight:700;font-size:1rem;">
                                                {{ (u.name || '?').charAt(0).toUpperCase() }}
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ u.name }}<span v-if="u.is_me" class="badge bg-light-info ms-1">You</span></h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ u.email }}</td>
                                    <td>{{ u.phone || '—' }}</td>
                                    <td><span class="badge" :class="u.role_badge">{{ u.role_label }}</span></td>
                                    <td><span class="badge" :class="u.status_badge">{{ ucfirst(u.status) }}</span></td>
                                    <td>{{ u.created_at }}</td>
                                    <td class="text-end">
                                        <template v-if="isAdmin">
                                            <button v-if="!u.is_me" type="button" class="avtar avtar-xs" :class="u.status === 'active' ? 'btn-link-warning' : 'btn-link-success'"
                                                :title="u.status === 'active' ? 'Deactivate' : 'Activate'" @click="toggleStatus(u)">
                                                <i class="ti f-18" :class="u.status === 'active' ? 'ti-user-off' : 'ti-user-check'"></i>
                                            </button>
                                            <Link :href="`/users/${u.uuid}/edit`" class="avtar avtar-xs btn-link-secondary" title="Edit"><i class="ti ti-edit f-18"></i></Link>
                                            <button v-if="!u.is_me && !u.is_admin" type="button" class="avtar avtar-xs btn-link-secondary" title="Delete" @click="askDelete(u)"><i class="ti ti-trash f-18"></i></button>
                                        </template>
                                    </td>
                                </tr>
                                <tr v-if="!users.length">
                                    <td colspan="8" class="text-center py-5 text-muted"><i class="ti ti-users f-40 d-block mb-2"></i>No users found.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { swalDelete } from '@/lib/swalDelete';

defineOptions({ layout: AppLayout });

defineProps({
    users:  { type: Array, default: () => [] },
    counts: { type: Object, default: () => ({}) },
});

const page = usePage();
const isAdmin = computed(() => page.props.auth?.user?.isAdmin === true);

const roleStats = [
    { role: 'admin', label: 'Admins', color: 'danger' },
    { role: 'manager', label: 'Managers', color: 'primary' },
    { role: 'receptionist', label: 'Receptionists', color: 'success' },
    { role: 'accountant', label: 'Accountants', color: 'warning' },
    { role: 'staff', label: 'Staff', color: 'secondary' },
];

const ucfirst = (s) => (s ? s.charAt(0).toUpperCase() + s.slice(1) : s);

function askDelete(u) {
    swalDelete(() => router.delete(`/users/${u.uuid}`, { preserveScroll: true }));
}
function toggleStatus(u) {
    router.post(`/users/${u.uuid}/toggle`, {}, { preserveScroll: true });
}
</script>
