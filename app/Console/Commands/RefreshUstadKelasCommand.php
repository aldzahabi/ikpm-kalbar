<?php

namespace App\Console\Commands;

use App\Models\Santri;
use Illuminate\Console\Command;

/**
 * Sinkronkan kolom kelas untuk status ustad (tahun ke) setiap pergantian tahun kalender.
 */
class RefreshUstadKelasCommand extends Command
{
    protected $signature = 'santris:refresh-ustad-kelas';

    protected $description = 'Perbarui kelas (tahun ustad) untuk semua santri berstatus ustad';

    public function handle(): int
    {
        $updated = 0;

        Santri::query()
            ->where('status', Santri::STATUS_USTAD)
            ->whereNotNull('ustad_mulai_tahun')
            ->orderBy('stambuk')
            ->chunk(100, function ($rows) use (&$updated) {
                foreach ($rows as $santri) {
                    Santri::applyUstadKelasRules($santri);
                    if ($santri->isDirty(['kelas'])) {
                        $santri->saveQuietly();
                        $updated++;
                    }
                }
            });

        $this->info("Diperbarui: {$updated} data ustad.");

        return self::SUCCESS;
    }
}
