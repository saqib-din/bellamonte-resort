<template>
    <div v-if="show" class="am-overlay" @click.self="$emit('close')">
        <div class="card am-panel" :style="`max-width:${maxWidth}px`">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ title }}</h5>
                <button type="button" class="btn-close" @click="$emit('close')"></button>
            </div>
            <div class="card-body">
                <slot />
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    show:     { type: Boolean, default: false },
    title:    { type: String, default: '' },
    maxWidth: { type: [String, Number], default: 460 },
});
defineEmits(['close']);
</script>

<style scoped>
.am-overlay {
    position: fixed;
    inset: 0;
    z-index: 10000;
    background: rgba(0, 0, 0, .45);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 16px;
}

.am-panel {
    width: 100%;
    max-height: 90vh;
    overflow: auto;
    box-shadow: 0 20px 60px rgba(0, 0, 0, .25);
}
</style>
