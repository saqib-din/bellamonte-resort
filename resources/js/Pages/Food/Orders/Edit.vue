<template>
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><Link href="/dashboard">Home</Link></li>
                        <li class="breadcrumb-item"><Link href="/food/orders">Food Orders</Link></li>
                        <li class="breadcrumb-item" aria-current="page">Edit — {{ order.order_number }}</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title"><h2 class="mb-0">🍽️ Edit Order — {{ order.order_number }}</h2></div>
                </div>
            </div>
        </div>
    </div>

    <OrderForm mode="edit" :order="order" :categories="categories" :bookings="bookings" :customers="customers" />
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import OrderForm from '@/Pages/Food/Orders/OrderForm.vue';

defineOptions({ layout: AppLayout });

defineProps({
    order:      { type: Object, required: true },
    categories: { type: Array, default: () => [] },
    bookings:   { type: Array, default: () => [] },
    customers:  { type: Array, default: () => [] },
});
</script>
