<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Admin, Ustad, Panitia
            $table->string('slug')->unique(); // admin, ustad, panitia
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert default roles
        DB::table('roles')->insert([
            ['name' => 'Admin', 'slug' => 'admin', 'description' => 'Administrator sistem', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ustad', 'slug' => 'ustad', 'description' => 'Ustad/Pengajar', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Panitia', 'slug' => 'panitia', 'description' => 'Panitia kegiatan', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
