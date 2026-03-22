<?php

namespace App\Jobs;

use App\Models\Rombongan;
use App\Models\Santri;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Reminder pembayaran / notifikasi WA — hubungkan ke Wablas, Twilio, Fonnte, dll.
 * Jalankan queue worker: php artisan queue:work (disarankan Redis: QUEUE_CONNECTION=redis).
 */
class SendWhatsappNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Santri $santri,
        public ?Rombongan $rombongan = null,
        public string $message = '',
    ) {}

    public function handle(): void
    {
        // TODO: integrasi provider WA
        Log::info('SendWhatsappNotification (placeholder)', [
            'stambuk' => $this->santri->stambuk,
            'rombongan_id' => $this->rombongan?->id,
            'message' => $this->message,
        ]);
    }
}
