<?php

namespace App\Observers;

use App\Helpers\ActivityLogHelper;
use App\Models\Santri;
use App\Services\DashboardStatsCache;

class SantriObserver
{
    public function created(Santri $santri): void
    {
        DashboardStatsCache::forget();
    }

    public function updated(Santri $santri): void
    {
        $changes = $santri->getChanges();
        unset($changes['updated_at']);

        if ($changes !== []) {
            ActivityLogHelper::log(
                'santri_updated',
                'Update santri '.$santri->stambuk.': '.json_encode($changes, JSON_UNESCAPED_UNICODE),
                Santri::class,
                (string) $santri->getKey()
            );
        }

        DashboardStatsCache::forget();
    }

    public function deleted(Santri $santri): void
    {
        ActivityLogHelper::log(
            'santri_deleted',
            'Menghapus santri '.$santri->stambuk.' — '.$santri->nama,
            Santri::class,
            (string) $santri->getKey()
        );

        DashboardStatsCache::forget();
    }
}
