<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'booking_number',
        'room_id',
        'customer_id',
        'guest_name',
        'guest_phone',
        'guest_cnic',
        'guest_email',
        'adults',
        'children',
        'check_in',
        'check_out',
        'nights',
        'room_price',
        'total_amount',
        'payment_status',
        'payment_method',
        'advance_paid',
        'status',
        'special_requests',
        'notes',
    ];
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

    public static function generateBookingNumber()
    {
        $last = self::orderBy('id', 'desc')->first();

        $nextId = $last ? $last->id + 1 : 1;

        return 'BK-' . date('Y') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
    }

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
    ];

    public function getPaymentBadgeClass()
    {
        return match ($this->payment_status) {
            'Paid'      => 'bg-light-success',
            'Pending'   => 'bg-light-warning',
            'Partial'   => 'bg-light-info',
            'Refunded'  => 'bg-light-danger',
            default     => 'bg-light-secondary',
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
        return $this->total_amount - $this->paid_amount;
    }
}
