<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Rombongan extends Model
{
    protected $fillable = [
        'nama',
        'moda_transportasi',
        'waktu_berangkat',
        'titik_kumpul',
        'kapasitas',
        'biaya_per_orang',
        'status',
    ];

    protected $casts = [
        'waktu_berangkat' => 'datetime',
        'biaya_per_orang' => 'decimal:2',
    ];

    public function santris(): BelongsToMany
    {
        return $this->belongsToMany(Santri::class, 'rombongan_santri', 'rombongan_id', 'santri_stambuk', 'id', 'stambuk')
            ->withPivot('nomor_kursi', 'status_pembayaran', 'catatan')
            ->withTimestamps();
    }

    public function getJumlahTerisiAttribute(): int
    {
        return $this->santris()->count();
    }

    public function getSisaKursiAttribute(): int
    {
        return max(0, $this->kapasitas - $this->jumlah_terisi);
    }

    public function isPenuh(): bool
    {
        return $this->jumlah_terisi >= $this->kapasitas;
    }

    public function getPersentaseTerisiAttribute(): float
    {
        if ($this->kapasitas == 0) {
            return 0;
        }
        return ($this->jumlah_terisi / $this->kapasitas) * 100;
    }
}
