<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Booking extends Model
{
    protected $fillable = [
        'booking_number',
        'room_id',
        'customer_id',
        'guest_name',
        'father_name',     
        'guest_phone',
        'guest_cnic',
        'guest_email',
        'adults',
        'children',
        'check_in',
        'check_out',
        'nights',
        'rate_type',
        'room_price',
        'total_amount',
        'payment_status',
        'payment_method',
        'advance_paid',
        'status',
        'special_requests',
        'notes',
    ];

    protected $casts = [
        'check_in'  => 'datetime',
        'check_out' => 'datetime',
    ];

    // Singular unit label for the booking's rate type
    public function getUnitLabel(): string
    {
        return match ($this->rate_type) {
            'Day'    => 'Day(s)',
            'Hourly' => 'Hour(s)',
            default  => 'Night(s)',
        };
    }

    // ── Auto-generate uuid ───────────────────────────
    protected static function booted(): void
    {
        static::creating(function (Booking $booking) {
            if (empty($booking->uuid)) {
                $booking->uuid = (string) Str::uuid();
            }
        });
    }

    // public URLs uuid se → /bookings/9b1deb4d-...
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    // ── Relations ────────────────────────────────────
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function bill()
    {
        return $this->hasOne(Bill::class);
    }

    // ── Helpers ──────────────────────────────────────
    public static function generateBookingNumber()
    {
        $last = self::orderBy('id', 'desc')->first();
        $nextId = $last ? $last->id + 1 : 1;

        return 'BK-' . date('Y') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
    }

    public function getPaymentBadgeClass()
    {
        return match ($this->payment_status) {
            'Paid'     => 'bg-light-success',
            'Pending'  => 'bg-light-warning',
            'Partial'  => 'bg-light-info',
            'Refunded' => 'bg-light-danger',
            default    => 'bg-light-secondary',
        };
    }

    public function getStatusBadgeClass()
    {
        return match ($this->status) {
            'Confirmed'   => 'bg-light-primary',
            'Checked In'  => 'bg-light-success',
            'Checked Out' => 'bg-light-secondary',
            'Cancelled'   => 'bg-light-danger',
            'No Show'     => 'bg-light-warning',
            default       => 'bg-light-dark',
        };
    }

    public function getRemainingBalance()
    {
        return $this->total_amount - $this->advance_paid;
    }

    // Booking is locked (view-only) once its invoice is fully paid
    public function isInvoicePaid(): bool
    {
        return optional($this->bill)->status === 'Paid';
    }
}
