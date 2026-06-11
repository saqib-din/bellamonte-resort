# Bellamonte Resort — Inertia + Vue Conversion Notes

Poora **admin panel + auth** ab Inertia.js + Vue 3 par hai (no page reload). **Landing (public) site Blade hi hai** (SEO + jQuery theme ke liye behtar).

---

## ✅ Final steps (apni machine par)

```bash
# 1. Dependencies (agar pehle se nahi kiye)
composer require inertiajs/inertia-laravel
npm install vue @inertiajs/vue3 @vitejs/plugin-vue

# 2. Build ya dev
npm run dev      # development (HMR)
# ya
npm run build    # production

# Agar purana public/hot file pada ho aur dev band ho:
del public\hot
```

App apne normal URL par kholें (`http://127.0.0.1:8000` ya XAMPP vhost), `localhost:5173` par nahi.

---

## 🗑️ Safe to DELETE (ab Inertia/Vue ne replace kar diya)

Yeh purani admin Blade views ab use nahi hotin (controllers `Inertia::render` karte hain):

```
resources/views/dashboard.blade.php

resources/views/pages/admin-side/rooms/index.blade.php
resources/views/pages/admin-side/rooms/create.blade.php
resources/views/pages/admin-side/rooms/edit.blade.php
resources/views/pages/admin-side/rooms/show.blade.php

resources/views/pages/admin-side/customers/index.blade.php
resources/views/pages/admin-side/customers/create.blade.php
resources/views/pages/admin-side/customers/edit.blade.php
resources/views/pages/admin-side/customers/show.blade.php
resources/views/pages/admin-side/customers/partials/        (poora folder)

resources/views/pages/admin-side/booking/index.blade.php
resources/views/pages/admin-side/booking/create.blade.php
resources/views/pages/admin-side/booking/edit.blade.php
resources/views/pages/admin-side/booking/show.blade.php

resources/views/pages/admin-side/food/orders/index.blade.php
resources/views/pages/admin-side/food/orders/create.blade.php
resources/views/pages/admin-side/food/orders/edit.blade.php
resources/views/pages/admin-side/food/orders/show.blade.php
resources/views/pages/admin-side/food/orders/partials/      (poora folder)
resources/views/pages/admin-side/food/categories/index.blade.php
resources/views/pages/admin-side/food/items/index.blade.php

resources/views/pages/admin-side/billing/index.blade.php
resources/views/pages/admin-side/billing/create.blade.php
resources/views/pages/admin-side/billing/edit.blade.php
resources/views/pages/admin-side/billing/show.blade.php

resources/views/pages/admin-side/events/index.blade.php
resources/views/pages/admin-side/events/create.blade.php
resources/views/pages/admin-side/events/edit.blade.php

resources/views/pages/admin-side/users/index.blade.php
resources/views/pages/admin-side/users/create.blade.php
resources/views/pages/admin-side/users/edit.blade.php

resources/views/pages/admin-side/contacts/index.blade.php
resources/views/pages/admin-side/about/index.blade.php
resources/views/pages/admin-side/settings/index.blade.php

resources/views/auth/login.blade.php
resources/views/auth/register.blade.php
resources/views/auth/forgot-password.blade.php
resources/views/auth/reset-password.blade.php
```

> Tip: delete karne se pehle har file ka naam project mein search kar lo (`view('pages.admin-side...')`) — confirm karne ke liye ke kahin aur reference to nahi.

---

## ⚠️ KEEP karein — ABHI bhi use hoti hain

```
resources/views/app.blade.php                              ← Inertia root (zaroori)

resources/views/pages/admin-side/food/orders/print.blade.php   ← FoodOrderController@print (DomPDF/print)
resources/views/pages/admin-side/billing/print.blade.php       ← BillController@print

resources/views/layouts/admin.blade.php                    ← verify-email / confirm-password abhi Blade hain
resources/views/partials/admin/sidebar.blade.php           ← layouts/admin.blade.php inko include karta hai
resources/views/partials/admin/topbar.blade.php
resources/views/auth/verify-email.blade.php                ← abhi Blade (rarely hit)
resources/views/auth/confirm-password.blade.php            ← abhi Blade (rarely hit)

# ── Landing (public) site — sab Blade rahega ──
resources/views/layouts/landing.blade.php
resources/views/partials/landing/                          (poora folder)
resources/views/pages/landing/index.blade.php
resources/views/pages/rooms/list.blade.php
resources/views/pages/rooms/details.blade.php
resources/views/pages/events/event.blade.php
resources/views/pages/events/details.blade.php
resources/views/pages/about-us/about.blade.php
resources/views/pages/contact-us/contact.blade.php
```

---

## 📝 Optional cleanup (zaroori nahi)

- **Yajra DataTables** ab use nahi hota (sab Vue tables hain). Hatana ho to: `composer remove yajra/laravel-datatables-oracle`
- **Alpine.js** transition ke liye `app.js` mein rakha hai. Agar koi Blade page Alpine use nahi karta to hata sakte hain (lekin nuksan nahi).
- `resources/views/profile/` + `ProfileController` ke koi routes nahi hain → orphan. Chahein to delete.
- `resources/views/components/` (Breeze blade components) — sirf purani auth/profile blades use karti thीं; woh delete karne ke baad yeh bhi orphan ho jayenge.

---

## 🧩 Naya Vue structure

```
resources/js/
├── app.js                      ← Inertia bootstrap (+ global date-picker/click handler)
├── Layouts/
│   ├── AppLayout.vue           ← admin sidebar + topbar + toasts + scroll
│   └── NavLink.vue             ← converted = <Link>, warna <a>
├── Components/
│   ├── SearchSelect.vue        ← searchable dropdown
│   └── AppModal.vue            ← dark-mode-safe modal (edit/view)
├── lib/
│   └── swalDelete.js           ← SweetAlert delete confirm
└── Pages/
    ├── Dashboard.vue
    ├── Rooms/  Customers/  Bookings/  Food/{Orders,Categories,Items}/
    ├── Billing/  Events/  Contacts/  About/  Settings/  Users/
    └── Auth/{Login,ForgotPassword,ResetPassword,Register}.vue
```
