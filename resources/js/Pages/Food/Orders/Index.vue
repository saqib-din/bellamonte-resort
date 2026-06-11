<template>
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><Link href="/dashboard">Home</Link></li>
                        <li class="breadcrumb-item">Food Orders</li>
                    </ul>
                </div>
                <div class="col-md-12 d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">🍽️ Food Orders</h2>
                    <Link href="/food/items" class="btn btn-outline-secondary d-flex"><i class="ti ti-tools-kitchen-2 me-1"></i> Manage Menu</Link>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-sm-flex align-items-center justify-content-between">
                        <h5 class="mb-3 mb-sm-0">All Orders</h5>
                        <div class="d-flex gap-2 align-items-center">
                            <select v-model="filters.per_page" class="form-select form-select-sm" style="width:90px;" @change="reload">
                                <option :value="15">15</option><option :value="30">30</option><option :value="50">50</option><option :value="100">100</option>
                            </select>
                            <input type="text" v-model="filters.search" class="form-control form-control-sm" style="width:200px;" placeholder="Search...">
                            <Link href="/food/orders/create" class="btn btn-primary d-flex"><i class="ti ti-plus me-1"></i> New Order</Link>
                        </div>
                    </div>
                </div>
                <div class="card-body table-card">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th role="button" @click="sortBy('order_number')">Order #</th>
                                    <th role="button" @click="sortBy('guest_name')">Guest</th>
                                    <th>Room</th>
                                    <th>Type</th>
                                    <th>Items</th>
                                    <th role="button" @click="sortBy('total_amount')">Total</th>
                                    <th>Balance</th>
                                    <th role="button" @click="sortBy('status')">Status</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="o in orders.data" :key="o.uuid">
                                    <td><strong>{{ o.order_number }}</strong><small class="text-muted d-block">{{ o.date }}</small></td>
                                    <td><h6 class="mb-0">{{ o.guest_name }}</h6><small class="text-muted">{{ o.guest_phone }}</small></td>
                                    <td><span v-if="o.room_number" class="badge bg-light-primary">Room {{ o.room_number }}</span><span v-else class="text-muted">—</span></td>
                                    <td><span class="badge" :class="o.orderTypeBadge">{{ o.order_type }}</span></td>
                                    <td><small class="text-muted">{{ o.items_count }} item(s)</small></td>
                                    <td><strong class="text-dark">₨ {{ n(o.total_amount) }}</strong></td>
                                    <td><span v-if="o.balance_due > 0" class="text-danger fw-500">₨ {{ n(o.balance_due) }}</span><span v-else class="text-muted">—</span></td>
                                    <td><span class="badge" :class="o.statusBadge">{{ o.status }}</span></td>
                                    <td class="text-end">
                                        <Link :href="`/food/orders/${o.uuid}`" class="avtar avtar-xs btn-link-secondary" title="View"><i class="ti ti-eye f-18"></i></Link>
                                        <Link :href="`/food/orders/${o.uuid}/edit`" class="avtar avtar-xs btn-link-secondary" title="Edit"><i class="ti ti-edit f-18"></i></Link>
                                        <a :href="`/food/orders/${o.uuid}/print`" target="_blank" class="avtar avtar-xs btn-link-secondary" title="Print"><i class="ti ti-printer f-18"></i></a>
                                        <button class="avtar avtar-xs btn-link-secondary" title="Delete" @click="askDelete(o)"><i class="ti ti-trash f-18"></i></button>
                                    </td>
                                </tr>
                                <tr v-if="!orders.data.length">
                                    <td colspan="9" class="text-center py-4 text-muted"><i class="ti ti-tools-kitchen-2 f-40 d-block mb-2"></i>No orders found.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-2">
                        <small class="text-muted">Showing {{ orders.from || 0 }} to {{ orders.to || 0 }} of {{ orders.total }} orders</small>
                        <nav v-if="orders.last_page > 1">
                            <ul class="pagination mb-0">
                                <li v-for="(link, i) in orders.links" :key="i" class="page-item" :class="{ active: link.active, disabled: !link.url }">
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
import { reactive, watch } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { swalDelete } from '@/lib/swalDelete';

defineOptions({ layout: AppLayout });

const props = defineProps({
    orders:  { type: Object, required: true },
    filters: { type: Object, default: () => ({}) },
});

const filters = reactive({
    search:   props.filters.search   ?? '',
    sort:     props.filters.sort     ?? 'created_at',
    dir:      props.filters.dir      ?? 'desc',
    per_page: props.filters.per_page ?? 15,
});

function reload() {
    router.get('/food/orders', {
        search: filters.search || undefined, sort: filters.sort, dir: filters.dir, per_page: filters.per_page,
    }, { preserveState: true, preserveScroll: true, replace: true });
}
let t = null;
watch(() => filters.search, () => { clearTimeout(t); t = setTimeout(reload, 350); });
function sortBy(col) {
    if (filters.sort === col) filters.dir = filters.dir === 'asc' ? 'desc' : 'asc';
    else { filters.sort = col; filters.dir = 'asc'; }
    reload();
}
function go(url) { router.get(url, {}, { preserveState: true, preserveScroll: true }); }
function askDelete(o) {
    swalDelete(() => router.delete(`/food/orders/${o.uuid}`, { preserveScroll: true }));
}
const n = (v) => Number(v || 0).toLocaleString('en-US');
</script>
