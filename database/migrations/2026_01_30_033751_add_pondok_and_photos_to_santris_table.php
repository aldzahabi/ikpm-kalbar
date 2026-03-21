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
        Schema::table('santris', function (Blueprint $table) {
            $table->string('pondok_cabang')->nullable()->after('kelas');
            $table->string('foto_diri')->nullable()->after('pondok_cabang');
            $table->string('foto_kk')->nullable()->after('foto_diri');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('santris', function (Blueprint $table) {
            $table->dropColumn(['pondok_cabang', 'foto_diri', 'foto_kk']);
        });
    }
};
