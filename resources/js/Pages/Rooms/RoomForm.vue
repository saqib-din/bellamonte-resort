<template>
    <form @submit.prevent="submit">
        <div class="row">
            <!-- Left Column -->
            <div class="col-lg-8">

                <!-- Basic Info -->
                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0"><i class="ti ti-door me-2"></i>Basic Information</h5></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Room Number <span class="text-danger">*</span></label>
                                <input type="text" v-model="form.room_number" class="form-control" :class="{ 'is-invalid': form.errors.room_number }" placeholder="e.g. 101">
                                <div v-if="form.errors.room_number" class="invalid-feedback">{{ form.errors.room_number }}</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Room Type <span class="text-danger">*</span></label>
                                <select v-model="form.type" class="form-select" :class="{ 'is-invalid': form.errors.type }">
                                    <option value="">-- Select Type --</option>
                                    <option v-for="t in types" :key="t" :value="t">{{ t }}</option>
                                </select>
                                <div v-if="form.errors.type" class="invalid-feedback">{{ form.errors.type }}</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Floor <span class="text-danger">*</span></label>
                                <input type="number" v-model="form.floor" class="form-control" :class="{ 'is-invalid': form.errors.floor }" min="1">
                                <div v-if="form.errors.floor" class="invalid-feedback">{{ form.errors.floor }}</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Price Per Night (₨) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">₨</span>
                                    <input type="number" v-model="form.price_per_night" class="form-control" :class="{ 'is-invalid': form.errors.price_per_night }" placeholder="5000" min="1">
                                </div>
                                <div v-if="form.errors.price_per_night" class="text-danger f-12 mt-1">{{ form.errors.price_per_night }}</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Day-Use Rate (₨)</label>
                                <div class="input-group">
                                    <span class="input-group-text">₨</span>
                                    <input type="number" v-model="form.day_rate" class="form-control" :class="{ 'is-invalid': form.errors.day_rate }" placeholder="Optional" min="0">
                                </div>
                                <div v-if="form.errors.day_rate" class="text-danger f-12 mt-1">{{ form.errors.day_rate }}</div>
                                <small v-else class="text-muted">Blank = uses night rate</small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Hourly Rate (₨)</label>
                                <div class="input-group">
                                    <span class="input-group-text">₨</span>
                                    <input type="number" v-model="form.hourly_rate" class="form-control" :class="{ 'is-invalid': form.errors.hourly_rate }" placeholder="Optional" min="0">
                                </div>
                                <div v-if="form.errors.hourly_rate" class="text-danger f-12 mt-1">{{ form.errors.hourly_rate }}</div>
                                <small v-else class="text-muted">Blank = uses night rate</small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Max Capacity <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ti ti-users"></i></span>
                                    <input type="number" v-model="form.capacity" class="form-control" :class="{ 'is-invalid': form.errors.capacity }" min="1" max="20">
                                    <span class="input-group-text">persons</span>
                                </div>
                                <div v-if="form.errors.capacity" class="text-danger f-12 mt-1">{{ form.errors.capacity }}</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Room Size</label>
                                <div class="input-group">
                                    <input type="text" v-model="form.size" class="form-control" placeholder="30">
                                    <span class="input-group-text">sq ft</span>
                                </div>
                                <div v-if="form.errors.size" class="text-danger f-12 mt-1">{{ form.errors.size }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bed & Timing -->
                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0"><i class="ti ti-bed me-2"></i>Bed & Timing</h5></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Bed Type</label>
                                <select v-model="form.bed_type" class="form-select" :class="{ 'is-invalid': form.errors.bed_type }">
                                    <option value="">-- Select Bed --</option>
                                    <option v-for="b in beds" :key="b" :value="b">{{ b }}</option>
                                </select>
                                <div v-if="form.errors.bed_type" class="invalid-feedback">{{ form.errors.bed_type }}</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Check In Time</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ti ti-clock"></i></span>
                                    <input type="time" v-model="form.check_in_time" class="form-control">
                                </div>
                                <div v-if="form.errors.check_in_time" class="text-danger f-12 mt-1">{{ form.errors.check_in_time }}</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Check Out Time</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ti ti-clock"></i></span>
                                    <input type="time" v-model="form.check_out_time" class="form-control">
                                </div>
                                <div v-if="form.errors.check_out_time" class="text-danger f-12 mt-1">{{ form.errors.check_out_time }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Services -->
                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0"><i class="ti ti-star me-2"></i>Services & Amenities</h5></div>
                    <div class="card-body">
                        <label class="form-label">Services <small class="text-muted">(comma separated)</small></label>
                        <div class="row mb-3">
                            <div class="col-md-3 col-6 mb-2" v-for="(svc, i) in serviceOptions" :key="i">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" :id="`svc_${i}`"
                                        :checked="isServiceChecked(svc)" @change="toggleService(svc, $event.target.checked)">
                                    <label class="form-check-label" :for="`svc_${i}`">{{ svc }}</label>
                                </div>
                            </div>
                        </div>
                        <input type="text" v-model="form.services" class="form-control" :class="{ 'is-invalid': form.errors.services }" placeholder="WiFi, AC, TV, Geyser ...">
                        <div v-if="form.errors.services" class="invalid-feedback">{{ form.errors.services }}</div>
                        <small class="text-muted">Check above or enter manually</small>
                    </div>
                </div>

                <!-- Description -->
                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0"><i class="ti ti-file-text me-2"></i>Description</h5></div>
                    <div class="card-body">
                        <textarea v-model="form.description" class="form-control" :class="{ 'is-invalid': form.errors.description }" rows="5" placeholder="Enter Room Description..."></textarea>
                        <div v-if="form.errors.description" class="invalid-feedback">{{ form.errors.description }}</div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0"><i class="ti ti-settings me-2"></i>Status & Image</h5></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Room Status <span class="text-danger">*</span></label>
                            <select v-model="form.status" class="form-select" :class="{ 'is-invalid': form.errors.status }">
                                <option value="Available">✅ Available</option>
                                <option value="Occupied">🔴 Occupied</option>
                                <option value="Maintenance">🔧 Maintenance</option>
                            </select>
                            <div v-if="form.errors.status" class="invalid-feedback">{{ form.errors.status }}</div>
                        </div>

                        <div class="mb-3 text-center" v-if="preview || existingImage">
                            <p class="text-muted f-12 mb-1">{{ preview ? 'New Image:' : 'Current Image:' }}</p>
                            <img :src="preview || existingImage" class="img-fluid rounded" style="max-height:180px;">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ mode === 'edit' ? 'Change Image' : 'Room Image' }}</label>
                            <input type="file" class="form-control" accept="image/*" @change="onFile">
                            <small v-if="mode === 'edit'" class="text-muted">Leave empty to keep current image</small>
                            <div v-if="form.errors.image" class="text-danger mt-1 f-12">{{ form.errors.image }}</div>
                        </div>
                    </div>
                </div>

                <!-- Quick Info -->
                <div class="card mb-4 border-primary">
                    <div class="card-header bg-light-primary"><h5 class="mb-0 text-primary"><i class="ti ti-info-circle me-2"></i>Quick Info</h5></div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><i class="ti ti-door text-muted me-2"></i>Room Number required</li>
                            <li class="mb-2"><i class="ti ti-currency-rupee text-muted me-2"></i>Price per night required</li>
                            <li class="mb-2"><i class="ti ti-users text-muted me-2"></i>Capacity in persons</li>
                            <li class="mb-2"><i class="ti ti-clock text-muted me-2"></i>Check-in: 2:00 PM default</li>
                            <li class="mb-2"><i class="ti ti-clock text-muted me-2"></i>Check-out: 12:00 PM default</li>
                            <li><i class="ti ti-photo text-muted me-2"></i>Image optional (max 2MB)</li>
                        </ul>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary" :disabled="form.processing">
                                <i class="ti ti-device-floppy me-2"></i>{{ mode === 'edit' ? 'Update Room' : 'Save Room' }}
                            </button>
                            <Link href="/admin/rooms" class="btn btn-outline-secondary">
                                <i class="ti ti-arrow-left me-2"></i>Cancel
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</template>

