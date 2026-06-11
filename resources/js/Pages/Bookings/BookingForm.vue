<template>
    <form @submit.prevent="submit">
        <div class="row">
            <!-- LEFT -->
            <div class="col-lg-8">
                <!-- Room & Customer -->
                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0"><i class="ti ti-home me-2"></i>Room & Customer</h5></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Select Room <span class="text-danger">*</span></label>
                                <SearchSelect v-model="form.room_id" :options="roomOpts" :invalid="!!form.errors.room_id"
                                    placeholder="-- Select Room --" search-placeholder="Search room..." />
                                <div v-if="form.errors.room_id" class="text-danger f-12 mt-1">{{ form.errors.room_id }}</div>
                                <div v-if="selectedRoom" class="mt-2 p-2 bg-light rounded">
                                    <small>
                                        <span class="text-muted">Type:</span> <strong>{{ selectedRoom.type }}</strong> &nbsp;|&nbsp;
                                        <span class="text-muted">Capacity:</span> <strong>{{ selectedRoom.capacity }} persons</strong> &nbsp;|&nbsp;
                                        <span class="text-muted">Price:</span> <strong class="text-success">₨{{ n(selectedRoom.price) }}</strong>/night
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Select Customer <span class="text-danger">*</span></label>
                                <SearchSelect v-model="form.customer_id" :options="customerOpts" :invalid="!!form.errors.customer_id"
                                    placeholder="-- Select Customer --" search-placeholder="Search customer..." @change="fillCustomer" />
                                <div v-if="form.errors.customer_id" class="text-danger f-12 mt-1">{{ form.errors.customer_id }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Guest Details -->
                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0"><i class="ti ti-user me-2"></i>Guest Details</h5></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Guest Name <span class="text-danger">*</span></label>
                                <input type="text" v-model="form.guest_name" class="form-control" :class="{ 'is-invalid': form.errors.guest_name }" placeholder="Full name">
                                <div v-if="form.errors.guest_name" class="invalid-feedback">{{ form.errors.guest_name }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Father Name</label>
                                <input type="text" v-model="form.father_name" class="form-control" placeholder="Father name (optional)">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone <span class="text-danger">*</span></label>
                                <input type="text" v-model="form.guest_phone" class="form-control" :class="{ 'is-invalid': form.errors.guest_phone }" placeholder="0300-1234567">
                                <div v-if="form.errors.guest_phone" class="invalid-feedback">{{ form.errors.guest_phone }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">CNIC / Passport</label>
                                <input type="text" v-model="form.guest_cnic" class="form-control" placeholder="35202-1234567-1">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" v-model="form.guest_email" class="form-control" placeholder="guest@email.com">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Adults <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ti ti-users"></i></span>
                                    <input type="number" v-model="form.adults" class="form-control" min="1">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Children</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ti ti-users"></i></span>
                                    <input type="number" v-model="form.children" class="form-control" min="0">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dates -->
                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0"><i class="ti ti-calendar me-2"></i>Check In / Check Out</h5></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Check In Date <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ti ti-calendar-event"></i></span>
                                    <input type="date" v-model="form.check_in" class="form-control" :class="{ 'is-invalid': form.errors.check_in }">
                                </div>
                                <div v-if="form.errors.check_in" class="invalid-feedback d-block">{{ form.errors.check_in }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Check Out Date <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ti ti-calendar-event"></i></span>
                                    <input type="date" v-model="form.check_out" class="form-control" :class="{ 'is-invalid': form.errors.check_out }">
                                </div>
                                <div v-if="form.errors.check_out" class="invalid-feedback d-block">{{ form.errors.check_out }}</div>
                            </div>
                            <div class="col-12" v-if="nights > 0 && selectedRoom">
                                <div class="alert alert-success mb-0 py-2">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                        <span><i class="ti ti-moon me-1"></i> Nights: <strong>{{ nights }}</strong></span>
                                        <span><i class="ti ti-currency-rupee me-1"></i> Per Night: <strong>₨{{ n(selectedRoom.price) }}</strong></span>
                                        <span class="fs-5"><i class="ti ti-calculator me-1"></i> Total: <strong class="text-success">₨{{ n(total) }}</strong></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0"><i class="ti ti-notes me-2"></i>Notes</h5></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Special Requests (Guest)</label>
                                <textarea v-model="form.special_requests" class="form-control" rows="3" placeholder="Any special requests..."></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Admin Notes</label>
                                <textarea v-model="form.notes" class="form-control" rows="3" placeholder="Internal notes..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT -->
            <div class="col-lg-4">
                <div v-if="mode === 'edit' && booking" class="card mb-4 border-warning">
                    <div class="card-header bg-light-warning"><h5 class="mb-0 text-warning"><i class="ti ti-info-circle me-2"></i>Current Info</h5></div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><small class="text-muted">Booking #</small><strong class="d-block">{{ booking.booking_number }}</strong></li>
                            <li class="mb-2"><small class="text-muted">Current Room</small><strong class="d-block">{{ booking.room_label }}</strong></li>
                            <li><small class="text-muted">Current Total</small><strong class="d-block text-success">₨{{ n(booking.total_amount) }}</strong></li>
                        </ul>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0"><i class="ti ti-settings me-2"></i>Booking Status</h5></div>
                    <div class="card-body">
                        <label class="form-label">Booking Status <span class="text-danger">*</span></label>
                        <select v-model="form.status" class="form-select">
                            <option v-for="s in statusOptions" :key="s.value" :value="s.value">{{ s.icon }} {{ s.value }}</option>
                        </select>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0"><i class="ti ti-credit-card me-2"></i>Payment</h5></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Payment Status</label>
                            <select v-model="form.payment_status" class="form-select">
                                <option v-for="ps in paymentStatuses" :key="ps" :value="ps">{{ ps }}</option>
                            </select>
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Payment Method</label>
                            <select v-model="form.payment_method" class="form-select">
                                <option v-for="m in paymentMethods" :key="m" :value="m">{{ m }}</option>
                            </select>
                        </div>
                        <div v-if="mode === 'edit' && booking" class="alert alert-info py-2 mb-0 mt-3">
                            <small><i class="ti ti-wallet me-1"></i> Remaining: <strong>₨{{ n(booking.remaining) }}</strong></small>
                        </div>
                    </div>
                </div>

                <!-- Quick Info -->
                <div class="card mb-4 border-info">
                    <div class="card-header bg-light-info"><h5 class="mb-0 text-info"><i class="ti ti-info-circle me-2"></i>Quick Info</h5></div>
                    <div class="card-body f-13">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><i class="ti ti-home text-primary me-1"></i><strong>Room &amp; Customer</strong><p class="text-muted mb-0 ms-3">Select an available room and customer. Guest details will be auto-filled from the selected customer.</p></li>
                            <hr class="my-2">
                            <li class="mb-2"><i class="ti ti-calendar text-warning me-1"></i><strong>Check In / Check Out</strong><p class="text-muted mb-0 ms-3">Total amount is calculated automatically based on selected dates and room price per night.</p></li>
                            <hr class="my-2">
                            <li class="mb-2"><i class="ti ti-settings text-success me-1"></i><strong>Booking Status</strong><p class="text-muted mb-0 ms-3">Set as Confirmed for future bookings or Checked In if the guest has already arrived.</p></li>
                            <hr class="my-2">
                            <li class="mb-2"><i class="ti ti-credit-card text-secondary me-1"></i><strong>Payment</strong><p class="text-muted mb-0 ms-3">Record the payment status and method. Full payment can be recorded at checkout via billing.</p></li>
                            <hr class="my-2">
                            <li><i class="ti ti-notes text-danger me-1"></i><strong>Notes</strong><p class="text-muted mb-0 ms-3">Special requests are visible to staff. Admin notes are for internal use only.</p></li>
                        </ul>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary" :disabled="form.processing">
                                <i class="ti ti-device-floppy me-2"></i>{{ mode === 'edit' ? 'Update Booking' : 'Confirm Booking' }}
                            </button>
                            <Link href="/bookings" class="btn btn-outline-secondary"><i class="ti ti-arrow-left me-2"></i>Cancel</Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</template>

<script setup>
import { computed } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import SearchSelect from '@/Components/SearchSelect.vue';

const props = defineProps({
    mode:      { type: String, default: 'create' },
    booking:   { type: Object, default: null },
    rooms:     { type: Array, default: () => [] },
    customers: { type: Array, default: () => [] },
});

const today    = new Date().toISOString().slice(0, 10);
const tomorrow = new Date(Date.now() + 86400000).toISOString().slice(0, 10);

const b = props.booking || {};
const form = useForm({
    room_id:          b.room_id ?? '',
    customer_id:      b.customer_id ?? '',
    guest_name:       b.guest_name ?? '',
    father_name:      b.father_name ?? '',
    guest_phone:      b.guest_phone ?? '',
    guest_cnic:       b.guest_cnic ?? '',
    guest_email:      b.guest_email ?? '',
    adults:           b.adults ?? 1,
    children:         b.children ?? 0,
    check_in:         b.check_in ?? today,
    check_out:        b.check_out ?? tomorrow,
    payment_status:   b.payment_status ?? 'Pending',
    payment_method:   b.payment_method ?? 'Cash',
    status:           b.status ?? 'Confirmed',
    special_requests: b.special_requests ?? '',
    notes:            b.notes ?? '',
});

const paymentMethods  = ['Cash', 'Card', 'Bank Transfer', 'JazzCash', 'EasyPaisa'];
const paymentStatuses = props.mode === 'edit' ? ['Pending', 'Paid', 'Partial', 'Refunded'] : ['Pending', 'Paid', 'Partial'];
const statusOptions = props.mode === 'edit'
    ? [
        { value: 'Confirmed', icon: '✅' }, { value: 'Checked In', icon: '🏨' },
        { value: 'Checked Out', icon: '🚪' }, { value: 'Cancelled', icon: '❌' }, { value: 'No Show', icon: '👻' },
    ]
    : [{ value: 'Confirmed', icon: '✅' }, { value: 'Checked In', icon: '🏨' }];

const selectedRoom = computed(() => props.rooms.find((r) => r.id === form.room_id) || null);

const roomOpts = computed(() => props.rooms.map((r) => ({
    value: r.id,
    label: `Room ${r.room_number} — ${r.type} (₨${n(r.price)}/night)` +
        (props.mode === 'edit' && r.id !== form.room_id && r.status !== 'Available' ? ' [Occupied]' : ''),
})));

const customerOpts = computed(() => props.customers.map((c) => ({
    value: c.id,
    label: `${c.name} — ${c.phone}`,
})));

const nights = computed(() => {
    if (!form.check_in || !form.check_out) return 0;
    const d = Math.ceil((new Date(form.check_out) - new Date(form.check_in)) / 86400000);
    return d > 0 ? d : 0;
});

const total = computed(() => (selectedRoom.value ? nights.value * selectedRoom.value.price : 0));

const n = (v) => Number(v || 0).toLocaleString('en-US');

function fillCustomer() {
    const c = props.customers.find((x) => x.id === form.customer_id);
    if (!c) return;
    form.guest_name  = c.name || '';
    form.guest_phone = c.phone || '';
    form.guest_cnic  = c.cnic || '';
    form.guest_email = c.email || '';
}

function submit() {
    if (props.mode === 'edit') {
        form.put(`/bookings/${props.booking.uuid}`);
    } else {
        form.post('/bookings');
    }
}
</script>
