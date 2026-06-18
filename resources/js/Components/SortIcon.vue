<template>
    <i v-if="active !== col" class="ti ti-arrows-sort text-muted" style="font-size:13px"></i>
    <i v-else :class="dir === 'asc' ? 'ti ti-sort-ascending text-primary' : 'ti ti-sort-descending text-primary'" style="font-size:13px"></i>
</template>

<script setup>
defineProps({
    col:    { type: String, required: true },
    active: { type: String, default: '' },
    dir:    { type: String, default: 'asc' },
});
</script>
