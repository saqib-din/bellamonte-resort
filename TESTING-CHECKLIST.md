# Bellamonte Resort — Full Project Check & Testing Checklist

_Generated after the project-wide validation + features pass._

---

## 1. Automated verification (PASS)

| Check | Result |
|---|---|
| **Frontend build** (`npm run build`) | ✅ All 43 Vue pages + components compile, no errors |
| **Form field errors** | ✅ Every editable field in every form shows an inline error (audited; only checkboxes & hidden token are exempt by design) |
| **Controller validation** | ✅ Every CRUD + Auth + Profile controller validates its fields (with messages) |
| **Inertia pages** | ✅ All 35 `Inertia::render()` targets have a matching `.vue` page |
| **Route controllers** | ✅ Every controller referenced in routes exists |
| **Broken references** | ✅ None (no leftover `invoiceLocked`, `tax_percent` form refs, or dead routes) |
| **Migration** | ✅ Booking `rate_type` + datetime migration present |

**Fixed this pass:** Food Order form was missing inline errors on `father_name`, `booking_id`, `customer_id` — added.

> Note: PHP runtime (DB, controllers executing, page loads) can't be exercised here — run the manual checklist below on XAMPP.

---

## 2. Before testing — run these

```
php artisan migrate
php artisan view:clear
php artisan config:clear
npm run build
```
Then hard-refresh the browser (Ctrl+F5).

---

## 3. Validation tests (every form)

For **each** form (Room, Customer, Booking, Food Order, Invoice, Event, User, Settings, Food Category, Food Item):
- [ ] Submit empty → required fields show red error underneath
- [ ] Enter an over-long value in a text field → "must not be greater than…" error
- [ ] Money fields (charges/discount/paid): negative or huge value → error, field turns red

**Invoice & Food Order specific:**
- [ ] **Amount Paid** more than total → error, submit disabled
- [ ] **Discount** more than subtotal → error, submit disabled
- [ ] **Room Charges** empty / negative / over ₨9,999,999 → error
- [ ] **Extra Charges** negative / over ₨9,999,999 → error

---

## 4. Module tests

**Bookings**
- [ ] Create booking — Stay Type **Night / Day / Hourly**, editable **Rate**, **date+time** check-in/out; live total = rate × duration
- [ ] Date-time inputs open the picker on click
- [ ] Select a customer → guest name, **father name**, phone, CNIC, email auto-fill
- [ ] Double-book same room/dates → blocked
- [ ] Detail page shows Stay Type + "X Nights/Days/Hours" + rate

**Invoices ↔ Bookings (locking & sync)**
- [ ] Create invoice against a booking, pay **partial** → booking payment status = **Partial**
- [ ] Pay it **fully** → booking status = **Paid**, and booking row shows **only View** (Edit/Delete hidden, 🔒)
- [ ] Try `/bookings/{id}/edit` of a paid booking via URL → redirected with error
- [ ] Paid invoice → invoice shows **View + Delete only** (no Edit); `/billing/{id}/edit` via URL → redirected
- [ ] Delete the invoice → booking reverts to **Pending**

**Food Orders**
- [ ] Select a booking → father name fills (from booking or its customer)
- [ ] Add items, discount, amount paid; print receipt looks like a proper 80mm thermal slip
- [ ] No tax field anywhere (removed)

**Food Items / Categories**
- [ ] Both pages have Show-entries / Search / sortable columns / Previous-Next pagination
- [ ] Add/Edit/Delete works; icon & description show errors when invalid

**Rooms / Customers / Events / Users / Settings**
- [ ] Create / Edit / Delete each; images upload; lists paginate, search, sort
- [ ] Dark mode: every form & table readable

**Roles & access**
- [ ] Log in as receptionist → typing `/settings` or `/users` bounces to dashboard with error
- [ ] Deactivate a user → that user can't log in; if logged in, next click logs them out

**Landing (public)**
- [ ] Home, Rooms, About, Events, Event detail, Contact load; WhatsApp button + footer say "Bellamonte Resort"

---

## 5. Verdict

The project **builds clean and is structurally consistent** — validations are complete across all forms, all pages/routes/controllers resolve, and the recent features (stay-type pricing, tax removal, invoice↔booking locking & payment sync, amount validation, pagination) are wired correctly. Run section 3–4 on XAMPP to confirm runtime behaviour, then deploy.
