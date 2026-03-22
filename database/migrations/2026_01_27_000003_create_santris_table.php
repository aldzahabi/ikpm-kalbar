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
        Schema::create('santris', function (Blueprint $table) {
            $table->string('stambuk', 5)->primary();
            $table->string('nama');
            $table->string('provinsi');
            $table->string('daerah'); // Kabupaten/Kota
            $table->text('alamat')->nullable();
            $table->enum('status', ['santri', 'alumni', 'ustad'])->default('santri');
            $table->string('kelas')->nullable(); // contoh: '1', '2', '3Int', '6'
            $table->enum('kenaikan_kelas', ['naik', 'tidak_naik', 'lulus', 'baru'])->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            // Index untuk pencarian
            $table->index('nama');
            $table->index('status');
            $table->index('kelas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('santris');
    }
};
