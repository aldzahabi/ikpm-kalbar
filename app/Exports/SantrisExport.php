<?php

namespace App\Exports;

use App\Models\Santri;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SantrisExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(
        protected Request $request,
    ) {}

    public function query()
    {
        $user = auth()->user();

        return Santri::query()
            ->forWebList($this->request, $user)
            ->with('user')
            ->orderBy('stambuk');
    }

    /**
     * @return list<string>
     */
    public function headings(): array
    {
        // Jika filter status = ustad, ganti header "Kelas" menjadi "Tahun"
        $kelasHeader = $this->request->get('status') === 'ustad' ? 'Tahun' : 'Kelas';

        return [
            'Stambuk',
            'Nama',
            'NIK',
            'Provinsi',
            'Daerah',
            $kelasHeader,
            'Pondok cabang',
            'Status',
            'No HP (user)',
        ];
    }

    /**
     * @param  Santri  $row
     * @return array<int, mixed>
     */
    public function map($row): array
    {
        $pondok = $row->pondok_cabang
            ? (Santri::getPondokCabangList()[$row->pondok_cabang] ?? $row->pondok_cabang)
            : '';

        // Untuk ustad, tampilkan "Tahun Ke-X" bukan angka mentah
        $kelasValue = $row->kelas ?? '';
        if ($row->status === 'ustad' && $row->kelas) {
            $kelasValue = 'Tahun Ke-' . $row->kelas;
        }

        return [
            $row->stambuk,
            $row->nama,
            $row->nik ?? '',
            $row->provinsi,
            $row->daerah,
            $kelasValue,
            $pondok,
            $row->status,
            $row->user?->no_hp ?? $row->user?->email ?? '',
        ];
    }
}
