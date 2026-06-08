<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FoodOrder extends Model
{
    protected $fillable = [
        'order_number',
        'booking_id',
        'customer_id',
        'guest_name',
        'father_name',
        'guest_phone',
        'room_number',
        'order_type',
        'status',
        'payment_method',
        'subtotal',
        'discount',
        'tax_percent',
        'tax_amount',
        'total_amount',
        'amount_paid',
        'balance_due',
        'notes'
    ];

    // ── Auto-generate UUID on create ───────────────────
    protected static function booted(): void
    {
        static::creating(function (FoodOrder $order) {
            if (empty($order->uuid)) {
                $order->uuid = (string) Str::uuid();
            }
        });
    }

    // UUID se route binding (id ki jagah)
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    // ── Relationships ──────────────────────────────────
    public function items()
    {
        return $this->hasMany(FoodOrderItem::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // ── Accessors ──────────────────────────────────────
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'Pending'   => 'bg-light-warning text-warning',
            'Preparing' => 'bg-light-info text-info',
            'Served'    => 'bg-light-primary text-primary',
            'Paid'      => 'bg-light-success text-success',
            'Cancelled' => 'bg-light-danger text-danger',
            default     => 'bg-light-secondary',
        };
    }

    public function getOrderTypeBadgeClassAttribute(): string
    {
        return match ($this->order_type) {
            'Room Service' => 'bg-light-primary text-primary',
            'Dine In'      => 'bg-light-success text-success',
            'Takeaway'     => 'bg-light-warning text-warning',
            default        => 'bg-secondary',
        };
    }

    // ── Order number generator ─────────────────────────
    public static function generateOrderNumber(): string
    {
        $last = self::latest()->first();
        $next = $last ? ((int) substr($last->order_number, 3)) + 1 : 1;
        return 'ORD' . str_pad($next, 5, '0', STR_PAD_LEFT);
    }
}
