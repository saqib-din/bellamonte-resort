<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Bill extends Model
{
    protected $fillable = [
        'invoice_number',
        'booking_id',
        'customer_id',

        'guest_name',
        'father_name',
        'guest_phone',
        'room_number',
        'room_type',
        'check_in',
        'check_out',
        'nights',

        // ── Vehicle Details ──
        'has_vehicle',
        'vehicle_number',
        'vehicle_type',
        'vehicle_model',
        'vehicle_color',
        'driver_name',
        'parking_charges',

        'room_charges',
        'extra_charges',
        'discount',
        'tax_percent',
        'tax_amount',
        'total_amount',

        'amount_paid',
        'balance_due',
        'payment_method',
        'status',

        'notes',
        'issue_date',
    ];

    protected $casts = [
        'issue_date'      => 'date',
        'check_in'        => 'date',
        'check_out'       => 'date',
        'has_vehicle'     => 'boolean',
        'parking_charges' => 'decimal:2',
    ];

    // ── Auto-generate uuid ───────────────────────────
    protected static function booted(): void
    {
        static::creating(function (Bill $bill) {
            if (empty($bill->uuid)) {
                $bill->uuid = (string) Str::uuid();
            }
        });
    }

    // public URLs uuid se → /bills/9b1deb4d-...
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    /*
    | Relationships
    */

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /*
    | Accessors
    */

    public function getFormattedTotalAttribute()
    {
        return '₨' . number_format($this->total_amount, 0);
    }

    public function getFormattedBalanceAttribute()
    {
        return '₨' . number_format($this->balance_due, 0);
    }

    /*
    | Helpers
    */

    public function getStatusBadgeClass()
    {
        return match ($this->status) {
            'Paid'    => 'bg-light-success',
            'Unpaid'  => 'bg-light-danger',
            'Partial' => 'bg-light-warning',
            default   => 'bg-light-secondary',
        };
    }

    public function isPaid()
    {
        return $this->status === 'Paid';
    }

    /*
    | Core Calculation
    */

    public function calculate()
    {
        $room       = $this->room_charges ?? 0;
        $extra      = $this->extra_charges ?? 0;
        $parking    = $this->parking_charges ?? 0;   // ← vehicle parking
        $discount   = $this->discount ?? 0;
        $taxPercent = $this->tax_percent ?? 0;
        $paid       = $this->amount_paid ?? 0;

        $subtotal      = $room + $extra + $parking;   // parking subtotal mein add
        $afterDiscount = $subtotal - $discount;
        $taxAmount     = ($afterDiscount * $taxPercent) / 100;
        $total         = $afterDiscount + $taxAmount;

        $this->tax_amount   = $taxAmount;
        $this->total_amount = $total;
        $this->balance_due  = $total - $paid;

        $this->status = match (true) {
            $paid <= 0     => 'Unpaid',
            $paid < $total => 'Partial',
            default        => 'Paid',
        };

        return $this;
    }
}
