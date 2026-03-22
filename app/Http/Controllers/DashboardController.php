<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\FinanceAccount;
use App\Models\FinanceTransaction;
use App\Models\Rombongan;
use App\Models\Santri;
use App\Services\DashboardStatsCache;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $forceRefresh = $request->boolean('refresh_stats');

        if ($forceRefresh) {
            DashboardStatsCache::forget();
        }

        $cached = Cache::remember(
            DashboardStatsCache::key(),
            DashboardStatsCache::TTL_SECONDS,
            function () {
                return $this->buildCachedDashboardPayload();
            }
        );

        try {
            $aktivitasTerbaru = ActivityLog::with('user')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            $aktivitasTerbaru = collect([]);
        }

        $santriTerbaru = Santri::whereIn('status', Santri::activePondokStatuses())
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard', array_merge($cached, [
            'aktivitasTerbaru' => $aktivitasTerbaru,
            'santriTerbaru' => $santriTerbaru,
        ]));
    }

    /**
     * @return array<string, mixed>
     */
    private function buildCachedDashboardPayload(): array
    {
        $totalSantri = Santri::whereIn('status', Santri::activePondokStatuses())->count();

        $accountKasIKPM = FinanceAccount::where('name', 'Kas Operasional IKPM')->first();
        $saldoKasIKPM = $accountKasIKPM ? $accountKasIKPM->current_balance : 0;

        $accountPerpulangan = FinanceAccount::where('name', 'Kas Perpulangan')->first();
        $saldoPerpulangan = $accountPerpulangan ? $accountPerpulangan->current_balance : 0;

        $totalSantriTerdaftar = DB::table('rombongan_santri')
            ->distinct()
            ->count('santri_stambuk');
        $progressRombongan = [
            'terdaftar' => $totalSantriTerdaftar,
            'total' => $totalSantri,
            'persentase' => $totalSantri > 0 ? round(($totalSantriTerdaftar / $totalSantri) * 100, 1) : 0,
        ];

        // Hanya array PHP (bukan Collection) — Collection di-cache bisa gagal saat unserialize.
        $sebaranDaerah = Santri::whereIn('status', Santri::activePondokStatuses())
            ->selectRaw('daerah, COUNT(*) as jumlah')
            ->groupBy('daerah')
            ->orderBy('jumlah', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'daerah' => $item->daerah ?: 'Tidak Diketahui',
                    'jumlah' => (int) $item->jumlah,
                ];
            })
            ->values()
            ->all();

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $arusKas = FinanceTransaction::whereBetween('transaction_date', [$startOfMonth, $endOfMonth])
            ->with('category')
            ->get()
            ->groupBy(function ($transaction) {
                return Carbon::parse($transaction->transaction_date)->format('d');
            })
            ->map(function ($transactions, $day) {
                $income = $transactions->filter(fn ($t) => $t->category && $t->category->type === 'income')->sum('amount');
                $expense = $transactions->filter(fn ($t) => $t->category && $t->category->type === 'expense')->sum('amount');

                return [
                    'day' => (int) $day,
                    'income' => (float) $income,
                    'expense' => (float) $expense,
                ];
            })
            ->sortBy('day')
            ->values();

        $daysInMonth = Carbon::now()->daysInMonth;
        $arusKasComplete = collect(range(1, $daysInMonth))->map(function ($day) use ($arusKas) {
            $found = $arusKas->firstWhere('day', $day);

            return $found ?: [
                'day' => $day,
                'income' => 0,
                'expense' => 0,
            ];
        })->values()->all();

        return [
            'totalSantri' => $totalSantri,
            'saldoKasIKPM' => $saldoKasIKPM,
            'saldoPerpulangan' => $saldoPerpulangan,
            'progressRombongan' => $progressRombongan,
            'sebaranDaerah' => $sebaranDaerah,
            'arusKasComplete' => $arusKasComplete,
        ];
    }
}
