<template>
    <!-- Breadcrumb -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><Link href="/dashboard">Home</Link></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Customers</a></li>
                        <li class="breadcrumb-item" aria-current="page">List</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title"><h2 class="mb-0">Customers Management</h2></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-sm-flex align-items-center justify-content-between">
                        <h5 class="mb-3 mb-sm-0">All Customers</h5>
                        <div class="d-flex gap-2 align-items-center">
                            <select v-model="filters.per_page" class="form-select form-select-sm" style="width:90px;" @change="reload">
                                <option :value="15">15</option>
                                <option :value="30">30</option>
                                <option :value="50">50</option>
                                <option :value="100">100</option>
                            </select>
                            <input type="text" v-model="filters.search" class="form-control form-control-sm" style="width:200px;" placeholder="Search...">
                            <Link href="/customers/create" class="btn btn-primary d-flex"><i class="ti ti-plus me-1"></i> Add Customer</Link>
                        </div>
                    </div>
                </div>

                <div class="card-body table-card">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th role="button" @click="sortBy('id')">ID</th>
                                    <th role="button" @click="sortBy('name')">Customer</th>
                                    <th role="button" @click="sortBy('cnic')">CNIC / Passport</th>
                                    <th role="button" @click="sortBy('phone')">Phone</th>
                                    <th role="button" @click="sortBy('city')">City / Nationality</th>
                                    <th role="button" @click="sortBy('gender')">Gender</th>
                                    <th role="button" @click="sortBy('status')">Status</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="c in customers.data" :key="c.uuid">
                                    <td>{{ c.id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <img v-if="c.image" :src="c.image" :alt="c.name" style="width:40px;height:40px;object-fit:cover;" class="rounded-circle">
                                                <div v-else class="avtar avtar-s bg-light-primary rounded-circle d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                                                    <span class="fw-bold text-primary">{{ (c.name || '?').charAt(0).toUpperCase() }}</span>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-0">{{ c.name }}</h6>
                                                <!-- <small v-if="c.father_name" class="text-muted d-block">S/O {{ c.father_name }}</small> -->
                                                <small class="text-muted">{{ c.email || 'No email' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ c.cnic }}</td>
                                    <td>{{ c.phone }}</td>
                                    <td>
                                        <h6 class="mb-0">{{ c.city || '—' }}</h6>
                                        <small class="text-muted">{{ c.nationality }}</small>
                                    </td>
                                    <td>{{ c.gender || '—' }}</td>
                                    <td><span class="badge" :class="c.statusBadge">{{ c.status }}</span></td>
                                    <td class="text-end">
                                        <Link :href="`/customers/${c.uuid}`" class="avtar avtar-xs btn-link-secondary" title="View"><i class="ti ti-eye f-20"></i></Link>
                                        <Link :href="`/customers/${c.uuid}/edit`" class="avtar avtar-xs btn-link-secondary" title="Edit"><i class="ti ti-edit f-20"></i></Link>
                                        <button type="button" class="avtar avtar-xs btn-link-secondary" title="Delete" @click="askDelete(c)"><i class="ti ti-trash f-20"></i></button>
                                    </td>
                                </tr>
                                <tr v-if="!customers.data.length">
                                    <td colspan="8" class="text-center py-4 text-muted">
                                        <i class="ti ti-users f-40 d-block mb-2"></i>
                                        No customer found — please add a customer first!
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-2">
                        <small class="text-muted">Showing {{ customers.from || 0 }} to {{ customers.to || 0 }} of {{ customers.total }} customers</small>
                        <nav v-if="customers.last_page > 1">
                            <ul class="pagination mb-0">
                                <li v-for="(link, i) in customers.links" :key="i" class="page-item" :class="{ active: link.active, disabled: !link.url }">
                                    <a class="page-link" href="#" @click.prevent="link.url && go(link.url)" v-html="link.label"></a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, watch } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { swalDelete } from '@/lib/swalDelete';

defineOptions({ layout: AppLayout });

const props = defineProps({
    customers: { type: Object, required: true },
    filters:   { type: Object, default: () => ({}) },
});

const filters = reactive({
    search:   props.filters.search   ?? '',
    sort:     props.filters.sort     ?? 'id',
    dir:      props.filters.dir      ?? 'desc',
    per_page: props.filters.per_page ?? 15,
});

function reload() {
    router.get('/customers', {
        search: filters.search || undefined,
        sort: filters.sort,
        dir: filters.dir,
        per_page: filters.per_page,
    }, { preserveState: true, preserveScroll: true, replace: true });
}

let t = null;
watch(() => filters.search, () => { clearTimeout(t); t = setTimeout(reload, 350); });

function sortBy(col) {
    if (filters.sort === col) filters.dir = filters.dir === 'asc' ? 'desc' : 'asc';
    else { filters.sort = col; filters.dir = 'asc'; }
    reload();
}

function go(url) {
    router.get(url, {}, { preserveState: true, preserveScroll: true });
}

function askDelete(c) {
    swalDelete(() => router.delete(`/customers/${c.uuid}`, { preserveScroll: true }));
}
</script>
