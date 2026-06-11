<template>
    <div class="auth-main">
        <div class="auth-wrapper v1">
            <div class="auth-form">
                <div class="card my-5">
                    <div class="card-body">
                        <div class="pt-0 pb-4 d-flex justify-content-center">
                            <img src="/admin/assets/images/favicon.png" class="img-fluid" alt="Logo" />
                        </div>

                        <h4 class="text-center f-w-500 mb-4">Reset Password</h4>

                        <form @submit.prevent="submit">
                            <div class="mb-3">
                                <input type="email" name="email" class="form-control" placeholder="Email Address"
                                    v-model="form.email" required autofocus autocomplete="username" />
                                <span v-if="form.errors.email" class="text-danger">{{ form.errors.email }}</span>
                            </div>
                            <div class="mb-3">
                                <input type="password" name="password" class="form-control" placeholder="New Password"
                                    v-model="form.password" required autocomplete="new-password" />
                                <span v-if="form.errors.password" class="text-danger">{{ form.errors.password }}</span>
                            </div>
                            <div class="mb-3">
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password"
                                    v-model="form.password_confirmation" required autocomplete="new-password" />
                            </div>
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-light-primary" :disabled="form.processing">
                                    {{ form.processing ? 'Resetting...' : 'Reset Password' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    email: { type: String, default: '' },
    token: { type: String, default: '' },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

function submit() {
    form.post('/reset-password', {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
}
</script>
