<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutSetting extends Model
{
    protected $fillable = ['data'];

    protected $casts = ['data' => 'array'];

    // ── Single row get or create ─────────────────────
    public static function getData(): array
    {
        $row = self::first();
        return $row ? ($row->data ?? []) : [];
    }

    public static function getValue(string $key, mixed $default = null): mixed
    {
        return self::getData()[$key] ?? $default;
    }

    // ── Image URL helpers ────────────────────────────
    public static function imageUrl(?string $filename, string $default): string
    {
        return $filename
            ? asset('uploads/about/' . $filename)
            : asset($default);
    }

    public static function serviceImageUrl(array $data, int $n): string
    {
        return self::imageUrl(
            $data["service_{$n}_image"] ?? null,
            "landing-assets/img/about/about-p{$n}.jpg"
        );
    }

    public static function videoBgUrl(array $data): string
    {
        return self::imageUrl(
            $data['video_bg_image'] ?? null,
            'landing-assets/img/video-bg.jpg'
        );
    }

    public static function galleryImageUrl(array $data, int $n): string
    {
        return self::imageUrl(
            $data["gallery_{$n}_image"] ?? null,
            "landing-assets/img/gallery/gallery-{$n}.jpg"
        );
    }
}
