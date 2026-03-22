<?php

namespace App\Observers;

use App\Models\FinanceAccount;
use App\Models\FinanceCategory;
use App\Models\FinanceTransaction;
use App\Services\DashboardStatsCache;
use Illuminate\Support\Facades\DB;

class FinanceTransactionObserver
{
    public function created(FinanceTransaction $transaction): void
    {
        DB::transaction(function () use ($transaction) {
            $transaction->loadMissing('category');
            if (! $transaction->category) {
                return;
            }

            $delta = $transaction->category->type === 'income'
                ? (float) $transaction->amount
                : -(float) $transaction->amount;

            FinanceAccount::query()
                ->whereKey($transaction->finance_account_id)
                ->lockForUpdate()
                ->first();

            $this->applyDeltaToAccount($transaction->finance_account_id, $delta);
        });

        DashboardStatsCache::forget();
    }

    public function updated(FinanceTransaction $transaction): void
    {
        if (! $transaction->wasChanged(['amount', 'finance_account_id', 'finance_category_id'])) {
            return;
        }

        DB::transaction(function () use ($transaction) {
            $oldAccountId = (int) $transaction->getOriginal('finance_account_id');
            $newAccountId = (int) $transaction->finance_account_id;
            $oldCategoryId = (int) $transaction->getOriginal('finance_category_id');
            $oldAmount = (float) $transaction->getOriginal('amount');
            $newAmount = (float) $transaction->amount;

            $oldCat = FinanceCategory::query()->find($oldCategoryId);
            $newCat = $transaction->category ?? FinanceCategory::query()->find($newCategoryId);

            if (! $oldCat || ! $newCat) {
                return;
            }

            $oldContribution = $oldCat->type === 'income' ? $oldAmount : -$oldAmount;
            $newContribution = $newCat->type === 'income' ? $newAmount : -$newAmount;

            $ids = collect([$oldAccountId, $newAccountId])->unique()->sort()->values();

            foreach ($ids as $id) {
                FinanceAccount::query()->whereKey($id)->lockForUpdate()->first();
            }

            if ($oldAccountId === $newAccountId) {
                $this->applyDeltaToAccount($oldAccountId, $newContribution - $oldContribution);

                return;
            }

            $this->applyDeltaToAccount($oldAccountId, -$oldContribution);
            $this->applyDeltaToAccount($newAccountId, $newContribution);
        });

        DashboardStatsCache::forget();
    }

    public function deleted(FinanceTransaction $transaction): void
    {
        DB::transaction(function () use ($transaction) {
            $transaction->loadMissing('category');
            if (! $transaction->category) {
                return;
            }

            $delta = $transaction->category->type === 'income'
                ? -(float) $transaction->amount
                : (float) $transaction->amount;

            FinanceAccount::query()
                ->whereKey($transaction->finance_account_id)
                ->lockForUpdate()
                ->first();

            $this->applyDeltaToAccount($transaction->finance_account_id, $delta);
        });

        DashboardStatsCache::forget();
    }

    /**
     * Terapkan perubahan saldo (panggil hanya setelah lockForUpdate pada baris akun yang sama).
     */
    private function applyDeltaToAccount(int $accountId, float $delta): void
    {
        if ($delta === 0.0) {
            return;
        }

        $account = FinanceAccount::query()->whereKey($accountId)->first();
        if (! $account) {
            return;
        }

        $account->current_balance = (float) $account->current_balance + $delta;
        $account->save();
    }
}
