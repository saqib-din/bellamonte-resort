<template>
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><Link href="/dashboard">Home</Link></li>
                        <li class="breadcrumb-item"><Link href="/billing">Billing</Link></li>
                        <li class="breadcrumb-item" aria-current="page">New Invoice</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title"><h2 class="mb-0">Create Invoice</h2></div>
                </div>
            </div>
        </div>
    </div>

    <BillForm mode="create" :bookings="bookings" :customers="customers" />
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import BillForm from '@/Pages/Billing/BillForm.vue';

defineOptions({ layout: AppLayout });

defineProps({
    bookings:  { type: Array, default: () => [] },
    customers: { type: Array, default: () => [] },
});
</script>
