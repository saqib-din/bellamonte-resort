<template>
    <form @submit.prevent="submit">
        <div class="row">
            <!-- LEFT -->
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0"><i class="ti ti-user me-2"></i>Personal Information</h5></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" v-model="form.name" class="form-control" :class="{ 'is-invalid': form.errors.name }" placeholder="Saqib Din">
                                <div v-if="form.errors.name" class="invalid-feedback">{{ form.errors.name }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Father Name</label>
                                <input type="text" v-model="form.father_name" class="form-control" :class="{ 'is-invalid': form.errors.father_name }" placeholder="Father name">
                                <div v-if="form.errors.father_name" class="invalid-feedback">{{ form.errors.father_name }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">CNIC / Passport <span class="text-danger">*</span></label>
                                <input type="text" v-model="form.cnic" class="form-control" :class="{ 'is-invalid': form.errors.cnic }" placeholder="35202-1234567-1">
                                <div v-if="form.errors.cnic" class="invalid-feedback">{{ form.errors.cnic }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone <span class="text-danger">*</span></label>
                                <input type="text" v-model="form.phone" class="form-control" :class="{ 'is-invalid': form.errors.phone }" placeholder="0316-8336096">
                                <div v-if="form.errors.phone" class="invalid-feedback">{{ form.errors.phone }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" v-model="form.email" class="form-control" :class="{ 'is-invalid': form.errors.email }" placeholder="ahmed@email.com">
                                <div v-if="form.errors.email" class="invalid-feedback">{{ form.errors.email }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Gender</label>
                                <select v-model="form.gender" class="form-select">
                                    <option value="">-- Select --</option>
                                    <option v-for="g in ['Male','Female','Other']" :key="g" :value="g">{{ g }}</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" v-model="form.dob" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nationality</label>
                                <input type="text" v-model="form.nationality" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">City</label>
                                <input type="text" v-model="form.city" class="form-control" placeholder="Lahore">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select v-model="form.status" class="form-select">
                                    <option value="Active">✅ Active</option>
                                    <option value="Blacklisted">🚫 Blacklisted</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Address</label>
                                <textarea v-model="form.address" class="form-control" rows="2" placeholder="House #, Street, Area, City..."></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Notes <small class="text-muted">(Admin only)</small></label>
                                <textarea v-model="form.notes" class="form-control" rows="2" placeholder="Enter a note ..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT -->
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0"><i class="ti ti-photo me-2"></i>Photo</h5></div>
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <img v-if="preview || existingImage" :src="preview || existingImage" class="rounded-circle mx-auto d-block" style="width:110px;height:110px;object-fit:cover;">
                            <div v-else class="rounded-circle bg-light-primary d-flex align-items-center justify-content-center mx-auto" style="width:110px;height:110px;">
                                <i class="ti ti-user" style="font-size:3rem;color:#4680ff;opacity:0.4;"></i>
                            </div>
                        </div>
                        <input type="file" class="form-control" accept="image/*" @change="onFile">
                        <small class="text-muted d-block mt-1">{{ mode === 'edit' ? 'Leave empty to keep current' : 'JPG, PNG — max 2MB' }}</small>
                        <div v-if="form.errors.image" class="text-danger mt-1 f-12">{{ form.errors.image }}</div>
                    </div>
                </div>

                <!-- Quick Info -->
                <div class="card mb-4 border-info">
                    <div class="card-header bg-light-info"><h5 class="mb-0 text-info"><i class="ti ti-info-circle me-2"></i>Quick Info</h5></div>
                    <div class="card-body f-13">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><i class="ti ti-user text-primary me-1"></i><strong>Full Name</strong><p class="text-muted mb-0 ms-3">Required. Enter the guest's full name as per their ID.</p></li>
                            <hr class="my-2">
                            <li class="mb-2"><i class="ti ti-id text-warning me-1"></i><strong>CNIC / Passport</strong><p class="text-muted mb-0 ms-3">Required & must be unique. Used to identify returning customers.</p></li>
                            <hr class="my-2">
                            <li class="mb-2"><i class="ti ti-phone text-success me-1"></i><strong>Phone Number</strong><p class="text-muted mb-0 ms-3">Required. Used for booking confirmation and contact purposes.</p></li>
                            <hr class="my-2">
                            <li class="mb-2"><i class="ti ti-photo text-secondary me-1"></i><strong>Photo</strong><p class="text-muted mb-0 ms-3">Optional. Upload a customer photo for easy identification. Max 2MB.</p></li>
                            <hr class="my-2">
                            <li><i class="ti ti-ban text-danger me-1"></i><strong>Blacklist Status</strong><p class="text-muted mb-0 ms-3">Set status to Blacklisted to flag problematic guests and prevent future bookings.</p></li>
                        </ul>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary" :disabled="form.processing">
                                <i class="ti ti-device-floppy me-2"></i>{{ mode === 'edit' ? 'Update Customer' : 'Save Customer' }}
                            </button>
                            <Link href="/customers" class="btn btn-outline-secondary"><i class="ti ti-arrow-left me-2"></i>Cancel</Link>
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
    mode:     { type: String, default: 'create' },
    customer: { type: Object, default: null },
});

const c = props.customer || {};
const form = useForm({
    name:        c.name ?? '',
    father_name: c.father_name ?? '',
    cnic:        c.cnic ?? '',
    phone:       c.phone ?? '',
    email:       c.email ?? '',
    gender:      c.gender ?? '',
    dob:         c.dob ?? '',
    nationality: c.nationality ?? 'Pakistani',
    city:        c.city ?? '',
    status:      c.status ?? 'Active',
    address:     c.address ?? '',
    notes:       c.notes ?? '',
    image:       null,
});

const existingImage = ref(props.customer?.image || null);
const preview       = ref(null);

function onFile(e) {
    const file = e.target.files[0];
    form.image = file || null;
    preview.value = file ? URL.createObjectURL(file) : null;
}

function submit() {
    if (props.mode === 'edit') {
        form.transform((d) => ({ ...d, _method: 'PUT' }))
            .post(`/customers/${props.customer.uuid}`, { forceFormData: true });
    } else {
        form.post('/customers', { forceFormData: true });
    }
}
</script>
