<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $bill->invoice_number }} — BM Resort</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Georgia', serif;
            font-size: 14px;
            color: #1a1a1a;
            background: #fff;
        }

        .invoice-box {
            max-width: 750px;
            margin: 30px auto;
            padding: 40px;
            border: 1px solid #e0e0e0;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
        }

        .hotel-name {
            font-size: 26px;
            font-weight: bold;
            color: #c9a84c;
            letter-spacing: 1px;
        }

        .hotel-info {
            color: #666;
            font-size: 12px;
            margin-top: 4px;
            line-height: 1.6;
        }

        .invoice-title {
            text-align: right;
        }

        .invoice-title h2 {
            font-size: 22px;
            color: #1a1a1a;
            letter-spacing: 3px;
        }

        .invoice-number {
            font-size: 14px;
            color: #444;
            margin-top: 4px;
        }

        .invoice-date {
            font-size: 12px;
            color: #888;
        }

        /* Status Badge */
        .badge {
            display: inline-block;
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
        }

        .badge-paid {
            background: #d4edda;
            color: #155724;
        }

        .badge-unpaid {
            background: #f8d7da;
            color: #721c24;
        }

        .badge-partial {
            background: #fff3cd;
            color: #856404;
        }

        /* Divider */
        .divider {
            border: none;
            border-top: 2px solid #c9a84c;
            margin: 20px 0;
        }

        .divider-thin {
            border: none;
            border-top: 1px solid #e0e0e0;
            margin: 16px 0;
        }

        /* Guest & Room */
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .info-block {
            width: 48%;
        }

        .info-block h5 {
            font-size: 11px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }

        .info-block p {
            margin-bottom: 4px;
            color: #222;
            font-size: 13px;
        }

        .info-block strong {
            font-size: 15px;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        thead tr {
            background: #f9f5ec;
        }

        th {
            padding: 10px 14px;
            text-align: left;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #555;
            border-bottom: 2px solid #c9a84c;
        }

        th.text-right,
        td.text-right {
            text-align: right;
        }

        td {
            padding: 10px 14px;
            font-size: 13px;
            border-bottom: 1px solid #f0f0f0;
        }

        tfoot td,
        tfoot th {
            border-top: 2px solid #ddd;
        }

        .total-row td,
        .total-row th {
            font-weight: bold;
            font-size: 15px;
            background: #f9f5ec;
        }

        .paid-row td {
            color: #2e7d32;
        }

        .balance-row td {
            color: #c62828;
            font-weight: bold;
        }

        .discount-row td {
            color: #2e7d32;
        }

        /* Summary bottom */
        .summary {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 10px;
        }

        .payment-info p {
            margin-bottom: 4px;
            font-size: 13px;
            color: #444;
        }

        /* Footer */
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            color: #888;
            font-size: 12px;
            line-height: 1.8;
        }

        .footer .thankyou {
            font-size: 15px;
            color: #c9a84c;
            font-style: italic;
            margin-bottom: 4px;
        }

        /* Print button */
        .print-btn {
            display: block;
            text-align: center;
            margin: 20px auto;
        }

        .btn-print {
            background: #c9a84c;
            color: white;
            border: none;
            padding: 10px 30px;
            font-size: 14px;
            border-radius: 6px;
            cursor: pointer;
            font-family: sans-serif;
        }

        .btn-print:hover {
            background: #b8934a;
        }

        @media print {
            .print-btn {
                display: none;
            }

            body {
                margin: 0;
            }

            .invoice-box {
                margin: 0;
                border: none;
                padding: 20px;
            }
        }
    </style>
</head>

