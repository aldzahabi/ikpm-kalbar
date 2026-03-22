<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Rombongan */
class RombonganResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $r = $this->resource;

        return [
            'id' => $r->id,
            'nama' => $r->nama,
            'moda_transportasi' => $r->moda_transportasi,
            'waktu_berangkat' => $r->waktu_berangkat?->toIso8601String(),
            'titik_kumpul' => $r->titik_kumpul,
            'kapasitas' => $r->kapasitas,
            'biaya_per_orang' => $r->biaya_per_orang,
            'status' => $r->status,
            'jumlah_terisi' => (int) ($r->santris_count ?? 0),
        ];
    }
}
