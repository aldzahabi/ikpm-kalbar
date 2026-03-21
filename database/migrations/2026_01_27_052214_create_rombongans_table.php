<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rombongans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_rombongan');
            $table->enum('jenis_moda', ['Bus', 'Pesawat', 'Kapal']);
            $table->integer('kapasitas');
            $table->decimal('biaya', 12, 2);
            $table->dateTime('waktu_berangkat');
            $table->enum('status', ['Open', 'Closed'])->default('Open');
            $table->timestamps();
            
            $table->index('status');
            $table->index('waktu_berangkat');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rombongans');
    }
};
