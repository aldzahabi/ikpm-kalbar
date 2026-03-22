<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

class Santri extends Model
{
    public const STATUS_SANTRI = 'santri';

    public const STATUS_USTAD = 'ustad';

    public const STATUS_ALUMNI = 'alumni';

    /** Status yang masih aktif di lingkungan pondok (bukan alumni). */
    public static function activePondokStatuses(): array
    {
        return [self::STATUS_SANTRI, self::STATUS_USTAD];
    }

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'stambuk';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'stambuk',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'nama_ayah',
        'nama',
        'provinsi',
        'daerah',
        'alamat',
        'status',
        'kelas',
        'pondok_cabang',
        'foto_diri',
        'foto_kk',
        'kenaikan_kelas',
        'ustad_mulai_tahun',
        'user_id',
    ];

    protected static function booted(): void
    {
        static::saving(function (Santri $santri) {
            self::applyUstadKelasRules($santri);
        });
    }

    /**
     * Aturan: status ustad → isi tahun mulai (default tahun berjalan), kelas = tahun ke (1,2,3…).
     * Bukan ustad → hapus tahun mulai ustad.
     */
    public static function applyUstadKelasRules(self $santri): void
    {
        if ($santri->status !== self::STATUS_USTAD) {
            $santri->ustad_mulai_tahun = null;

            return;
        }

        if (empty($santri->ustad_mulai_tahun)) {
            $santri->ustad_mulai_tahun = (int) now()->format('Y');
        }

        $tahunKe = max(1, (int) now()->format('Y') - (int) $santri->ustad_mulai_tahun + 1);
        $santri->kelas = (string) $tahunKe;
    }

    /** Tahun ke berapa sebagai ustad (null jika bukan ustad). */
    public function ustadTahunKe(): ?int
    {
        if ($this->status !== self::STATUS_USTAD || empty($this->ustad_mulai_tahun)) {
            return null;
        }

        return max(1, (int) now()->format('Y') - (int) $this->ustad_mulai_tahun + 1);
    }

    /**
     * Daftar Pondok Modern Darussalam Gontor dan Cabang-cabangnya.
     */
    public static function getPondokCabangList(): array
    {
        return [
            '1' => 'Gontor 1 (Ponorogo)',
            '2' => 'Gontor 2 (Madusari, Ponorogo)',
            '3' => 'Gontor 3 (Kediri)',
            '4' => 'Gontor 4 (Banyuwangi)', // Khusus Putri
            '5' => 'Gontor 5 (Magelang)',
            '6' => 'Gontor 6 (Poso, Sulawesi Tengah)',
            '7' => 'Gontor 7 (Riyadhul Jannah, Lampung)',
            '8' => 'Gontor 8 (Labuhan Ratu, Lampung)', // Khusus Putri
            '9' => 'Gontor 9 (Kalianda, Lampung)',
            '10' => 'Gontor 10 (Serang, Banten)', // Khusus Putri
            '11' => 'Gontor 11 (Sulit Air, Sumatera Barat)',
            '12' => 'Gontor 12 (Tanjung Jabung Timur, Jambi)',
            '13' => 'Gontor 13 (Siak, Riau)',
            '14' => 'Gontor 14 (Sigli, Aceh)', // Khusus Putri
            '15' => 'Gontor 15 (Aceh Besar)', // Khusus Putri
            '16' => 'Gontor 16 (Tangerang)', // Khusus Putri
            '17' => 'Gontor 17 (Konawe Selatan, Sulawesi Tenggara)',
            '18' => 'Gontor 18 (Sentul, Bogor)', // Khusus Putri
            '19' => 'Gontor 19 (Kendari, Sulawesi Tenggara)', // Khusus Putri
            '20' => 'Gontor 20 (Sidamanik, Sumatera Utara)',
            '21' => 'Gontor 21 (Batam, Kepulauan Riau)', // Khusus Putri
        ];
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_lahir' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'ustad_mulai_tahun' => 'integer',
    ];

    /**
     * Get the user that created this santri record.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include santri kelas 4.
     */
    public function scopeKelas4($query)
    {
        return $query->where('kelas', '4');
    }

    /**
     * Scope a query to filter santri by provinsi.
     */
    public function scopeProvinsi($query, $provinsi)
    {
        return $query->where('provinsi', $provinsi);
    }

    /**
     * Filter daftar santri (index / export) — sama dengan logika SantriController.
     */
    public function scopeForWebList(Builder $query, Request $request, $user): Builder
    {
        if ($user && method_exists($user, 'isUstad') && $user->isUstad()) {
            $assignedPondok = $user->pondokCabang();
            if (! empty($assignedPondok)) {
                $query->whereIn('pondok_cabang', $assignedPondok);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        if ($request->filled('pondok_cabang')) {
            $query->where('pondok_cabang', $request->pondok_cabang);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%'.$search.'%')
                    ->orWhere('stambuk', 'like', '%'.$search.'%');
            });
        }

        return $query;
    }

    /**
     * Get the rombongans that this santri belongs to.
     */
    public function rombongans()
    {
        return $this->belongsToMany(Rombongan::class, 'rombongan_santri', 'santri_stambuk', 'rombongan_id', 'stambuk', 'id')
            ->withPivot('nomor_kursi', 'status_pembayaran')
            ->withTimestamps();
    }
}
