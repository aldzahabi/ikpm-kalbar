<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
| Backup DB (Spatie) — set MAIL_* / notifikasi di config/backup.php.
| Aktifkan jadwal: BACKUP_SCHEDULE=true di .env + konfigurasi disk destinasi.
*/
Schedule::command('backup:run --only-db')
    ->dailyAt('02:00')
    ->when(fn () => filter_var(env('BACKUP_SCHEDULE', false), FILTER_VALIDATE_BOOLEAN));

Schedule::command('backup:clean')
    ->weekly()
    ->when(fn () => filter_var(env('BACKUP_SCHEDULE', false), FILTER_VALIDATE_BOOLEAN));

// Tahun ustad (kolom kelas) — 1 Jan setiap tahun
Schedule::command('santris:refresh-ustad-kelas')
    ->yearlyOn(1, 1, '00:30');
