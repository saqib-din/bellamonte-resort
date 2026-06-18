<template>
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><Link href="/dashboard">Home</Link></li>
                        <li class="breadcrumb-item">Billing</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title"><h2 class="mb-0">Invoices</h2></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-sm-flex align-items-center justify-content-between">
                        <h5 class="mb-3 mb-sm-0">All Invoices</h5>
                        <Link href="/billing/create" class="btn btn-primary d-flex"><i class="ti ti-plus me-1"></i> New Invoice</Link>
                    </div>
                </div>
                <div class="card-body table-card">
                    <TableToolbar v-model:perPage="filters.per_page" v-model:search="filters.search" :per-page-options="[10, 15, 25, 50, 100]" />

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th role="button" @click="sortBy('invoice_number')">Invoice # <SortIcon col="invoice_number" :active="filters.sort" :dir="filters.dir" /></th>
                                    <th role="button" @click="sortBy('guest_name')">Guest <SortIcon col="guest_name" :active="filters.sort" :dir="filters.dir" /></th>
                                    <th>Room</th>
                                    <th>Check In</th>
                                    <th>Check Out</th>
                                    <th>Method</th>
                                    <th role="button" @click="sortBy('total_amount')">Total <SortIcon col="total_amount" :active="filters.sort" :dir="filters.dir" /></th>
                                    <th role="button" @click="sortBy('status')">Status <SortIcon col="status" :active="filters.sort" :dir="filters.dir" /></th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="b in bills.data" :key="b.uuid">
                                    <td><strong class="text-muted">{{ b.invoice_number }}</strong></td>
                                    <td><h6 class="mb-0">{{ b.guest_name }}</h6><small class="text-muted">{{ b.guest_phone }}</small></td>
                                    <td>
                                        <template v-if="b.room_number">
                                            <span class="badge bg-light-primary">Room {{ b.room_number }}</span>
                                            <small class="text-muted d-block">{{ b.room_type }}</small>
                                        </template>
                                        <span v-else class="text-muted">—</span>
                                    </td>
                                    <td>{{ b.check_in || '—' }}</td>
                                    <td>{{ b.check_out || '—' }}</td>
                                    <td><small>{{ b.payment_method }}</small></td>
                                    <td><strong class="text-muted">₨ {{ n(b.total_amount) }}</strong></td>
                                    <td><span class="badge" :class="b.statusBadge">{{ b.status }}</span></td>
                                    <td class="text-end">
                                        <a :href="`/billing/${b.uuid}/print`" target="_blank" class="avtar avtar-xs btn-link-secondary" title="Print"><i class="ti ti-printer f-18"></i></a>
                                        <Link :href="`/billing/${b.uuid}`" class="avtar avtar-xs btn-link-secondary" title="View"><i class="ti ti-eye f-18"></i></Link>
                                        <Link :href="`/billing/${b.uuid}/edit`" class="avtar avtar-xs btn-link-secondary" title="Edit"><i class="ti ti-edit f-18"></i></Link>
                                        <button class="avtar avtar-xs btn-link-secondary" title="Delete" @click="askDelete(b)"><i class="ti ti-trash f-18"></i></button>
                                    </td>
                                </tr>
                                <tr v-if="!bills.data.length">
                                    <td colspan="9" class="text-center py-4 text-muted"><i class="ti ti-file-invoice f-40 d-block mb-2"></i>No invoices found — please create your first invoice!</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <TableFooter :from="bills.from" :to="bills.to" :total="bills.total"
                        :can-prev="!!bills.prev_page_url" :can-next="!!bills.next_page_url"
                        @prev="go(bills.prev_page_url)" @next="go(bills.next_page_url)" />
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
    bills:   { type: Object, required: true },
    filters: { type: Object, default: () => ({}) },
});

const filters = reactive({
    search:   props.filters.search   ?? '',
    sort:     props.filters.sort     ?? 'issue_date',
    dir:      props.filters.dir      ?? 'desc',
    per_page: props.filters.per_page ?? 15,
});

function reload() {
    router.get('/billing', {
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
function go(url) { if (url) router.get(url, {}, { preserveState: true, preserveScroll: true }); }
function askDelete(b) {
    swalDelete(() => router.delete(`/billing/${b.uuid}`, { preserveScroll: true }));
}
const n = (v) => Number(v || 0).toLocaleString('en-US');
</script>
