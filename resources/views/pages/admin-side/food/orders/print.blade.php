<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Order {{ $foodOrder->order_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            padding: 10px;
            max-width: 300px;
            margin: auto;
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }

        .row {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="center bold" style="font-size:16px">🏨 White Castle Resort</div>
    <div class="center">Food Order Receipt</div>
    <div class="divider"></div>
    <div class="row"><span>Order #</span><span class="bold">{{ $foodOrder->order_number }}</span></div>
    <div class="row"><span>Date</span><span>{{ $foodOrder->created_at->format('d/m/Y h:i A') }}</span></div>
    <div class="row"><span>Guest</span><span>{{ $foodOrder->guest_name }}</span></div>
    @if ($foodOrder->father_name)
        <div class="row"><span>Father</span><span>{{ $foodOrder->father_name }}</span></div>
    @endif
    @if ($foodOrder->room_number)
        <div class="row"><span>Room</span><span>{{ $foodOrder->room_number }}</span></div>
    @endif
    <div class="row"><span>Type</span><span>{{ $foodOrder->order_type }}</span></div>
    <div class="divider"></div>
    <div class="bold" style="margin-bottom:5px">ITEMS:</div>
    @foreach ($foodOrder->items as $item)
        <div class="row">
            <span>{{ $item->item_name }} {{ $item->quantity }}</span>
            <span>₨{{ number_format($item->subtotal) }}</span>
        </div>
    @endforeach
    <div class="divider"></div>
    <div class="row"><span>Subtotal</span><span>₨{{ number_format($foodOrder->subtotal) }}</span></div>
    @if ($foodOrder->discount > 0)
        <div class="row"><span>Discount</span><span>-₨{{ number_format($foodOrder->discount) }}</span></div>
    @endif
    @if ($foodOrder->tax_amount > 0)
        <div class="row"><span>Tax
                ({{ $foodOrder->tax_percent }}%)</span><span>₨{{ number_format($foodOrder->tax_amount) }}</span>
        </div>
    @endif
    <div class="divider"></div>
    <div class="total-row"><span>TOTAL</span><span>₨{{ number_format($foodOrder->total_amount) }}</span></div>
    <div class="row"><span>Paid</span><span>₨{{ number_format($foodOrder->amount_paid) }}</span></div>
    <div class="row bold"><span>Balance</span><span>₨{{ number_format($foodOrder->balance_due) }}</span></div>
    @if ($foodOrder->notes)
        <div class="divider"></div>
        <div>Notes: {{ $foodOrder->notes }}</div>
    @endif
    <div class="divider"></div>
    <div class="center">{{ $foodOrder->payment_method }}</div>
    <div class="center" style="margin-top:8px">Thank you!</div>
    <script>
        window.onload = () => window.print();
    </script>
</body>

</html>
