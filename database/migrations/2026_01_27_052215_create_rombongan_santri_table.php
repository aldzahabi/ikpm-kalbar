<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rombongan_santri', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rombongan_id')->constrained('rombongans')->onDelete('cascade');
            $table->string('santri_stambuk', 5);
            $table->foreign('santri_stambuk')->references('stambuk')->on('santris')->onDelete('cascade');
            $table->integer('nomor_kursi')->nullable();
            $table->enum('status_pembayaran', ['Lunas', 'Belum'])->default('Belum');
            $table->timestamps();
            
            $table->unique(['rombongan_id', 'santri_stambuk'], 'unique_santri_rombongan');
            $table->unique(['rombongan_id', 'nomor_kursi'], 'unique_kursi_rombongan');
            $table->index('status_pembayaran');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rombongan_santri');
    }
};
