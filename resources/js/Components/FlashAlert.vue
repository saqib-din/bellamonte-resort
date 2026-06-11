<template>
    <Teleport v-if="ready" to="#flash-anchor">
        <div v-for="a in alerts" :key="a.id"
            class="alert alert-dismissible fade show mt-2" :class="`alert-${a.variant}`" role="alert">
            {{ a.message }}
            <button type="button" class="btn-close" @click="dismiss(a.id)" aria-label="Close"></button>
        </div>
    </Teleport>
</template>

<script setup>
import { ref, watch, onMounted, onBeforeUnmount, nextTick } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const alerts = ref([]);
const ready = ref(false);
let id = 0;
let anchor = null;

function push(message, variant) {
    if (!message) return;
    const aid = ++id;
    alerts.value.push({ id: aid, message, variant });
    setTimeout(() => dismiss(aid), 3000); // 3s auto-dismiss
}
function dismiss(aid) {
    alerts.value = alerts.value.filter((a) => a.id !== aid);
}
function showFromFlash(flash) {
    if (!flash) return;
    if (flash.success) push(flash.success, 'success');
    if (flash.status)  push(flash.status, 'success');
    if (flash.error)   push(flash.error, 'danger');
    if (flash.warning) push(flash.warning, 'warning');
    if (flash.info)    push(flash.info, 'info');
}

function placeAnchor() {
    const content = document.querySelector('.pc-content');
    if (!content) return false;
    if (!anchor) {
        anchor = document.createElement('div');
        anchor.id = 'flash-anchor';
    }
    const header = content.querySelector(':scope > .page-header');
    if (header) {
        if (header.nextElementSibling !== anchor) header.after(anchor);
    } else if (anchor.parentNode !== content) {
        content.prepend(anchor);
    }
    return true;
}

async function refresh() {
    await nextTick();
    if (placeAnchor()) ready.value = true;
}

onMounted(async () => {
    await refresh();
    showFromFlash(page.props.flash);
});

watch(() => page.component, () => { refresh(); });

watch(() => page.props.flash, (f) => showFromFlash(f), { deep: true });

onBeforeUnmount(() => { anchor?.remove(); });
</script>
