<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('santris', function (Blueprint $table) {
            $table->string('nik', 16)->nullable()->after('stambuk');
            $table->string('tempat_lahir')->nullable()->after('nik');
            $table->date('tanggal_lahir')->nullable()->after('tempat_lahir');
            $table->string('nama_ayah')->nullable()->after('tanggal_lahir');
            
            $table->index('nik');
        });
    }

    public function down(): void
    {
        Schema::table('santris', function (Blueprint $table) {
            $table->dropIndex(['nik']);
            $table->dropColumn(['nik', 'tempat_lahir', 'tanggal_lahir', 'nama_ayah']);
        });
    }
};
