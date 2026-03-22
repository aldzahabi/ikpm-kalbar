<?php

namespace App\Services;

use App\Models\FinanceAccount;
use App\Models\FinanceCategory;
use App\Models\FinanceTransaction;
use App\Models\Rombongan;
use App\Models\Santri;
use Illuminate\Support\Facades\DB;

/**
 * Logika update status pembayaran pivot + auto transaksi keuangan (dipakai web & API).
 */
class RombonganPembayaranService
{
    /**
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public static function updateStatus(Rombongan $rombongan, string $stambuk, string $statusPembayaran, int $actingUserId): void
    {
        $pivot = DB::table('rombongan_santri')
            ->where('rombongan_id', $rombongan->id)
            ->where('santri_stambuk', $stambuk)
            ->first();

        $oldStatus = $pivot ? $pivot->status_pembayaran : null;

        $rombongan->santris()->updateExistingPivot($stambuk, [
            'status_pembayaran' => $statusPembayaran,
        ]);

        if ($statusPembayaran === 'lunas' && $oldStatus !== 'lunas') {
            $account = FinanceAccount::where('name', 'Kas Perpulangan')->first();
            $category = FinanceCategory::where('name', 'Tiket Santri')
                ->where('type', 'income')
                ->first();

            if ($account && $category) {
                $santri = Santri::query()->whereKey($stambuk)->firstOrFail();

                FinanceTransaction::create([
                    'finance_account_id' => $account->id,
                    'finance_category_id' => $category->id,
                    'amount' => $rombongan->biaya_per_orang,
                    'transaction_date' => now()->toDateString(),
                    'description' => "Pembayaran tiket perpulangan - {$rombongan->nama} - Santri: {$santri->nama} ({$santri->stambuk})",
                    'reference_id' => "ROMBONGAN_{$rombongan->id}_SANTRI_{$stambuk}",
                    'user_id' => $actingUserId,
                ]);
            }
        }
    }
}
