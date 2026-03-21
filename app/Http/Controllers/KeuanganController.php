<?php

namespace App\Http\Controllers;

use App\Models\FinanceAccount;
use App\Models\FinanceCategory;
use App\Models\FinanceTransaction;
use App\Exports\FinanceTransactionExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class KeuanganController extends Controller
{
    public function index(Request $request)
    {
        // Get all accounts with current balance
        $accounts = FinanceAccount::orderBy('name')->get();
        
        // Get account IDs for filtering
        $accountKasIKPM = FinanceAccount::where('name', 'Kas Operasional IKPM')->first();
        $accountPerpulangan = FinanceAccount::where('name', 'Kas Perpulangan')->first();
        $accountForbis = FinanceAccount::where('name', 'Kas Forbis')->first();

        // Calculate summary statistics (all accounts)
        $totalIncome = FinanceTransaction::whereHas('category', function($q) {
            $q->where('type', 'income');
        })->sum('amount');
        
        $totalExpense = FinanceTransaction::whereHas('category', function($q) {
            $q->where('type', 'expense');
        })->sum('amount');
        
        $netBalance = $totalIncome - $totalExpense;

        // Get transactions based on active tab (default: summary)
        $activeTab = $request->get('tab', 'ringkasan');
        $query = FinanceTransaction::with(['account', 'category', 'user'])
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc');

        // Filter by account based on tab
        if ($activeTab === 'kas-ikpm' && $accountKasIKPM) {
            $query->where('finance_account_id', $accountKasIKPM->id);
        } elseif ($activeTab === 'perpulangan' && $accountPerpulangan) {
            $query->where('finance_account_id', $accountPerpulangan->id);
        } elseif ($activeTab === 'forbis' && $accountForbis) {
            $query->where('finance_account_id', $accountForbis->id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('transaction_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('transaction_date', '<=', $request->date_to);
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('finance_category_id', $request->category_id);
        }

        // Filter by type (income/expense)
        if ($request->filled('type')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('type', $request->type);
            });
        }

        $transactions = $query->paginate(20)->withQueryString();

        // Get categories for filter dropdown
        $categories = FinanceCategory::orderBy('type')->orderBy('name')->get();

        return view('keuangan.index', compact(
            'accounts', 
            'transactions', 
            'categories', 
            'activeTab',
            'totalIncome',
            'totalExpense',
            'netBalance',
            'accountKasIKPM',
            'accountPerpulangan',
            'accountForbis'
        ));
    }

    public function create()
    {
        $this->authorize('canManageFinance');
        
        $accounts = FinanceAccount::orderBy('name')->get();
        $categories = FinanceCategory::orderBy('type')->orderBy('name')->get();

        return view('keuangan.create', compact('accounts', 'categories'));
    }

    public function store(Request $request)
    {
        $this->authorize('canManageFinance');
        
        $validated = $request->validate([
            'finance_account_id' => 'required|exists:finance_accounts,id',
            'finance_category_id' => 'required|exists:finance_categories,id',
            'amount' => 'required|numeric|min:0.01',
            'transaction_date' => 'required|date',
            'description' => 'nullable|string|max:1000',
            'reference_id' => 'nullable|string|max:255',
        ]);

        $validated['user_id'] = Auth::id();

        FinanceTransaction::create($validated);

        return redirect()->route('keuangan.index')
            ->with('success', 'Transaksi berhasil dicatat.');
    }

    public function edit(FinanceTransaction $transaction)
    {
        $this->authorize('canManageFinance');
        
        $accounts = FinanceAccount::orderBy('name')->get();
        $categories = FinanceCategory::orderBy('type')->orderBy('name')->get();

        return view('keuangan.edit', compact('transaction', 'accounts', 'categories'));
    }

    public function update(Request $request, FinanceTransaction $transaction)
    {
        $this->authorize('canManageFinance');
        
        $validated = $request->validate([
            'finance_account_id' => 'required|exists:finance_accounts,id',
            'finance_category_id' => 'required|exists:finance_categories,id',
            'amount' => 'required|numeric|min:0.01',
            'transaction_date' => 'required|date',
            'description' => 'nullable|string|max:1000',
            'reference_id' => 'nullable|string|max:255',
        ]);

        $transaction->update($validated);

        return redirect()->route('keuangan.index')
            ->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(FinanceTransaction $transaction)
    {
        $this->authorize('canManageFinance');
        
        $transaction->delete();

        return redirect()->route('keuangan.index')
            ->with('success', 'Transaksi berhasil dihapus.');
    }

    public function exportExcel(Request $request)
    {
        $this->authorize('canManageFinance');
        
        $activeTab = $request->get('tab', 'ringkasan');
        
        // Get account IDs for filtering
        $accountKasIKPM = FinanceAccount::where('name', 'Kas Operasional IKPM')->first();
        $accountPerpulangan = FinanceAccount::where('name', 'Kas Perpulangan')->first();
        $accountForbis = FinanceAccount::where('name', 'Kas Forbis')->first();

        // Build query same as index
        $query = FinanceTransaction::with(['account', 'category', 'user'])
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc');

        $accountName = 'Semua Akun';
        
        // Filter by account based on tab
        if ($activeTab === 'kas-ikpm' && $accountKasIKPM) {
            $query->where('finance_account_id', $accountKasIKPM->id);
            $accountName = 'Kas Operasional IKPM';
        } elseif ($activeTab === 'perpulangan' && $accountPerpulangan) {
            $query->where('finance_account_id', $accountPerpulangan->id);
            $accountName = 'Kas Perpulangan';
        } elseif ($activeTab === 'forbis' && $accountForbis) {
            $query->where('finance_account_id', $accountForbis->id);
            $accountName = 'Kas Forbis';
        }

        // Apply filters
        if ($request->filled('date_from')) {
            $query->whereDate('transaction_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('transaction_date', '<=', $request->date_to);
        }
        if ($request->filled('category_id')) {
            $query->where('finance_category_id', $request->category_id);
        }
        if ($request->filled('type')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('type', $request->type);
            });
        }

        $transactions = $query->get();

        $filename = 'Laporan_Keuangan_' . str_replace(' ', '_', $accountName) . '_' . date('Y-m-d') . '.xlsx';

        return Excel::download(new FinanceTransactionExport($transactions, $accountName), $filename);
    }

    public function exportPdf(Request $request)
    {
        $this->authorize('canManageFinance');
        
        $activeTab = $request->get('tab', 'ringkasan');
        
        // Get account IDs for filtering
        $accountKasIKPM = FinanceAccount::where('name', 'Kas Operasional IKPM')->first();
        $accountPerpulangan = FinanceAccount::where('name', 'Kas Perpulangan')->first();
        $accountForbis = FinanceAccount::where('name', 'Kas Forbis')->first();

        // Build query same as index
        $query = FinanceTransaction::with(['account', 'category', 'user'])
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc');

        $account = null;
        $accountName = 'Semua Akun';
        
        // Filter by account based on tab
        if ($activeTab === 'kas-ikpm' && $accountKasIKPM) {
            $query->where('finance_account_id', $accountKasIKPM->id);
            $account = $accountKasIKPM;
            $accountName = 'Kas Operasional IKPM';
        } elseif ($activeTab === 'perpulangan' && $accountPerpulangan) {
            $query->where('finance_account_id', $accountPerpulangan->id);
            $account = $accountPerpulangan;
            $accountName = 'Kas Perpulangan';
        } elseif ($activeTab === 'forbis' && $accountForbis) {
            $query->where('finance_account_id', $accountForbis->id);
            $account = $accountForbis;
            $accountName = 'Kas Forbis';
        }

        // Apply filters
        if ($request->filled('date_from')) {
            $query->whereDate('transaction_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('transaction_date', '<=', $request->date_to);
        }
        if ($request->filled('category_id')) {
            $query->where('finance_category_id', $request->category_id);
        }
        if ($request->filled('type')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('type', $request->type);
            });
        }

        $transactions = $query->get();

        // Calculate totals
        $totalIncome = $transactions->where('category.type', 'income')->sum('amount');
        $totalExpense = $transactions->where('category.type', 'expense')->sum('amount');
        $netBalance = $totalIncome - $totalExpense;

        $printedBy = Auth::user()->name ?? 'System';

        $pdf = Pdf::loadView('keuangan.export-pdf', compact(
            'transactions',
            'account',
            'accountName',
            'totalIncome',
            'totalExpense',
            'netBalance',
            'activeTab',
            'printedBy'
        ))->setPaper('a4', 'landscape');

        $filename = 'Laporan_Keuangan_' . str_replace(' ', '_', $accountName) . '_' . date('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }
}
