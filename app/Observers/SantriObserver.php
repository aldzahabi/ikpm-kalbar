<?php

namespace App\Observers;

use App\Helpers\ActivityLogHelper;
use App\Models\Role;
use App\Models\Santri;
use App\Models\User;
use App\Services\DashboardStatsCache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SantriObserver
{
    public function created(Santri $santri): void
    {
        // Jika santri baru dengan status ustad, buat user otomatis
        if ($santri->status === Santri::STATUS_USTAD) {
            $this->createOrActivateUserForUstad($santri);
        }

        DashboardStatsCache::forget();
    }

    public function updated(Santri $santri): void
    {
        $changes = $santri->getChanges();
        unset($changes['updated_at']);

        if ($changes !== []) {
            ActivityLogHelper::log(
                'santri_updated',
                'Update santri '.$santri->stambuk.': '.json_encode($changes, JSON_UNESCAPED_UNICODE),
                Santri::class,
                (string) $santri->getKey()
            );
        }

        // Cek perubahan status
        if ($santri->wasChanged('status')) {
            $this->handleStatusChange($santri);
        }

        // Cek perubahan pondok_cabang untuk ustad
        if ($santri->status === Santri::STATUS_USTAD && $santri->wasChanged('pondok_cabang')) {
            $this->syncUserPondokCabang($santri);
        }

        DashboardStatsCache::forget();
    }

    public function deleted(Santri $santri): void
    {
        ActivityLogHelper::log(
            'santri_deleted',
            'Menghapus santri '.$santri->stambuk.' — '.$santri->nama,
            Santri::class,
            (string) $santri->getKey()
        );

        // Nonaktifkan user jika ada
        $user = User::where('email', $santri->stambuk.'@ikpm.local')->first();
        if ($user) {
            $user->update(['is_active' => false]);
            ActivityLogHelper::log(
                'user_deactivated',
                'User '.$user->email.' dinonaktifkan karena santri dihapus',
                User::class,
                (string) $user->id
            );
        }

        DashboardStatsCache::forget();
    }

    /**
     * Handle perubahan status santri.
     */
    protected function handleStatusChange(Santri $santri): void
    {
        $oldStatus = $santri->getOriginal('status');
        $newStatus = $santri->status;

        // Status berubah menjadi ustad → buat/aktifkan user
        if ($newStatus === Santri::STATUS_USTAD) {
            $this->createOrActivateUserForUstad($santri);
        }
        // Status berubah dari ustad ke selain ustad → nonaktifkan user
        elseif ($oldStatus === Santri::STATUS_USTAD && $newStatus !== Santri::STATUS_USTAD) {
            $this->deactivateUserForSantri($santri);
        }
        // Status berubah menjadi alumni → nonaktifkan user (kecuali direksi - handle manual)
        elseif ($newStatus === Santri::STATUS_ALUMNI) {
            $this->deactivateUserForSantri($santri);
        }
    }

    /**
     * Buat atau aktifkan user untuk santri yang menjadi ustad.
     */
    protected function createOrActivateUserForUstad(Santri $santri): void
    {
        $email = $santri->stambuk.'@ikpm.local';
        $user = User::where('email', $email)->first();

        // Get role ustad
        $ustadRole = Role::where('slug', 'ustad')->first();
        if (! $ustadRole) {
            return; // Role ustad tidak ditemukan
        }

        if ($user) {
            // User sudah ada, aktifkan dan pastikan role-nya ustad
            $user->update([
                'is_active' => true,
                'role_id' => $ustadRole->id,
                'name' => $santri->nama,
            ]);

            ActivityLogHelper::log(
                'user_activated',
                'User '.$email.' diaktifkan karena santri '.$santri->stambuk.' menjadi ustad',
                User::class,
                (string) $user->id
            );
        } else {
            // Buat user baru dengan password = stambuk
            $user = User::create([
                'name' => $santri->nama,
                'email' => $email,
                'password' => $santri->stambuk, // Will be hashed by cast
                'role_id' => $ustadRole->id,
                'is_active' => true,
            ]);

            ActivityLogHelper::log(
                'user_created',
                'User '.$email.' dibuat otomatis untuk ustad '.$santri->nama.' (stambuk: '.$santri->stambuk.')',
                User::class,
                (string) $user->id
            );
        }

        // Sinkronkan pondok cabang
        $this->syncUserPondokCabang($santri, $user);
    }

    /**
     * Nonaktifkan user untuk santri yang bukan ustad lagi.
     */
    protected function deactivateUserForSantri(Santri $santri): void
    {
        $email = $santri->stambuk.'@ikpm.local';
        $user = User::where('email', $email)->first();

        if ($user && $user->is_active) {
            // Cek apakah user adalah admin (direksi) - jangan nonaktifkan
            if ($user->isAdmin()) {
                return;
            }

            $user->update(['is_active' => false]);

            ActivityLogHelper::log(
                'user_deactivated',
                'User '.$email.' dinonaktifkan karena santri '.$santri->stambuk.' bukan ustad lagi (status: '.$santri->status.')',
                User::class,
                (string) $user->id
            );
        }
    }

    /**
     * Sinkronkan pondok cabang user dengan santri.
     */
    protected function syncUserPondokCabang(Santri $santri, ?User $user = null): void
    {
        if (! $user) {
            $email = $santri->stambuk.'@ikpm.local';
            $user = User::where('email', $email)->first();
        }

        if (! $user) {
            return;
        }

        // Jika santri punya pondok_cabang, assign ke user
        if (! empty($santri->pondok_cabang)) {
            $user->syncPondokCabang([$santri->pondok_cabang]);

            ActivityLogHelper::log(
                'user_pondok_synced',
                'Pondok cabang user '.$user->email.' disinkronkan ke Gontor '.$santri->pondok_cabang,
                User::class,
                (string) $user->id
            );
        }
    }
}
