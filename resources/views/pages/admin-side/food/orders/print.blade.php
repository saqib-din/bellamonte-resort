@php
    $hotel = \App\Models\Setting::get('hotel_name') ?: 'Bellamonte Resort';
    $phone = \App\Models\Setting::get('hotel_phone') ?: '0329 6777222';
    $addr  = \App\Models\Setting::get('hotel_address') ?: 'Shogran, Kaghan Valley, Pakistan';
    $isPaid = ($foodOrder->payment_status === 'Paid') || ($foodOrder->balance_due <= 0);
    $pct = rtrim(rtrim(number_format($foodOrder->tax_percent, 2), '0'), '.');
    $change = max($foodOrder->amount_paid - $foodOrder->total_amount, 0);
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt {{ $foodOrder->order_number }}</title>
    <style>
        /* ===== Thermal 80mm receipt ===== */
        @page {
            size: 80mm auto;
            margin: 0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            background: #fff;
            color: #000;
        }

        body {
            font-family: 'Courier New', 'Consolas', monospace;
            width: 80mm;
            margin: 0 auto;
            padding: 6mm 5mm 7mm;
            font-size: 12px;
            line-height: 1.45;
            -webkit-font-smoothing: none;
        }

        .c { text-align: center; }
        .b { font-weight: bold; }
        .r { text-align: right; white-space: nowrap; }
        .upper { text-transform: uppercase; letter-spacing: .5px; }

        .name {
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .sub { font-size: 11px; }

        .hr {
            border-top: 1px dashed #000;
            margin: 7px 0;
        }

        .hr.solid { border-top: 1px solid #000; }

        .hr.dbl {
            border-top: 3px double #000;
            margin: 6px 0;
        }

        .row {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin: 2px 0;
        }

        .meta .row { margin: 1px 0; font-size: 11.5px; }

        .item-name { font-weight: bold; margin-top: 5px; }

        .item-line {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            font-size: 11.5px;
        }

        .grand { font-size: 16px; font-weight: bold; }

        .stamp {
            display: inline-block;
            border: 2px solid #000;
            border-radius: 4px;
            padding: 2px 16px;
            font-weight: bold;
            letter-spacing: 3px;
            font-size: 15px;
            margin-top: 8px;
        }

        .foot { font-size: 10.5px; margin-top: 6px; }

        .order-no {
            font-size: 12px;
            letter-spacing: 2px;
            margin-top: 6px;
        }

        /* ----- screen preview only ----- */
        @media screen {
            body {
                margin: 22px auto;
                box-shadow: 0 2px 16px rgba(0, 0, 0, .18);
                border-radius: 2px;
            }
        }

        .no-print { margin-top: 16px; }

        .btn {
            font-family: inherit;
            background: #198754;
            color: #fff;
            border: none;
            padding: 9px 20px;
            border-radius: 6px;
            font-size: 13px;
            cursor: pointer;
        }

        .btn.secondary { background: #6c757d; }

        @media print {
            .no-print { display: none !important; }
            body { padding: 2mm 3mm 4mm; box-shadow: none; }
        }
    </style>
</head>

<body>
    {{-- ===== Header ===== --}}
    <div class="c name">{{ $hotel }}</div>
    <div class="c sub">{{ $addr }}</div>
    <div class="c sub">Tel: {{ $phone }}</div>
    <div class="c sub b upper" style="margin-top:5px;">Food Order Receipt</div>

    <div class="hr"></div>

    {{-- ===== Order meta ===== --}}
    <div class="meta">
        <div class="row"><span class="b">Order #</span><span class="r b">{{ $foodOrder->order_number }}</span></div>
        <div class="row"><span>Date</span><span class="r">{{ $foodOrder->created_at->format('d/m/Y') }}</span></div>
        <div class="row"><span>Time</span><span class="r">{{ $foodOrder->created_at->format('h:i A') }}</span></div>
        <div class="row"><span>Type</span><span class="r">{{ $foodOrder->order_type }}</span></div>
        @if ($foodOrder->room_number)
            <div class="row"><span>Room</span><span class="r">{{ $foodOrder->room_number }}</span></div>
        @endif
        <div class="row"><span>Guest</span><span class="r">{{ $foodOrder->guest_name }}</span></div>
        @if ($foodOrder->guest_phone)
            <div class="row"><span>Phone</span><span class="r">{{ $foodOrder->guest_phone }}</span></div>
        @endif
    </div>

    <div class="hr"></div>

    {{-- ===== Items ===== --}}
    <div class="row b" style="font-size:11px;"><span>ITEM</span><span class="r">AMOUNT</span></div>
    <div class="hr solid" style="margin:4px 0;"></div>

    @foreach ($foodOrder->items as $item)
        <div class="item-name">{{ $item->item_name }}</div>
        <div class="item-line">
            <span>{{ $item->quantity }} x Rs {{ number_format($item->unit_price) }}</span>
            <span class="r">Rs {{ number_format($item->subtotal) }}</span>
        </div>
    @endforeach

    <div class="hr"></div>

    {{-- ===== Totals ===== --}}
    <div class="row"><span>Subtotal</span><span class="r">Rs {{ number_format($foodOrder->subtotal) }}</span></div>
    @if ($foodOrder->discount > 0)
        <div class="row"><span>Discount</span><span class="r">- Rs {{ number_format($foodOrder->discount) }}</span></div>
    @endif
    @if ($foodOrder->tax_amount > 0)
        <div class="row"><span>Tax ({{ $pct }}%)</span><span class="r">Rs {{ number_format($foodOrder->tax_amount) }}</span></div>
    @endif

    <div class="hr dbl"></div>

    <div class="row grand"><span>TOTAL</span><span class="r">Rs {{ number_format($foodOrder->total_amount) }}</span></div>
    <div class="row"><span>Paid ({{ $foodOrder->payment_method }})</span><span class="r">Rs {{ number_format($foodOrder->amount_paid) }}</span></div>
    @if ($foodOrder->balance_due > 0)
        <div class="row b"><span>Balance Due</span><span class="r">Rs {{ number_format($foodOrder->balance_due) }}</span></div>
    @elseif ($change > 0)
        <div class="row"><span>Change</span><span class="r">Rs {{ number_format($change) }}</span></div>
    @endif

    <div class="c"><span class="stamp">{{ $isPaid ? 'PAID' : 'UNPAID' }}</span></div>

    @if ($foodOrder->notes)
        <div class="hr"></div>
        <div class="sub"><span class="b">Notes:</span> {{ $foodOrder->notes }}</div>
    @endif

    {{-- ===== Footer ===== --}}
    <div class="hr"></div>
    <div class="c b">Thank you for your order!</div>
    <div class="c foot">Please visit again — {{ $hotel }}</div>
    <div class="c order-no b">* {{ $foodOrder->order_number }} *</div>
    <div class="c foot">This is a computer generated receipt.</div>

    {{-- ===== Screen-only controls ===== --}}
    <div class="no-print c">
        <button class="btn" onclick="window.print()">&#128424; Print Receipt</button>
        <button class="btn secondary" onclick="window.close()">Close</button>
    </div>

    <script>
        window.onload = function () { window.print(); };
    </script>
</body>

</html>
