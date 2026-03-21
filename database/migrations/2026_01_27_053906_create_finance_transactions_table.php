<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('finance_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('finance_account_id')->constrained('finance_accounts')->onDelete('cascade');
            $table->foreignId('finance_category_id')->constrained('finance_categories')->onDelete('restrict');
            $table->decimal('amount', 15, 2);
            $table->date('transaction_date');
            $table->text('description')->nullable();
            $table->string('reference_id')->nullable()->comment('ID relasi (misal: ID Pembayaran Santri)');
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->timestamps();
            
            $table->index('transaction_date');
            $table->index('finance_account_id');
            $table->index('finance_category_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finance_transactions');
    }
};
