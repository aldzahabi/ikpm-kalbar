<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rombongan;
use App\Services\RombonganPembayaranService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PembayaranController extends Controller
{
    /**
     * Update status pembayaran santri pada rombongan (sama dengan flow web).
     */
    public function store(Request $request): JsonResponse
    {
        Gate::authorize('canManageRombongan');

        $data = $request->validate([
            'rombongan_id' => 'required|integer|exists:rombongans,id',
            'santri_stambuk' => 'required|string|exists:santris,stambuk',
            'status_pembayaran' => 'required|in:lunas,belum_lunas',
        ]);

        $rombongan = Rombongan::query()->findOrFail($data['rombongan_id']);

        RombonganPembayaranService::updateStatus(
            $rombongan,
            $data['santri_stambuk'],
            $data['status_pembayaran'],
            (int) $request->user()->id
        );

        return response()->json([
            'message' => 'Status pembayaran diperbarui.',
            'rombongan_id' => $rombongan->id,
            'santri_stambuk' => $data['santri_stambuk'],
            'status_pembayaran' => $data['status_pembayaran'],
        ]);
    }
}
