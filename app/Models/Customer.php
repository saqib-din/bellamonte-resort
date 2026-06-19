<?php

namespace App\Models;

use App\Models\Concerns\ClearsDashboardCache;
use Illuminate\Database\Eloquent\Model;
use App\Models\Booking;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Customer extends Model
{
    use ClearsDashboardCache;

    protected $fillable = [
        'name',
        'father_name',
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

    // ── Auto-generate UUID on create ───────────────────
    protected static function booted(): void
    {
        static::creating(function (Customer $customer) {
            if (empty($customer->uuid)) {
                $customer->uuid = (string) Str::uuid();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function getImageUrlAttribute(): string
    {
        return $this->image
            ? asset('uploads/customers/' . $this->image) 
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
