<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use App\Models\FinanceAccount;
use App\Models\FinanceTransaction;
use App\Models\Rombongan;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Statistik Cepat
        $totalSantri = Santri::where('status', 'santri')->count();
        
        // Saldo Kas IKPM
        $accountKasIKPM = FinanceAccount::where('name', 'Kas Operasional IKPM')->first();
        $saldoKasIKPM = $accountKasIKPM ? $accountKasIKPM->current_balance : 0;
        
        // Saldo Perpulangan
        $accountPerpulangan = FinanceAccount::where('name', 'Kas Perpulangan')->first();
        $saldoPerpulangan = $accountPerpulangan ? $accountPerpulangan->current_balance : 0;
        
        // Progress Rombongan
        $totalSantriTerdaftar = DB::table('rombongan_santri')
            ->distinct()
            ->count('santri_stambuk');
        $progressRombongan = [
            'terdaftar' => $totalSantriTerdaftar,
            'total' => $totalSantri,
            'persentase' => $totalSantri > 0 ? round(($totalSantriTerdaftar / $totalSantri) * 100, 1) : 0
        ];
        
        // 2. Sebaran Santri per Daerah (Top 5)
        $sebaranDaerah = Santri::where('status', 'santri')
            ->selectRaw('daerah, COUNT(*) as jumlah')
            ->groupBy('daerah')
            ->orderBy('jumlah', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'daerah' => $item->daerah ?: 'Tidak Diketahui',
                    'jumlah' => $item->jumlah
                ];
            });
        
        // 3. Arus Kas Bulanan (Pemasukan vs Pengeluaran)
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        
        // Get transactions for current month grouped by day
        $arusKas = FinanceTransaction::whereBetween('transaction_date', [$startOfMonth, $endOfMonth])
            ->with('category')
            ->get()
            ->groupBy(function ($transaction) {
                return Carbon::parse($transaction->transaction_date)->format('d');
            })
            ->map(function ($transactions, $day) {
                $income = $transactions->where('category.type', 'income')->sum('amount');
                $expense = $transactions->where('category.type', 'expense')->sum('amount');
                return [
                    'day' => (int)$day,
                    'income' => (float)$income,
                    'expense' => (float)$expense
                ];
            })
            ->sortBy('day')
            ->values();
        
        // Fill missing days with 0
        $daysInMonth = Carbon::now()->daysInMonth;
        $arusKasComplete = collect(range(1, $daysInMonth))->map(function ($day) use ($arusKas) {
            $found = $arusKas->firstWhere('day', $day);
            return $found ?: [
                'day' => $day,
                'income' => 0,
                'expense' => 0
            ];
        });
        
        // 4. Aktivitas Terbaru
        try {
            $aktivitasTerbaru = ActivityLog::with('user')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            // Jika tabel activity_logs belum ada, return empty collection
            $aktivitasTerbaru = collect([]);
        }
        
        // Santri Terbaru (untuk tabel)
        $santriTerbaru = Santri::where('status', 'santri')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard', compact(
            'totalSantri',
            'saldoKasIKPM',
            'saldoPerpulangan',
            'progressRombongan',
            'sebaranDaerah',
            'arusKasComplete',
            'aktivitasTerbaru',
            'santriTerbaru'
        ));
    }
}
