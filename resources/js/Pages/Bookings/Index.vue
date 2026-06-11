<template>
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><Link href="/dashboard">Home</Link></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Bookings</a></li>
                        <li class="breadcrumb-item" aria-current="page">List</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title"><h2 class="mb-0">Bookings Management</h2></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-sm-flex align-items-center justify-content-between">
                        <h5 class="mb-3 mb-sm-0">All Bookings</h5>
                        <div class="d-flex gap-2 align-items-center">
                            <select v-model="filters.per_page" class="form-select form-select-sm" style="width:90px;" @change="reload">
                                <option :value="15">15</option>
                                <option :value="30">30</option>
                                <option :value="50">50</option>
                                <option :value="100">100</option>
                            </select>
                            <input type="text" v-model="filters.search" class="form-control form-control-sm" style="width:200px;" placeholder="Search...">
                            <Link href="/bookings/create" class="btn btn-primary d-flex"><i class="ti ti-plus me-1"></i> New Booking</Link>
                        </div>
                    </div>
                </div>

                <div class="card-body table-card">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th role="button" @click="sortBy('booking_number')">Booking #</th>
                                    <th role="button" @click="sortBy('guest_name')">Guest</th>
                                    <th>Room</th>
                                    <th role="button" @click="sortBy('check_in')">Check In</th>
                                    <th role="button" @click="sortBy('check_out')">Check Out</th>
                                    <th role="button" @click="sortBy('total_amount')">Total</th>
                                    <th role="button" @click="sortBy('status')">Status</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="b in bookings.data" :key="b.uuid">
                                    <td><strong>{{ b.booking_number }}</strong></td>
                                    <td>
                                        <h6 class="mb-0">{{ b.guest_name }}</h6>
                                        <small class="text-muted">{{ b.guest_phone }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light-primary">Room {{ b.room_number }}</span><br>
                                        <small class="text-muted">{{ b.room_type }}</small>
                                    </td>
                                    <td><i class="ti ti-calendar-event f-13 text-muted me-1"></i>{{ b.check_in }}</td>
                                    <td><i class="ti ti-calendar-event f-13 text-muted me-1"></i>{{ b.check_out }}</td>
                                    <td><strong class="text-success">₨ {{ n(b.total_amount) }}</strong></td>
                                    <td><span class="badge" :class="b.statusBadge">{{ b.status }}</span></td>
                                    <td class="text-end">
                                        <button v-if="b.status === 'Confirmed'" class="avtar avtar-xs btn-link-success" title="Check In" @click="doAction(b, 'checkin')">
                                            <i class="ti ti-login f-18"></i>
                                        </button>
                                        <button v-if="b.status === 'Checked In'" class="avtar avtar-xs btn-link-warning" title="Check Out" @click="doAction(b, 'checkout')">
                                            <i class="ti ti-logout f-18"></i>
                                        </button>
                                        <Link :href="`/bookings/${b.uuid}`" class="avtar avtar-xs btn-link-secondary" title="View"><i class="ti ti-eye f-18"></i></Link>
                                        <Link :href="`/bookings/${b.uuid}/edit`" class="avtar avtar-xs btn-link-secondary" title="Edit"><i class="ti ti-edit f-18"></i></Link>
                                        <button class="avtar avtar-xs btn-link-secondary" title="Delete" @click="askDelete(b)"><i class="ti ti-trash f-18"></i></button>
                                    </td>
                                </tr>
                                <tr v-if="!bookings.data.length">
                                    <td colspan="8" class="text-center py-4 text-muted">
                                        <i class="ti ti-calendar-off f-40 d-block mb-2"></i>
                                        No bookings found.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-2">
                        <small class="text-muted">Showing {{ bookings.from || 0 }} to {{ bookings.to || 0 }} of {{ bookings.total }} bookings</small>
                        <nav v-if="bookings.last_page > 1">
                            <ul class="pagination mb-0">
                                <li v-for="(link, i) in bookings.links" :key="i" class="page-item" :class="{ active: link.active, disabled: !link.url }">
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
    bookings: { type: Object, required: true },
    filters:  { type: Object, default: () => ({}) },
});

const filters = reactive({
    search:   props.filters.search   ?? '',
    sort:     props.filters.sort     ?? 'id',
    dir:      props.filters.dir      ?? 'desc',
    per_page: props.filters.per_page ?? 15,
});

function reload() {
    router.get('/bookings', {
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

function doAction(b, action) {
    router.post(`/bookings/${b.uuid}/${action}`, {}, { preserveScroll: true });
}

function askDelete(b) {
    swalDelete(() => router.delete(`/bookings/${b.uuid}`, { preserveScroll: true }));
}

const n = (v) => Number(v || 0).toLocaleString('en-US');
</script>
