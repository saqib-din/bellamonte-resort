<template>
    <div class="auth-main">
        <div class="auth-wrapper v1">
            <div class="auth-form">
                <div class="card my-5">
                    <div class="card-body">
                        <div class="pt-0 pb-4 d-flex justify-content-center">
                            <img src="/admin/assets/images/favicon.png" class="img-fluid" alt="Bellamonte Resort App Logo" />
                        </div>

                        <h4 class="text-center f-w-500 mb-4">Login with your email</h4>

                        <div v-if="status" class="alert alert-success">{{ status }}</div>

                        <form @submit.prevent="submit">
                            <div class="mb-3">
                                <input type="email" id="email" name="email" class="form-control" placeholder="Email Address"
                                    v-model="form.email" required autofocus autocomplete="username" />
                                <span v-if="form.errors.email" class="text-danger">{{ form.errors.email }}</span>
                            </div>

                            <div class="mb-3">
                                <input type="password" id="password" name="password" class="form-control" placeholder="Password"
                                    v-model="form.password" required autocomplete="current-password" />
                                <span v-if="form.errors.password" class="text-danger">{{ form.errors.password }}</span>
                            </div>

                            <div class="d-flex mt-1 justify-content-between align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input input-primary" type="checkbox" id="remember_me" v-model="form.remember" />
                                    <label class="form-check-label text-muted" for="remember_me">Remember me?</label>
                                </div>
                                <!-- <h6 v-if="canResetPassword" class="text-secondary f-w-400 mb-0">
                                    <Link href="/forgot-password">Forgot Password?</Link>
                                </h6> -->
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-light-primary" :disabled="form.processing">
                                    {{ form.processing ? 'Logging in...' : 'Login' }}
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
import { Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: { type: Boolean, default: false },
    status:           { type: String, default: '' },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

function submit() {
    form.post('/login', {
        onFinish: () => form.reset('password'),
    });
}
</script>
