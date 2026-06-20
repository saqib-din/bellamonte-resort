<template>
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><Link href="/dashboard">Home</Link></li>
                        <li class="breadcrumb-item"><Link href="/food/orders">Food Orders</Link></li>
                        <li class="breadcrumb-item">{{ order.order_number }}</li>
                    </ul>
                </div>
                <div class="col-md-12 d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">🍽️ {{ order.order_number }}</h2>
                    <div class="d-flex gap-2">
                        <a :href="`/food/orders/${order.uuid}/print`" target="_blank" class="btn btn-outline-secondary"><i class="ti ti-printer me-1"></i> Print</a>
                        <Link v-if="order.status !== 'Cancelled' && order.payment_status !== 'Paid'" :href="`/food/orders/${order.uuid}/edit`" class="btn btn-primary"><i class="ti ti-edit me-1"></i> Edit</Link>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0">Order Items</h5></div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr><th>Item</th><th class="text-center">Qty</th><th class="text-end">Unit Price</th><th class="text-end">Subtotal</th></tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, i) in order.items" :key="i">
                                <td>{{ item.item_name }}</td>
                                <td class="text-center">{{ item.quantity }}</td>
                                <td class="text-end">₨{{ n(item.unit_price) }}</td>
                                <td class="text-end fw-500">₨{{ n(item.subtotal) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <div class="row justify-content-end">
                        <div class="col-md-4">
                            <table class="table table-sm mb-0">
                                <tr><td class="text-muted">Subtotal</td><td class="text-end">₨{{ n(order.subtotal) }}</td></tr>
                                <tr><td class="text-muted">Discount</td><td class="text-end text-success">-₨{{ n(order.discount) }}</td></tr>
                                <tr v-if="order.tax_amount > 0"><td class="text-muted">Tax ({{ order.tax_percent }}%)</td><td class="text-end">₨{{ n(order.tax_amount) }}</td></tr>
                                <tr class="fw-bold"><td>Total</td><td class="text-end text-primary">₨{{ n(order.total_amount) }}</td></tr>
                                <tr><td class="text-muted">Paid</td><td class="text-end text-success">₨{{ n(order.amount_paid) }}</td></tr>
                                <tr class="fw-bold text-danger"><td>Balance</td><td class="text-end">₨{{ n(order.balance_due) }}</td></tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0">Order Info</h5></div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0 d-flex justify-content-between"><span class="text-muted">Order Status</span><span class="badge" :class="order.statusBadge">{{ order.status }}</span></li>
                        <li class="list-group-item px-0 d-flex justify-content-between"><span class="text-muted">Payment</span><span class="badge" :class="order.paymentBadge">{{ order.payment_status }}</span></li>
                        <li class="list-group-item px-0 d-flex justify-content-between"><span class="text-muted">Type</span><span class="badge" :class="order.orderTypeBadge">{{ order.order_type }}</span></li>
                        <li class="list-group-item px-0 d-flex justify-content-between"><span class="text-muted">Guest</span><span>{{ order.guest_name }}</span></li>
                        <li v-if="order.father_name" class="list-group-item px-0 d-flex justify-content-between"><span class="text-muted">Father Name</span><span>{{ order.father_name }}</span></li>
                        <li v-if="order.guest_phone" class="list-group-item px-0 d-flex justify-content-between"><span class="text-muted">Phone</span><span>{{ order.guest_phone }}</span></li>
                        <li v-if="order.room_number" class="list-group-item px-0 d-flex justify-content-between"><span class="text-muted">Room</span><span class="badge bg-light-primary">Room {{ order.room_number }}</span></li>
                        <li class="list-group-item px-0 d-flex justify-content-between"><span class="text-muted">Method</span><span>{{ order.payment_method }}</span></li>
                        <li class="list-group-item px-0 d-flex justify-content-between"><span class="text-muted">Date</span><span>{{ order.date }}</span></li>
                    </ul>
                </div>
            </div>

            <div v-if="order.payment_status !== 'Paid'" class="card mb-4">
                <div class="card-header"><h5 class="mb-0">Update Status</h5></div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button v-for="s in statuses" :key="s" class="btn w-100" :class="order.status === s ? 'btn-primary' : 'btn-outline-secondary'" @click="setStatus(s)">{{ s }}</button>
                    </div>
                </div>
            </div>
            <div v-else class="card mb-4">
                <div class="card-body text-center text-muted py-3">
                    <i class="ti ti-lock me-1"></i> Order is Paid &amp; Completed — status locked.
                </div>
            </div>

            <div v-if="order.notes" class="card">
                <div class="card-header"><h5 class="mb-0">Notes</h5></div>
                <div class="card-body"><p class="mb-0">{{ order.notes }}</p></div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineOptions({ layout: AppLayout });

const props = defineProps({
    order: { type: Object, required: true },
});

const statuses = ['Pending', 'Preparing', 'Served', 'Completed', 'Cancelled'];
const n = (v) => Number(v || 0).toLocaleString('en-US');

function setStatus(status) {
    router.post(`/food/orders/${props.order.uuid}/status`, { status }, { preserveScroll: true });
}
</script>
