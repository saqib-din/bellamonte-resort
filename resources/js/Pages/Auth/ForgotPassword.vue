<template>
    <div class="auth-main">
        <div class="auth-wrapper v1">
            <div class="auth-form">
                <div class="card my-5">
                    <div class="card-body">
                        <div class="pt-0 pb-4 d-flex justify-content-center">
                            <img src="/admin/assets/images/favicon.png" class="img-fluid" alt="Logo" />
                        </div>

                        <h4 class="text-center f-w-500 mb-2">Forgot Password?</h4>
                        <p class="text-muted text-center mb-4 f-13">Enter your email and we'll send you a password reset link.</p>

                        <div v-if="status" class="alert alert-success">{{ status }}</div>

                        <form @submit.prevent="submit">
                            <div class="mb-3">
                                <input type="email" name="email" class="form-control" placeholder="Email Address"
                                    v-model="form.email" required autofocus autocomplete="username" />
                                <span v-if="form.errors.email" class="text-danger">{{ form.errors.email }}</span>
                            </div>
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-light-primary" :disabled="form.processing">
                                    {{ form.processing ? 'Sending...' : 'Email Password Reset Link' }}
                                </button>
                            </div>
                        </form>

                        <div class="text-center mt-4">
                            <Link href="/login" class="link-primary">← Back to Login</Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';

defineProps({
    status: { type: String, default: '' },
});

const form = useForm({ email: '' });

function submit() {
    form.post('/forgot-password');
}
</script>