<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    mode: { type: String, default: 'create' },
    room: { type: Object, default: null },
});

const types = ['Deluxe Suite Room', 'Duplex Suite Room', 'VIP Honeymoon Room', 'Deluxe VIP Room', 'VIP Excutive Room'];
const beds  = ['Single Bed', 'Double Bed', 'Queen Bed', 'King Bed', 'Twin Beds', 'King Beds x2'];
const serviceOptions = ['WiFi', 'AC', 'TV', 'Geyser', 'Mini Bar', 'Jacuzzi', 'Parking', 'Room Service', 'Laundry', 'Breakfast', 'Swimming Pool', 'Gym'];

const r = props.room || {};
const form = useForm({
    room_number:     r.room_number ?? '',
    type:            r.type ?? '',
    floor:           r.floor ?? 1,
    price_per_night: r.price_per_night ?? '',
    day_rate:        r.day_rate ?? '',
    hourly_rate:     r.hourly_rate ?? '',
    capacity:        r.capacity ?? 2,
    size:            r.size ?? '',
    bed_type:        r.bed_type ?? '',
    services:        r.services ?? '',
    description:     r.description ?? '',
    check_in_time:   r.check_in_time ?? '14:00',
    check_out_time:  r.check_out_time ?? '12:00',
    status:          r.status ?? 'Available',
    image:           null,
});

const existingImage = ref(props.room?.image || null);
const preview       = ref(null);

function onFile(e) {
    const file = e.target.files[0];
    form.image = file || null;
    preview.value = file ? URL.createObjectURL(file) : null;
}

// Services checkbox <-> text sync
function currentServices() {
    return (form.services || '').split(',').map((s) => s.trim()).filter(Boolean);
}
function isServiceChecked(svc) {
    return currentServices().some((s) => s.toLowerCase() === svc.toLowerCase());
}
function toggleService(svc, checked) {
    let list = currentServices();
    if (checked) {
        if (!isServiceChecked(svc)) list.push(svc);
    } else {
        list = list.filter((s) => s.toLowerCase() !== svc.toLowerCase());
    }
    form.services = list.join(', ');
}

function submit() {
    if (props.mode === 'edit') {
        form.transform((data) => ({ ...data, _method: 'PUT' }))
            .post(`/admin/rooms/${props.room.uuid}`, { forceFormData: true });
    } else {
        form.post('/admin/rooms', { forceFormData: true });
    }
}
</script>
