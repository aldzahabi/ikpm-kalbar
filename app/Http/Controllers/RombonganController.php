<?php

namespace App\Http\Controllers;

use App\Models\Rombongan;
use App\Models\Santri;
use App\Services\RombonganPembayaranService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class RombonganController extends Controller
{
    public function index()
    {
        $rombongans = Rombongan::withCount('santris')
            ->orderBy('waktu_berangkat', 'desc')
            ->paginate(10);

        return view('rombongan.index', compact('rombongans'));
    }

    public function create()
    {
        $this->authorize('canManageRombongan');
        return view('rombongan.create');
    }

    public function store(Request $request)
    {
        $this->authorize('canManageRombongan');
        
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'moda_transportasi' => 'required|in:Bus,Pesawat,Kapal',
            'waktu_berangkat' => 'required|date',
            'titik_kumpul' => 'nullable|string|max:255',
            'kapasitas' => 'required|integer|min:1',
            'biaya_per_orang' => 'required|numeric|min:0',
            'status' => 'required|in:open,closed,departed',
        ]);

        Rombongan::create($validated);

        return redirect()->route('rombongan.index')
            ->with('success', 'Rombongan berhasil ditambahkan.');
    }

    public function show(Rombongan $rombongan)
    {
        $rombongan->load(['santris' => function ($q) {
            $q->orderByPivot('nomor_kursi');
        }]);
        
        // Santri yang belum terdaftar di rombongan manapun atau rombongan ini
        $santriAvailable = Santri::whereIn('status', Santri::activePondokStatuses())
            ->whereDoesntHave('rombongans', function ($query) use ($rombongan) {
                $query->where('rombongans.id', '!=', $rombongan->id);
            })
            ->orderBy('nama')
            ->get();

        return view('rombongan.show', compact('rombongan', 'santriAvailable'));
    }

    public function edit(Rombongan $rombongan)
    {
        $this->authorize('canManageRombongan');
        return view('rombongan.edit', compact('rombongan'));
    }

    public function update(Request $request, Rombongan $rombongan)
    {
        $this->authorize('canManageRombongan');
        
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'moda_transportasi' => 'required|in:Bus,Pesawat,Kapal',
            'waktu_berangkat' => 'required|date',
            'titik_kumpul' => 'nullable|string|max:255',
            'kapasitas' => 'required|integer|min:1',
            'biaya_per_orang' => 'required|numeric|min:0',
            'status' => 'required|in:open,closed,departed',
        ]);

        // Validasi kapasitas tidak boleh kurang dari jumlah santri yang sudah terdaftar
        $jumlahTerdaftar = $rombongan->santris()->count();
        if ($validated['kapasitas'] < $jumlahTerdaftar) {
            return redirect()->back()
                ->withErrors(['kapasitas' => "Kapasitas tidak boleh kurang dari jumlah santri yang sudah terdaftar ($jumlahTerdaftar)."])
                ->withInput();
        }

        $rombongan->update($validated);

        return redirect()->route('rombongan.index')
            ->with('success', 'Rombongan berhasil diperbarui.');
    }

    public function destroy(Rombongan $rombongan)
    {
        $this->authorize('canManageRombongan');
        
        $rombongan->delete();

        return redirect()->route('rombongan.index')
            ->with('success', 'Rombongan berhasil dihapus.');
    }

    public function addSantri(Request $request, Rombongan $rombongan)
    {
        $this->authorize('canManageRombongan');
        
        $request->validate([
            'santri_stambuk' => 'required|string|exists:santris,stambuk',
            'nomor_kursi' => 'nullable|string|max:50',
            'catatan' => 'nullable|string|max:500',
        ]);

        // Validasi kapasitas
        if ($rombongan->isPenuh()) {
            return redirect()->back()
                ->with('error', 'Rombongan sudah penuh!');
        }

        // Validasi santri belum terdaftar di rombongan lain
        $santri = Santri::findOrFail($request->santri_stambuk);
        $rombonganLain = $santri->rombongans()
            ->where('rombongans.id', '!=', $rombongan->id)
            ->first();

        if ($rombonganLain) {
            return redirect()->back()
                ->with('error', "Santri sudah terdaftar di rombongan: {$rombonganLain->nama}");
        }

        // Validasi nomor kursi unik jika diisi
        if ($request->nomor_kursi) {
            $kursiTerpakai = DB::table('rombongan_santri')
                ->where('rombongan_id', $rombongan->id)
                ->where('nomor_kursi', $request->nomor_kursi)
                ->exists();

            if ($kursiTerpakai) {
                return redirect()->back()
                    ->with('error', "Nomor kursi {$request->nomor_kursi} sudah terpakai!");
            }
        }

        // Tambahkan santri ke rombongan
        $rombongan->santris()->attach($request->santri_stambuk, [
            'nomor_kursi' => $request->nomor_kursi,
            'status_pembayaran' => 'belum_lunas',
            'catatan' => $request->catatan ?? null,
        ]);

        return redirect()->back()
            ->with('success', 'Santri berhasil ditambahkan ke rombongan.');
    }

    public function removeSantri(Request $request, Rombongan $rombongan, $stambuk)
    {
        $this->authorize('canManageRombongan');
        
        $rombongan->santris()->detach($stambuk);

        return redirect()->back()
            ->with('success', 'Santri berhasil dihapus dari rombongan.');
    }

    public function updatePembayaran(Request $request, Rombongan $rombongan, $stambuk)
    {
        $this->authorize('canManageRombongan');
        
        $request->validate([
            'status_pembayaran' => 'required|in:lunas,belum_lunas',
        ]);

        RombonganPembayaranService::updateStatus(
            $rombongan,
            $stambuk,
            $request->status_pembayaran,
            (int) auth()->id()
        );

        return redirect()->back()
            ->with('success', 'Status pembayaran berhasil diperbarui.');
    }

    public function searchSantri(Request $request, Rombongan $rombongan)
    {
        $search = $request->get('search', '');
        
        $santriAvailable = Santri::whereIn('status', Santri::activePondokStatuses())
            ->whereDoesntHave('rombongans', function ($query) use ($rombongan) {
                $query->where('rombongans.id', '!=', $rombongan->id);
            })
            ->where(function($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('stambuk', 'like', '%' . $search . '%');
            })
            ->orderBy('nama')
            ->limit(10)
            ->get();

        return response()->json($santriAvailable);
    }

    public function exportPdf(Rombongan $rombongan)
    {
        return $this->printManifest($rombongan->id);
    }

    public function printManifest($id)
    {
        $rombongan = Rombongan::with(['santris' => function ($q) {
            $q->orderByPivot('nomor_kursi')->with('user');
        }])->findOrFail($id);
        
        $pdf = Pdf::loadView('rombongan.manifest-pdf', compact('rombongan'))
            ->setPaper('a4', 'portrait');

        $filename = 'Manifest_' . str_replace(' ', '_', $rombongan->nama) . '_' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }
}
