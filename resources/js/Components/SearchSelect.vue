<template>
    <div class="ss-wrap" ref="root">
        <div class="form-select ss-control" :class="{ 'is-invalid': invalid }" @click="toggle">
            <span v-if="selectedLabel" class="ss-value">{{ selectedLabel }}</span>
            <span v-else class="text-muted">{{ placeholder }}</span>
        </div>

        <div v-if="open" class="ss-dropdown">
            <input ref="searchInput" v-model="q" type="text" class="form-control form-control-sm ss-search"
                :placeholder="searchPlaceholder" @click.stop>
            <ul class="ss-list">
                <li v-if="clearable" class="ss-item ss-clear" @click="choose('')">{{ placeholder }}</li>
                <li v-for="o in filtered" :key="o.value" class="ss-item" :class="{ active: o.value === modelValue }"
                    @click="choose(o.value)">{{ o.label }}</li>
                <li v-if="!filtered.length" class="ss-empty">No results found</li>
            </ul>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, nextTick, onMounted, onUnmounted } from 'vue';

const props = defineProps({
    modelValue:        { type: [String, Number], default: '' },
    options:           { type: Array, default: () => [] }, // [{ value, label }]
    placeholder:       { type: String, default: '-- Select --' },
    searchPlaceholder: { type: String, default: 'Search...' },
    invalid:           { type: Boolean, default: false },
    clearable:         { type: Boolean, default: true },
});

const emit = defineEmits(['update:modelValue', 'change']);

const root        = ref(null);
const searchInput = ref(null);
const open        = ref(false);
const q           = ref('');

const selectedLabel = computed(() => {
    const o = props.options.find((x) => x.value === props.modelValue);
    return o ? o.label : '';
});

const filtered = computed(() => {
    const s = q.value.trim().toLowerCase();
    if (!s) return props.options;
    return props.options.filter((o) => o.label.toLowerCase().includes(s));
});

function toggle() {
    open.value = !open.value;
    if (open.value) {
        q.value = '';
        nextTick(() => searchInput.value?.focus());
    }
}

function choose(value) {
    emit('update:modelValue', value);
    emit('change', value);
    open.value = false;
}

function onClickOutside(e) {
    if (root.value && !root.value.contains(e.target)) open.value = false;
}

onMounted(() => document.addEventListener('click', onClickOutside));
onUnmounted(() => document.removeEventListener('click', onClickOutside));
</script>

<style scoped>
.ss-wrap {
    position: relative;
}

.ss-control {
    cursor: pointer;
    display: flex;
    align-items: center;
    min-height: 38px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.ss-value {
    overflow: hidden;
    text-overflow: ellipsis;
}

.ss-dropdown {
    position: absolute;
    z-index: 1056;
    top: calc(100% + 2px);
    left: 0;
    right: 0;
    background: #fff;
    border: 1px solid #d9e1ef;
    border-radius: 6px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, .12);
    padding: 8px;
}

.ss-search {
    margin-bottom: 6px;
}

.ss-list {
    list-style: none;
    margin: 0;
    padding: 0;
    max-height: 220px;
    overflow-y: auto;
}

.ss-item {
    padding: 7px 10px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
}

.ss-item:hover {
    background: #f1f5fb;
}

.ss-item.active {
    background: #e7f0ff;
    color: #1c64f2;
    font-weight: 500;
}

.ss-clear {
    color: #8a9ab5;
}

.ss-empty {
    padding: 8px 10px;
    color: #8a9ab5;
    font-size: 13px;
    text-align: center;
}

[data-pc-theme="dark"] .ss-dropdown {
    background: #1b2431;
    border-color: #2a3547;
}

[data-pc-theme="dark"] .ss-item:hover {
    background: #2a3547;
}
</style>
