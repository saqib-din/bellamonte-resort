<template>
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><Link href="/dashboard">Home</Link></li>
                        <li class="breadcrumb-item">About Page</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title"><h2 class="mb-0">About Page Settings</h2></div>
                </div>
            </div>
        </div>
    </div>

    <form @submit.prevent="submit">
        <div class="row">
            <!-- LEFT -->
            <div class="col-lg-8">
                <!-- Welcome -->
                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0"><i class="ti ti-home me-2"></i>Welcome Section</h5></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" v-model="form.welcome_title" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea v-model="form.welcome_description" class="form-control" rows="4" placeholder="Enter a description"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Offers -->
                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0"><i class="ti ti-list me-2"></i>Offers / Services List</h5></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6" v-for="n in 5" :key="`offer${n}`">
                                <label class="form-label">Offer {{ n }}</label>
                                <input type="text" v-model="form[`offer_${n}`]" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Service Cards -->
                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0"><i class="ti ti-photo me-2"></i>Service Cards</h5></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4" v-for="n in 3" :key="`svc${n}`">
                                <label class="form-label">Service {{ n }} Title</label>
                                <input type="text" v-model="form[`service_${n}_title`]" class="form-control mb-2">
                                <label class="form-label">Image</label>
                                <input type="file" class="form-control" accept="image/*" @change="onFile(`service_${n}_image`, `service_${n}`, $event)">
                                <img v-if="previews[`service_${n}`] || images[`service_${n}`]" :src="previews[`service_${n}`] || images[`service_${n}`]" class="mt-2 rounded" style="height:80px; object-fit:cover; width:100%;">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Video -->
                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0"><i class="ti ti-video me-2"></i>Video Section</h5></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Title</label>
                                <input type="text" v-model="form.video_title" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Subtitle</label>
                                <input type="text" v-model="form.video_subtitle" class="form-control" placeholder="Enter a subtitle">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">YouTube URL</label>
                                <input type="url" v-model="form.video_url" class="form-control" :class="{ 'is-invalid': form.errors.video_url }">
                                <div v-if="form.errors.video_url" class="invalid-feedback">{{ form.errors.video_url }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Background Image</label>
                                <input type="file" class="form-control" accept="image/*" @change="onFile('video_bg_image', 'video_bg', $event)">
                                <img v-if="previews.video_bg || images.video_bg" :src="previews.video_bg || images.video_bg" class="mt-2 rounded" style="height:80px; object-fit:cover; width:100%;">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gallery -->
                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0"><i class="ti ti-layout me-2"></i>Gallery</h5></div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4" v-for="n in 5" :key="`gal${n}`">
                                <label class="form-label">Gallery {{ n }} Title</label>
                                <input type="text" v-model="form[`gallery_${n}_title`]" class="form-control mb-2">
                                <label class="form-label">Image</label>
                                <input type="file" class="form-control" accept="image/*" @change="onFile(`gallery_${n}_image`, `gallery_${n}`, $event)">
                                <img v-if="previews[`gallery_${n}`] || images[`gallery_${n}`]" :src="previews[`gallery_${n}`] || images[`gallery_${n}`]" class="mt-2 rounded" style="height:80px; object-fit:cover; width:100%;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT -->
            <div class="col-lg-4">
                <!-- Quick Info -->
                <div class="card mb-4 border-info">
                    <div class="card-header bg-light-info"><h5 class="mb-0 text-info"><i class="ti ti-info-circle me-2"></i>Quick Info</h5></div>
                    <div class="card-body f-13">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><i class="ti ti-home text-primary me-1"></i><strong>Welcome Section</strong><p class="text-muted mb-0 ms-3">Update the resort title and description.</p></li>
                            <hr class="my-2">
                            <li class="mb-2"><i class="ti ti-list text-success me-1"></i><strong>Offers List</strong><p class="text-muted mb-0 ms-3">5 offers/services displayed as bullet points on the page.</p></li>
                            <hr class="my-2">
                            <li class="mb-2"><i class="ti ti-photo text-warning me-1"></i><strong>Service Cards</strong><p class="text-muted mb-0 ms-3">Set title and background image for 3 service cards.</p></li>
                            <hr class="my-2">
                            <li class="mb-2"><i class="ti ti-video text-danger me-1"></i><strong>Video Section</strong><p class="text-muted mb-0 ms-3">Update the YouTube link, title and background image.</p></li>
                            <hr class="my-2">
                            <li class="mb-2"><i class="ti ti-layout text-secondary me-1"></i><strong>Gallery</strong><p class="text-muted mb-0 ms-3">Set titles and images for 5 gallery items.</p></li>
                            <hr class="my-2">
                            <li><i class="ti ti-photo-off text-muted me-1"></i><strong>Image Upload</strong><p class="text-muted mb-0 ms-3">If no image is uploaded, the default image will be shown automatically.</p></li>
                        </ul>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card sticky-top" style="top: 20px;">
                    <div class="card-header"><h5 class="mb-0"><i class="ti ti-settings me-2"></i>Actions</h5></div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary" :disabled="form.processing">
                                <i class="ti ti-device-floppy me-2"></i>Save Changes
                            </button>
                            <a href="/about-us" target="_blank" class="btn btn-outline-secondary">
                                <i class="ti ti-eye me-2"></i>Preview Page
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</template>

<script setup>
import { reactive } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineOptions({ layout: AppLayout });

const props = defineProps({
    form:   { type: Object, default: () => ({}) },
    images: { type: Object, default: () => ({}) },
});

const form = useForm({
    ...props.form,
    service_1_image: null, service_2_image: null, service_3_image: null,
    video_bg_image: null,
    gallery_1_image: null, gallery_2_image: null, gallery_3_image: null, gallery_4_image: null, gallery_5_image: null,
});

const previews = reactive({});

function onFile(fileField, previewKey, ev) {
    const f = ev.target.files[0];
    form[fileField] = f || null;
    previews[previewKey] = f ? URL.createObjectURL(f) : null;
}

function submit() {
    form.transform((d) => ({ ...d, _method: 'PUT' })).post('/about', { forceFormData: true });
}
</script>
