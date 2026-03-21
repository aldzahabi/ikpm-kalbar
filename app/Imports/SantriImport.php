<?php

namespace App\Imports;

use App\Models\Santri;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Illuminate\Validation\Rule;

class SantriImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, SkipsEmptyRows
{
    use SkipsFailures;

    protected $updated = 0;
    protected $created = 0;
    protected $skipped = 0;

    public function model(array $row)
    {
        // Normalize column names (handle case-insensitive)
        $stambuk = $row['stambuk'] ?? $row['Stambuk'] ?? null;
        $nama = $row['nama'] ?? $row['Nama'] ?? null;
        $kelas = $row['kelas'] ?? $row['Kelas'] ?? null;
        $daerah = $row['daerah'] ?? $row['Daerah'] ?? null;
        $namaAyah = $row['nama_ayah'] ?? $row['Nama Ayah'] ?? $row['nama_ayah'] ?? null;
        $provinsi = $row['provinsi'] ?? $row['Provinsi'] ?? null;
        $nik = $row['nik'] ?? $row['NIK'] ?? null;
        $tempatLahir = $row['tempat_lahir'] ?? $row['Tempat Lahir'] ?? null;
        $tanggalLahir = $row['tanggal_lahir'] ?? $row['Tanggal Lahir'] ?? null;

        // Skip jika stambuk atau nama kosong
        if (empty($stambuk) || empty($nama)) {
            $this->skipped++;
            return null;
        }

        // Cari santri berdasarkan stambuk
        $santri = Santri::find($stambuk);

        // Prepare alamat dengan nama ayah jika ada
        $alamat = null;
        if ($namaAyah) {
            $alamat = "Nama Ayah: {$namaAyah}";
        }

        if ($santri) {
            // Update jika sudah ada
            $updateData = [
                'nama' => $nama,
                'kelas' => $kelas ?? $santri->kelas,
                'daerah' => $daerah ?? $santri->daerah,
                'status' => $santri->status ?? 'santri',
            ];
            
            // Update provinsi jika ada
            if ($provinsi) {
                $updateData['provinsi'] = $provinsi;
            }
            
            // Update biodata jika ada
            if ($nik) {
                $updateData['nik'] = $nik;
            }
            if ($tempatLahir) {
                $updateData['tempat_lahir'] = $tempatLahir;
            }
            if ($tanggalLahir) {
                $updateData['tanggal_lahir'] = $tanggalLahir;
            }
            if ($namaAyah) {
                $updateData['nama_ayah'] = $namaAyah;
            }
            
            // Update alamat jika ada nama ayah (untuk backward compatibility)
            if ($alamat && !$namaAyah) {
                $updateData['alamat'] = $alamat . ($santri->alamat ? "\n" . $santri->alamat : '');
            }
            
            $santri->update($updateData);
            $this->updated++;
            return null; // Return null karena sudah di-update
        } else {
            // Create jika belum ada
            $this->created++;
            return new Santri([
                'stambuk' => $stambuk,
                'nik' => $nik ?? null,
                'tempat_lahir' => $tempatLahir ?? null,
                'tanggal_lahir' => $tanggalLahir ?? null,
                'nama_ayah' => $namaAyah ?? null,
                'nama' => $nama,
                'kelas' => $kelas ?? null,
                'daerah' => $daerah ?? null,
                'provinsi' => $provinsi ?? null,
                'alamat' => $alamat,
                'status' => 'santri',
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'stambuk' => ['required', 'string', 'max:5'],
            'nama' => ['required', 'string', 'max:255'],
            'kelas' => ['nullable', 'string'],
            'daerah' => ['nullable', 'string'],
            'nama_ayah' => ['nullable', 'string', 'max:255'],
            'nik' => ['nullable', 'string', 'digits:16'],
            'tempat_lahir' => ['nullable', 'string', 'max:255'],
            'tanggal_lahir' => ['nullable', 'date'],
        ];
    }

    public function getUpdatedCount(): int
    {
        return $this->updated;
    }

    public function getCreatedCount(): int
    {
        return $this->created;
    }

    public function getSkippedCount(): int
    {
        return $this->skipped;
    }
}
