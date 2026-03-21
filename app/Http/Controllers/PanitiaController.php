<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use Illuminate\Http\Request;

class PanitiaController extends Controller
{
    /**
     * Display a listing of santri kelas 4 (calon panitia).
     */
    public function index(Request $request)
    {
        $query = Santri::kelas4()->where('status', 'santri');

        // Filter by provinsi jika ada
        if ($request->has('provinsi') && $request->provinsi != '') {
            $query->provinsi($request->provinsi);
        }

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('stambuk', 'like', '%' . $search . '%')
                  ->orWhere('daerah', 'like', '%' . $search . '%');
            });
        }

        // Order by provinsi, kemudian daerah, kemudian nama
        $query->orderBy('provinsi')->orderBy('daerah')->orderBy('nama');

        $santris = $query->paginate(20);

        // Get list provinsi untuk filter
        $provinsis = Santri::kelas4()
            ->where('status', 'santri')
            ->distinct()
            ->pluck('provinsi')
            ->sort()
            ->values();

        return view('panitia.index', compact('santris', 'provinsis'));
    }

    /**
     * Export Excel (dummy link for now).
     */
    public function export()
    {
        // TODO: Implement Excel export
        return redirect()->route('panitia.index')
            ->with('info', 'Fitur export Excel akan segera tersedia.');
    }
}
