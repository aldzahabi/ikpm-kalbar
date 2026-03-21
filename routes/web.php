<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SantriController;
use App\Http\Controllers\SantriPromotionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LandingContentController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\PanitiaController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\RombonganController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Public News/Berita Routes
Route::get('/berita', [BeritaController::class, 'index'])->name('berita.index');
Route::get('/berita/{id}', [BeritaController::class, 'show'])->name('berita.show');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Routes
Route::middleware('auth')->group(function () {
    // Dashboard Route - Semua user yang login bisa akses
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Routes - Semua user yang login bisa akses
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // Santri Routes - Semua user yang login bisa akses (Read Only untuk non-admin)
    Route::resource('santri', SantriController::class);
    Route::post('santri/import', [SantriController::class, 'import'])->name('santri.import')->can('isAdmin');
    Route::post('santri/promote', [SantriPromotionController::class, 'promote'])->name('santri.promote')->can('isAdmin');

    // User Management Routes - Hanya Admin
    Route::middleware('can:isAdmin')->group(function () {
        Route::resource('users', UserController::class);
        
        // Settings Routes
        Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::put('settings/organization', [SettingsController::class, 'updateOrganization'])->name('settings.update.organization');
        
        // Finance Accounts Settings
        Route::post('settings/account', [SettingsController::class, 'storeAccount'])->name('settings.store.account');
        Route::put('settings/account/{account}', [SettingsController::class, 'updateAccount'])->name('settings.update.account');
        Route::delete('settings/account/{account}', [SettingsController::class, 'destroyAccount'])->name('settings.destroy.account');
        
        // Finance Categories Settings
        Route::post('settings/category', [SettingsController::class, 'storeCategory'])->name('settings.store.category');
        Route::put('settings/category/{category}', [SettingsController::class, 'updateCategory'])->name('settings.update.category');
        Route::delete('settings/category/{category}', [SettingsController::class, 'destroyCategory'])->name('settings.destroy.category');
    });

    // Landing Content Routes - Hanya Admin
    Route::middleware('can:isAdmin')->group(function () {
        Route::resource('landing-content', LandingContentController::class);
        Route::put('landing-content-contact', [LandingContentController::class, 'updateContact'])->name('landing-content.update.contact');
    });

    // Panitia Routes - Semua user bisa lihat, tapi export hanya admin/panitia
    Route::get('panitia', [PanitiaController::class, 'index'])->name('panitia.index');
    Route::get('panitia/export', [PanitiaController::class, 'export'])->name('panitia.export')->can('isPanitia');

    // Keuangan Routes - View semua bisa, tapi Create/Edit/Delete hanya Bendahara
    Route::get('keuangan', [KeuanganController::class, 'index'])->name('keuangan.index');
    Route::get('keuangan/export/excel', [KeuanganController::class, 'exportExcel'])->name('keuangan.export.excel')->can('canManageFinance');
    Route::get('keuangan/export/pdf', [KeuanganController::class, 'exportPdf'])->name('keuangan.export.pdf')->can('canManageFinance');
    Route::middleware('can:canManageFinance')->group(function () {
        Route::get('keuangan/create', [KeuanganController::class, 'create'])->name('keuangan.create');
        Route::post('keuangan', [KeuanganController::class, 'store'])->name('keuangan.store');
        Route::get('keuangan/{transaction}/edit', [KeuanganController::class, 'edit'])->name('keuangan.edit');
        Route::put('keuangan/{transaction}', [KeuanganController::class, 'update'])->name('keuangan.update');
        Route::delete('keuangan/{transaction}', [KeuanganController::class, 'destroy'])->name('keuangan.destroy');
    });

    // Rombongan Routes - View semua bisa, tapi Create/Edit/Delete hanya Panitia
    Route::get('rombongan', [RombonganController::class, 'index'])->name('rombongan.index');
    Route::middleware('can:canManageRombongan')->group(function () {
        // NOTE: keep static routes before parameter routes (avoid "create" being treated as {rombongan})
        Route::get('rombongan/create', [RombonganController::class, 'create'])->name('rombongan.create');
        Route::post('rombongan', [RombonganController::class, 'store'])->name('rombongan.store');
        Route::get('rombongan/{rombongan}/edit', [RombonganController::class, 'edit'])->name('rombongan.edit');
        Route::put('rombongan/{rombongan}', [RombonganController::class, 'update'])->name('rombongan.update');
        Route::delete('rombongan/{rombongan}', [RombonganController::class, 'destroy'])->name('rombongan.destroy');
        Route::post('rombongan/{rombongan}/add-santri', [RombonganController::class, 'addSantri'])->name('rombongan.add-santri');
        Route::delete('rombongan/{rombongan}/remove-santri/{stambuk}', [RombonganController::class, 'removeSantri'])->name('rombongan.remove-santri');
        Route::put('rombongan/{rombongan}/update-pembayaran/{stambuk}', [RombonganController::class, 'updatePembayaran'])->name('rombongan.update-pembayaran');
    });

    // Parameter routes (must be after /rombongan/create)
    Route::get('rombongan/{rombongan}', [RombonganController::class, 'show'])->name('rombongan.show');
    Route::get('rombongan/{rombongan}/export-pdf', [RombonganController::class, 'exportPdf'])->name('rombongan.export-pdf');
    Route::get('rombongan/{id}/print-manifest', [RombonganController::class, 'printManifest'])->name('rombongan.print-manifest');
    Route::get('rombongan/{rombongan}/search-santri', [RombonganController::class, 'searchSantri'])->name('rombongan.search-santri');
});
