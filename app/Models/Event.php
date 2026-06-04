<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'tag',
        'image',
        'event_date',
        'short_description',
        'description',
        'detail_image_1',
        'detail_image_2',
        'detail_image_3',
        'section_1_title',
        'section_1_text',
        'section_2_title',
        'section_2_text',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'event_date' => 'date',
        'is_active'  => 'boolean',
    ];

    // ── Scopes ──────────────────────────────────────
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // ── Image URL helpers ────────────────────────────
    public function getImageUrlAttribute(): string
    {
        return $this->image
            ? asset('uploads/events/' . $this->image)
            : asset('landing-assets/img/blog/blog-1.jpg');
    }

    public function getDetailImage1UrlAttribute(): string
    {
        return $this->detail_image_1
            ? asset('uploads/events/' . $this->detail_image_1)
            : asset('landing-assets/img/blog/blog-details/blog-details-1.jpg');
    }

    public function getDetailImage2UrlAttribute(): string
    {
        return $this->detail_image_2
            ? asset('uploads/events/' . $this->detail_image_2)
            : asset('landing-assets/img/blog/blog-details/blog-details-2.jpg');
    }

    public function getDetailImage3UrlAttribute(): string
    {
        return $this->detail_image_3
            ? asset('uploads/events/' . $this->detail_image_3)
            : asset('landing-assets/img/blog/blog-details/blog-details-3.jpg');
    }
}
