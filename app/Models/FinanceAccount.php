<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'current_balance',
    ];

    protected $casts = [
        'current_balance' => 'decimal:2',
    ];

    public function transactions()
    {
        return $this->hasMany(FinanceTransaction::class);
    }
}
