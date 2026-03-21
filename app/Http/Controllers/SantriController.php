<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use App\Imports\SantriImport;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;

class SantriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Santri::query();

        // Filter by pondok cabang for Ustad
        if ($user && $user->isUstad()) {
            $assignedPondok = $user->pondokCabang();
            if (!empty($assignedPondok)) {
                $query->whereIn('pondok_cabang', $assignedPondok);
            } else {
                // Ustad without assigned pondok sees nothing
                $query->whereRaw('1 = 0');
            }
        }

        // Filter by pondok cabang (for all users)
        if ($request->has('pondok_cabang') && $request->pondok_cabang != '') {
            $query->where('pondok_cabang', $request->pondok_cabang);
        }

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('stambuk', 'like', '%' . $search . '%');
            });
        }

        // Order by created_at descending (newest first)
        $query->orderBy('created_at', 'desc');

        // Paginate results
        $santris = $query->paginate(10);

        // Get pondok list for filter dropdown
        $pondokCabangList = Santri::getPondokCabangList();
        
        // For ustad, only show their assigned pondok in filter
        $filterPondokList = $pondokCabangList;
        if ($user && $user->isUstad()) {
            $assignedPondok = $user->pondokCabang();
            $filterPondokList = array_intersect_key($pondokCabangList, array_flip($assignedPondok));
        }

        return view('santri.index', compact('santris', 'pondokCabangList', 'filterPondokList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('canManageSantri');
        
        $user = auth()->user();
        $pondokCabangList = Santri::getPondokCabangList();
        
        // For ustad, only show their assigned pondok
        if ($user->isUstad()) {
            $assignedPondok = $user->pondokCabang();
            $pondokCabangList = array_intersect_key($pondokCabangList, array_flip($assignedPondok));
        }
        
        return view('santri.create', compact('pondokCabangList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('canManageSantri');
        
        $user = auth()->user();
        
        // Validate pondok_cabang for ustad
        $pondokValidation = 'nullable|string|max:50';
        if ($user->isUstad()) {
            $assignedPondok = $user->pondokCabang();
            $pondokValidation = ['required', 'string', 'max:50', Rule::in($assignedPondok)];
        }
        
        $validated = $request->validate([
            'stambuk' => 'required|string|max:5|unique:santris,stambuk',
            'nik' => ['nullable', 'string', 'digits:16', Rule::unique('santris', 'nik')],
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'nama_ayah' => 'nullable|string|max:255',
            'nama' => 'required|string|max:255',
            'provinsi' => 'required|string|max:255',
            'daerah' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'status' => 'required|in:santri,alumni,alumnus',
            'kelas' => 'nullable|string|max:50',
            'pondok_cabang' => $pondokValidation,
            'foto_diri' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'foto_kk' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'kenaikan_kelas' => 'nullable|in:naik,tidak_naik,lulus,baru',
        ]);

        // Handle foto_diri upload
        if ($request->hasFile('foto_diri')) {
            $validated['foto_diri'] = $request->file('foto_diri')->store('santri/foto-diri', 'public');
        }

        // Handle foto_kk upload
        if ($request->hasFile('foto_kk')) {
            $validated['foto_kk'] = $request->file('foto_kk')->store('santri/foto-kk', 'public');
        }

        // Add current user_id if authenticated
        if (auth()->check()) {
            $validated['user_id'] = auth()->id();
        }

        Santri::create($validated);

        return redirect()->route('santri.index')
            ->with('success', 'Data santri berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Santri $santri)
    {
        $user = auth()->user();
        
        // Ustad can only view santri in their assigned pondok
        if ($user && $user->isUstad()) {
            if (!$user->canManageSantri($santri)) {
                abort(403, 'Anda tidak memiliki akses untuk melihat data santri ini.');
            }
        }
        
        return view('santri.show', compact('santri'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Santri $santri)
    {
        $this->authorize('canManageSantri');
        
        $user = auth()->user();
        
        // Check if user can manage this specific santri
        if (!$user->canManageSantri($santri)) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah data santri ini.');
        }
        
        $pondokCabangList = Santri::getPondokCabangList();
        
        // For ustad, only show their assigned pondok
        if ($user->isUstad()) {
            $assignedPondok = $user->pondokCabang();
            $pondokCabangList = array_intersect_key($pondokCabangList, array_flip($assignedPondok));
        }
        
        return view('santri.edit', compact('santri', 'pondokCabangList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Santri $santri)
    {
        $this->authorize('canManageSantri');
        
        $user = auth()->user();
        
        // Check if user can manage this specific santri
        if (!$user->canManageSantri($santri)) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah data santri ini.');
        }
        
        // Validate pondok_cabang for ustad
        $pondokValidation = 'nullable|string|max:50';
        if ($user->isUstad()) {
            $assignedPondok = $user->pondokCabang();
            $pondokValidation = ['required', 'string', 'max:50', Rule::in($assignedPondok)];
        }
        
        $validated = $request->validate([
            'nik' => ['nullable', 'string', 'digits:16', Rule::unique('santris', 'nik')->ignore($santri->stambuk, 'stambuk')],
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'nama_ayah' => 'nullable|string|max:255',
            'nama' => 'required|string|max:255',
            'provinsi' => 'required|string|max:255',
            'daerah' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'status' => 'required|in:santri,alumni,alumnus',
            'kelas' => 'nullable|string|max:50',
            'pondok_cabang' => $pondokValidation,
            'foto_diri' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'foto_kk' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'kenaikan_kelas' => 'nullable|in:naik,tidak_naik,lulus,baru',
        ]);

        // Handle foto_diri upload
        if ($request->hasFile('foto_diri')) {
            // Delete old file if exists
            if ($santri->foto_diri) {
                Storage::disk('public')->delete($santri->foto_diri);
            }
            $validated['foto_diri'] = $request->file('foto_diri')->store('santri/foto-diri', 'public');
        }

        // Handle foto_kk upload
        if ($request->hasFile('foto_kk')) {
            // Delete old file if exists
            if ($santri->foto_kk) {
                Storage::disk('public')->delete($santri->foto_kk);
            }
            $validated['foto_kk'] = $request->file('foto_kk')->store('santri/foto-kk', 'public');
        }

        $santri->update($validated);

        return redirect()->route('santri.index')
            ->with('success', 'Data berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Santri $santri)
    {
        $this->authorize('canManageSantri');
        
        $user = auth()->user();
        
        // Check if user can manage this specific santri
        if (!$user->canManageSantri($santri)) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus data santri ini.');
        }
        
        // Delete uploaded files if exists
        if ($santri->foto_diri) {
            Storage::disk('public')->delete($santri->foto_diri);
        }
        if ($santri->foto_kk) {
            Storage::disk('public')->delete($santri->foto_kk);
        }
        
        $santri->delete();

        return redirect()->route('santri.index')
            ->with('success', 'Data santri berhasil dihapus.');
    }

    /**
     * Import santri from Excel file.
     */
    public function import(Request $request)
    {
        $this->authorize('isAdmin');
        
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048',
        ]);

        try {
            $import = new SantriImport();
            Excel::import($import, $request->file('file'));

            $created = $import->getCreatedCount();
            $updated = $import->getUpdatedCount();
            $skipped = $import->getSkippedCount();

            $message = "Import berhasil! ";
            $message .= "Ditambahkan: {$created}, ";
            $message .= "Diperbarui: {$updated}";
            if ($skipped > 0) {
                $message .= ", Dilewati: {$skipped}";
            }

            return redirect()->route('santri.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->route('santri.index')
                ->with('error', 'Error saat import: ' . $e->getMessage());
        }
    }
}