<body>

    <div class="print-btn">
        <button class="btn-print" onclick="window.print()">🖨️ Print Invoice</button>
        <button class="btn-print" style="background:#666;margin-left:10px;" onclick="window.close()">✕ Close</button>
    </div>

    <div class="invoice-box">

        <!-- Header -->
        <div class="header">
            <div>
                <div class="hotel-name">🏨 BM Resort</div>
                <div class="hotel-info">
                    Hotel Management System<br>
                    Lahore, Pakistan<br>
                    Tel: 0300-1234567
                </div>
            </div>
            <div class="invoice-title">
                <h2>INVOICE</h2>
                <div class="invoice-number">{{ $bill->invoice_number }}</div>
                <div class="invoice-date">Date: {{ $bill->issue_date->format('d M Y') }}</div>
                <div style="margin-top:6px">
                    <span class="badge badge-{{ strtolower($bill->status) }}">{{ $bill->status }}</span>
                </div>
            </div>
        </div>

        <hr class="divider">

        <!-- Guest & Room Info -->
        <div class="info-row">
            <div class="info-block">
                <h5>Bill To</h5>
                <strong>{{ $bill->guest_name }}</strong>
                @if ($bill->guest_phone)
                    <p>📞 {{ $bill->guest_phone }}</p>
                @endif
                @if ($bill->customer)
                    <p style="color:#888;font-size:12px;">CNIC: {{ $bill->customer->cnic ?? '—' }}</p>
                @endif
            </div>
            <div class="info-block" style="text-align:right;">
                <h5>Stay Details</h5>
                @if ($bill->room_number)
                    <strong>Room {{ $bill->room_number }}
                        @if ($bill->room_type)
                            — {{ $bill->room_type }}
                        @endif
                    </strong>
                @endif
                @if ($bill->check_in && $bill->check_out)
                    <p>Check In: {{ $bill->check_in->format('d M Y') }}</p>
                    <p>Check Out: {{ $bill->check_out->format('d M Y') }}</p>
                    <p>Duration: {{ $bill->nights }} night(s)</p>
                @endif
            </div>
        </div>

        <hr class="divider-thin">

        <!-- Charges Table -->
        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="text-right">Amount (₨)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        Room Charges
                        @if ($bill->nights > 1)
                            <span style="color:#888;font-size:12px;">
                                (₨{{ number_format($bill->room_charges / $bill->nights) }}
                                × {{ $bill->nights }} nights)
                            </span>
                        @endif
                    </td>
                    <td class="text-right">{{ number_format($bill->room_charges) }}</td>
                </tr>
                @if ($bill->extra_charges > 0)
                    <tr>
                        <td>Extra Services (Food, Laundry, etc.)</td>
                        <td class="text-right">{{ number_format($bill->extra_charges) }}</td>
                    </tr>
                @endif
                @if ($bill->discount > 0)
                    <tr class="discount-row">
                        <td>Discount</td>
                        <td class="text-right">- {{ number_format($bill->discount) }}</td>
                    </tr>
                @endif
                @if ($bill->tax_amount > 0)
                    <tr>
                        <td>Tax ({{ $bill->tax_percent }}%)</td>
                        <td class="text-right">{{ number_format($bill->tax_amount) }}</td>
                    </tr>
                @endif
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td>TOTAL AMOUNT</td>
                    <td class="text-right" style="color:#c9a84c;">₨ {{ number_format($bill->total_amount) }}</td>
                </tr>
                <tr class="paid-row">
                    <td>Amount Paid</td>
                    <td class="text-right">₨ {{ number_format($bill->amount_paid) }}</td>
                </tr>
                @if ($bill->balance_due > 0)
                    <tr class="balance-row">
                        <td>Balance Due</td>
                        <td class="text-right">₨ {{ number_format($bill->balance_due) }}</td>
                    </tr>
                @endif
            </tfoot>
        </table>

        <!-- Payment Info + Notes -->
        <div class="summary">
            <div class="payment-info">
                <p><strong>Payment Method:</strong> {{ $bill->payment_method }}</p>
                @if ($bill->notes)
                    <p><strong>Notes:</strong> {{ $bill->notes }}</p>
                @endif
            </div>
            <div style="text-align:right;">
                <p style="font-size:12px;color:#888;">Authorized Signature</p>
                <div style="margin-top:30px;border-top:1px solid #ccc;padding-top:4px;font-size:12px;color:#888;">BM
                    Resort</div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="thankyou">Thank you for staying with us.</div>
            <div>BM Resort — Lahore, Pakistan | info@bmresort.com | 0300-1234567</div>
            <div style="margin-top:6px;font-size:11px;color:#bbb;">
                Generated on {{ now()->format('d M Y, h:i A') }}
            </div>
        </div>

    </div>

</body>

</html>
