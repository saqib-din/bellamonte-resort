<template>
    <form @submit.prevent="submit">
        <div class="row">
            <!-- Left -->
            <div class="col-lg-8">
                <!-- Basic Info -->
                <div class="card mb-3">
                    <div class="card-header"><h5 class="mb-0">Basic Information</h5></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" v-model="form.title" class="form-control" :class="{ 'is-invalid': form.errors.title }" placeholder="Event title">
                            <div v-if="form.errors.title" class="invalid-feedback">{{ form.errors.title }}</div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tag <span class="text-danger">*</span></label>
                                <input type="text" v-model="form.tag" class="form-control" :class="{ 'is-invalid': form.errors.tag }" placeholder="e.g. Travel, Nature, Event">
                                <div v-if="form.errors.tag" class="invalid-feedback">{{ form.errors.tag }}</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Event Date <span class="text-danger">*</span></label>
                                <input type="date" v-model="form.event_date" class="form-control" :class="{ 'is-invalid': form.errors.event_date }">
                                <div v-if="form.errors.event_date" class="invalid-feedback">{{ form.errors.event_date }}</div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Short Description</label>
                            <textarea v-model="form.short_description" class="form-control" :class="{ 'is-invalid': form.errors.short_description }" rows="2" placeholder="Brief summary (shown in listing)"></textarea>
                            <div v-if="form.errors.short_description" class="invalid-feedback">{{ form.errors.short_description }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Full Description</label>
                            <textarea v-model="form.description" class="form-control" :class="{ 'is-invalid': form.errors.description }" rows="4" placeholder="Detailed description for event detail page"></textarea>
                            <div v-if="form.errors.description" class="invalid-feedback">{{ form.errors.description }}</div>
                        </div>
                    </div>
                </div>

                <!-- Detail Sections -->
                <div class="card mb-3">
                    <div class="card-header"><h5 class="mb-0">Detail Page Sections</h5></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Section 1 Title</label>
                            <input type="text" v-model="form.section_1_title" class="form-control" :class="{ 'is-invalid': form.errors.section_1_title }" placeholder="e.g. Luxury & Comfort">
                            <div v-if="form.errors.section_1_title" class="invalid-feedback">{{ form.errors.section_1_title }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Section 1 Text</label>
                            <textarea v-model="form.section_1_text" class="form-control" :class="{ 'is-invalid': form.errors.section_1_text }" rows="3"></textarea>
                            <div v-if="form.errors.section_1_text" class="invalid-feedback">{{ form.errors.section_1_text }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Section 2 Title</label>
                            <input type="text" v-model="form.section_2_title" class="form-control" :class="{ 'is-invalid': form.errors.section_2_title }" placeholder="e.g. Perfect Destination">
                            <div v-if="form.errors.section_2_title" class="invalid-feedback">{{ form.errors.section_2_title }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Section 2 Text</label>
                            <textarea v-model="form.section_2_text" class="form-control" :class="{ 'is-invalid': form.errors.section_2_text }" rows="3"></textarea>
                            <div v-if="form.errors.section_2_text" class="invalid-feedback">{{ form.errors.section_2_text }}</div>
                        </div>
                    </div>
                </div>

                <!-- Detail Images -->
                <div class="card mb-3">
                    <div class="card-header"><h5 class="mb-0">Detail Page Images (3 images)</h5></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3" v-for="n in 3" :key="n">
                                <label class="form-label">Image {{ n }}</label>
                                <img v-if="detailPreviews[n] || event?.[`detail_image_${n}_url`]" :src="detailPreviews[n] || event[`detail_image_${n}_url`]" class="img-fluid mb-2 rounded" style="max-height:100px; object-fit:cover;">
                                <input type="file" class="form-control" :class="{ 'is-invalid': form.errors[`detail_image_${n}`] }" accept="image/*" @change="onDetailFile(n, $event)">
                                <div v-if="form.errors[`detail_image_${n}`]" class="invalid-feedback">{{ form.errors[`detail_image_${n}`] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right -->
            <div class="col-lg-4">
                <!-- Main Image -->
                <div class="card mb-3">
                    <div class="card-header"><h5 class="mb-0">Main Card Image <span class="text-danger">*</span></h5></div>
                    <div class="card-body">
                        <img v-if="mainPreview || event?.image_url" :src="mainPreview || event.image_url" class="img-fluid mb-2 rounded" style="max-height:160px; object-fit:cover; width:100%;">
                        <input type="file" class="form-control" :class="{ 'is-invalid': form.errors.image }" accept="image/*" @change="onMainFile">
                        <small class="text-muted">JPG, PNG, WEBP — max 2MB</small>
                        <div v-if="form.errors.image" class="invalid-feedback">{{ form.errors.image }}</div>
                    </div>
                </div>

                <!-- Settings -->
                <div class="card mb-3">
                    <div class="card-header"><h5 class="mb-0">Settings</h5></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Sort Order</label>
                            <input type="number" v-model.number="form.sort_order" class="form-control" :class="{ 'is-invalid': form.errors.sort_order }" min="0">
                            <div v-if="form.errors.sort_order" class="invalid-feedback">{{ form.errors.sort_order }}</div>
                            <small class="text-muted">Lower number = shown first</small>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" v-model="form.is_active" id="is_active">
                            <label class="form-check-label" for="is_active">Active (show on website)</label>
                        </div>
                    </div>
                </div>

                <!-- Quick Info -->
                <div class="card mb-3">
                    <div class="card-header"><h5 class="mb-0"><i class="ti ti-info-circle me-2 text-primary"></i>Quick Info</h5></div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush" style="font-size:13px;">
                            <li class="list-group-item d-flex justify-content-between align-items-center py-2"><span class="text-muted">Max Events Allowed</span><span class="badge bg-light-primary">9</span></li>
                            <li class="list-group-item d-flex justify-content-between align-items-center py-2"><span class="text-muted">Events Added</span><span class="badge bg-light-info">{{ count }} / 9</span></li>
                            <li class="list-group-item d-flex justify-content-between align-items-center py-2"><span class="text-muted">Main Image</span><span class="badge bg-light-warning">Required</span></li>
                            <li class="list-group-item d-flex justify-content-between align-items-center py-2"><span class="text-muted">Detail Images</span><span class="badge bg-light-secondary">Optional (3)</span></li>
                            <li class="list-group-item d-flex justify-content-between align-items-center py-2"><span class="text-muted">Image Size</span><span class="badge bg-light-secondary">Max 2MB</span></li>
                            <li class="list-group-item d-flex justify-content-between align-items-center py-2"><span class="text-muted">Formats</span><span class="badge bg-light-secondary">JPG, PNG, WEBP</span></li>
                            <li class="list-group-item d-flex justify-content-between align-items-center py-2"><span class="text-muted">Sort Order</span><span class="text-muted" style="font-size:12px;">0 = Show first</span></li>
                            <li class="list-group-item d-flex justify-content-between align-items-center py-2"><span class="text-muted">Status</span><span class="text-muted" style="font-size:12px;">Inactive = Hidden</span></li>
                        </ul>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary" :disabled="form.processing">
                                <i class="ti ti-device-floppy me-1"></i>{{ mode === 'edit' ? 'Update Event' : 'Save Event' }}
                            </button>
                            <Link href="/events" class="btn btn-light-secondary">Cancel</Link>
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
    mode:  { type: String, default: 'create' },
    event: { type: Object, default: null },
    count: { type: Number, default: 0 },
});

const e = props.event || {};
const form = useForm({
    title:             e.title ?? '',
    tag:               e.tag ?? '',
    event_date:        e.event_date ?? '',
    short_description: e.short_description ?? '',
    description:       e.description ?? '',
    section_1_title:   e.section_1_title ?? '',
    section_1_text:    e.section_1_text ?? '',
    section_2_title:   e.section_2_title ?? '',
    section_2_text:    e.section_2_text ?? '',
    sort_order:        e.sort_order ?? 0,
    is_active:         props.event ? e.is_active : true,
    image:             null,
    detail_image_1:    null,
    detail_image_2:    null,
    detail_image_3:    null,
});

const mainPreview    = ref(null);
const detailPreviews = ref({ 1: null, 2: null, 3: null });

function onMainFile(ev) {
    const f = ev.target.files[0];
    form.image = f || null;
    mainPreview.value = f ? URL.createObjectURL(f) : null;
}
function onDetailFile(n, ev) {
    const f = ev.target.files[0];
    form[`detail_image_${n}`] = f || null;
    detailPreviews.value[n] = f ? URL.createObjectURL(f) : null;
}

function submit() {
    if (props.mode === 'edit') {
        form.transform((d) => ({ ...d, _method: 'PUT' })).post(`/events/${props.event.slug}`, { forceFormData: true });
    } else {
        form.post('/events', { forceFormData: true });
    }
}
</script>
