<?php

namespace App\Providers;

use App\Models\Santri;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Gate: isAdmin - Hanya role 'admin'
        Gate::define('isAdmin', function ($user) {
            return $user->role && $user->role->slug === 'admin';
        });

        // Gate: isUstad - Hanya role 'ustad'
        Gate::define('isUstad', function ($user) {
            return $user->role && $user->role->slug === 'ustad';
        });

        // Gate: canManageSantri - Admin atau Ustad (dengan batasan pondok cabang)
        Gate::define('canManageSantri', function ($user) {
            if (!$user->role) return false;
            return in_array($user->role->slug, ['admin', 'ustad']);
        });

        // Gate: canManageSantriRecord - Check if user can manage specific santri
        Gate::define('canManageSantriRecord', function ($user, Santri $santri) {
            return $user->canManageSantri($santri);
        });

        // Gate: isBendahara - Role 'admin' ATAU 'ustad' (Pengurus Harian)
        Gate::define('isBendahara', function ($user) {
            if (!$user->role) return false;
            return in_array($user->role->slug, ['admin', 'ustad']);
        });

        // Gate: isPanitia - Role 'admin' ATAU 'panitia'
        Gate::define('isPanitia', function ($user) {
            if (!$user->role) return false;
            return in_array($user->role->slug, ['admin', 'panitia']);
        });

        // Gate: canManageUsers - Hanya Admin
        Gate::define('canManageUsers', function ($user) {
            return $user->role && $user->role->slug === 'admin';
        });

        // Gate: canManageFinance - Admin atau Ustad (Bendahara)
        Gate::define('canManageFinance', function ($user) {
            if (!$user->role) return false;
            return in_array($user->role->slug, ['admin', 'ustad']);
        });

        // Gate: canManageRombongan - Admin atau Panitia
        Gate::define('canManageRombongan', function ($user) {
            if (!$user->role) return false;
            return in_array($user->role->slug, ['admin', 'panitia']);
        });
    }
}
