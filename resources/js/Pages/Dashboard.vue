<template>
    <!-- Header -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title d-flex justify-content-between">
                        <h2 class="mb-0">Dashboard</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ══ WELCOME BANNER ══ -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card welcome-banner">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-lg-6 col-md-12 d-flex align-items-center">
                            <div class="d-flex align-items-start">
                                <div class="me-3">
                                    <div class="user-upload wid-75">
                                        <img src="/admin/assets/images/favicon.png" alt="Logo" class="img-fluid" style="max-width:140px;" />
                                    </div>
                                </div>
                                <div class="content-stack">
                                    <h2 class="text-white mb-1">Bellamonte Resort</h2>
                                    <p class="text-white">Bellamonte Resort Shogran offers luxury rooms, mountain views,
                                        hotel booking, family stays, and premium hospitality services in Shogran.</p>
                                    <div class="quick-stat mt-2">
                                        <i class="ti ti-calendar"></i>
                                        <span>{{ today }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 d-none d-lg-block">
                            <div class="classical-image-container">
                                <div class="image-wrapper">
                                    <div class="glass-frame">
                                        <img src="/admin/assets/images/widget/welcome-banner.png" alt="Welcome Banner" class="banner-image" />
                                    </div>
                                    <div class="float-icon icon-1"><i class="ti ti-sparkles"></i></div>
                                    <div class="float-icon icon-2"><i class="ti ti-star-filled"></i></div>
                                    <div class="float-icon icon-3"><i class="ti ti-circle-check-filled"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ══ KPI Stat Cards ══ -->
    <div class="row g-3 mb-4">

        <!-- Total Rooms -->
        <div class="col-lg-4 col-md-6">
            <div class="card bm-stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <p class="bm-stat-label">Total Rooms</p>
                            <h3 class="bm-stat-value text-primary">{{ stats.totalRooms }}</h3>
                            <p class="bm-stat-sub"><span class="text-muted">Available:</span>
                                <strong class="text-success ms-1">+{{ stats.availableRooms }}</strong></p>
                        </div>
                        <div class="avtar avtar-m bg-light-primary rounded"><i class="ti ti-home f-24"></i></div>
                    </div>
                    <div class="bm-stat-footer">
                        <Link v-if="canManage" href="/admin/rooms" class="text-primary f-12"><i class="ti ti-eye me-1"></i> View all rooms</Link>
                        <span v-else class="text-muted f-12"><i class="ti ti-lock me-1"></i> Only Admin / Manager can view rooms</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Bookings -->
        <div class="col-lg-4 col-md-6">
            <div class="card bm-stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <p class="bm-stat-label">Total Bookings</p>
                            <h3 class="bm-stat-value text-primary">{{ stats.totalBookings }}</h3>
                            <p class="bm-stat-sub"><span class="text-muted">This Month:</span>
                                <strong class="text-primary ms-1">{{ stats.thisMonthBookings }}</strong></p>
                        </div>
                        <div class="avtar avtar-m bg-light-primary rounded"><i class="ti ti-calendar f-24"></i></div>
                    </div>
                    <div class="bm-stat-footer">
                        <span v-if="stats.bookingGrowth >= 0" class="text-success f-12"><i class="ti ti-trending-up me-1"></i>+{{ stats.bookingGrowth }}% vs last month</span>
                        <span v-else class="text-danger f-12"><i class="ti ti-trending-down me-1"></i>{{ stats.bookingGrowth }}% vs last month</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Customers -->
        <div class="col-lg-4 col-md-6">
            <div class="card bm-stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <p class="bm-stat-label">Total Customers</p>
                            <h3 class="bm-stat-value text-info">{{ stats.totalCustomers }}</h3>
                            <p class="bm-stat-sub"><span class="text-muted">New this month:</span>
                                <strong class="text-success ms-1">+{{ stats.newCustomers }}</strong></p>
                        </div>
                        <div class="avtar avtar-m bg-light-info rounded"><i class="ti ti-users f-24"></i></div>
                    </div>
                    <div class="bm-stat-footer">
                        <Link v-if="canManage" href="/customers" class="text-info f-12"><i class="ti ti-eye me-1"></i> View all customers</Link>
                        <span v-else class="text-muted f-12"><i class="ti ti-lock me-1"></i> Only Admin / Manager can view customers</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Paid Invoices (Revenue) -->
        <div class="col-lg-6 col-md-6">
            <div class="card bm-stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <p class="bm-stat-label">Paid Invoices</p>
                            <h3 class="bm-stat-value text-success">{{ n(stats.revenueThisMonth) }} ₨</h3>
                            <p class="bm-stat-sub"><span class="text-muted">Today:</span>
                                <strong class="text-success ms-1">{{ n(stats.revenueToday) }} ₨</strong></p>
                        </div>
                        <div class="avtar avtar-m bg-light-success rounded"><i class="ti ti-currency-dollar f-24"></i></div>
                    </div>
                    <div class="bm-stat-footer">
                        <span v-if="stats.revenueGrowth >= 0" class="text-success f-12"><i class="ti ti-trending-up me-1"></i>+{{ stats.revenueGrowth }}% vs last month</span>
                        <span v-else class="text-danger f-12"><i class="ti ti-trending-down me-1"></i>{{ stats.revenueGrowth }}% vs last month</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Invoices -->
        <div class="col-lg-6 col-md-6">
            <div class="card bm-stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <p class="bm-stat-label">Pending Invoices</p>
                            <h3 class="bm-stat-value text-danger">{{ n(stats.pendingAmount) }} ₨</h3>
                            <p class="bm-stat-sub"><span class="text-muted">Unpaid:</span>
                                <strong class="text-danger ms-1">{{ stats.unpaidBills }}</strong>
                                <span class="text-muted ms-2">Partial:</span>
                                <strong class="text-warning ms-1">{{ stats.partialBills }}</strong></p>
                        </div>
                        <div class="avtar avtar-m bg-light-danger rounded"><i class="ti ti-receipt f-24"></i></div>
                    </div>
                    <div class="bm-stat-footer">
                        <Link v-if="canManage" href="/billing" class="text-danger f-12"><i class="ti ti-eye me-1"></i> View all invoices</Link>
                        <span v-else class="text-muted f-12"><i class="ti ti-lock me-1"></i> Only Admin / Manager can view invoices</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Food Orders -->
        <div class="col-lg-6 col-md-6">
            <div class="card bm-stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <p class="bm-stat-label">Total Food Orders</p>
                            <h3 class="bm-stat-value text-primary">{{ stats.totalFoodOrders }}</h3>
                            <p class="bm-stat-sub"><span class="text-muted">Pending:</span>
                                <strong class="text-warning ms-1">{{ stats.pendingFoodOrders }}</strong>
                                <span class="text-muted ms-2">Served:</span>
                                <strong class="text-info ms-1">{{ stats.servedFoodOrders }}</strong></p>
                        </div>
                        <div class="avtar avtar-m bg-light-primary rounded"><i class="ti ti-tools-kitchen-2 f-24"></i></div>
                    </div>
                    <div class="bm-stat-footer">
                        <Link v-if="canManage" href="/food/orders" class="text-primary f-12"><i class="ti ti-eye me-1"></i> View all food orders</Link>
                        <span v-else class="text-muted f-12"><i class="ti ti-lock me-1"></i> Only Admin / Manager can view food orders</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Food Revenue -->
        <div class="col-lg-6 col-md-6">
            <div class="card bm-stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <p class="bm-stat-label">Food Revenue</p>
                            <h3 class="bm-stat-value text-success">{{ n(stats.foodRevenueThisMonth) }} ₨</h3>
                            <p class="bm-stat-sub"><span class="text-muted">Today:</span>
                                <strong class="text-success ms-1">{{ n(stats.foodRevenueToday) }} ₨</strong></p>
                        </div>
                        <div class="avtar avtar-m bg-light-success rounded"><i class="ti ti-cash f-24"></i></div>
                    </div>
                    <div class="bm-stat-footer">
                        <span class="text-muted f-12"><i class="ti ti-clock me-1"></i> Pending: <strong class="text-danger">{{ n(stats.foodPendingAmount) }} ₨</strong></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Events -->
        <div class="col-lg-4 col-md-6">
            <div class="card bm-stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <p class="bm-stat-label">Total Events</p>
                            <h3 class="bm-stat-value text-info">{{ stats.totalEvents }}</h3>
                            <p class="bm-stat-sub"><span class="text-muted">Active:</span>
                                <strong class="text-success ms-1">+{{ stats.activeEvents }}</strong></p>
                        </div>
                        <div class="avtar avtar-m bg-light-info rounded"><i class="ti ti-calendar-event f-24"></i></div>
                    </div>
                    <div class="bm-stat-footer">
                        <Link v-if="canManage" href="/events" class="text-info f-12"><i class="ti ti-eye me-1"></i> View all events</Link>
                        <span v-else class="text-muted f-12"><i class="ti ti-lock me-1"></i> Only Admin / Manager can view events</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Contacts -->
        <div class="col-lg-4 col-md-6">
            <div class="card bm-stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <p class="bm-stat-label">Total Contacts</p>
                            <h3 class="bm-stat-value text-warning">{{ stats.totalContacts }}</h3>
                            <p class="bm-stat-sub"><span class="text-muted">Replied:</span>
                                <strong class="text-success ms-1">+{{ stats.repliedContacts }}</strong></p>
                        </div>
                        <div class="avtar avtar-m bg-light-warning rounded"><i class="ti ti-message f-24"></i></div>
                    </div>
                    <div class="bm-stat-footer">
                        <Link v-if="canManage" href="/contacts" class="text-warning f-12"><i class="ti ti-eye me-1"></i> View all contacts</Link>
                        <span v-else class="text-muted f-12"><i class="ti ti-lock me-1"></i> Only Admin / Manager can view contacts</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Users -->
        <div class="col-lg-4 col-md-6">
            <div class="card bm-stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <p class="bm-stat-label">Total Users</p>
                            <h3 class="bm-stat-value text-success">{{ stats.totalUsers }}</h3>
                            <p class="bm-stat-sub"><span class="text-muted">Active:</span>
                                <strong class="text-primary ms-1">+{{ stats.activeUsers }}</strong></p>
                        </div>
                        <div class="avtar avtar-m bg-light-success rounded"><i class="ti ti-user f-24"></i></div>
                    </div>
                    <div class="bm-stat-footer">
                        <Link v-if="canManage" href="/users" class="text-success f-12"><i class="ti ti-eye me-1"></i> View all users</Link>
                        <span v-else class="text-muted f-12"><i class="ti ti-lock me-1"></i> Only Admin / Manager can view users</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineOptions({ layout: AppLayout });

defineProps({
    today: { type: String, default: '' },
    stats: { type: Object, required: true },
});

const page = usePage();
const canManage = computed(() => page.props.auth?.user?.canManage === true);

// number_format() equivalent (thousands separators, no decimals)
const n = (v) => Number(v || 0).toLocaleString('en-US');
</script>
