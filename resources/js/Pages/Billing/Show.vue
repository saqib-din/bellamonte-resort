<template>
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><Link href="/dashboard">Home</Link></li>
                        <li class="breadcrumb-item"><Link href="/billing">Billing</Link></li>
                        <li class="breadcrumb-item" aria-current="page">{{ bill.invoice_number }}</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="page-header-title"><h2 class="mb-0">{{ bill.invoice_number }}</h2></div>
                        <div class="d-flex gap-2 flex-wrap">
                            <a :href="`/billing/${bill.uuid}/print`" target="_blank" class="btn btn-success"><i class="ti ti-printer me-1"></i> Print</a>
                            <Link :href="`/billing/${bill.uuid}/edit`" class="btn btn-primary"><i class="ti ti-edit me-1"></i> Edit</Link>
                            <Link href="/billing" class="btn btn-outline-secondary"><i class="ti ti-arrow-left me-1"></i> Back</Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- LEFT: Invoice -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-3">
                        <div>
                            <h3 class="mb-1" style="font-family:Georgia,serif;color:#c9a84c;">🏨 White Castle Resort</h3>
                            <p class="text-muted mb-0 f-13">Hotel Management System</p>
                            <p class="text-muted f-12">Shogran, Pakistan | 0329 6777222</p>
                        </div>
                        <div class="text-end">
                            <h4 class="mb-1 text-primary">INVOICE</h4>
                            <p class="mb-0 fw-500">{{ bill.invoice_number }}</p>
                            <p class="text-muted f-12 mb-0">Date: {{ bill.issue_date }}</p>
                            <span class="badge mt-1" :class="bill.statusBadge">{{ bill.status }}</span>
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-4">
                        <div class="col-sm-6">
                            <h6 class="text-muted mb-2">Bill To:</h6>
                            <p class="mb-1 fw-500">{{ bill.guest_name }}</p>
                            <p v-if="bill.father_name" class="text-muted f-13 mb-1">S/O {{ bill.father_name }}</p>
                            <p v-if="bill.guest_phone" class="text-muted f-13 mb-0">📞 {{ bill.guest_phone }}</p>
                        </div>
                        <div class="col-sm-6 text-sm-end">
                            <h6 class="text-muted mb-2">Room Details:</h6>
                            <p v-if="bill.room_number" class="mb-1 fw-500">Room {{ bill.room_number }}<template v-if="bill.room_type"> — {{ bill.room_type }}</template></p>
                            <p v-if="bill.check_in && bill.check_out" class="text-muted f-13 mb-0">{{ bill.check_in }} → {{ bill.check_out }} ({{ bill.nights }} nights)</p>
                        </div>
                    </div>

                    <table class="table table-bordered mb-4">
                        <thead class="table-light"><tr><th>Description</th><th class="text-end">Amount</th></tr></thead>
                        <tbody>
                            <tr><td>Room Charges</td><td class="text-end">₨{{ n(bill.room_charges) }}</td></tr>
                            <tr v-if="bill.extra_charges > 0"><td>Extra Services (Food, Laundry, etc.)</td><td class="text-end">₨{{ n(bill.extra_charges) }}</td></tr>
                            <tr v-if="bill.parking_charges > 0"><td>Parking Charges <small v-if="bill.vehicle_number" class="text-muted">({{ bill.vehicle_number }})</small></td><td class="text-end">₨{{ n(bill.parking_charges) }}</td></tr>
                            <tr v-if="bill.discount > 0"><td class="text-success">Discount</td><td class="text-end text-success">-₨{{ n(bill.discount) }}</td></tr>
                            <tr v-if="bill.tax_amount > 0"><td>Tax ({{ bill.tax_percent }}%)</td><td class="text-end">₨{{ n(bill.tax_amount) }}</td></tr>
                        </tbody>
                        <tfoot>
                            <tr class="table-light"><th>Total Amount</th><th class="text-end text-primary fs-5">₨{{ n(bill.total_amount) }}</th></tr>
                            <tr><td class="text-success">Amount Paid</td><td class="text-end text-success">₨{{ n(bill.amount_paid) }}</td></tr>
                            <tr v-if="bill.balance_due > 0"><td class="text-danger fw-500">Balance Due</td><td class="text-end text-danger fw-bold">₨{{ n(bill.balance_due) }}</td></tr>
                        </tfoot>
                    </table>

                    <div v-if="bill.notes" class="mb-3 p-3 rounded" style="background:var(--bs-gray-100,#f8f9fa);border:1px solid #e9ecef;">
                        <h6 class="text-muted mb-1"><i class="ti ti-notes me-1"></i>Notes</h6>
                        <p class="mb-0 f-13">{{ bill.notes }}</p>
                    </div>
                    <hr>
                    <div class="text-center text-muted f-13">
                        <p class="mb-1">Thank you for staying with us. 🙏</p>
                        <p class="mb-0">White Castle Resort — Shogran, Pakistan</p>
                    </div>
                </div>
            </div>

            <div v-if="bill.has_vehicle" class="card mb-4 border-secondary">
                <div class="card-header bg-light-secondary"><h5 class="mb-0"><i class="ti ti-car me-2"></i>Vehicle Details</h5></div>
                <div class="card-body f-13">
                    <table class="table table-sm mb-0">
                        <tbody>
                            <tr v-if="bill.vehicle_number"><td class="text-muted">Vehicle No.</td><td class="text-end fw-500">{{ bill.vehicle_number }}</td></tr>
                            <tr v-if="bill.vehicle_type"><td class="text-muted">Type</td><td class="text-end fw-500">{{ bill.vehicle_type }}</td></tr>
                            <tr v-if="bill.vehicle_model"><td class="text-muted">Model</td><td class="text-end fw-500">{{ bill.vehicle_model }}</td></tr>
                            <tr v-if="bill.vehicle_color"><td class="text-muted">Color</td><td class="text-end fw-500">{{ bill.vehicle_color }}</td></tr>
                            <tr v-if="bill.driver_name"><td class="text-muted">Driver</td><td class="text-end fw-500">{{ bill.driver_name }}</td></tr>
                            <tr><td class="text-muted">Parking Charges</td><td class="text-end fw-500">₨{{ n(bill.parking_charges) }}</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- RIGHT -->
        <div class="col-lg-4">
            <div class="card mb-4 border-primary">
                <div class="card-header bg-light-primary"><h5 class="mb-0 text-primary"><i class="ti ti-cash me-2"></i>Payment Summary</h5></div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2"><span class="text-muted f-13">Total Amount</span><span class="fw-bold text-primary">₨{{ n(bill.total_amount) }}</span></div>
                    <div class="d-flex justify-content-between mb-2"><span class="text-muted f-13">Amount Paid</span><span class="text-success fw-500">₨{{ n(bill.amount_paid) }}</span></div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between"><span class="fw-500">Balance Due</span><span class="fw-bold" :class="bill.balance_due > 0 ? 'text-danger' : 'text-success'">₨{{ n(bill.balance_due) }}</span></div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0"><i class="ti ti-info-circle me-2"></i>Bill Details</h5></div>
                <div class="card-body f-13">
                    <table class="table table-sm mb-0">
                        <tbody>
                            <tr><td class="text-muted">Invoice #</td><td class="text-end fw-500">{{ bill.invoice_number }}</td></tr>
                            <tr><td class="text-muted">Status</td><td class="text-end"><span class="badge" :class="bill.statusBadge">{{ bill.status }}</span></td></tr>
                            <tr><td class="text-muted">Payment Method</td><td class="text-end fw-500">{{ bill.payment_method }}</td></tr>
                            <tr><td class="text-muted">Issue Date</td><td class="text-end fw-500">{{ bill.issue_date }}</td></tr>
                            <tr v-if="bill.check_in"><td class="text-muted">Check In</td><td class="text-end fw-500">{{ bill.check_in }}</td></tr>
                            <tr v-if="bill.check_out"><td class="text-muted">Check Out</td><td class="text-end fw-500">{{ bill.check_out }}</td></tr>
                            <tr><td class="text-muted">Nights</td><td class="text-end fw-500">{{ bill.nights }}</td></tr>
                            <tr><td class="text-muted">Created</td><td class="text-end fw-500">{{ bill.created_at }}</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div v-if="bill.customer" class="card mb-4 border-info">
                <div class="card-header bg-light-info"><h5 class="mb-0 text-info"><i class="ti ti-link me-2"></i>Linked Records</h5></div>
                <div class="card-body f-13">
                    <span class="text-muted">Customer</span><br>
                    <span class="fw-500"><i class="ti ti-user me-1"></i>{{ bill.customer.name }}</span>
                    <div v-if="bill.customer.phone" class="text-muted">📞 {{ bill.customer.phone }}</div>
                    <div v-if="bill.customer.cnic" class="text-muted">CNIC: {{ bill.customer.cnic }}</div>
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
    bill: { type: Object, required: true },
});

const n = (v) => Number(v || 0).toLocaleString('en-US');
</script>
