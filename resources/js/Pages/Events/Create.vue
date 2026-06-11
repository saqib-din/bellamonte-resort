<template>
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><Link href="/dashboard">Home</Link></li>
                        <li class="breadcrumb-item"><Link href="/events">Events</Link></li>
                        <li class="breadcrumb-item" aria-current="page">Create</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title"><h2 class="mb-0">Add New Event</h2></div>
                </div>
            </div>
        </div>
    </div>

    <EventForm mode="create" :count="count" />
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import EventForm from '@/Pages/Events/EventForm.vue';

defineOptions({ layout: AppLayout });

defineProps({
    count: { type: Number, default: 0 },
});
</script>
