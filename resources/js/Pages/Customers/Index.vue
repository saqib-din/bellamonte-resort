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
                        <Link href="/customers/create" class="btn btn-primary d-flex"><i class="ti ti-plus me-1"></i> Add Customer</Link>
                    </div>
                </div>

                <div class="card-body table-card">
                    <TableToolbar v-model:perPage="filters.per_page" v-model:search="filters.search" :per-page-options="[10, 15, 25, 50, 100]" />

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th role="button" @click="sortBy('id')">ID <SortIcon col="id" :active="filters.sort" :dir="filters.dir" /></th>
                                    <th role="button" @click="sortBy('name')">Customer <SortIcon col="name" :active="filters.sort" :dir="filters.dir" /></th>
                                    <th role="button" @click="sortBy('cnic')">CNIC / Passport <SortIcon col="cnic" :active="filters.sort" :dir="filters.dir" /></th>
                                    <th role="button" @click="sortBy('phone')">Phone <SortIcon col="phone" :active="filters.sort" :dir="filters.dir" /></th>
                                    <th role="button" @click="sortBy('city')">City / Nationality <SortIcon col="city" :active="filters.sort" :dir="filters.dir" /></th>
                                    <th role="button" @click="sortBy('gender')">Gender <SortIcon col="gender" :active="filters.sort" :dir="filters.dir" /></th>
                                    <th role="button" @click="sortBy('status')">Status <SortIcon col="status" :active="filters.sort" :dir="filters.dir" /></th>
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

                    <TableFooter :from="customers.from" :to="customers.to" :total="customers.total"
                        :can-prev="!!customers.prev_page_url" :can-next="!!customers.next_page_url"
                        @prev="go(customers.prev_page_url)" @next="go(customers.next_page_url)" />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { reactive, watch } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { swalDelete } from '@/lib/swalDelete';
import TableToolbar from '@/Components/TableToolbar.vue';
import TableFooter from '@/Components/TableFooter.vue';
import SortIcon from '@/Components/SortIcon.vue';

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
watch(() => filters.per_page, reload);

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
