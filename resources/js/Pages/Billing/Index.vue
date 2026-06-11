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
                        <div class="d-flex gap-2 align-items-center">
                            <select v-model="filters.per_page" class="form-select form-select-sm" style="width:90px;" @change="reload">
                                <option :value="15">15</option><option :value="30">30</option><option :value="50">50</option><option :value="100">100</option>
                            </select>
                            <input type="text" v-model="filters.search" class="form-control form-control-sm" style="width:200px;" placeholder="Search...">
                            <Link href="/billing/create" class="btn btn-primary d-flex"><i class="ti ti-plus me-1"></i> New Invoice</Link>
                        </div>
                    </div>
                </div>
                <div class="card-body table-card">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th role="button" @click="sortBy('invoice_number')">Invoice #</th>
                                    <th role="button" @click="sortBy('guest_name')">Guest</th>
                                    <th>Room</th>
                                    <th>Check In</th>
                                    <th>Check Out</th>
                                    <th>Method</th>
                                    <th role="button" @click="sortBy('total_amount')">Total</th>
                                    <th role="button" @click="sortBy('status')">Status</th>
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
                                    <td colspan="9" class="text-center py-4 text-muted"><i class="ti ti-file-invoice f-40 d-block mb-2"></i>No invoices found.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-2">
                        <small class="text-muted">Showing {{ bills.from || 0 }} to {{ bills.to || 0 }} of {{ bills.total }} invoices</small>
                        <nav v-if="bills.last_page > 1">
                            <ul class="pagination mb-0">
                                <li v-for="(link, i) in bills.links" :key="i" class="page-item" :class="{ active: link.active, disabled: !link.url }">
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
function sortBy(col) {
    if (filters.sort === col) filters.dir = filters.dir === 'asc' ? 'desc' : 'asc';
    else { filters.sort = col; filters.dir = 'asc'; }
    reload();
}
function go(url) { router.get(url, {}, { preserveState: true, preserveScroll: true }); }
function askDelete(b) {
    swalDelete(() => router.delete(`/billing/${b.uuid}`, { preserveScroll: true }));
}
const n = (v) => Number(v || 0).toLocaleString('en-US');
</script>
