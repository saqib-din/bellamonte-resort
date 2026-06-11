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
                    <div v-else class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr><th>#</th><th>Image</th><th>Title</th><th>Tag</th><th>Date</th><th>Order</th><th>Status</th><th class="text-end">Action</th></tr>
                            </thead>
                            <tbody>
                                <tr v-for="(event, i) in events" :key="event.slug">
                                    <td>{{ i + 1 }}</td>
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
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineOptions({ layout: AppLayout });

defineProps({
    events: { type: Array, default: () => [] },
    count:  { type: Number, default: 0 },
});

function truncate(title, words) {
    const parts = (title || '').split(' ');
    return parts.length > words ? parts.slice(0, words).join(' ') + '...' : title;
}
</script>
