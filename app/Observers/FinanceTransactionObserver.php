<?php

namespace App\Observers;

use App\Models\FinanceTransaction;
use App\Models\FinanceAccount;

class FinanceTransactionObserver
{
    /**
     * Handle the FinanceTransaction "created" event.
     */
    public function created(FinanceTransaction $transaction): void
    {
        $this->updateAccountBalance($transaction, 'add');
    }

    /**
     * Handle the FinanceTransaction "updated" event.
     */
    public function updated(FinanceTransaction $transaction): void
    {
        // Rollback old balance
        if ($transaction->wasChanged(['amount', 'finance_account_id', 'finance_category_id'])) {
            $oldAccount = FinanceAccount::find($transaction->getOriginal('finance_account_id'));
            $oldCategory = \App\Models\FinanceCategory::find($transaction->getOriginal('finance_category_id'));
            $oldAmount = $transaction->getOriginal('amount');

            if ($oldAccount && $oldCategory) {
                $this->adjustBalance($oldAccount, $oldCategory->type, $oldAmount, 'subtract');
            }
        }

        // Apply new balance
        $this->updateAccountBalance($transaction, 'add');
    }

    /**
     * Handle the FinanceTransaction "deleted" event.
     */
    public function deleted(FinanceTransaction $transaction): void
    {
        $this->updateAccountBalance($transaction, 'subtract');
    }

    /**
     * Update account balance based on transaction type.
     */
    private function updateAccountBalance(FinanceTransaction $transaction, string $operation): void
    {
        $account = $transaction->account;
        $category = $transaction->category;

        if (!$account || !$category) {
            return;
        }

        $this->adjustBalance($account, $category->type, $transaction->amount, $operation);
    }

    /**
     * Adjust balance: income adds, expense subtracts.
     */
    private function adjustBalance(FinanceAccount $account, string $categoryType, float $amount, string $operation): void
    {
        $adjustment = $categoryType === 'income' ? $amount : -$amount;
        
        if ($operation === 'subtract') {
            $adjustment = -$adjustment;
        }

        $account->increment('current_balance', $adjustment);
    }
}
