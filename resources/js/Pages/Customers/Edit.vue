<template>
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><Link href="/dashboard">Home</Link></li>
                        <li class="breadcrumb-item"><Link href="/customers">Customers</Link></li>
                        <li class="breadcrumb-item" aria-current="page">Edit — {{ customer.name }}</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title"><h2 class="mb-0">Edit Customer</h2></div>
                </div>
            </div>
        </div>
    </div>

    <CustomerForm mode="edit" :customer="customer" />
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import CustomerForm from '@/Pages/Customers/CustomerForm.vue';

defineOptions({ layout: AppLayout });

defineProps({
    customer: { type: Object, required: true },
});
</script>
