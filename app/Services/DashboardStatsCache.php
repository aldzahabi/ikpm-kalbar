<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

/**
 * Cache agregat dashboard (ringan di DB). Bukan di dalam request berat Chart.js.
 */
class DashboardStatsCache
{
    public const TTL_SECONDS = 3600;

    public static function key(): string
    {
        return 'dashboard_stats_v2_'.now()->format('Y-m');
    }

    public static function forget(): void
    {
        Cache::forget(static::key());
    }
}
