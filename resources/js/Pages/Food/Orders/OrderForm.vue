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
                                <label class="form-label">Booking Select (Optional)</label>
                                <SearchSelect v-model="form.booking_id" :options="bookingOpts" :invalid="!!form.errors.booking_id" placeholder="-- Select Booking --" search-placeholder="Search booking..." @change="fillFromBooking" />
                                <div v-if="form.errors.booking_id" class="text-danger f-12 mt-1">{{ form.errors.booking_id }}</div>
                                <small class="text-muted">Select a booking to auto-fill guest details.</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Customer (Optional)</label>
                                <SearchSelect v-model="form.customer_id" fetch-url="/customers/search" :preload="customers" :invalid="!!form.errors.customer_id" placeholder="-- Customer --" search-placeholder="Search customer..." />
                                <div v-if="form.errors.customer_id" class="text-danger f-12 mt-1">{{ form.errors.customer_id }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Guest Info -->
                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0"><i class="ti ti-user me-2"></i>Guest Info</h5></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Guest Name <span class="text-danger">*</span></label>
                                <input type="text" v-model="form.guest_name" class="form-control" :class="{ 'is-invalid': form.errors.guest_name }" placeholder="Saqib Din">
                                <div v-if="form.errors.guest_name" class="invalid-feedback">{{ form.errors.guest_name }}</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Father Name (Optional)</label>
                                <input type="text" v-model="form.father_name" class="form-control" :class="{ 'is-invalid': form.errors.father_name }" placeholder="Father name">
                                <div v-if="form.errors.father_name" class="invalid-feedback">{{ form.errors.father_name }}</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Phone (Optional)</label>
                                <input type="text" v-model="form.guest_phone" class="form-control" :class="{ 'is-invalid': form.errors.guest_phone }" placeholder="0316-8336096">
                                <div v-if="form.errors.guest_phone" class="invalid-feedback">{{ form.errors.guest_phone }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Room Number (Optional)</label>
                                <input type="text" v-model="form.room_number" class="form-control" :class="{ 'is-invalid': form.errors.room_number }" placeholder="101">
                                <div v-if="form.errors.room_number" class="invalid-feedback">{{ form.errors.room_number }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Order Type <span class="text-danger">*</span></label>
                                <select v-model="form.order_type" class="form-select" :class="{ 'is-invalid': form.errors.order_type }">
                                    <option v-for="t in orderTypes" :key="t" :value="t">{{ t }}</option>
                                </select>
                                <div v-if="form.errors.order_type" class="invalid-feedback">{{ form.errors.order_type }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Menu & Cart -->
                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0"><i class="ti ti-tools-kitchen-2 me-2"></i>Menu & Order Items</h5></div>
                    <div class="card-body">
                        <ul class="nav nav-tabs mb-3">
                            <li class="nav-item" v-for="cat in categories" :key="cat.id">
                                <button type="button" class="nav-link" :class="{ active: activeTab === cat.id }" @click="activeTab = cat.id">
                                    {{ cat.icon }} {{ cat.name }}
                                </button>
                            </li>
                        </ul>

                        <div class="mb-4">
                            <div class="row g-2">
                                <div class="col-md-4" v-for="item in activeItems" :key="item.id">
                                    <div class="card border h-100 menu-item-card" :class="{ 'border-primary': flashId === item.id }" style="cursor:pointer;transition:all .2s" @click="addToCart(item)">
                                        <div class="card-body p-3 text-center">
                                            <div class="f-24 mb-1">🍴</div>
                                            <h6 class="mb-1 f-14">{{ item.name }}</h6>
                                            <small v-if="item.description" class="text-muted d-block mb-1">{{ truncate(item.description, 40) }}</small>
                                            <span class="badge bg-light-success text-success fw-bold">₨{{ n(item.price) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="!activeItems.length" class="col-12"><p class="text-muted text-center py-3">No items in this category.</p></div>
                            </div>
                        </div>

                        <!-- Cart -->
                        <div class="rounded p-3 surface-box">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0"><i class="ti ti-shopping-cart me-2"></i>Order Cart</h6>
                                <button type="button" class="btn btn-sm btn-outline-danger" @click="cart = []"><i class="ti ti-x me-1"></i>Clear</button>
                            </div>
                            <div v-if="!cart.length" class="text-muted text-center py-3">
                                <i class="ti ti-shopping-cart-off f-24 d-block mb-1"></i>
                                Click items from the menu above to add them
                            </div>
                            <div v-for="c in cart" :key="c.id" class="d-flex align-items-center justify-content-between mb-2 py-2 border-bottom">
                                <div>
                                    <span class="fw-500">{{ c.name }}</span>
                                    <small class="text-muted d-block">₨{{ n(c.price) }} each</small>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-secondary px-2 py-1" @click="changeQty(c, -1)"><i class="ti ti-minus"></i></button>
                                    <span class="fw-bold">{{ c.qty }}</span>
                                    <button type="button" class="btn btn-sm btn-outline-primary px-2 py-1" @click="changeQty(c, 1)"><i class="ti ti-plus"></i></button>
                                    <span class="text-primary fw-500 ms-2" style="min-width:70px;text-align:right">₨{{ n(c.price * c.qty) }}</span>
                                </div>
                            </div>
                            <div v-if="form.errors.items" class="text-danger f-13 mt-2">{{ form.errors.items }}</div>
                        </div>
                    </div>
                </div>

                <!-- Charges -->
                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0"><i class="ti ti-calculator me-2"></i>Charges & Payment</h5></div>
                    <div class="card-body">
                        <div class="row g-3">
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
                                <div class="col-3"><div class="text-muted f-12">Subtotal</div><div class="fw-500">₨{{ n(totals.subtotal) }}</div></div>
                                <div class="col-3"><div class="text-muted f-12">Discount</div><div class="fw-500 text-success">-₨{{ n(totals.discount) }}</div></div>
                                <div class="col-3"><div class="text-muted f-12">Total</div><div class="fw-bold text-primary fs-5">₨{{ n(totals.total) }}</div></div>
                                <div class="col-3"><div class="text-muted f-12">Balance</div><div class="fw-bold text-danger">₨{{ n(totals.balance) }}</div></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0"><i class="ti ti-notes me-2"></i>Notes (Optional)</h5></div>
                    <div class="card-body">
                        <textarea v-model="form.notes" class="form-control" :class="{ 'is-invalid': form.errors.notes }" rows="3" placeholder="Special instructions, allergies, etc."></textarea>
                        <div v-if="form.errors.notes" class="invalid-feedback">{{ form.errors.notes }}</div>
                    </div>
                </div>
            </div>

            <!-- RIGHT -->
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0"><i class="ti ti-credit-card me-2"></i>Payment</h5></div>
                    <div class="card-body">
                        <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                        <select v-model="form.payment_method" class="form-select" :class="{ 'is-invalid': form.errors.payment_method }">
                            <option v-for="m in paymentMethods" :key="m" :value="m">{{ m }}</option>
                        </select>
                        <div v-if="form.errors.payment_method" class="invalid-feedback">{{ form.errors.payment_method }}</div>
                    </div>
                </div>

                <div class="card mb-4 border-primary">
                    <div class="card-header bg-light-primary"><h5 class="mb-0 text-primary"><i class="ti ti-receipt me-2"></i>Order Preview</h5></div>
                    <div class="card-body">
                        <div v-if="!cart.length"><p class="text-muted f-13 text-center">No items yet</p></div>
                        <div v-for="c in cart" :key="c.id" class="d-flex justify-content-between mb-1">
                            <span class="f-13 text-muted">{{ c.name }} × {{ c.qty }}</span>
                            <span class="f-13">₨{{ n(c.price * c.qty) }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-2"><span class="text-muted f-13">Subtotal</span><span>₨{{ n(totals.subtotal) }}</span></div>
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
                            <button type="submit" class="btn btn-primary" :disabled="form.processing || overDiscount || overPaid">
                                <i class="ti ti-receipt me-2"></i>{{ mode === 'edit' ? 'Update Order' : 'Place Food Order' }}
                            </button>
                            <Link href="/food/orders" class="btn btn-outline-secondary"><i class="ti ti-arrow-left me-2"></i>Cancel</Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import SearchSelect from '@/Components/SearchSelect.vue';

const props = defineProps({
    mode:       { type: String, default: 'create' },
    order:      { type: Object, default: null },
    categories: { type: Array, default: () => [] },
    bookings:   { type: Array, default: () => [] },
    customers:  { type: Array, default: () => [] },
});

const orderTypes     = ['Room Service', 'Dine In', 'Takeaway'];
const paymentMethods = ['Cash', 'Card', 'Bank Transfer', 'JazzCash', 'EasyPaisa'];

const o = props.order || {};
const form = useForm({
    booking_id:     o.booking_id ?? '',
    customer_id:    o.customer_id ?? '',
    guest_name:     o.guest_name ?? '',
    father_name:    o.father_name ?? '',
    guest_phone:    o.guest_phone ?? '',
    room_number:    o.room_number ?? '',
    order_type:     o.order_type ?? 'Room Service',
    payment_method: o.payment_method ?? 'Cash',
    discount:       Number(o.discount ?? 0),
    amount_paid:    Number(o.amount_paid ?? 0),
    notes:          o.notes ?? '',
    items:          [],
});

const cart = ref(props.order?.cart ? props.order.cart.map((c) => ({ ...c })) : []);

const activeTab = ref(props.categories[0]?.id ?? null);
const activeItems = computed(() => props.categories.find((c) => c.id === activeTab.value)?.items ?? []);

const bookingOpts  = computed(() => props.bookings.map((b) => ({ value: b.id, label: b.label })));

const flashId = ref(null);
function addToCart(item) {
    const ex = cart.value.find((c) => c.id === item.id);
    if (ex) ex.qty++;
    else cart.value.push({ id: item.id, name: item.name, price: item.price, qty: 1 });
    flashId.value = item.id;
    setTimeout(() => { flashId.value = null; }, 600);
}
function changeQty(c, delta) {
    c.qty += delta;
    if (c.qty <= 0) cart.value = cart.value.filter((x) => x.id !== c.id);
}

const totals = computed(() => {
    const subtotal = cart.value.reduce((s, c) => s + c.price * c.qty, 0);
    const discount = Number(form.discount) || 0;
    const paid     = Number(form.amount_paid) || 0;
    const afterDis = subtotal - discount;
    const total    = Math.round(afterDis * 100) / 100;
    const balance  = Math.max(0, total - paid);
    return { subtotal, discount, total, paid, balance };
});

const overDiscount = computed(() => (Number(form.discount) || 0) > totals.value.subtotal);
const overPaid     = computed(() => (Number(form.amount_paid) || 0) > totals.value.total);

const n = (v) => Number(v || 0).toLocaleString('en-US');
const truncate = (s, len) => (s && s.length > len ? s.slice(0, len) + '…' : s);

function fillFromBooking() {
    const b = props.bookings.find((x) => x.id === form.booking_id);
    if (!b) return;
    form.guest_name  = b.guest_name || '';
    form.father_name = b.father_name || '';
    form.guest_phone = b.guest_phone || '';
    form.room_number = b.room_number || '';
    if (b.customer_id) form.customer_id = b.customer_id;
}

function submit() {
    form.items = cart.value.map((c) => ({ food_item_id: c.id, quantity: c.qty }));
    if (props.mode === 'edit') {
        form.put(`/food/orders/${props.order.uuid}`);
    } else {
        form.post('/food/orders');
    }
}
</script>
