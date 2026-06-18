<template>
    <div class="row align-items-center mt-3 g-2">
        <div class="col-sm-6">
            <small class="text-muted">Showing {{ from || 0 }} to {{ to || 0 }} of {{ total || 0 }} entries</small>
        </div>
        <div class="col-sm-6">
            <div class="d-flex justify-content-sm-end gap-2">
                <button type="button" class="btn btn-sm btn-light-secondary" :disabled="!canPrev" @click="$emit('prev')">Previous</button>
                <button type="button" class="btn btn-sm btn-light-secondary" :disabled="!canNext" @click="$emit('next')">Next</button>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    from:    { type: Number, default: 0 },
    to:      { type: Number, default: 0 },
    total:   { type: Number, default: 0 },
    canPrev: { type: Boolean, default: false },
    canNext: { type: Boolean, default: false },
});
defineEmits(['prev', 'next']);
</script>
