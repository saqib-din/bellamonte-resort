<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    // ── Auto-generate uuid + slug ────────────────────
    protected static function booted(): void
    {
        static::creating(function (Event $event) {
            if (empty($event->uuid)) {
                $event->uuid = (string) Str::uuid();
            }

            if (empty($event->slug)) {
                $event->slug = static::generateUniqueSlug($event->title);
            }
        });
    }

    protected static function generateUniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'event';
        $slug = $base;
        $i = 1;
        while (static::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }

    // public URLs slug se chalein → /events/summer-festival-2026
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

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
