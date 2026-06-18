<template>
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><Link href="/dashboard">Home</Link></li>
                        <li class="breadcrumb-item" aria-current="page">Settings</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title"><h2 class="mb-0">⚙️ Hotel Settings</h2></div>
                </div>
            </div>
        </div>
    </div>

    <form @submit.prevent="submit">
        <div class="row">
            <!-- LEFT -->
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0"><i class="ti ti-building me-2"></i>Hotel Information</h5></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Hotel Name <span class="text-danger">*</span></label>
                                <input type="text" v-model="form.hotel_name" class="form-control" :class="{ 'is-invalid': form.errors.hotel_name }" placeholder="Bellamonte Resort">
                                <div v-if="form.errors.hotel_name" class="invalid-feedback">{{ form.errors.hotel_name }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" v-model="form.hotel_email" class="form-control" :class="{ 'is-invalid': form.errors.hotel_email }" placeholder="info@bellamonteresort.com">
                                <div v-if="form.errors.hotel_email" class="invalid-feedback">{{ form.errors.hotel_email }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="text" v-model="form.hotel_phone" class="form-control" placeholder="0300-1234567">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Country</label>
                                <input type="text" v-model="form.hotel_country" class="form-control" placeholder="Pakistan">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Full Address</label>
                                <input type="text" v-model="form.hotel_address" class="form-control" placeholder="Street, Area, City, Pakistan">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0"><i class="ti ti-share me-2"></i>Social Media</h5></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label"><i class="ti ti-brand-facebook text-primary me-1"></i>Facebook</label>
                                <input type="text" v-model="form.facebook" class="form-control" placeholder="https://facebook.com/...">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"><i class="ti ti-brand-instagram text-danger me-1"></i>Instagram</label>
                                <input type="text" v-model="form.instagram" class="form-control" placeholder="https://instagram.com/...">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"><i class="ti ti-brand-twitter text-info me-1"></i>Twitter / X</label>
                                <input type="text" v-model="form.twitter" class="form-control" placeholder="https://twitter.com/...">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT -->
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0"><i class="ti ti-photo me-2"></i>Hotel Logo</h5></div>
                    <div class="card-body text-center">
                        <img v-if="preview || logoUrl" :src="preview || logoUrl" class="img-fluid mb-3 rounded" style="max-height:120px;">
                        <div v-else class="bg-light rounded d-flex align-items-center justify-content-center mb-3 mx-auto" style="height:100px;width:180px;">
                            <i class="ti ti-building f-40 text-muted opacity-50"></i>
                        </div>
                        <input type="file" class="form-control" accept="image/*" @change="onLogo">
                        <small class="text-muted d-block mt-1">PNG, JPG, SVG — max 2MB</small>
                        <small class="text-muted d-block">Will appear on invoices and headers</small>
                        <div v-if="form.errors.hotel_logo" class="text-danger f-12 mt-1">{{ form.errors.hotel_logo }}</div>
                    </div>
                </div>

                <div class="card mb-4 border-primary">
                    <div class="card-header bg-light-primary"><h5 class="mb-0 text-primary"><i class="ti ti-info-circle me-2"></i>Settings Info</h5></div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0 f-13">
                            <li class="mb-2"><i class="ti ti-check text-success me-2"></i>Hotel name appears on invoices</li>
                            <li class="mb-2"><i class="ti ti-check text-success me-2"></i>Logo appears on the print page</li>
                        </ul>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg" :disabled="form.processing">
                                <i class="ti ti-device-floppy me-2"></i>Save Settings
                            </button>
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
import AppLayout from '@/Layouts/AppLayout.vue';

defineOptions({ layout: AppLayout });

const props = defineProps({
    settings: { type: Object, default: () => ({}) },
    logoUrl:  { type: String, default: null },
});

const s = props.settings || {};
const form = useForm({
    hotel_name:    s.hotel_name ?? '',
    hotel_email:   s.hotel_email ?? '',
    hotel_phone:   s.hotel_phone ?? '',
    hotel_country: s.hotel_country ?? '',
    hotel_address: s.hotel_address ?? '',
    facebook:      s.facebook ?? '',
    instagram:     s.instagram ?? '',
    twitter:       s.twitter ?? '',
    hotel_logo:    null,
});

const preview = ref(null);
function onLogo(e) {
    const f = e.target.files[0];
    form.hotel_logo = f || null;
    preview.value = f ? URL.createObjectURL(f) : null;
}

function submit() {
    form.transform((d) => ({ ...d, _method: 'PUT' })).post('/settings', { forceFormData: true });
}
</script>
