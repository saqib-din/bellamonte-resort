<template>
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><Link href="/dashboard">Home</Link></li>
                        <li class="breadcrumb-item" aria-current="page">Events</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title"><h2 class="mb-0">Events Management</h2></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="mb-0">All Events</h5>
                        <small class="text-muted">{{ count }} / 9 events added</small>
                    </div>
                    <Link v-if="count < 9" href="/events/create" class="btn btn-primary d-flex"><i class="ti ti-plus me-1"></i> Add Event</Link>
                    <span v-else class="badge bg-light-danger">Max 9 events reached</span>
                </div>

                <div class="card-body table-card">
                    <div v-if="!events.length" class="text-center py-5">
                        <i class="ti ti-calendar-off" style="font-size:48px; color:#ccc;"></i>
                        <h5 class="mt-3 text-muted">No Events Yet</h5>
                    </div>
                    <template v-else>
                        <TableToolbar v-model:perPage="perPage" v-model:search="search" :per-page-options="[10, 15, 25, 50, 100]" />
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Image</th>
                                        <th role="button" @click="sortBy('title')">Title <SortIcon col="title" :active="sort" :dir="dir" /></th>
                                        <th role="button" @click="sortBy('tag')">Tag <SortIcon col="tag" :active="sort" :dir="dir" /></th>
                                        <th role="button" @click="sortBy('event_date')">Date <SortIcon col="event_date" :active="sort" :dir="dir" /></th>
                                        <th role="button" @click="sortBy('sort_order')">Order <SortIcon col="sort_order" :active="sort" :dir="dir" /></th>
                                        <th>Status</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(event, i) in paginated" :key="event.slug">
                                        <td>{{ from + i + 1 }}</td>
                                        <td><img :src="event.image_url" alt="img" style="width:60px; height:45px; object-fit:cover; border-radius:6px;"></td>
                                        <td><div style="max-width:220px; font-weight:600; font-size:13px;" :title="event.title">{{ truncate(event.title, 4) }}</div></td>
                                        <td><span class="badge bg-light-primary">{{ event.tag }}</span></td>
                                        <td style="font-size:13px;">{{ event.event_date }}</td>
                                        <td>{{ event.sort_order }}</td>
                                        <td><span class="badge" :class="event.is_active ? 'bg-light-success' : 'bg-light-danger'">{{ event.is_active ? 'Active' : 'Inactive' }}</span></td>
                                        <td class="text-end">
                                            <Link :href="`/events/${event.slug}/edit`" class="avtar avtar-xs btn-link-secondary" title="Edit"><i class="ti ti-edit f-18"></i></Link>
                                        </td>
                                    </tr>
                                    <tr v-if="!filtered.length">
                                        <td colspan="8" class="text-center py-4 text-muted">No events match your search.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <TableFooter :from="filtered.length ? from + 1 : 0" :to="to" :total="filtered.length"
                            :can-prev="cpage > 1" :can-next="cpage < totalPages"
                            @prev="cpage > 1 && cpage--" @next="cpage < totalPages && cpage++" />
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import TableToolbar from '@/Components/TableToolbar.vue';
import TableFooter from '@/Components/TableFooter.vue';
import SortIcon from '@/Components/SortIcon.vue';

defineOptions({ layout: AppLayout });

const props = defineProps({
    events: { type: Array, default: () => [] },
    count:  { type: Number, default: 0 },
});

function truncate(title, words) {
    const parts = (title || '').split(' ');
    return parts.length > words ? parts.slice(0, words).join(' ') + '...' : title;
}

const search  = ref('');
const sort    = ref('sort_order');
const dir     = ref('asc');
const cpage   = ref(1);
const perPage = ref(15);

const filtered = computed(() => {
    const s = search.value.trim().toLowerCase();
    let list = props.events.filter((e) =>
        !s ||
        (e.title || '').toLowerCase().includes(s) ||
        (e.tag || '').toLowerCase().includes(s),
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
const from       = computed(() => (cpage.value - 1) * perPage.value);
const to         = computed(() => Math.min(from.value + perPage.value, filtered.value.length));
const paginated  = computed(() => filtered.value.slice(from.value, from.value + perPage.value));

watch([search, sort, dir, perPage], () => { cpage.value = 1; });

function sortBy(col) {
    if (sort.value === col) dir.value = dir.value === 'asc' ? 'desc' : 'asc';
    else { sort.value = col; dir.value = 'asc'; }
}
</script>
