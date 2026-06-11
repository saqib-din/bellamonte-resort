<template>
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><Link href="/dashboard">Home</Link></li>
                        <li class="breadcrumb-item"><Link href="/users">Users</Link></li>
                        <li class="breadcrumb-item" aria-current="page">Edit — {{ user.name }}</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title"><h2 class="mb-0">Edit User</h2></div>
                </div>
            </div>
        </div>
    </div>

    <UserForm mode="edit" :user="user" />
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import UserForm from '@/Pages/Users/UserForm.vue';

defineOptions({ layout: AppLayout });

defineProps({
    user: { type: Object, required: true },
});
</script>
