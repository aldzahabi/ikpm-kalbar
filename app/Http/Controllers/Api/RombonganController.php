<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RombonganResource;
use App\Models\Rombongan;
use Illuminate\Http\Request;

class RombonganController extends Controller
{
    public function index(Request $request)
    {
        $query = Rombongan::query()
            ->withCount('santris')
            ->orderByDesc('waktu_berangkat');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $perPage = min(max((int) $request->get('per_page', 20), 1), 100);

        return RombonganResource::collection($query->paginate($perPage));
    }
}
