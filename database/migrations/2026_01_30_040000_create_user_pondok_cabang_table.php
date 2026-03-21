<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_pondok_cabang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('pondok_cabang', 10); // Nomor pondok cabang (1, 2, 3, dst)
            $table->timestamps();
            
            // Unique constraint to prevent duplicate assignments
            $table->unique(['user_id', 'pondok_cabang']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_pondok_cabang');
    }
};
