<template>
    <!-- ══════════ SIDEBAR ══════════ -->
    <nav class="pc-sidebar" :class="{ 'pc-sidebar-hide': sidebarHidden, 'mob-sidebar-active': mobileOpen }">
        <div class="navbar-wrapper">
            <div class="navbar-content">

                <!-- User card -->
                <div class="card pc-user-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <NavLink href="/dashboard" :inertia="isConverted('/dashboard')">
                                    <img src="/admin/assets/images/user/avatar-1.jpg" alt="user"
                                        class="user-avtar rounded-circle" style="width: 50px; height: 50px;" />
                                </NavLink>
                            </div>
                            <div class="flex-grow-1 ms-3 me-2">
                                <h6 class="mb-0">{{ user.name }}</h6>
                                <small class="text-muted text-capitalize">{{ user.role }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <ul class="pc-navbar">
                    <li class="pc-item pc-caption"><label>Navigation</label></li>

                    <!-- Dashboard — all roles -->
                    <li class="pc-item" :class="{ active: isActive('/dashboard') }">
                        <NavLink href="/dashboard" cls="pc-link" :inertia="isConverted('/dashboard')">
                            <span class="pc-micon"><svg class="pc-icon"><use xlink:href="#custom-status-up"></use></svg></span>
                            <span class="pc-mtext">Dashboard</span>
                        </NavLink>
                    </li>

                    <!-- Rooms — admin + manager -->
                    <li v-if="can(['admin','manager'])" class="pc-item" :class="{ active: isActive('/admin/rooms') }">
                        <NavLink href="/admin/rooms" cls="pc-link" :inertia="isConverted('/admin/rooms')">
                            <span class="pc-micon"><svg class="pc-icon"><use xlink:href="#custom-element-plus"></use></svg></span>
                            <span class="pc-mtext">Rooms</span>
                        </NavLink>
                    </li>

                    <!-- Customers — admin + manager + receptionist -->
                    <li v-if="can(['admin','manager','receptionist'])" class="pc-item" :class="{ active: isActive('/customers') }">
                        <NavLink href="/customers" cls="pc-link" :inertia="isConverted('/customers')">
                            <span class="pc-micon"><i class="ti ti-users"></i></span>
                            <span class="pc-mtext">Customers</span>
                        </NavLink>
                    </li>

                    <!-- Bookings — admin + manager + receptionist -->
                    <li v-if="can(['admin','manager','receptionist'])" class="pc-item" :class="{ active: isActive('/bookings') }">
                        <NavLink href="/bookings" cls="pc-link" :inertia="isConverted('/bookings')">
                            <span class="pc-micon"><svg class="pc-icon"><use xlink:href="#custom-link"></use></svg></span>
                            <span class="pc-mtext">Bookings</span>
                        </NavLink>
                    </li>

                    <!-- Billing — admin + manager + accountant -->
                    <li v-if="can(['admin','manager','accountant'])" class="pc-item" :class="{ active: isActive('/billing') }">
                        <NavLink href="/billing" cls="pc-link" :inertia="isConverted('/billing')">
                            <span class="pc-micon"><svg class="pc-icon"><use xlink:href="#custom-mouse-circle"></use></svg></span>
                            <span class="pc-mtext">Invoices</span>
                        </NavLink>
                    </li>

                    <!-- Foods Menu — admin + manager + receptionist -->
                    <li v-if="can(['admin','manager','receptionist'])" class="pc-item pc-hasmenu"
                        :class="{ 'pc-trigger': foodOpen, active: isActive('/food') }">
                        <a href="#!" class="pc-link" @click.prevent="foodOpen = !foodOpen">
                            <span class="pc-micon"><i class="ti ti-tools-kitchen-2"></i></span>
                            <span class="pc-mtext">Foods Menu</span>
                            <span class="pc-arrow">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-chevron-right">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </span>
                        </a>
                        <ul class="pc-submenu" :style="foodOpen ? 'display:block;' : 'display:none;'">
                            <template v-if="can(['admin','manager'])">
                                <li class="pc-item" :class="{ active: isActive('/food/categories') }">
                                    <NavLink cls="pc-link" href="/food/categories" :inertia="isConverted('/food/categories')">Categories</NavLink>
                                </li>
                                <li class="pc-item" :class="{ active: isActive('/food/items') }">
                                    <NavLink cls="pc-link" href="/food/items" :inertia="isConverted('/food/items')">Items</NavLink>
                                </li>
                            </template>
                            <li class="pc-item" :class="{ active: isActive('/food/orders') }">
                                <NavLink cls="pc-link" href="/food/orders" :inertia="isConverted('/food/orders')">Orders</NavLink>
                            </li>
                        </ul>
                    </li>

                    <!-- Events — admin + manager -->
                    <li v-if="can(['admin','manager'])" class="pc-item" :class="{ active: isActive('/events') }">
                        <NavLink href="/events" cls="pc-link" :inertia="isConverted('/events')">
                            <span class="pc-micon"><i class="ti ti-calendar-event"></i></span>
                            <span class="pc-mtext">Events</span>
                        </NavLink>
                    </li>

                    <!-- About Us — admin + manager -->
                    <li v-if="can(['admin','manager'])" class="pc-item" :class="{ active: isActive('/about') }">
                        <NavLink href="/about" cls="pc-link" :inertia="isConverted('/about')">
                            <span class="pc-micon"><svg class="pc-icon"><use xlink:href="#custom-keyboard"></use></svg></span>
                            <span class="pc-mtext">About Us</span>
                        </NavLink>
                    </li>

                    <!-- Contacts — admin + manager + receptionist -->
                    <li v-if="can(['admin','manager','receptionist'])" class="pc-item" :class="{ active: isActive('/contacts') }">
                        <NavLink href="/contacts" cls="pc-link" :inertia="isConverted('/contacts')">
                            <span class="pc-micon"><svg class="pc-icon"><use xlink:href="#custom-24-support"></use></svg></span>
                            <span class="pc-mtext">Contacts</span>
                        </NavLink>
                    </li>

                    <!-- Settings — admin only -->
                    <li v-if="can(['admin'])" class="pc-item" :class="{ active: isActive('/settings') }">
                        <NavLink href="/settings" cls="pc-link" :inertia="isConverted('/settings')">
                            <span class="pc-micon"><svg class="pc-icon"><use xlink:href="#custom-setting-2"></use></svg></span>
                            <span class="pc-mtext">Settings</span>
                        </NavLink>
                    </li>

                    <!-- Users — admin only -->
                    <li v-if="can(['admin'])" class="pc-item" :class="{ active: isActive('/users') }">
                        <NavLink href="/users" cls="pc-link" :inertia="isConverted('/users')">
                            <span class="pc-micon"><svg class="pc-icon"><use xlink:href="#custom-user-square"></use></svg></span>
                            <span class="pc-mtext">Users</span>
                        </NavLink>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ══════════ TOPBAR ══════════ -->
    <header class="pc-header">
        <div class="header-wrapper">
            <div class="me-auto pc-mob-drp">
                <ul class="list-unstyled">
                    <li class="pc-h-item pc-sidebar-collapse">
                        <a href="#" class="pc-head-link ms-0" @click.prevent="sidebarHidden = !sidebarHidden">
                            <i class="ti ti-menu-2"></i>
                        </a>
                    </li>
                    <li class="pc-h-item pc-sidebar-popup">
                        <a href="#" class="pc-head-link ms-0" @click.prevent="mobileOpen = !mobileOpen">
                            <i class="ti ti-menu-2"></i>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="ms-auto">
                <ul class="list-unstyled">

                    <!-- Dark / Light mode -->
                    <li class="dropdown pc-h-item">
                        <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button">
                            <svg class="pc-icon"><use xlink:href="#custom-sun-1"></use></svg>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end pc-h-dropdown">
                            <a href="#!" class="dropdown-item" @click.prevent="setTheme('dark')">
                                <svg class="pc-icon"><use xlink:href="#custom-moon"></use></svg><span>Dark</span>
                            </a>
                            <a href="#!" class="dropdown-item" @click.prevent="setTheme('light')">
                                <svg class="pc-icon"><use xlink:href="#custom-sun-1"></use></svg><span>Light</span>
                            </a>
                            <a href="#!" class="dropdown-item" @click.prevent="setTheme('default')">
                                <svg class="pc-icon"><use xlink:href="#custom-setting-2"></use></svg><span>Default</span>
                            </a>
                        </div>
                    </li>

                    <!-- Profile / Logout -->
                    <li class="dropdown pc-h-item">
                        <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button">
                            <svg class="pc-icon"><use xlink:href="#custom-setting-2"></use></svg>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end pc-h-dropdown">
                            <NavLink :href="`/users/${user.uuid}/edit`" cls="dropdown-item" :inertia="isConverted('/users')">
                                <i class="ti ti-user"></i><span>Profile</span>
                            </NavLink>
                            <a href="#" class="dropdown-item" @click.prevent="logout">
                                <i class="ti ti-power"></i><span>Logout</span>
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <!-- ══════════ PAGE CONTENT ══════════ -->
    <div class="pc-container">
        <div class="pc-content">
            <FlashAlert />
            <slot />
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import NavLink from '@/Layouts/NavLink.vue';
import FlashAlert from '@/Components/FlashAlert.vue';

const page = usePage();
const user = computed(() => page.props.auth?.user ?? {});

// Role-based menu visibility
function can(roles) {
    return roles.includes(user.value.role);
}

const convertedPrefixes = [
    '/dashboard',
    '/admin/rooms',
    '/customers',
    '/bookings',
    '/food',
    '/billing',
    '/events',
    '/about',
    '/contacts',
    '/settings',
    '/users',
];
function isConverted(path) {
    return convertedPrefixes.some((p) => path === p || path.startsWith(p));
}

// Active link highlight
function isActive(prefix) {
    return (page.url || '').startsWith(prefix);
}

// Sidebar collapse / mobile
const sidebarHidden = ref(false);
const mobileOpen    = ref(false);
const foodOpen      = ref(isActive('/food'));

function logout() {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/logout';
    const token = document.querySelector('meta[name="csrf-token"]')?.content || '';
    form.innerHTML = `<input type="hidden" name="_token" value="${token}">`;
    document.body.appendChild(form);
    form.submit();
}

// Theme (theme.js globals)
const setTheme = (mode) => {
    if (mode === 'dark')    { localStorage.setItem('theme', 'dark');  window.layout_change?.('dark'); }
    if (mode === 'light')   { localStorage.setItem('theme', 'light'); window.layout_change?.('light'); }
    if (mode === 'default') { localStorage.removeItem('theme');        window.layout_change_default?.(); }
};

</script>

<style>
.pc-sidebar .navbar-wrapper {
    height: 100%;
}

.pc-sidebar .navbar-content {
    max-height: 100vh;
    overflow-y: auto !important;
    overflow-x: hidden;
}

.pc-sidebar .navbar-content::-webkit-scrollbar {
    width: 6px;
}

.pc-sidebar .navbar-content::-webkit-scrollbar-thumb {
    background: transparent;
    border-radius: 3px;
    transition: background .25s ease;
}

.pc-sidebar .navbar-content:hover::-webkit-scrollbar-thumb {
    background: rgba(0, 0, 0, .25);
}

[data-pc-theme="dark"] .pc-sidebar .navbar-content:hover::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, .25);
}

/* Firefox */
.pc-sidebar .navbar-content {
    scrollbar-width: thin;
    scrollbar-color: transparent transparent;
}

.pc-sidebar .navbar-content:hover {
    scrollbar-color: rgba(0, 0, 0, .25) transparent;
}
</style>
