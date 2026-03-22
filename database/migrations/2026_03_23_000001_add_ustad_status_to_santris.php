<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom tahun mulai ustad, gabungkan alumnus → alumni, longgarkan kolom status (string).
     */
    public function up(): void
    {
        Schema::table('santris', function (Blueprint $table) {
            if (! Schema::hasColumn('santris', 'ustad_mulai_tahun')) {
                $table->unsignedSmallInteger('ustad_mulai_tahun')->nullable()->after('kenaikan_kelas');
            }
        });

        DB::table('santris')->where('status', 'alumnus')->update(['status' => 'alumni']);

        Schema::table('santris', function (Blueprint $table) {
            $table->string('status', 20)->default('santri')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('santris')->where('status', 'ustad')->update(['status' => 'santri', 'ustad_mulai_tahun' => null]);

        Schema::table('santris', function (Blueprint $table) {
            $table->dropColumn('ustad_mulai_tahun');
        });

        // Kembalikan ke enum/check seperti migrasi awal (tanpa ustad di DB layer).
        Schema::table('santris', function (Blueprint $table) {
            $table->enum('status', ['santri', 'alumni', 'alumnus'])->default('santri')->change();
        });
    }
};
