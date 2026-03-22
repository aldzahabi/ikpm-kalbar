<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'no_hp',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the role that owns the user.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the pondok cabang assigned to this user.
     * Returns array of pondok_cabang codes.
     */
    public function pondokCabang()
    {
        return DB::table('user_pondok_cabang')
            ->where('user_id', $this->id)
            ->pluck('pondok_cabang')
            ->toArray();
    }

    /**
     * Get pondok cabang with names.
     */
    public function pondokCabangWithNames()
    {
        $codes = $this->pondokCabang();
        $list = Santri::getPondokCabangList();
        $result = [];
        
        foreach ($codes as $code) {
            $result[$code] = $list[$code] ?? 'Gontor ' . $code;
        }
        
        return $result;
    }

    /**
     * Check if user is assigned to a specific pondok cabang.
     */
    public function hasPondokCabang(string $pondokCabang): bool
    {
        return in_array($pondokCabang, $this->pondokCabang());
    }

    /**
     * Sync pondok cabang assignments for this user.
     */
    public function syncPondokCabang(array $pondokCabangs): void
    {
        // Delete existing assignments
        DB::table('user_pondok_cabang')->where('user_id', $this->id)->delete();
        
        // Insert new assignments
        $data = [];
        foreach ($pondokCabangs as $pondok) {
            if (!empty($pondok)) {
                $data[] = [
                    'user_id' => $this->id,
                    'pondok_cabang' => $pondok,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        
        if (!empty($data)) {
            DB::table('user_pondok_cabang')->insert($data);
        }
    }

    /**
     * Check if user is an Ustad (can manage santri in assigned pondok).
     */
    public function isUstad(): bool
    {
        return $this->role && $this->role->slug === 'ustad';
    }

    /**
     * Check if user is Admin.
     */
    public function isAdmin(): bool
    {
        return $this->role && $this->role->slug === 'admin';
    }

    /**
     * Check if user can manage a specific santri based on pondok cabang.
     */
    public function canManageSantri(Santri $santri): bool
    {
        // Admin can manage all
        if ($this->isAdmin()) {
            return true;
        }
        
        // Ustad can only manage santri in their assigned pondok
        if ($this->isUstad()) {
            // If santri has no pondok_cabang, ustad cannot manage
            if (empty($santri->pondok_cabang)) {
                return false;
            }
            
            return $this->hasPondokCabang($santri->pondok_cabang);
        }
        
        return false;
    }
}
