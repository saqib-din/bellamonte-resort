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
                        <Link href="/food/orders/create" class="btn btn-primary d-flex"><i class="ti ti-plus me-1"></i> New Order</Link>
                    </div>
                </div>
                <div class="card-body table-card">
                    <TableToolbar v-model:perPage="filters.per_page" v-model:search="filters.search" :per-page-options="[10, 15, 25, 50, 100]" />

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th role="button" @click="sortBy('order_number')">Order # <SortIcon col="order_number" :active="filters.sort" :dir="filters.dir" /></th>
                                    <th role="button" @click="sortBy('guest_name')">Guest <SortIcon col="guest_name" :active="filters.sort" :dir="filters.dir" /></th>
                                    <th>Room</th>
                                    <th>Type</th>
                                    <th>Items</th>
                                    <th role="button" @click="sortBy('total_amount')">Total <SortIcon col="total_amount" :active="filters.sort" :dir="filters.dir" /></th>
                                    <th>Balance</th>
                                    <th role="button" @click="sortBy('status')">Order <SortIcon col="status" :active="filters.sort" :dir="filters.dir" /></th>
                                    <th>Payment</th>
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
                                    <td><strong class="text-muted">₨ {{ n(o.total_amount) }}</strong></td>
                                    <td><span v-if="o.balance_due > 0" class="text-danger fw-500">₨ {{ n(o.balance_due) }}</span><span v-else class="text-muted">—</span></td>
                                    <td><span class="badge" :class="o.statusBadge">{{ o.status }}</span></td>
                                    <td><span class="badge" :class="o.paymentBadge">{{ o.payment_status }}</span></td>
                                    <td class="text-end">
                                        <Link :href="`/food/orders/${o.uuid}`" class="avtar avtar-xs btn-link-secondary" title="View"><i class="ti ti-eye f-18"></i></Link>
                                        <Link v-if="o.status !== 'Cancelled' && o.payment_status !== 'Paid'" :href="`/food/orders/${o.uuid}/edit`" class="avtar avtar-xs btn-link-secondary" title="Edit"><i class="ti ti-edit f-18"></i></Link>
                                        <a :href="`/food/orders/${o.uuid}/print`" target="_blank" class="avtar avtar-xs btn-link-secondary" title="Print"><i class="ti ti-printer f-18"></i></a>
                                        <button class="avtar avtar-xs btn-link-secondary" title="Delete" @click="askDelete(o)"><i class="ti ti-trash f-18"></i></button>
                                    </td>
                                </tr>
                                <tr v-if="!orders.data.length">
                                    <td colspan="10" class="text-center py-4 text-muted"><i class="ti ti-tools-kitchen-2 f-40 d-block mb-2"></i>No orders found.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <TableFooter :from="orders.from" :to="orders.to" :total="orders.total"
                        :can-prev="!!orders.prev_page_url" :can-next="!!orders.next_page_url"
                        @prev="go(orders.prev_page_url)" @next="go(orders.next_page_url)" />
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
watch(() => filters.per_page, reload);
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
