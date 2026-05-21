<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Room extends Model
{
    protected $fillable = [

        'room_number',
        'type',
        'floor',
        'price_per_night',
        'capacity',
        'size',
        'bed_type',
        'services',
        'description',
        'check_in_time',
        'check_out_time',
        'image',
        'status',
    ];

    /*
    | Relationships
    */

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /*
    | Helpers
    */

    public function getStatusBadgeClass()
    {
        return match ($this->status) {

            'Available'   => 'bg-light-success',

            'Occupied'    => 'bg-light-danger',

            'Maintenance' => 'bg-light-warning',

            default       => 'bg-light-secondary',
        };
    }

    public function getServicesArray()
    {
        return $this->services
            ? array_map('trim', explode(',', $this->services))
            : [];
    }

    public function getFormattedPriceAttribute()
    {
        return '₨' . number_format($this->price_per_night);
    }

    public function isAvailable()
    {
        return $this->status === 'Available';
    }
}