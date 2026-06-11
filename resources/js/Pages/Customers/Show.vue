<template>
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><Link href="/dashboard">Home</Link></li>
                        <li class="breadcrumb-item"><Link href="/customers">Customers</Link></li>
                        <li class="breadcrumb-item" aria-current="page">{{ customer.name }}</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="page-header-title"><h2 class="mb-0">{{ customer.name }}</h2></div>
                        <div class="d-flex gap-2">
                            <Link :href="`/customers/${customer.uuid}/edit`" class="btn btn-primary"><i class="ti ti-edit me-1"></i> Edit</Link>
                            <Link href="/customers" class="btn btn-outline-secondary"><i class="ti ti-arrow-left me-1"></i> Back</Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- LEFT: Profile -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-body text-center p-4">
                    <img v-if="customer.image" :src="customer.image" class="rounded-circle mb-3" style="width:110px;height:110px;object-fit:cover;">
                    <div v-else class="rounded-circle bg-light-primary d-flex align-items-center justify-content-center mx-auto mb-3" style="width:110px;height:110px;">
                        <span class="fw-bold text-primary" style="font-size:2.5rem;">{{ (customer.name || '?').charAt(0).toUpperCase() }}</span>
                    </div>
                    <h4 class="mb-1">{{ customer.name }}</h4>
                    <p v-if="customer.father_name" class="text-muted mb-1 f-13">S/O {{ customer.father_name }}</p>
                    <p class="text-muted mb-2">{{ customer.email || 'No email' }}</p>
                    <span class="badge mb-3" :class="customer.statusBadge">{{ customer.status }}</span>
                    <hr>
                    <div class="row text-center">
                        <div class="col-4"><h5 class="mb-0 text-primary">{{ customer.totalStays }}</h5><small class="text-muted">Total Stays</small></div>
                        <div class="col-4"><h5 class="mb-0 text-success">₨{{ n(customer.totalSpent) }}</h5><small class="text-muted">Total Spent</small></div>
                        <div class="col-4"><h5 class="mb-0 text-info">{{ customer.age ?? '—' }}</h5><small class="text-muted">Age</small></div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0"><i class="ti ti-info-circle me-2"></i>Details</h5></div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li v-if="customer.father_name" class="mb-3 d-flex gap-3"><i class="ti ti-user-check text-muted mt-1"></i><div><small class="text-muted d-block">Father Name</small><strong>{{ customer.father_name }}</strong></div></li>
                        <li class="mb-3 d-flex gap-3"><i class="ti ti-id text-muted mt-1"></i><div><small class="text-muted d-block">CNIC / Passport</small><strong>{{ customer.cnic }}</strong></div></li>
                        <li class="mb-3 d-flex gap-3"><i class="ti ti-phone text-muted mt-1"></i><div><small class="text-muted d-block">Phone</small><strong>{{ customer.phone }}</strong></div></li>
                        <li class="mb-3 d-flex gap-3"><i class="ti ti-user text-muted mt-1"></i><div><small class="text-muted d-block">Gender</small><strong>{{ customer.gender || '—' }}</strong></div></li>
                        <li class="mb-3 d-flex gap-3"><i class="ti ti-flag text-muted mt-1"></i><div><small class="text-muted d-block">Nationality</small><strong>{{ customer.nationality }}</strong></div></li>
                        <li class="mb-3 d-flex gap-3"><i class="ti ti-building text-muted mt-1"></i><div><small class="text-muted d-block">City</small><strong>{{ customer.city || '—' }}</strong></div></li>
                        <li v-if="customer.address" class="mb-3 d-flex gap-3"><i class="ti ti-map-pin text-muted mt-1"></i><div><small class="text-muted d-block">Address</small><strong>{{ customer.address }}</strong></div></li>
                        <li v-if="customer.notes" class="d-flex gap-3"><i class="ti ti-notes text-muted mt-1"></i><div><small class="text-muted d-block">Notes</small><strong>{{ customer.notes }}</strong></div></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- RIGHT: Booking History -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="ti ti-calendar me-2"></i>Booking History</h5>
                    <span class="badge bg-light-primary">{{ customer.bookings.length }} bookings</span>
                </div>
                <div class="card-body table-card" style="padding:0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Booking #</th><th>Room</th><th>Check In</th><th>Check Out</th><th>Nights</th><th>Total</th><th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="b in customer.bookings" :key="b.uuid">
                                    <td><a :href="`/bookings/${b.uuid}`" class="text-primary fw-500">{{ b.booking_number }}</a></td>
                                    <td>Room <strong>{{ b.room_number }}</strong><small class="text-muted d-block">{{ b.room_type }}</small></td>
                                    <td>{{ b.check_in }}</td>
                                    <td>{{ b.check_out }}</td>
                                    <td><span class="badge bg-light-secondary">{{ b.nights }}n</span></td>
                                    <td class="text-success fw-500">₨{{ n(b.total_amount) }}</td>
                                    <td><span class="badge" :class="b.statusBadge">{{ b.status }}</span></td>
                                </tr>
                                <tr v-if="!customer.bookings.length">
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <i class="ti ti-calendar-off f-30 d-block mb-1"></i>
                                        No bookings found
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
    customer: { type: Object, required: true },
});

const n = (v) => Number(v || 0).toLocaleString('en-US');
</script>
