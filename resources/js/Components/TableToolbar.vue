<template>
    <div class="row align-items-center mb-3 g-2">
        <div class="col-sm-6">
            <div class="d-flex align-items-center">
                <span class="me-2">Show</span>
                <select :value="perPage" class="form-select form-select-sm" style="width:75px"
                    @change="$emit('update:perPage', Number($event.target.value))">
                    <option v-for="o in perPageOptions" :key="o" :value="o">{{ o }}</option>
                </select>
                <span class="ms-2">entries</span>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="d-flex align-items-center justify-content-sm-end">
                <span class="me-2">Search:</span>
                <input type="text" :value="search" class="form-control form-control-sm" style="max-width:240px"
                    @input="$emit('update:search', $event.target.value)">
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    perPage:        { type: [Number, String], default: 10 },
    search:         { type: String, default: '' },
    perPageOptions: { type: Array, default: () => [10, 25, 50, 100] },
});
defineEmits(['update:perPage', 'update:search']);
</script>
