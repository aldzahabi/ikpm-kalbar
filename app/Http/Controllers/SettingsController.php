<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\FinanceAccount;
use App\Models\FinanceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $this->authorize('isAdmin');
        
        // Get organization settings
        $orgSettings = [
            'org_name' => Setting::get('org_name', 'IKPM Gontor Pontianak'),
            'org_address' => Setting::get('org_address', ''),
            'org_phone' => Setting::get('org_phone', ''),
            'org_email' => Setting::get('org_email', ''),
            'org_website' => Setting::get('org_website', ''),
            'org_logo' => Setting::get('org_logo', ''),
            'org_description' => Setting::get('org_description', ''),
        ];
        
        // Get finance accounts and categories
        $financeAccounts = FinanceAccount::orderBy('name')->get();
        $financeCategories = FinanceCategory::orderBy('type')->orderBy('name')->get();
        
        return view('settings.index', compact('orgSettings', 'financeAccounts', 'financeCategories'));
    }

    /**
     * Update organization settings
     */
    public function updateOrganization(Request $request)
    {
        $this->authorize('isAdmin');
        
        $validated = $request->validate([
            'org_name' => 'required|string|max:255',
            'org_address' => 'nullable|string|max:500',
            'org_phone' => 'nullable|string|max:20',
            'org_email' => 'nullable|email|max:255',
            'org_website' => 'nullable|url|max:255',
            'org_description' => 'nullable|string|max:1000',
            'org_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle logo upload
        if ($request->hasFile('org_logo')) {
            // Delete old logo if exists
            $oldLogo = Setting::get('org_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
            
            $logoPath = $request->file('org_logo')->store('settings', 'public');
            Setting::set('org_logo', $logoPath, 'string', 'organization', 'Logo Organisasi');
        }

        // Save other settings
        Setting::set('org_name', $validated['org_name'], 'string', 'organization', 'Nama Organisasi');
        Setting::set('org_address', $validated['org_address'] ?? '', 'text', 'organization', 'Alamat');
        Setting::set('org_phone', $validated['org_phone'] ?? '', 'string', 'organization', 'Telepon');
        Setting::set('org_email', $validated['org_email'] ?? '', 'string', 'organization', 'Email');
        Setting::set('org_website', $validated['org_website'] ?? '', 'string', 'organization', 'Website');
        Setting::set('org_description', $validated['org_description'] ?? '', 'text', 'organization', 'Deskripsi');

        return redirect()->route('settings.index')
            ->with('success', 'Pengaturan organisasi berhasil disimpan.');
    }

    /**
     * Store new finance account
     */
    public function storeAccount(Request $request)
    {
        $this->authorize('isAdmin');
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:finance_accounts,name',
            'description' => 'nullable|string|max:500',
        ]);

        FinanceAccount::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'current_balance' => 0,
        ]);

        return redirect()->route('settings.index', ['tab' => 'akun'])
            ->with('success', 'Akun keuangan berhasil ditambahkan.');
    }

    /**
     * Update finance account
     */
    public function updateAccount(Request $request, FinanceAccount $account)
    {
        $this->authorize('isAdmin');
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:finance_accounts,name,' . $account->id,
            'description' => 'nullable|string|max:500',
        ]);

        $account->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('settings.index', ['tab' => 'akun'])
            ->with('success', 'Akun keuangan berhasil diperbarui.');
    }

    /**
     * Delete finance account
     */
    public function destroyAccount(FinanceAccount $account)
    {
        $this->authorize('isAdmin');
        
        // Check if account has transactions
        if ($account->transactions()->count() > 0) {
            return redirect()->route('settings.index', ['tab' => 'akun'])
                ->with('error', 'Akun tidak dapat dihapus karena masih memiliki transaksi.');
        }

        $account->delete();

        return redirect()->route('settings.index', ['tab' => 'akun'])
            ->with('success', 'Akun keuangan berhasil dihapus.');
    }

    /**
     * Store new finance category
     */
    public function storeCategory(Request $request)
    {
        $this->authorize('isAdmin');
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
        ]);

        // Check unique name per type
        $exists = FinanceCategory::where('name', $validated['name'])
            ->where('type', $validated['type'])
            ->exists();
            
        if ($exists) {
            return redirect()->route('settings.index', ['tab' => 'kategori'])
                ->with('error', 'Kategori dengan nama dan tipe yang sama sudah ada.');
        }

        FinanceCategory::create($validated);

        return redirect()->route('settings.index', ['tab' => 'kategori'])
            ->with('success', 'Kategori keuangan berhasil ditambahkan.');
    }

    /**
     * Update finance category
     */
    public function updateCategory(Request $request, FinanceCategory $category)
    {
        $this->authorize('isAdmin');
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
        ]);

        // Check unique name per type (excluding current)
        $exists = FinanceCategory::where('name', $validated['name'])
            ->where('type', $validated['type'])
            ->where('id', '!=', $category->id)
            ->exists();
            
        if ($exists) {
            return redirect()->route('settings.index', ['tab' => 'kategori'])
                ->with('error', 'Kategori dengan nama dan tipe yang sama sudah ada.');
        }

        $category->update($validated);

        return redirect()->route('settings.index', ['tab' => 'kategori'])
            ->with('success', 'Kategori keuangan berhasil diperbarui.');
    }

    /**
     * Delete finance category
     */
    public function destroyCategory(FinanceCategory $category)
    {
        $this->authorize('isAdmin');
        
        // Check if category has transactions
        if ($category->transactions()->count() > 0) {
            return redirect()->route('settings.index', ['tab' => 'kategori'])
                ->with('error', 'Kategori tidak dapat dihapus karena masih memiliki transaksi.');
        }

        $category->delete();

        return redirect()->route('settings.index', ['tab' => 'kategori'])
            ->with('success', 'Kategori keuangan berhasil dihapus.');
    }
}
