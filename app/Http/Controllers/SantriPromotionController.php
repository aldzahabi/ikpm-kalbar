<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use Illuminate\Http\Request;

class SantriPromotionController extends Controller
{
    /**
     * Promote selected santris to next class level.
     */
    public function promote(Request $request)
    {
        $this->authorize('isAdmin');
        
        $request->validate([
            'santri_ids' => 'required|array',
            'santri_ids.*' => 'required|string|exists:santris,stambuk',
        ]);

        $santriIds = $request->santri_ids;
        $promotedCount = 0;

        foreach ($santriIds as $stambuk) {
            $santri = Santri::find($stambuk);
            
            if (!$santri) {
                continue;
            }

            if ($santri->status === Santri::STATUS_USTAD) {
                continue;
            }

            $currentKelas = $santri->kelas;
            $newKelas = null;
            $newStatus = $santri->status;
            $kenaikanKelas = null;

            // Logic kenaikan kelas
            switch ($currentKelas) {
                case '1':
                    $newKelas = '2';
                    $kenaikanKelas = 'naik';
                    break;
                
                case '2':
                    $newKelas = '3';
                    $kenaikanKelas = 'naik';
                    break;
                
                case '3Int':
                    $newKelas = '4';
                    $kenaikanKelas = 'naik';
                    break;
                
                case '3':
                    // Kelas 3 reguler default naik ke 4
                    $newKelas = '4';
                    $kenaikanKelas = 'naik';
                    break;
                
                case '4':
                    $newKelas = '5';
                    $kenaikanKelas = 'naik';
                    break;
                
                case '5':
                    $newKelas = '6';
                    $kenaikanKelas = 'naik';
                    break;
                
                case '6':
                    // Kelas 6 lulus, ubah status jadi alumni
                    $newKelas = 'lulus';
                    $newStatus = 'alumni';
                    $kenaikanKelas = 'lulus';
                    break;
                
                default:
                    // Jika kelas tidak sesuai, skip
                    continue 2;
            }

            // Update santri
            $santri->update([
                'kelas' => $newKelas,
                'status' => $newStatus,
                'kenaikan_kelas' => $kenaikanKelas,
            ]);

            $promotedCount++;
        }

        if ($promotedCount > 0) {
            return redirect()->route('santri.index')
                ->with('success', $promotedCount . ' Santri berhasil dinaikkan kelasnya.');
        }

        return redirect()->route('santri.index')
            ->with('error', 'Tidak ada santri yang berhasil dinaikkan kelasnya.');
    }
}
