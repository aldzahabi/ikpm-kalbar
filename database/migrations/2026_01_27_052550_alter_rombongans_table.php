<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rombongans', function (Blueprint $table) {
            // Drop old columns if exist
            if (Schema::hasColumn('rombongans', 'nama_rombongan')) {
                $table->dropColumn('nama_rombongan');
            }
            if (Schema::hasColumn('rombongans', 'jenis_moda')) {
                $table->dropColumn('jenis_moda');
            }
            if (Schema::hasColumn('rombongans', 'biaya')) {
                $table->dropColumn('biaya');
            }
            if (Schema::hasColumn('rombongans', 'status')) {
                $table->dropColumn('status');
            }
        });

        Schema::table('rombongans', function (Blueprint $table) {
            // Add new columns
            $table->string('nama')->after('id');
            $table->enum('moda_transportasi', ['Bus', 'Pesawat', 'Kapal'])->after('nama');
            $table->string('titik_kumpul')->nullable()->after('waktu_berangkat');
            $table->decimal('biaya_per_orang', 12, 2)->after('kapasitas');
            $table->enum('status', ['open', 'closed', 'departed'])->default('open')->after('biaya_per_orang');
            
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::table('rombongans', function (Blueprint $table) {
            $table->dropColumn(['nama', 'moda_transportasi', 'titik_kumpul', 'biaya_per_orang', 'status']);
        });

        Schema::table('rombongans', function (Blueprint $table) {
            $table->string('nama_rombongan')->after('id');
            $table->enum('jenis_moda', ['Bus', 'Pesawat', 'Kapal'])->after('nama_rombongan');
            $table->decimal('biaya', 12, 2)->after('kapasitas');
            $table->enum('status', ['Open', 'Closed'])->default('Open')->after('biaya');
        });
    }
};
