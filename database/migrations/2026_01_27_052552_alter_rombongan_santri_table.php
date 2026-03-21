<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update nomor_kursi dari integer ke string
        Schema::table('rombongan_santri', function (Blueprint $table) {
            if (Schema::hasColumn('rombongan_santri', 'nomor_kursi')) {
                $table->dropColumn('nomor_kursi');
            }
        });
        
        Schema::table('rombongan_santri', function (Blueprint $table) {
            $table->string('nomor_kursi')->nullable()->after('santri_stambuk');
        });

        // Update status_pembayaran enum
        DB::statement("ALTER TABLE rombongan_santri MODIFY COLUMN status_pembayaran ENUM('belum_lunas', 'lunas') DEFAULT 'belum_lunas'");

        // Add catatan column
        Schema::table('rombongan_santri', function (Blueprint $table) {
            if (!Schema::hasColumn('rombongan_santri', 'catatan')) {
                $table->string('catatan')->nullable()->after('status_pembayaran');
            }
        });
    }

    public function down(): void
    {
        Schema::table('rombongan_santri', function (Blueprint $table) {
            if (Schema::hasColumn('rombongan_santri', 'catatan')) {
                $table->dropColumn('catatan');
            }
            if (Schema::hasColumn('rombongan_santri', 'nomor_kursi')) {
                $table->dropColumn('nomor_kursi');
            }
        });

        DB::statement("ALTER TABLE rombongan_santri MODIFY COLUMN status_pembayaran ENUM('Lunas', 'Belum') DEFAULT 'Belum'");
        
        Schema::table('rombongan_santri', function (Blueprint $table) {
            $table->integer('nomor_kursi')->nullable()->after('santri_stambuk');
        });
    }
};
