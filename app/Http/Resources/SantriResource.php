<?php

namespace App\Http\Resources;

use App\Models\Santri;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Santri */
class SantriResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Santri $s */
        $s = $this->resource;
        $pondok = $s->pondok_cabang
            ? (Santri::getPondokCabangList()[$s->pondok_cabang] ?? $s->pondok_cabang)
            : null;

        return [
            'stambuk' => $s->stambuk,
            'nama' => $s->nama,
            'nik' => $s->nik,
            'provinsi' => $s->provinsi,
            'daerah' => $s->daerah,
            'kelas' => $s->kelas,
            'pondok_cabang' => $s->pondok_cabang,
            'pondok_cabang_label' => $pondok,
            'status' => $s->status,
            'ustad_mulai_tahun' => $s->ustad_mulai_tahun,
            'ustad_tahun_ke' => $s->ustadTahunKe(),
            'kenaikan_kelas' => $s->kenaikan_kelas,
            'created_at' => $s->created_at?->toIso8601String(),
        ];
    }
}
