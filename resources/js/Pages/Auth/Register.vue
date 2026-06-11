<template>
    <div class="auth-main">
        <div class="auth-wrapper v1">
            <div class="auth-form">
                <div class="card my-5">
                    <div class="card-body">
                        <div class="pt-0 pb-4 d-flex justify-content-center">
                            <img src="/admin/assets/images/favicon.png" class="img-fluid" alt="Logo" />
                        </div>

                        <h4 class="text-center f-w-500 mb-4">Create an Account</h4>

                        <form @submit.prevent="submit">
                            <div class="mb-3">
                                <input type="text" name="name" class="form-control" placeholder="Full Name"
                                    v-model="form.name" required autofocus autocomplete="name" />
                                <span v-if="form.errors.name" class="text-danger">{{ form.errors.name }}</span>
                            </div>
                            <div class="mb-3">
                                <input type="email" name="email" class="form-control" placeholder="Email Address"
                                    v-model="form.email" required autocomplete="username" />
                                <span v-if="form.errors.email" class="text-danger">{{ form.errors.email }}</span>
                            </div>
                            <div class="mb-3">
                                <input type="password" name="password" class="form-control" placeholder="Password"
                                    v-model="form.password" required autocomplete="new-password" />
                                <span v-if="form.errors.password" class="text-danger">{{ form.errors.password }}</span>
                            </div>
                            <div class="mb-3">
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password"
                                    v-model="form.password_confirmation" required autocomplete="new-password" />
                            </div>
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-light-primary" :disabled="form.processing">
                                    {{ form.processing ? 'Creating...' : 'Create Account' }}
                                </button>
                            </div>
                        </form>

                        <div class="d-flex justify-content-between align-items-end mt-4">
                            <h6 class="f-w-500 mb-0">Already have an account?</h6>
                            <Link href="/login" class="link-primary">Login</Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

function submit() {
    form.post('/register', {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
}
</script>
