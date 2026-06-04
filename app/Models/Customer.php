<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Booking;
use Illuminate\Support\Carbon;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'cnic',
        'phone',
        'email',
        'city',
        'nationality',
        'gender',
        'dob',
        'address',
        'image',
        'status',
        'notes',
    ];
    protected $casts = [
        'dob' => 'date',
    ];
    // ADD THIS
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }


    public function getImageUrlAttribute(): string
    {
        return $this->image
            ? asset('uploads/customers/' . $this->image) // ✅ sahi folder
            : asset('landing-assets/img/room/room-1.jpg');
    }

    public function getStatusBadgeClass()
    {
        return match ($this->status) {
            'Active' => 'bg-light-success',
            'Blacklisted' => 'bg-light-danger',
            default => 'bg-light-secondary',
        };
    }

    public function getTotalStays()
    {
        return $this->bookings()
            ->where('status', 'Checked Out')
            ->count();
    }

    public function getTotalSpent()
    {
        return $this->bookings()
            ->where('payment_status', 'Paid')
            ->sum('total_amount');
    }

    public function getAge()
    {
        return $this->dob
            ? Carbon::parse($this->dob)->age
            : null;
    }
}
