<template>
    <div v-if="show" class="cm-overlay" @click.self="$emit('cancel')">
        <div class="card cm-panel">
            <div class="card-body text-center p-4">
                <div class="cm-icon mx-auto mb-3"><i class="ti ti-trash"></i></div>
                <h5 class="mb-2">{{ title }}</h5>
                <p class="text-muted mb-4">{{ message }}</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button class="btn btn-outline-secondary flex-fill" @click="$emit('cancel')">Cancel</button>
                    <button class="btn btn-danger flex-fill" @click="$emit('confirm')">Delete</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    show:    { type: Boolean, default: false },
    title:   { type: String, default: 'Delete?' },
    message: { type: String, default: 'This record will be permanently deleted.' },
});
defineEmits(['cancel', 'confirm']);
</script>

<style scoped>
.cm-overlay {
    position: fixed;
    inset: 0;
    z-index: 10000;
    background: rgba(0, 0, 0, .45);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 16px;
}

.cm-panel {
    width: 100%;
    max-width: 380px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, .25);
}

.cm-icon {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: rgba(217, 48, 37, .12);
    display: flex;
    align-items: center;
    justify-content: center;
}

.cm-icon i {
    font-size: 26px;
    color: #d93025;
}
</style>
