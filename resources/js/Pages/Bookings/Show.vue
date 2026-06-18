<template>
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><Link href="/dashboard">Home</Link></li>
                        <li class="breadcrumb-item"><Link href="/bookings">Bookings</Link></li>
                        <li class="breadcrumb-item">{{ booking.booking_number }}</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="page-header-title"><h2 class="mb-0">Booking — {{ booking.booking_number }}</h2></div>
                        <div class="d-flex gap-2">
                            <button v-if="booking.status === 'Confirmed'" class="btn btn-success" @click="doAction('checkin')"><i class="ti ti-login me-1"></i> Check In</button>
                            <button v-if="booking.status === 'Checked In'" class="btn btn-warning" @click="doAction('checkout')"><i class="ti ti-logout me-1"></i> Check Out</button>
                            <Link v-if="!booking.locked" :href="`/bookings/${booking.uuid}/edit`" class="btn btn-primary"><i class="ti ti-edit me-1"></i> Edit</Link>
                            <span v-else class="btn btn-light-success disabled"><i class="ti ti-lock me-1"></i> Invoice Paid — Locked</span>
                            <Link href="/bookings" class="btn btn-outline-secondary"><i class="ti ti-arrow-left me-1"></i> Back</Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="ti ti-clipboard me-2"></i>Booking Details</h5>
                    <span class="badge f-14" :class="booking.statusBadge">{{ booking.status }}</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <tbody>
                                <tr><td class="text-muted fw-500" width="35%">Booking Number</td><td><strong>{{ booking.booking_number }}</strong></td></tr>
                                <tr><td class="text-muted fw-500">Room</td><td>Room <strong>{{ booking.room_number }}</strong> — {{ booking.room_type }} (Floor {{ booking.room_floor }})</td></tr>
                                <tr><td class="text-muted fw-500">Check In</td><td><i class="ti ti-calendar-event text-success me-1"></i><strong>{{ booking.check_in }}</strong><small v-if="booking.check_in_time" class="text-muted ms-2">after {{ booking.check_in_time }}</small></td></tr>
                                <tr><td class="text-muted fw-500">Check Out</td><td><i class="ti ti-calendar-event text-danger me-1"></i><strong>{{ booking.check_out }}</strong><small v-if="booking.check_out_time" class="text-muted ms-2">before {{ booking.check_out_time }}</small></td></tr>
                                <tr><td class="text-muted fw-500">Stay Type</td><td><span class="badge bg-light-primary">{{ booking.rate_type }}</span></td></tr>
                                <tr><td class="text-muted fw-500">Duration</td><td><span class="badge bg-light-secondary">{{ booking.nights }} {{ booking.unit_label }}</span></td></tr>
                                <tr><td class="text-muted fw-500">Guests</td><td>{{ booking.adults }} Adults, {{ booking.children }} Children</td></tr>
                                <tr v-if="booking.special_requests"><td class="text-muted fw-500">Special Requests</td><td>{{ booking.special_requests }}</td></tr>
                                <tr v-if="booking.notes"><td class="text-muted fw-500">Admin Notes</td><td>{{ booking.notes }}</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0"><i class="ti ti-user me-2"></i>Guest Information</h5></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <tbody>
                                <tr><td class="text-muted fw-500" width="35%">Name</td><td><strong>{{ booking.guest_name }}</strong></td></tr>
                                <tr><td class="text-muted fw-500">Father Name</td><td>{{ booking.father_name || '—' }}</td></tr>
                                <tr><td class="text-muted fw-500">Phone</td><td>{{ booking.guest_phone }}</td></tr>
                                <tr><td class="text-muted fw-500">CNIC / Passport</td><td>{{ booking.guest_cnic || '—' }}</td></tr>
                                <tr><td class="text-muted fw-500">Email</td><td>{{ booking.guest_email || '—' }}</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0"><i class="ti ti-credit-card me-2"></i>Payment Summary</h5></div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2"><span class="text-muted">Rate ({{ booking.rate_type }})</span><span>₨{{ n(booking.room_price) }}</span></div>
                    <div class="d-flex justify-content-between mb-2"><span class="text-muted">{{ booking.unit_label }}</span><span>× {{ booking.nights }}</span></div>
                    <hr>
                    <div class="d-flex justify-content-between mb-2"><span class="fw-500">Total Amount</span><span class="text-success fw-bold fs-5">₨{{ n(booking.total_amount) }}</span></div>
                    <div class="d-flex justify-content-between mb-2"><span class="text-muted">Advance Paid</span><span class="text-info">₨{{ n(booking.advance_paid) }}</span></div>
                    <div class="d-flex justify-content-between"><span class="fw-500">Remaining</span><span class="text-danger fw-bold">₨{{ n(booking.remaining) }}</span></div>
                    <hr>
                    <div class="d-flex justify-content-between mb-2"><span class="text-muted">Payment Method</span><span>{{ booking.payment_method }}</span></div>
                    <div class="d-flex justify-content-between"><span class="text-muted">Payment Status</span><span class="badge" :class="booking.paymentBadge">{{ booking.payment_status }}</span></div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h5 class="mb-0"><i class="ti ti-bolt me-2"></i>Quick Actions</h5></div>
                <div class="card-body d-grid gap-2">
                    <div v-if="booking.locked" class="alert alert-success py-2 mb-1 f-13"><i class="ti ti-lock me-1"></i> Invoice <strong>{{ booking.invoice_number }}</strong> fully paid — this booking is locked (view only).</div>
                    <Link v-if="!booking.locked" :href="`/bookings/${booking.uuid}/edit`" class="btn btn-outline-primary"><i class="ti ti-edit me-1"></i> Edit Booking</Link>
                    <Link href="/bookings" class="btn btn-outline-secondary"><i class="ti ti-list me-1"></i> All Bookings</Link>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineOptions({ layout: AppLayout });

const props = defineProps({
    booking: { type: Object, required: true },
});

const n = (v) => Number(v || 0).toLocaleString('en-US');

function doAction(action) {
    router.post(`/bookings/${props.booking.uuid}/${action}`, {}, { preserveScroll: true });
}
</script>
