<template>
    <!-- Breadcrumb -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><Link href="/dashboard">Home</Link></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Rooms</a></li>
                        <li class="breadcrumb-item" aria-current="page">List</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Rooms Management</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-sm-flex align-items-center justify-content-between">
                        <h5 class="mb-3 mb-sm-0">All Rooms</h5>
                        <Link href="/admin/rooms/create" class="btn btn-primary d-flex">
                            <i class="ti ti-plus me-1"></i> Add New Room
                        </Link>
                    </div>
                </div>

                <div class="card-body table-card">
                    <TableToolbar v-model:perPage="perPage" v-model:search="search" :per-page-options="[10, 15, 25, 50, 100]" />

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th role="button" @click="sortBy('id')"># <SortIcon col="id" :active="sort" :dir="dir" /></th>
                                    <th role="button" @click="sortBy('room_number')">Room <SortIcon col="room_number" :active="sort" :dir="dir" /></th>
                                    <th role="button" @click="sortBy('type')">Type <SortIcon col="type" :active="sort" :dir="dir" /></th>
                                    <th>Check In</th>
                                    <th>Check Out</th>
                                    <th role="button" @click="sortBy('price_per_night')">Price <SortIcon col="price_per_night" :active="sort" :dir="dir" /></th>
                                    <th role="button" @click="sortBy('status')">Status <SortIcon col="status" :active="sort" :dir="dir" /></th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="room in paginated" :key="room.uuid">
                                    <td>{{ room.id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <img v-if="room.image" :src="room.image" :alt="`Room ${room.room_number}`"
                                                    style="width:45px;height:45px;object-fit:cover;" class="rounded">
                                                <div v-else class="avtar avtar-s bg-light-primary rounded">
                                                    <i class="ti ti-bed f-20"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-0">Room {{ room.room_number }}</h6>
                                                <small class="text-muted">Floor {{ room.floor }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-light-primary">{{ room.type }}</span></td>
                                    <td><i class="ti ti-clock f-13 text-muted"></i> {{ room.check_in_time || '—' }}</td>
                                    <td><i class="ti ti-clock f-13 text-muted"></i> {{ room.check_out_time || '—' }}</td>
                                    <td><strong class="text-success">₨ {{ n(room.price_per_night) }}</strong></td>
                                    <td><span class="badge" :class="room.statusBadge">{{ room.status }}</span></td>
                                    <td class="text-end">
                                        <Link :href="`/admin/rooms/${room.uuid}`" class="avtar avtar-xs btn-link-secondary" title="View">
                                            <i class="ti ti-eye f-20"></i>
                                        </Link>
                                        <Link :href="`/admin/rooms/${room.uuid}/edit`" class="avtar avtar-xs btn-link-secondary" title="Edit">
                                            <i class="ti ti-edit f-20"></i>
                                        </Link>
                                        <button type="button" class="avtar avtar-xs btn-link-secondary" title="Delete" @click="askDelete(room)">
                                            <i class="ti ti-trash f-20"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="!filtered.length">
                                    <td colspan="8" class="text-center py-4 text-muted">
                                        <i class="ti ti-bed f-40 d-block mb-2"></i>
                                        No rooms found — please add your first room!
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <TableFooter :from="filtered.length ? from + 1 : 0" :to="to" :total="filtered.length"
                        :can-prev="page > 1" :can-next="page < totalPages"
                        @prev="page > 1 && page--" @next="page < totalPages && page++" />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { swalDelete } from '@/lib/swalDelete';
import TableToolbar from '@/Components/TableToolbar.vue';
import TableFooter from '@/Components/TableFooter.vue';
import SortIcon from '@/Components/SortIcon.vue';

defineOptions({ layout: AppLayout });

const props = defineProps({
    rooms: { type: Array, default: () => [] },
});

const search  = ref('');
const sort    = ref('id');
const dir     = ref('desc');
const page    = ref(1);
const perPage = ref(15);

const n = (v) => Number(v || 0).toLocaleString('en-US');

const filtered = computed(() => {
    const s = search.value.trim().toLowerCase();
    let list = props.rooms.filter((r) =>
        !s ||
        String(r.room_number).toLowerCase().includes(s) ||
        (r.type || '').toLowerCase().includes(s) ||
        (r.status || '').toLowerCase().includes(s),
    );
    list = [...list].sort((a, b) => {
        let x = a[sort.value], y = b[sort.value];
        if (typeof x === 'string') { x = x.toLowerCase(); y = (y || '').toLowerCase(); }
        if (x < y) return dir.value === 'asc' ? -1 : 1;
        if (x > y) return dir.value === 'asc' ? 1 : -1;
        return 0;
    });
    return list;
});

const totalPages = computed(() => Math.max(1, Math.ceil(filtered.value.length / perPage.value)));
const from       = computed(() => (page.value - 1) * perPage.value);
const to         = computed(() => Math.min(from.value + perPage.value, filtered.value.length));
const paginated  = computed(() => filtered.value.slice(from.value, from.value + perPage.value));

watch([search, sort, dir, perPage], () => { page.value = 1; });

function sortBy(col) {
    if (sort.value === col) dir.value = dir.value === 'asc' ? 'desc' : 'asc';
    else { sort.value = col; dir.value = 'asc'; }
}

function askDelete(room) {
    swalDelete(() => router.delete(`/admin/rooms/${room.uuid}`, { preserveScroll: true }));
}
</script>
