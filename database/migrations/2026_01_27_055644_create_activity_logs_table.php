<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action'); // 'create', 'update', 'delete', 'login', dll
            $table->string('model_type')->nullable(); // 'App\Models\Santri', dll
            $table->string('model_id')->nullable(); // ID dari model terkait
            $table->text('description'); // Deskripsi aktivitas
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('created_at');
            $table->index(['model_type', 'model_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
