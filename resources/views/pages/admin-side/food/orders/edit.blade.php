@extends('layouts.admin')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tom-select/2.3.1/css/tom-select.bootstrap5.min.css">
    <style>
        .ts-wrapper .ts-control {
            border-radius: 6px;
            min-height: 38px;
            padding: 4px 8px;
        }

        .ts-wrapper.focus .ts-control {
            border-color: #4680ff;
            box-shadow: 0 0 0 0.2rem rgba(70, 128, 255, .25);
        }
    </style>
@endpush

@section('content')
    <div class="pc-container">
        <div class="pc-content">

            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('food.orders.index') }}">Food Orders</a></li>
                                <li class="breadcrumb-item" aria-current="page">Edit Order</li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">✏️ Edit Order — {{ $foodOrder->order_number }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('components.alerts')

            <form action="{{ route('food.orders.update', $foodOrder) }}" method="POST" id="foodOrderForm">
                @csrf
                @method('PUT')

                <div class="row">

                    {{-- ═══════════════ LEFT COL ════════════════════ --}}
                    <div class="col-lg-8">

                        {{-- Auto-fill from booking --}}
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="ti ti-calendar me-2"></i>Auto-Fill from Booking (Optional)</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Booking Select</label>
                                        <select name="booking_id" id="bookingSelect" class="form-select">
                                            <option value="">-- Select Booking --</option>
                                            @foreach ($bookings as $booking)
                                                <option value="{{ $booking->id }}" data-guest="{{ $booking->guest_name }}"
                                                    data-father="{{ $booking->father_name }}"
                                                    data-phone="{{ $booking->guest_phone }}"
                                                    data-room="{{ $booking->room->room_number ?? '' }}"
                                                    data-customer="{{ $booking->customer_id }}"
                                                    {{ $foodOrder->booking_id == $booking->id ? 'selected' : '' }}>
                                                    {{ $booking->booking_number }} — {{ $booking->guest_name }} |
                                                    Room {{ $booking->room->room_number ?? '?' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Search & select a booking to auto-fill guest
                                            details.</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Customer (Optional)</label>
                                        <select name="customer_id" id="customerSelect" class="form-select">
                                            <option value="">-- Customer --</option>
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}"
                                                    {{ $foodOrder->customer_id == $customer->id ? 'selected' : '' }}>
                                                    {{ $customer->name }} — {{ $customer->phone }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Guest Info --}}
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="ti ti-user me-2"></i>Guest Info</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Guest Name <span class="text-danger">*</span></label>
                                        <input type="text" name="guest_name" id="guestName"
                                            class="form-control @error('guest_name') is-invalid @enderror"
                                            value="{{ old('guest_name', $foodOrder->guest_name) }}"
                                            placeholder="Ahmed Ali">
                                        @error('guest_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Father Name</label>
                                        <input type="text" name="father_name" id="fatherName" class="form-control"
                                            value="{{ old('father_name', $foodOrder->father_name) }}"
                                            placeholder="Father name">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Phone</label>
                                        <input type="text" name="guest_phone" id="guestPhone" class="form-control"
                                            value="{{ old('guest_phone', $foodOrder->guest_phone) }}"
                                            placeholder="0300-1234567">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Room Number</label>
                                        <input type="text" name="room_number" id="roomNumber" class="form-control"
                                            value="{{ old('room_number', $foodOrder->room_number) }}" placeholder="101">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Order Type <span class="text-danger">*</span></label>
                                        <select name="order_type" class="form-select">
                                            @foreach (['Room Service', 'Dine In', 'Takeaway'] as $t)
                                                <option value="{{ $t }}"
                                                    {{ old('order_type', $foodOrder->order_type) === $t ? 'selected' : '' }}>
                                                    {{ $t }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-select">
                                            @foreach (['Pending', 'Preparing', 'Served', 'Paid', 'Cancelled'] as $s)
                                                <option value="{{ $s }}"
                                                    {{ old('status', $foodOrder->status) === $s ? 'selected' : '' }}>
                                                    {{ $s }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- MENU / ORDER ITEMS --}}
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="ti ti-tools-kitchen-2 me-2"></i>Menu & Order Items</h5>
                            </div>
                            <div class="card-body">

                                {{-- Category Tabs --}}
                                <ul class="nav nav-tabs mb-3" id="categoryTabs" role="tablist">
                                    @foreach ($categories as $index => $cat)
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link {{ $index === 0 ? 'active' : '' }}"
                                                id="tab-{{ $cat->id }}" data-bs-toggle="tab"
                                                data-bs-target="#cat-{{ $cat->id }}" type="button" role="tab">
                                                {{ $cat->icon }} {{ $cat->name }}
                                            </button>
                                        </li>
                                    @endforeach
                                </ul>

                                {{-- Category Tab Content --}}
                                <div class="tab-content mb-4" id="categoryTabContent">
                                    @foreach ($categories as $index => $cat)
                                        <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}"
                                            id="cat-{{ $cat->id }}" role="tabpanel">
                                            <div class="row g-2">
                                                @forelse($cat->availableItems as $item)
                                                    <div class="col-md-4">
                                                        <div class="card border h-100 menu-item-card"
                                                            style="cursor:pointer;transition:all .2s"
                                                            onclick="addToCart({{ $item->id }}, '{{ addslashes($item->name) }}', {{ $item->price }})">
                                                            <div class="card-body p-3 text-center">
                                                                <div class="f-24 mb-1">🍴</div>
                                                                <h6 class="mb-1 f-14">{{ $item->name }}</h6>
                                                                @if ($item->description)
                                                                    <small
                                                                        class="text-muted d-block mb-1">{{ Str::limit($item->description, 40) }}</small>
                                                                @endif
                                                                <span class="badge bg-light-success text-success fw-bold">
                                                                    ₨{{ number_format($item->price) }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <div class="col-12">
                                                        <p class="text-muted text-center py-3">No items in this category.
                                                        </p>
                                                    </div>
                                                @endforelse
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Cart --}}
                                <div class="border rounded p-3" style="background:var(--bs-gray-100,#f8f9fa)">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="mb-0"><i class="ti ti-shopping-cart me-2"></i>Order Cart</h6>
                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                            onclick="clearCart()">
                                            <i class="ti ti-x me-1"></i>Clear
                                        </button>
                                    </div>
                                    <div id="cartItems">
                                        <p class="text-muted text-center py-3">
                                            <i class="ti ti-shopping-cart-off f-24 d-block mb-1"></i>
                                            Loading existing items...
                                        </p>
                                    </div>
                                    <div id="cartInputs"></div>
                                </div>

                            </div>
                        </div>

                        {{-- Charges --}}
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="ti ti-calculator me-2"></i>Charges & Payment</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Discount (₨)</label>
                                        <div class="input-group">
                                            <span class="input-group-text">₨</span>
                                            <input type="number" name="discount" id="discount" class="form-control"
                                                value="{{ old('discount', $foodOrder->discount) }}" min="0"
                                                oninput="calcTotal()">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Tax (%)</label>
                                        <div class="input-group">
                                            <input type="number" name="tax_percent" id="taxPercent"
                                                class="form-control"
                                                value="{{ old('tax_percent', $foodOrder->tax_percent) }}" min="0"
                                                max="100" oninput="calcTotal()">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Amount Paid (₨)</label>
                                        <div class="input-group">
                                            <span class="input-group-text">₨</span>
                                            <input type="number" name="amount_paid" id="amountPaid"
                                                class="form-control"
                                                value="{{ old('amount_paid', $foodOrder->amount_paid) }}" min="0"
                                                oninput="calcTotal()">
                                        </div>
                                    </div>
                                </div>

                                {{-- Live Total --}}
                                <div class="mt-4 p-3 rounded"
                                    style="background:var(--bs-gray-100,#f8f9fa);border:1px solid #e0e0e0;">
                                    <div class="row text-center">
                                        <div class="col-3">
                                            <div class="text-muted f-12">Subtotal</div>
                                            <div class="fw-500" id="calcSubtotal">₨0</div>
                                        </div>
                                        <div class="col-2">
                                            <div class="text-muted f-12">Discount</div>
                                            <div class="fw-500 text-success" id="calcDiscount">-₨0</div>
                                        </div>
                                        <div class="col-2">
                                            <div class="text-muted f-12">Tax</div>
                                            <div class="fw-500 text-warning" id="calcTax">₨0</div>
                                        </div>
                                        <div class="col-3">
                                            <div class="text-muted f-12">Total</div>
                                            <div class="fw-bold text-primary fs-5" id="calcTotal">₨0</div>
                                        </div>
                                        <div class="col-2">
                                            <div class="text-muted f-12">Balance</div>
                                            <div class="fw-bold text-danger" id="calcBalance">₨0</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Notes --}}
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="ti ti-notes me-2"></i>Notes</h5>
                            </div>
                            <div class="card-body">
                                <textarea name="notes" class="form-control" rows="3" placeholder="Special instructions, allergies, etc.">{{ old('notes', $foodOrder->notes) }}</textarea>
                            </div>
                        </div>

                    </div>

                    {{-- ═══════════════ RIGHT COL ═══════════════════ --}}
                    <div class="col-lg-4">

                        {{-- Payment --}}
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="ti ti-credit-card me-2"></i>Payment</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                                    <select name="payment_method" class="form-select">
                                        @foreach (['Cash', 'Card', 'Bank Transfer', 'JazzCash', 'EasyPaisa'] as $m)
                                            <option value="{{ $m }}"
                                                {{ old('payment_method', $foodOrder->payment_method) == $m ? 'selected' : '' }}>
                                                {{ $m }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Order Preview --}}
                        <div class="card mb-4 border-primary">
                            <div class="card-header bg-light-primary">
                                <h5 class="mb-0 text-primary"><i class="ti ti-receipt me-2"></i>Order Preview</h5>
                            </div>
                            <div class="card-body">
                                <div id="previewItems">
                                    <p class="text-muted f-13 text-center">No items yet</p>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted f-13">Subtotal</span>
                                    <span id="prev-sub">₨0</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted f-13">Discount</span>
                                    <span id="prev-dis" class="text-success">-₨0</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted f-13">Tax</span>
                                    <span id="prev-tax">₨0</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="fw-500">Total</span>
                                    <span class="fw-bold text-primary" id="prev-total">₨0</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted f-13">Amount Paid</span>
                                    <span id="prev-paid" class="text-success">₨0</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="fw-500">Balance Due</span>
                                    <span class="fw-bold text-danger" id="prev-bal">₨0</span>
                                </div>
                            </div>
                        </div>

                        {{-- Buttons --}}
                        <div class="card">
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-device-floppy me-2"></i>Update Order
                                    </button>
                                    <a href="{{ route('food.orders.show', $foodOrder) }}"
                                        class="btn btn-outline-secondary">
                                        <i class="ti ti-arrow-left me-2"></i>Cancel
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tom-select/2.3.1/js/tom-select.complete.min.js"></script>
    <script>
        /* ─── Existing items from DB (PHP → JS) ─────────── */
        const existingItems = @json($existingItems);

        let cart = {};
        let bookingReady = false; 

        /* ─── Page load ──────────────────────────────────── */
        document.addEventListener('DOMContentLoaded', () => {

            existingItems.forEach(item => {
                cart[item.id] = {
                    name: item.name,
                    price: item.price,
                    qty: item.qty
                };
            });
            renderCart();
            calcTotal();

            // Booking Select with search
            new TomSelect('#bookingSelect', {
                placeholder: 'Search booking...',
                allowEmptyOption: true,
                onChange: function() {
                    if (bookingReady) fillFromBooking();
                }
            });

            // Customer Select with search
            new TomSelect('#customerSelect', {
                placeholder: 'Search customer...',
                allowEmptyOption: true
            });

            bookingReady = true;
        });

        /* ─── Cart Functions ─────────────────────────────── */
        function addToCart(id, name, price) {
            if (cart[id]) {
                cart[id].qty++;
            } else {
                cart[id] = {
                    name,
                    price,
                    qty: 1
                };
            }
            renderCart();
            calcTotal();

            const cards = document.querySelectorAll('.menu-item-card');
            cards.forEach(c => {
                if (c.getAttribute('onclick').includes(`addToCart(${id},`)) {
                    c.style.borderColor = '#4680ff';
                    setTimeout(() => c.style.borderColor = '', 600);
                }
            });
        }

        function changeQty(id, delta) {
            if (!cart[id]) return;
            cart[id].qty += delta;
            if (cart[id].qty <= 0) delete cart[id];
            renderCart();
            calcTotal();
        }

        function clearCart() {
            cart = {};
            renderCart();
            calcTotal();
        }

        function renderCart() {
            const cartDiv = document.getElementById('cartItems');
            const inputDiv = document.getElementById('cartInputs');
            const previewDiv = document.getElementById('previewItems');
            const ids = Object.keys(cart);

            if (ids.length === 0) {
                cartDiv.innerHTML = `<p class="text-muted text-center py-3">
                <i class="ti ti-shopping-cart-off f-24 d-block mb-1"></i>
                Cart is empty</p>`;
                inputDiv.innerHTML = '';
                previewDiv.innerHTML = '<p class="text-muted f-13 text-center">No items yet</p>';
                return;
            }

            let cartHTML = '',
                prevHTML = '',
                inputHTML = '',
                idx = 0;

            ids.forEach(id => {
                const item = cart[id];
                const lineTotal = item.price * item.qty;

                cartHTML += `
                <div class="d-flex align-items-center justify-content-between mb-2 py-2 border-bottom">
                    <div>
                        <span class="fw-500">${item.name}</span>
                        <small class="text-muted d-block">₨${item.price.toLocaleString()} each</small>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary px-2 py-1"
                            onclick="changeQty(${id}, -1)"><i class="ti ti-minus"></i></button>
                        <span class="fw-bold">${item.qty}</span>
                        <button type="button" class="btn btn-sm btn-outline-primary px-2 py-1"
                            onclick="changeQty(${id}, 1)"><i class="ti ti-plus"></i></button>
                        <span class="text-primary fw-500 ms-2" style="min-width:70px;text-align:right">
                            ₨${lineTotal.toLocaleString()}
                        </span>
                    </div>
                </div>`;

                prevHTML += `
                <div class="d-flex justify-content-between mb-1">
                    <span class="f-13 text-muted">${item.name} × ${item.qty}</span>
                    <span class="f-13">₨${lineTotal.toLocaleString()}</span>
                </div>`;

                inputHTML += `
                <input type="hidden" name="items[${idx}][food_item_id]" value="${id}">
                <input type="hidden" name="items[${idx}][quantity]"     value="${item.qty}">`;
                idx++;
            });

            cartDiv.innerHTML = cartHTML;
            inputDiv.innerHTML = inputHTML;
            previewDiv.innerHTML = prevHTML;
        }

        /* ─── Calculator ─────────────────────────────────── */
        function getCartSubtotal() {
            return Object.values(cart).reduce((sum, i) => sum + i.price * i.qty, 0);
        }

        function calcTotal() {
            const sub = getCartSubtotal();
            const dis = parseFloat(document.getElementById('discount').value) || 0;
            const taxPct = parseFloat(document.getElementById('taxPercent').value) || 0;
            const paid = parseFloat(document.getElementById('amountPaid').value) || 0;
            const afterDis = sub - dis;
            const tax = Math.round(afterDis * (taxPct / 100) * 100) / 100;
            const total = Math.round((afterDis + tax) * 100) / 100;
            const balance = Math.max(0, total - paid);
            const fmt = n => '₨' + n.toLocaleString();

            document.getElementById('calcSubtotal').textContent = fmt(sub);
            document.getElementById('calcDiscount').textContent = '-' + fmt(dis);
            document.getElementById('calcTax').textContent = fmt(tax);
            document.getElementById('calcTotal').textContent = fmt(total);
            document.getElementById('calcBalance').textContent = fmt(balance);
            document.getElementById('prev-sub').textContent = fmt(sub);
            document.getElementById('prev-dis').textContent = '-' + fmt(dis);
            document.getElementById('prev-tax').textContent = fmt(tax);
            document.getElementById('prev-total').textContent = fmt(total);
            document.getElementById('prev-paid').textContent = fmt(paid);
            document.getElementById('prev-bal').textContent = fmt(balance);
        }

        /* ─── Booking autofill ───────────────────────────── */
        function fillFromBooking() {
            const sel = document.getElementById('bookingSelect');
            const opt = sel.options[sel.selectedIndex];
            if (!opt || !opt.value) return;

            document.getElementById('guestName').value = opt.dataset.guest || '';
            document.getElementById('fatherName').value = opt.dataset.father || '';
            document.getElementById('guestPhone').value = opt.dataset.phone || '';
            document.getElementById('roomNumber').value = opt.dataset.room || '';

            // Customer sync (TomSelect ke through)
            const custTS = document.getElementById('customerSelect').tomselect;
            if (custTS && opt.dataset.customer) {
                custTS.setValue(opt.dataset.customer);
            }
        }
    </script>
@endpush
