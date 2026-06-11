<template>
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><Link href="/dashboard">Home</Link></li>
                        <li class="breadcrumb-item"><Link href="/bookings">Bookings</Link></li>
                        <li class="breadcrumb-item" aria-current="page">New Booking</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title"><h2 class="mb-0">New Booking</h2></div>
                </div>
            </div>
        </div>
    </div>

    <BookingForm mode="create" :rooms="rooms" :customers="customers" />
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import BookingForm from '@/Pages/Bookings/BookingForm.vue';

defineOptions({ layout: AppLayout });

defineProps({
    rooms:     { type: Array, default: () => [] },
    customers: { type: Array, default: () => [] },
});
</script>
