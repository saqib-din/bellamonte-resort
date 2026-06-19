<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Cache;

/**
 * Forget the cached dashboard stats whenever a record changes, so the
 * dashboard always reflects fresh data. It simply re-caches on next load,
 * so reads stay fast the rest of the time.
 */
trait ClearsDashboardCache
{
    protected static function bootClearsDashboardCache(): void
    {
        static::saved(fn () => Cache::forget('dashboard_stats'));
        static::deleted(fn () => Cache::forget('dashboard_stats'));
    }
}
