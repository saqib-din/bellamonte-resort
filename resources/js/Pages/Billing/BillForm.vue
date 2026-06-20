<template>
    <form @submit.prevent="submit">
        <div class="row">
            <!-- LEFT -->
            <div class="col-lg-8">
                <!-- Auto-fill from booking -->
                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0"><i class="ti ti-calendar me-2"></i>Auto-Fill from Booking (Optional)</h5></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Booking Select</label>
                                <SearchSelect v-model="form.booking_id" :options="bookingOpts" :invalid="!!form.errors.booking_id" placeholder="-- Booking Select --" search-placeholder="Search booking..." @change="fillFromBooking" />
                                <div v-if="form.errors.booking_id" class="text-danger f-12 mt-1">{{ form.errors.booking_id }}</div>
                                <small class="text-muted">Select a booking to auto-fill all details.</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Customer (Optional)</label>
                                <SearchSelect v-model="form.customer_id" fetch-url="/customers/search" :preload="customers" :invalid="!!form.errors.customer_id" placeholder="-- Customer --" search-placeholder="Search customer..." />
                                <div v-if="form.errors.customer_id" class="text-danger f-12 mt-1">{{ form.errors.customer_id }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Guest & Room Info -->
                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0"><i class="ti ti-user me-2"></i>Guest &amp; Room Info</h5></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Guest Name <span class="text-danger">*</span></label>
                                <input type="text" v-model="form.guest_name" class="form-control" :class="{ 'is-invalid': form.errors.guest_name }" placeholder="Ahmed Ali">
                                <div v-if="form.errors.guest_name" class="invalid-feedback">{{ form.errors.guest_name }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Father Name</label>
                                <input type="text" v-model="form.father_name" class="form-control" :class="{ 'is-invalid': form.errors.father_name }" placeholder="Father name (optional)">
                                <div v-if="form.errors.father_name" class="invalid-feedback">{{ form.errors.father_name }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="text" v-model="form.guest_phone" class="form-control" :class="{ 'is-invalid': form.errors.guest_phone }" placeholder="0300-1234567">
                                <div v-if="form.errors.guest_phone" class="invalid-feedback">{{ form.errors.guest_phone }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Room Number</label>
                                <input type="text" v-model="form.room_number" class="form-control" :class="{ 'is-invalid': form.errors.room_number }" placeholder="101">
                                <div v-if="form.errors.room_number" class="invalid-feedback">{{ form.errors.room_number }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Room Type</label>
                                <input type="text" v-model="form.room_type" class="form-control" :class="{ 'is-invalid': form.errors.room_type }" placeholder="Deluxe">
                                <div v-if="form.errors.room_type" class="invalid-feedback">{{ form.errors.room_type }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nights</label>
                                <input type="number" v-model.number="form.nights" class="form-control" :class="{ 'is-invalid': form.errors.nights }" min="1">
                                <div v-if="form.errors.nights" class="invalid-feedback">{{ form.errors.nights }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Check In</label>
                                <input type="datetime-local" v-model="form.check_in" class="form-control" :class="{ 'is-invalid': form.errors.check_in }">
                                <div v-if="form.errors.check_in" class="invalid-feedback">{{ form.errors.check_in }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Check Out</label>
                                <input type="datetime-local" v-model="form.check_out" class="form-control" :class="{ 'is-invalid': form.errors.check_out }">
                                <div v-if="form.errors.check_out" class="invalid-feedback">{{ form.errors.check_out }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Vehicle -->
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0"><i class="ti ti-car me-2"></i>Vehicle Details</h5>
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" v-model="form.has_vehicle" id="hasVehicle">
                            <label class="form-check-label f-13" for="hasVehicle">Guest has a vehicle</label>
                        </div>
                    </div>
                    <div class="card-body" v-show="form.has_vehicle">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Vehicle Number</label>
                                <input type="text" v-model="form.vehicle_number" class="form-control text-uppercase" :class="{ 'is-invalid': form.errors.vehicle_number }" placeholder="LEA-1234">
                                <div v-if="form.errors.vehicle_number" class="invalid-feedback">{{ form.errors.vehicle_number }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Vehicle Type</label>
                                <select v-model="form.vehicle_type" class="form-select" :class="{ 'is-invalid': form.errors.vehicle_type }">
                                    <option value="">-- Select --</option>
                                    <option v-for="vt in vehicleTypes" :key="vt" :value="vt">{{ vt }}</option>
                                </select>
                                <div v-if="form.errors.vehicle_type" class="invalid-feedback">{{ form.errors.vehicle_type }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Vehicle Model</label>
                                <input type="text" v-model="form.vehicle_model" class="form-control" :class="{ 'is-invalid': form.errors.vehicle_model }" placeholder="Corolla 2022">
                                <div v-if="form.errors.vehicle_model" class="invalid-feedback">{{ form.errors.vehicle_model }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Vehicle Color</label>
                                <input type="text" v-model="form.vehicle_color" class="form-control" :class="{ 'is-invalid': form.errors.vehicle_color }" placeholder="White">
                                <div v-if="form.errors.vehicle_color" class="invalid-feedback">{{ form.errors.vehicle_color }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Driver Name</label>
                                <input type="text" v-model="form.driver_name" class="form-control" :class="{ 'is-invalid': form.errors.driver_name }" placeholder="Optional">
                                <div v-if="form.errors.driver_name" class="invalid-feedback">{{ form.errors.driver_name }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Parking Charges (₨)</label>
                                <div class="input-group"><span class="input-group-text">₨</span><input type="number" v-model.number="form.parking_charges" class="form-control" :class="{ 'is-invalid': form.errors.parking_charges || parkingChargesError }" min="0"></div>
                                <div v-if="form.errors.parking_charges || parkingChargesError" class="text-danger f-13 mt-1">{{ form.errors.parking_charges || parkingChargesError }}</div>
                                <small class="text-muted">Added to the total.</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charges -->
                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0"><i class="ti ti-calculator me-2"></i>Charges &amp; Payment</h5></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Room Charges (₨) <span class="text-danger">*</span></label>
                                <div class="input-group"><span class="input-group-text">₨</span><input type="number" v-model.number="form.room_charges" class="form-control" :class="{ 'is-invalid': form.errors.room_charges || roomChargesError }" min="0"></div>
                                <div v-if="form.errors.room_charges || roomChargesError" class="text-danger f-12 mt-1">{{ form.errors.room_charges || roomChargesError }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Extra Charges (₨) <small class="text-muted">Food, Laundry, etc.</small></label>
                                <div class="input-group"><span class="input-group-text">₨</span><input type="number" v-model.number="form.extra_charges" class="form-control" :class="{ 'is-invalid': form.errors.extra_charges || extraChargesError }" min="0"></div>
                                <div v-if="form.errors.extra_charges || extraChargesError" class="text-danger f-13 mt-1">{{ form.errors.extra_charges || extraChargesError }}</div>
                                <small v-if="selectedBooking && selectedBooking.food_count > 0" class="text-info f-12 d-block mt-1"><i class="ti ti-tools-kitchen-2 me-1"></i>Includes ₨{{ n(selectedBooking.food_total) }} from {{ selectedBooking.food_count }} food order(s) on this booking — they'll be marked Paid with this invoice.</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Discount (₨)</label>
                                <div class="input-group"><span class="input-group-text">₨</span><input type="number" v-model.number="form.discount" class="form-control" :class="{ 'is-invalid': form.errors.discount || overDiscount }" min="0"></div>
                                <div v-if="form.errors.discount || overDiscount" class="text-danger f-13 mt-1">{{ form.errors.discount || ('Discount cannot exceed subtotal (₨' + n(totals.subtotal) + ')') }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Amount Paid (₨)</label>
                                <div class="input-group"><span class="input-group-text">₨</span><input type="number" v-model.number="form.amount_paid" class="form-control" :class="{ 'is-invalid': form.errors.amount_paid || overPaid }" min="0"></div>
                                <div v-if="form.errors.amount_paid || overPaid" class="text-danger f-13 mt-1">{{ form.errors.amount_paid || ('Amount paid cannot exceed total (₨' + n(totals.total) + ')') }}</div>
                            </div>
                        </div>
                        <div class="mt-4 p-3 rounded surface-box">
                            <div class="row text-center">
                                <div class="col-3"><div class="text-muted f-12">Room + Extra + Parking</div><div class="fw-500">₨{{ n(totals.subtotal) }}</div></div>
                                <div class="col-3"><div class="text-muted f-12">Discount</div><div class="fw-500 text-success">-₨{{ n(totals.discount) }}</div></div>
                                <div class="col-3"><div class="text-muted f-12">Total</div><div class="fw-bold text-primary fs-5">₨{{ n(totals.total) }}</div></div>
                                <div class="col-3"><div class="text-muted f-12">Balance</div><div class="fw-bold text-danger">₨{{ n(totals.balance) }}</div></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0"><i class="ti ti-notes me-2"></i>Notes</h5></div>
                    <div class="card-body"><textarea v-model="form.notes" class="form-control" :class="{ 'is-invalid': form.errors.notes }" rows="3" placeholder="Enter notes ..."></textarea>
                        <div v-if="form.errors.notes" class="invalid-feedback">{{ form.errors.notes }}</div></div>
                </div>
            </div>

            <!-- RIGHT -->
            <div class="col-lg-4">
                <!-- Quick Info -->
                <div class="card mb-4 border-info">
                    <div class="card-header bg-light-info"><h5 class="mb-0 text-info"><i class="ti ti-info-circle me-2"></i>Quick Info</h5></div>
                    <div class="card-body f-13">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><i class="ti ti-calendar text-primary me-1"></i><strong>Auto-Fill from Booking</strong><p class="text-muted mb-0 ms-3">Select an existing booking to automatically fill guest name, room, dates and charges.</p></li>
                            <hr class="my-2">
                            <li class="mb-2"><i class="ti ti-car text-info me-1"></i><strong>Vehicle Details</strong><p class="text-muted mb-0 ms-3">Toggle on if the guest has a vehicle. Parking charges are added automatically to the total.</p></li>
                            <hr class="my-2">
                            <li class="mb-2"><i class="ti ti-calculator text-warning me-1"></i><strong>Live Calculator</strong><p class="text-muted mb-0 ms-3">Total amount is calculated in real-time as you enter charges and discount.</p></li>
                            <hr class="my-2">
                            <li class="mb-2"><i class="ti ti-credit-card text-secondary me-1"></i><strong>Payment Status</strong><p class="text-muted mb-0 ms-3">Status is set automatically to Paid, Unpaid, or Partial based on the amount received.</p></li>
                            <hr class="my-2">
                            <li><i class="ti ti-file-invoice text-danger me-1"></i><strong>Invoice Preview</strong><p class="text-muted mb-0 ms-3">The panel below shows a live breakdown of all charges before generating the invoice.</p></li>
                        </ul>
                    </div>
                </div>

                <!-- Payment -->
                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0"><i class="ti ti-credit-card me-2"></i>Payment</h5></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                            <select v-model="form.payment_method" class="form-select" :class="{ 'is-invalid': form.errors.payment_method }">
                                <option v-for="m in paymentMethods" :key="m" :value="m">{{ m }}</option>
                            </select>
                            <div v-if="form.errors.payment_method" class="invalid-feedback">{{ form.errors.payment_method }}</div>
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Issue Date <span class="text-danger">*</span></label>
                            <input type="date" v-model="form.issue_date" class="form-control" :class="{ 'is-invalid': form.errors.issue_date }">
                            <div v-if="form.errors.issue_date" class="invalid-feedback">{{ form.errors.issue_date }}</div>
                        </div>
                    </div>
                </div>

                <!-- Invoice Preview -->
                <div class="card mb-4 border-primary">
                    <div class="card-header bg-light-primary"><h5 class="mb-0 text-primary"><i class="ti ti-file-invoice me-2"></i>Invoice Preview</h5></div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2"><span class="text-muted f-13">Room Charges</span><span>₨{{ n(form.room_charges) }}</span></div>
                        <div class="d-flex justify-content-between mb-2"><span class="text-muted f-13">Extra Charges</span><span>₨{{ n(form.extra_charges) }}</span></div>
                        <div class="d-flex justify-content-between mb-2" v-if="form.has_vehicle && Number(form.parking_charges) > 0"><span class="text-muted f-13">Parking Charges</span><span>₨{{ n(form.parking_charges) }}</span></div>
                        <div class="d-flex justify-content-between mb-2"><span class="text-muted f-13">Discount</span><span class="text-success">-₨{{ n(totals.discount) }}</span></div>
                        <hr>
                        <div class="d-flex justify-content-between mb-2"><span class="fw-500">Total</span><span class="fw-bold text-primary">₨{{ n(totals.total) }}</span></div>
                        <div class="d-flex justify-content-between mb-2"><span class="text-muted f-13">Amount Paid</span><span class="text-success">₨{{ n(totals.paid) }}</span></div>
                        <div class="d-flex justify-content-between"><span class="fw-500">Balance Due</span><span class="fw-bold text-danger">₨{{ n(totals.balance) }}</span></div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary" :disabled="form.processing || overDiscount || overPaid || !!roomChargesError || !!extraChargesError || !!parkingChargesError"><i class="ti ti-file-invoice me-2"></i>{{ mode === 'edit' ? 'Update Invoice' : 'Generate Invoice' }}</button>
                            <Link href="/billing" class="btn btn-outline-secondary"><i class="ti ti-arrow-left me-2"></i>Cancel</Link>
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
    bill:      { type: Object, default: null },
    bookings:  { type: Array, default: () => [] },
    customers: { type: Array, default: () => [] },
});

const vehicleTypes   = ['Car', 'SUV', 'Van', 'Bike', 'Jeep', 'Other'];
const paymentMethods = ['Cash', 'Card', 'Bank Transfer', 'JazzCash', 'EasyPaisa'];
const today = new Date().toISOString().slice(0, 10);

const b = props.bill || {};
const form = useForm({
    booking_id:      b.booking_id ?? '',
    customer_id:     b.customer_id ?? '',
    guest_name:      b.guest_name ?? '',
    father_name:     b.father_name ?? '',
    guest_phone:     b.guest_phone ?? '',
    room_number:     b.room_number ?? '',
    room_type:       b.room_type ?? '',
    nights:          b.nights ?? 1,
    check_in:        b.check_in ?? '',
    check_out:       b.check_out ?? '',
    has_vehicle:     b.has_vehicle ?? false,
    vehicle_number:  b.vehicle_number ?? '',
    vehicle_type:    b.vehicle_type ?? '',
    vehicle_model:   b.vehicle_model ?? '',
    vehicle_color:   b.vehicle_color ?? '',
    driver_name:     b.driver_name ?? '',
    parking_charges: Number(b.parking_charges ?? 0),
    room_charges:    Number(b.room_charges ?? 0),
    extra_charges:   Number(b.extra_charges ?? 0),
    discount:        Number(b.discount ?? 0),
    amount_paid:     Number(b.amount_paid ?? 0),
    payment_method:  b.payment_method ?? 'Cash',
    issue_date:      b.issue_date ?? today,
    notes:           b.notes ?? '',
});

const bookingOpts  = computed(() => props.bookings.map((x) => ({ value: x.id, label: x.label })));
const selectedBooking = computed(() => props.bookings.find((x) => x.id === form.booking_id) || null);

const totals = computed(() => {
    const room    = Number(form.room_charges) || 0;
    const extra   = Number(form.extra_charges) || 0;
    const parking = form.has_vehicle ? (Number(form.parking_charges) || 0) : 0;
    const dis     = Number(form.discount) || 0;
    const paid    = Number(form.amount_paid) || 0;
    const subtotal = room + extra + parking;
    const afterDis = subtotal - dis;
    const total    = Math.round(afterDis * 100) / 100;
    const balance  = Math.max(0, Math.round((total - paid) * 100) / 100);
    return { subtotal, discount: dis, total, paid, balance };
});

const overDiscount = computed(() => (Number(form.discount) || 0) > totals.value.subtotal);
const overPaid     = computed(() => (Number(form.amount_paid) || 0) > totals.value.total);

const MAX_MONEY = 9999999;
const roomChargesError = computed(() => {
    const raw = form.room_charges;
    if (raw === '' || raw === null || raw === undefined || isNaN(Number(raw))) return 'Room charges are required.';
    const v = Number(raw);
    if (v < 0) return 'Room charges cannot be negative.';
    if (v > MAX_MONEY) return 'Room charges cannot exceed ₨9,999,999.';
    return '';
});
const extraChargesError = computed(() => {
    const v = Number(form.extra_charges) || 0;
    if (v < 0) return 'Extra charges cannot be negative.';
    if (v > MAX_MONEY) return 'Extra charges cannot exceed ₨9,999,999.';
    return '';
});
const parkingChargesError = computed(() => {
    if (!form.has_vehicle) return '';
    const v = Number(form.parking_charges) || 0;
    if (v < 0) return 'Parking charges cannot be negative.';
    if (v > MAX_MONEY) return 'Parking charges cannot exceed ₨9,999,999.';
    return '';
});

const n = (v) => Number(v || 0).toLocaleString('en-US');

function fillFromBooking() {
    const bk = props.bookings.find((x) => x.id === form.booking_id);
    if (!bk) return;
    form.guest_name   = bk.guest_name || '';
    form.father_name  = bk.father_name || '';
    form.guest_phone  = bk.guest_phone || '';
    form.room_number  = bk.room_number || '';
    form.room_type    = bk.room_type || '';
    form.check_in     = bk.check_in || '';
    form.check_out    = bk.check_out || '';
    form.nights       = bk.nights || 1;
    form.room_charges = Number(bk.amount || 0);
    form.extra_charges = Number(bk.food_total || 0);
    if (bk.customer_id) form.customer_id = bk.customer_id;
}

function submit() {
    if (props.mode === 'edit') {
        form.put(`/billing/${props.bill.uuid}`);
    } else {
        form.post('/billing');
    }
}
</script>
