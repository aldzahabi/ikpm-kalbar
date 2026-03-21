<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'finance_account_id',
        'finance_category_id',
        'amount',
        'transaction_date',
        'description',
        'reference_id',
        'user_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'date',
    ];

    public function account()
    {
        return $this->belongsTo(FinanceAccount::class, 'finance_account_id');
    }

    public function category()
    {
        return $this->belongsTo(FinanceCategory::class, 'finance_category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
