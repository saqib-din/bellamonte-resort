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
                <li v-if="loading" class="ss-empty">Searching...</li>
                <li v-else-if="!filtered.length" class="ss-empty">No results found</li>
            </ul>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch, nextTick, onMounted, onUnmounted } from 'vue';

const props = defineProps({
    modelValue:        { type: [String, Number], default: '' },
    options:           { type: Array, default: () => [] },
    placeholder:       { type: String, default: '-- Select --' },
    searchPlaceholder: { type: String, default: 'Search...' },
    invalid:           { type: Boolean, default: false },
    clearable:         { type: Boolean, default: true },
    fetchUrl:          { type: String, default: '' },
    preload:           { type: Array, default: () => [] },
});

const emit = defineEmits(['update:modelValue', 'change', 'select']);

const root        = ref(null);
const searchInput = ref(null);
const open        = ref(false);
const q           = ref('');
const remote      = ref([]);
const loading     = ref(false);
const isAsync     = computed(() => !!props.fetchUrl);

const pool = computed(() => {
    if (!isAsync.value) return props.options;
    const map = new Map();
    [...props.preload, ...remote.value].forEach((o) => map.set(o.value, o));
    return [...map.values()];
});

const selectedLabel = computed(() => {
    const o = pool.value.find((x) => x.value === props.modelValue);
    return o ? o.label : '';
});

const filtered = computed(() => {
    if (isAsync.value) {
        return (remote.value.length || q.value) ? remote.value : props.preload;
    }
    const s = q.value.trim().toLowerCase();
    if (!s) return props.options;
    return props.options.filter((o) => o.label.toLowerCase().includes(s));
});

let timer = null;
async function fetchRemote() {
    loading.value = true;
    try {
        const url = props.fetchUrl + (props.fetchUrl.includes('?') ? '&' : '?') + 'q=' + encodeURIComponent(q.value);
        const res = await fetch(url, {
            headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin',
        });
        if (res.ok) remote.value = await res.json();
    } catch (e) {
        /* ignore network errors */
    }
    loading.value = false;
}

watch(q, () => {
    if (!isAsync.value) return;
    clearTimeout(timer);
    timer = setTimeout(fetchRemote, 300);
});

function toggle() {
    open.value = !open.value;
    if (open.value) {
        q.value = '';
        if (isAsync.value && !remote.value.length) fetchRemote();
        nextTick(() => searchInput.value?.focus());
    }
}

function choose(value) {
    emit('update:modelValue', value);
    emit('change', value);
    emit('select', pool.value.find((x) => x.value === value) || null);
    open.value = false;
}

function onClickOutside(e) {
    if (root.value && !root.value.contains(e.target)) open.value = false;
}

onMounted(() => document.addEventListener('click', onClickOutside));
onUnmounted(() => document.removeEventListener('click', onClickOutside));
</script>

<style scoped>
.ss-wrap { position: relative; }
.ss-control {
    cursor: pointer;
    display: flex;
    align-items: center;
    min-height: 38px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.ss-value { overflow: hidden; text-overflow: ellipsis; }
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
.ss-search { margin-bottom: 6px; }
.ss-list { list-style: none; margin: 0; padding: 0; max-height: 220px; overflow-y: auto; }
.ss-item { padding: 7px 10px; border-radius: 5px; cursor: pointer; font-size: 14px; }
.ss-item:hover { background: #f1f5fb; }
.ss-item.active { background: #e7f0ff; color: #1c64f2; font-weight: 500; }
.ss-clear { color: #8a9ab5; }
.ss-empty { padding: 7px 10px; color: #8a9ab5; font-size: 13px; }

/* Dark mode (Able Pro uses data-pc-theme="dark" on <html>) */
[data-pc-theme="dark"] .ss-dropdown {
    background: #1d2630;
    border-color: #3a4a5a;
    box-shadow: 0 8px 24px rgba(0, 0, 0, .45);
}
[data-pc-theme="dark"] .ss-item { color: #bfc8d6; }
[data-pc-theme="dark"] .ss-item:hover { background: rgba(255, 255, 255, .06); }
[data-pc-theme="dark"] .ss-item.active { background: rgba(110, 168, 254, .16); color: #6ea8fe; }
[data-pc-theme="dark"] .ss-clear,
[data-pc-theme="dark"] .ss-empty { color: #8693a4; }
</style>
