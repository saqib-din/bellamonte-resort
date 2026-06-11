<template>
    <Link v-if="inertia" :href="href" :class="cls"><slot /></Link>
    <a v-else :href="href" :class="cls"><slot /></a>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    href:    { type: String, required: true },
    inertia: { type: Boolean, default: false },
    cls:     { type: String, default: '' },
});
</script>
