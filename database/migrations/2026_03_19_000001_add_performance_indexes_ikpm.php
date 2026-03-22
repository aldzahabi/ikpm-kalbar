<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Indeks tambahan untuk filter dashboard, laporan keuangan, dan pivot rombongan.
     * Catatan: kolom `nama` sudah punya index di migration awal santris — tidak duplikasi fullText
     * agar kompatibel PostgreSQL & MySQL tanpa konfigurasi khusus.
     */
    public function up(): void
    {
        Schema::table('santris', function (Blueprint $table) {
            $table->index(['status', 'pondok_cabang'], 'santris_status_pondok_cabang_index');
            $table->index(['kelas', 'kenaikan_kelas'], 'santris_kelas_kenaikan_index');
            $table->index('created_at', 'santris_created_at_index');
        });

        Schema::table('finance_transactions', function (Blueprint $table) {
            $table->index(['finance_account_id', 'transaction_date'], 'finance_tx_account_date_index');
            $table->index('reference_id', 'finance_transactions_reference_id_index');
        });

        // Index `santri_stambuk` sering sudah ter-cover oleh FK/unik di DB tertentu; hindari duplikasi.
    }

    public function down(): void
    {
        Schema::table('santris', function (Blueprint $table) {
            $table->dropIndex('santris_status_pondok_cabang_index');
            $table->dropIndex('santris_kelas_kenaikan_index');
            $table->dropIndex('santris_created_at_index');
        });

        Schema::table('finance_transactions', function (Blueprint $table) {
            $table->dropIndex('finance_tx_account_date_index');
            $table->dropIndex('finance_transactions_reference_id_index');
        });

    }
};
