<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;

/**
 * Jika belum ada user admin, buat akun default (mis. setelah migrate tanpa seed).
 */
class EnsureIkpmAdminCommand extends Command
{
    protected $signature = 'ikpm:ensure-admin
                            {--email=admin@ikpm.com : Email admin}
                            {--password= : Password (default: env IKPM_ADMIN_PASSWORD atau password123)}
                            {--reset-password : Setel ulang password untuk email ini}';

    protected $description = 'Pastikan ada user admin (buat jika belum ada; opsional reset password)';

    public function handle(): int
    {
        $adminRole = Role::where('slug', 'admin')->first();
        if (! $adminRole) {
            $this->error('Tabel roles belum ada slug "admin". Jalankan migrasi + seeder roles terlebih dahulu.');

            return self::FAILURE;
        }

        $email = (string) $this->option('email');
        $password = $this->option('password')
            ?: env('IKPM_ADMIN_PASSWORD', 'password123');

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => 'Super Admin',
                'password' => $password,
                'role_id' => $adminRole->id,
                'no_hp' => '081234567890',
                'is_active' => true,
            ]
        );

        if (! $user->wasRecentlyCreated) {
            if ($this->option('reset-password')) {
                $user->password = $password;
                $user->role_id = $adminRole->id;
                $user->is_active = true;
                $user->save();
                $this->info("Password di-reset untuk: {$email}");
            } else {
                $this->info("Admin sudah ada: {$email} (password tidak diubah). Pakai --reset-password untuk mengganti.");
            }
        } else {
            $this->info("Admin dibuat: {$email}");
        }

        $this->line('Login web: gunakan email + password yang Anda set.');

        return self::SUCCESS;
    }
}
