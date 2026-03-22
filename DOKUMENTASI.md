# Dokumentasi Project IKPM Gontor Pontianak

**IKPM Gontor Pontianak** = Ikatan Keluarga Pondok Modern Gontor - Cabang Pontianak  
Sistem SaaS Management untuk Data Santri, Alumni, dan Logistik Perpulangan.

---

## 1. Informasi Umum

| Item | Nilai |
|------|-------|
| Framework | Laravel 12 |
| PHP | 8.2+ |
| Database | MySQL |
| Frontend | Blade Templates + Tailwind CSS (CDN) + Alpine.js (CDN) |
| Theme | Islamic Pastel Green |
| Primary Key | Auto-increment (ID) untuk sebagian besar tabel, String (stambuk) untuk Santri |
| Chart Library | Chart.js (CDN) |
| PDF Export | barryvdh/laravel-dompdf |
| Excel Import/Export | maatwebsite/excel |

---

## 2. Tech Stack

```json
{
  "php": "^8.2",
  "laravel/framework": "^12.0",
  "barryvdh/laravel-dompdf": "^3.0",
  "maatwebsite/excel": "^3.1"
}
```

**Tidak menggunakan Node.js/NPM untuk production** — Tailwind CSS, Alpine.js, dan Chart.js via CDN.

---

## 3. Design System

### Color Palette (Islamic Pastel Theme)

```css
brand-bg: #f0fdf4      /* Background utama - Hijau sangat muda */
brand-primary: #15803d /* Warna utama - Hijau tua */
brand-accent: #d97706  /* Aksen - Amber/Orange */
```

### Typography

- **Font Family**: Poppins (Google Fonts)
- **Text Color**: Dark slate/charcoal (bukan hitam pekat)

### UI Guidelines

- Desain clean dan minimalis
- Tidak ada dark mode
- Border radius: rounded-lg atau rounded-xl
- Shadow: shadow-sm atau shadow-lg
- Semua form memiliki CSRF protection

---

## 4. Struktur Database

### Tabel Utama

| Tabel | Deskripsi | Primary Key |
|-------|-----------|-------------|
| users | Pengguna sistem | id |
| roles | Role pengguna | id |
| santris | Data santri/alumni | stambuk (5 digit) |
| rombongans | Data rombongan perpulangan | id |
| rombongan_santri | Pivot table santri-rombongan | composite |
| finance_accounts | Akun keuangan (multi-account) | id |
| finance_categories | Kategori transaksi | id |
| finance_transactions | Transaksi keuangan | id |
| landing_contents | Konten halaman landing page | id |
| activity_logs | Log aktivitas sistem | id |

### Skema Detail

#### users
```sql
- id (bigint, auto-increment)
- name (string)
- email (string, unique)
- password (string, hashed)
- role_id (FK -> roles)
- no_hp (string, nullable)
- is_active (boolean, default: true)
- timestamps
```

#### roles
```sql
- id (bigint, auto-increment)
- name (string) -- "Administrator", "Ustad Pembimbing", "Panitia"
- slug (string) -- "admin", "ustad", "panitia"
- timestamps
```

#### santris
```sql
- stambuk (string(5), PRIMARY KEY) -- contoh: "00001"
- nik (string(16), nullable) -- NIK KTP
- tempat_lahir (string, nullable)
- tanggal_lahir (date, nullable)
- nama_ayah (string, nullable)
- nama (string)
- provinsi (string)
- daerah (string) -- Kabupaten/Kota
- alamat (text, nullable)
- status: `santri` | `ustad` | `alumni` (string; `alumnus` lama digabung ke `alumni`)
- `ustad_mulai_tahun`: tahun mulai status ustad; `kelas` = tahun ke (otomatis per tahun kalender)
- kelas (string, nullable) -- '1', '2', '3', '3Int', '4', '5', '6'
- kenaikan_kelas ENUM('naik', 'tidak_naik', 'lulus', 'baru', null)
- user_id (FK -> users, nullable) -- Siapa yang input
- timestamps
```

#### rombongans
```sql
- id (bigint, auto-increment)
- nama (string)
- moda_transportasi ENUM('Bus', 'Pesawat', 'Kapal')
- waktu_berangkat (datetime)
- titik_kumpul (string, nullable)
- kapasitas (integer)
- biaya_per_orang (decimal(12,2))
- status ENUM('open', 'closed', 'departed')
- timestamps
```

#### rombongan_santri (Pivot Table)
```sql
- id (bigint, auto-increment)
- rombongan_id (FK -> rombongans)
- santri_stambuk (FK -> santris.stambuk)
- nomor_kursi (string, nullable)
- status_pembayaran ENUM('lunas', 'belum_lunas')
- catatan (text, nullable)
- timestamps
- UNIQUE(rombongan_id, nomor_kursi) -- Kursi unik per rombongan
```

#### finance_accounts
```sql
- id (bigint, auto-increment)
- name (string) -- "Kas Operasional IKPM", "Kas Perpulangan", "Kas Forbis"
- description (text, nullable)
- current_balance (decimal(15,2), default: 0) -- Auto-update via Observer
- timestamps
```

#### finance_categories
```sql
- id (bigint, auto-increment)
- name (string) -- "Iuran Anggota", "Tiket Santri", "Sewa Bus", dll
- type ENUM('income', 'expense')
- timestamps
```

#### finance_transactions
```sql
- id (bigint, auto-increment)
- finance_account_id (FK -> finance_accounts)
- finance_category_id (FK -> finance_categories)
- amount (decimal(15,2))
- transaction_date (date)
- description (text, nullable)
- reference_id (string, nullable) -- Untuk relasi, misal: "ROMBONGAN_1_SANTRI_00001"
- user_id (FK -> users) -- Siapa yang input
- timestamps
```

#### activity_logs
```sql
- id (bigint, auto-increment)
- user_id (FK -> users, nullable)
- action (string) -- 'create', 'update', 'delete'
- model_type (string) -- 'App\Models\Santri', dll
- model_id (string, nullable)
- description (text, nullable)
- ip_address (string, nullable)
- timestamps
```

---

## 5. Arsitektur & Pola Desain

### Role-Based Access Control (RBAC)

Didefinisikan di app/Providers/AuthServiceProvider.php:

```php
// Gate Definitions
Gate::define('isAdmin', fn(\$user) => \$user->role?->slug === 'admin');

Gate::define('isBendahara', fn(\$user) => 
    in_array(\$user->role?->slug, ['admin', 'ustad']));

Gate::define('isPanitia', fn(\$user) => 
    in_array(\$user->role?->slug, ['admin', 'panitia']));

Gate::define('canManageUsers', fn(\$user) => 
    \$user->role?->slug === 'admin');

Gate::define('canManageFinance', fn(\$user) => 
    in_array(\$user->role?->slug, ['admin', 'ustad']));

Gate::define('canManageRombongan', fn(\$user) => 
    in_array(\$user->role?->slug, ['admin', 'panitia']));
```

### Penggunaan Gate di Controller

```php
public function create()
{
    \$this->authorize('canManageRombongan');
    return view('rombongan.create');
}
```

### Penggunaan Gate di Blade

```blade
@can('isAdmin')
    <button>Hapus Data</button>
@endcan
```

### Finance Transaction Observer

Otomatis update current_balance di finance_accounts saat transaksi dibuat/diupdate/dihapus:

```php
// app/Observers/FinanceTransactionObserver.php
public function created(FinanceTransaction \$transaction): void
{
    // Income: tambah saldo, Expense: kurangi saldo
    \$this->updateAccountBalance(\$transaction, 'add');
}

public function deleted(FinanceTransaction \$transaction): void
{
    \$this->updateAccountBalance(\$transaction, 'subtract');
}
```

### Integrasi Otomatis: Pembayaran Tiket → Transaksi Keuangan

Saat status pembayaran santri di rombongan diubah ke "lunas", otomatis membuat transaksi income ke "Kas Perpulangan":

```php
// Di RombonganController@updatePembayaran
if (\$request->status_pembayaran === 'lunas' && \$oldStatus !== 'lunas') {
    FinanceTransaction::create([
        'finance_account_id' => \$account->id,
        'finance_category_id' => \$category->id,
        'amount' => \$rombongan->biaya_per_orang,
        'transaction_date' => now()->toDateString(),
        'description' => "Pembayaran tiket perpulangan - {\$rombongan->nama}...",
        // ...
    ]);
}
```

---

## 6. Struktur File

```
ikpmpontianak.com/
├── app/
│   ├── Exports/
│   │   └── FinanceTransactionExport.php    # Export Excel keuangan
│   ├── Helpers/
│   │   └── ActivityLogHelper.php           # Helper untuk logging
│   ├── Http/
│   │   └── Controllers/
│   │       ├── AuthController.php          # Login/Logout
│   │       ├── Controller.php              # Base controller + AuthorizesRequests
│   │       ├── DashboardController.php     # Dashboard utama + statistik
│   │       ├── KeuanganController.php      # CRUD transaksi keuangan
│   │       ├── LandingContentController.php # CMS landing page
│   │       ├── PanitiaController.php       # Modul panitia
│   │       ├── ProfileController.php       # Edit profil user
│   │       ├── RombonganController.php     # CRUD rombongan + plot santri
│   │       ├── SantriController.php        # CRUD santri + import Excel
│   │       ├── SantriPromotionController.php # Mass promotion kelas
│   │       ├── SettingsController.php      # Pengaturan sistem
│   │       └── UserController.php          # Manajemen user
│   ├── Imports/
│   │   └── SantriImport.php                # Import santri dari Excel
│   ├── Models/
│   │   ├── ActivityLog.php
│   │   ├── FinanceAccount.php
│   │   ├── FinanceCategory.php
│   │   ├── FinanceTransaction.php
│   │   ├── LandingContent.php
│   │   ├── Role.php
│   │   ├── Rombongan.php
│   │   ├── Santri.php                      # PK: stambuk (string)
│   │   └── User.php
│   ├── Observers/
│   │   └── FinanceTransactionObserver.php  # Auto-update saldo akun
│   └── Providers/
│       ├── AppServiceProvider.php          # Register observer
│       └── AuthServiceProvider.php         # Define gates (RBAC)
├── database/
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 2026_01_27_000003_create_santris_table.php
│   │   ├── 2026_01_27_000004_create_roles_table.php
│   │   ├── 2026_01_27_052214_create_rombongans_table.php
│   │   ├── 2026_01_27_052215_create_rombongan_santri_table.php
│   │   ├── 2026_01_27_053322_add_biodata_to_santris_table.php
│   │   ├── 2026_01_27_053904_create_finance_accounts_table.php
│   │   ├── 2026_01_27_053905_create_finance_categories_table.php
│   │   ├── 2026_01_27_053906_create_finance_transactions_table.php
│   │   └── 2026_01_27_055644_create_activity_logs_table.php
│   └── seeders/
│       ├── DatabaseSeeder.php              # Main seeder (Users, Santri, dll)
│       └── FinanceSeeder.php               # Seed akun & kategori keuangan
├── resources/views/
│   ├── auth/
│   │   └── login.blade.php
│   ├── components/
│   │   └── sidebar.blade.php               # Sidebar navigation + @can directives
│   ├── dashboard.blade.php                 # Dashboard + Chart.js
│   ├── errors/
│   │   ├── 403.blade.php                   # Custom 403 page
│   │   └── 404.blade.php                   # Custom 404 page
│   ├── keuangan/
│   │   ├── create.blade.php
│   │   ├── edit.blade.php
│   │   ├── export-pdf.blade.php            # Template PDF export
│   │   ├── index.blade.php                 # Tab UI (Ringkasan, Kas IKPM, dll)
│   │   └── partials/
│   │       └── filter-table.blade.php      # Reusable table component
│   ├── landing-content/
│   │   ├── create.blade.php
│   │   ├── edit.blade.php
│   │   └── index.blade.php
│   ├── layouts/
│   │   └── app.blade.php                   # Main layout + sidebar + topbar
│   ├── profile/
│   │   └── edit.blade.php                  # Edit nama, no HP, password
│   ├── rombongan/
│   │   ├── create.blade.php
│   │   ├── edit.blade.php
│   │   ├── index.blade.php
│   │   ├── manifest-pdf.blade.php          # Template manifest PDF
│   │   └── show.blade.php                  # Detail + plot santri
│   ├── santri/
│   │   ├── create.blade.php
│   │   ├── edit.blade.php
│   │   └── index.blade.php                 # List + mass promotion + import
│   ├── settings/
│   │   └── index.blade.php
│   ├── users/
│   │   ├── create.blade.php
│   │   ├── edit.blade.php
│   │   └── index.blade.php
│   └── welcome.blade.php                   # Landing page publik
└── routes/
    └── web.php                             # Semua route definitions
```

---

## 7. Fitur yang Sudah Diimplementasi

### Authentication & Authorization
- [x] Login / Logout
- [x] Role-Based Access Control (Admin, Ustad, Panitia)
- [x] Gate definitions untuk setiap fitur sensitif
- [x] Custom error pages (403, 404)

### Dashboard
- [x] Statistik Cepat (Total Santri, Saldo Kas, Progress Rombongan)
- [x] Chart.js: Bar Chart (Sebaran Santri per Daerah)
- [x] Chart.js: Line Chart (Arus Kas Bulanan)
- [x] Tabel Aktivitas Terbaru
- [x] Tabel Santri Terbaru

### Data Santri
- [x] CRUD Santri dengan validasi
- [x] Import dari Excel (maatwebsite/excel)
- [x] Mass Promotion (naik kelas massal)
- [x] Filter dan pencarian
- [x] Biodata lengkap (NIK, tempat/tanggal lahir, nama ayah)

### Modul Perpulangan (Rombongan)
- [x] CRUD Rombongan (Bus, Pesawat, Kapal)
- [x] Plot santri ke rombongan dengan nomor kursi
- [x] Validasi kapasitas dan kursi unik
- [x] Status pembayaran (Lunas/Belum Lunas)
- [x] Export Manifest PDF (barryvdh/laravel-dompdf)
- [x] AJAX search untuk santri available
- [x] Auto-create transaksi keuangan saat pembayaran lunas

### Modul Keuangan (Multi-Account)
- [x] 3 Akun: Kas Operasional IKPM, Kas Perpulangan, Kas Forbis
- [x] Kategori Income & Expense
- [x] CRUD Transaksi
- [x] Auto-update saldo via Observer
- [x] Tab UI untuk filter per akun
- [x] Export Excel
- [x] Export PDF

### Manajemen User
- [x] CRUD User dengan role assignment
- [x] Aktivasi/Deaktivasi user
- [x] Edit profil sendiri (nama, no HP, password)

### Landing Page CMS
- [x] CRUD konten landing page
- [x] Modern landing page design

### Pengaturan
- [x] Halaman pengaturan sistem

---

## 8. Fitur yang Belum Selesai (TODO)

### Modul Data Santri
- [ ] Export santri ke Excel/PDF
- [ ] Bulk delete santri
- [ ] Riwayat perubahan data santri

### Modul Perpulangan
- [ ] Notifikasi reminder pembayaran
- [ ] Cetak kartu boarding pass
- [ ] Tracking lokasi rombongan (real-time)

### Modul Keuangan
- [ ] Laporan keuangan per periode
- [ ] Grafik tren keuangan
- [ ] Budget planning

### Modul Alumni
- [ ] Database alumni terpisah
- [ ] Direktori alumni
- [ ] Event alumni

### Fitur Umum
- [ ] Notifikasi sistem (bell notification)
- [ ] Activity log yang lebih detail
- [ ] Backup database otomatis
- [ ] API untuk mobile app
- [ ] Multi-language support

---

## 9. Setup Project

### Requirements
- PHP 8.2+
- MySQL 5.7+ atau MariaDB
- Composer
- Web server (Nginx/Apache)

### Instalasi

```bash
# Clone atau copy project
cd /path/to/ikpmpontianak.com

# Install dependencies
composer install

# Copy .env
cp .env.example .env

# Generate app key
php artisan key:generate

# Konfigurasi database di .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ikpmpontianak
DB_USERNAME=root
DB_PASSWORD=yourpassword

# Jalankan migrasi
php artisan migrate

# Seed data demo
php artisan db:seed

# Buat storage link
php artisan storage:link

# Set permission (Linux)
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Akun Demo

Setelah php artisan db:seed:

| Role | Email | Password |
|------|-------|----------|
| Super Admin | admin@ikpm.com | password123 |
| Bendahara (Ustad) | bendahara@ikpm.com | password123 |
| Ustad Pembimbing | ustadahmadfauzi@ikpm.com | password123 |

---

## 10. Routing

### Public Routes

| Route | Method | Deskripsi |
|-------|--------|-----------|
| / | GET | Landing page publik |
| /login | GET/POST | Halaman login |
| /logout | POST | Logout |

### Protected Routes (Semua User Login)

| Route | Method | Deskripsi |
|-------|--------|-----------|
| /dashboard | GET | Dashboard utama |
| /profile | GET | Edit profil |
| /profile | PUT | Update nama/no HP |
| /profile/password | PUT | Ganti password |
| /santri | GET | List santri |
| /santri/{stambuk} | GET | Detail santri |
| /keuangan | GET | Dashboard keuangan |
| /rombongan | GET | List rombongan |
| /rombongan/{id} | GET | Detail rombongan |
| /panitia | GET | Halaman panitia |

### Admin Only Routes

| Route | Method | Deskripsi |
|-------|--------|-----------|
| /santri/create | GET | Form tambah santri |
| /santri | POST | Simpan santri baru |
| /santri/{stambuk}/edit | GET | Form edit santri |
| /santri/{stambuk} | PUT | Update santri |
| /santri/{stambuk} | DELETE | Hapus santri |
| /santri/import | POST | Import Excel |
| /santri/promote | POST | Mass promotion |
| /users | Resource | Manajemen user |
| /settings | GET | Pengaturan |
| /landing-content | Resource | CMS landing page |

### Bendahara Routes (Admin + Ustad)

| Route | Method | Deskripsi |
|-------|--------|-----------|
| /keuangan/create | GET | Form transaksi baru |
| /keuangan | POST | Simpan transaksi |
| /keuangan/{id}/edit | GET | Form edit transaksi |
| /keuangan/{id} | PUT | Update transaksi |
| /keuangan/{id} | DELETE | Hapus transaksi |
| /keuangan/export/excel | GET | Export Excel |
| /keuangan/export/pdf | GET | Export PDF |

### Panitia Routes (Admin + Panitia)

| Route | Method | Deskripsi |
|-------|--------|-----------|
| /rombongan/create | GET | Form tambah rombongan |
| /rombongan | POST | Simpan rombongan |
| /rombongan/{id}/edit | GET | Form edit rombongan |
| /rombongan/{id} | PUT | Update rombongan |
| /rombongan/{id} | DELETE | Hapus rombongan |
| /rombongan/{id}/add-santri | POST | Tambah santri ke rombongan |
| /rombongan/{id}/remove-santri/{stambuk} | DELETE | Hapus santri dari rombongan |
| /rombongan/{id}/update-pembayaran/{stambuk} | PUT | Update status bayar |

---

## 11. Pola Kode Penting

### Model dengan Custom Primary Key (Santri)

```php
class Santri extends Model
{
    protected \$primaryKey = 'stambuk';
    protected \$keyType = 'string';
    public \$incrementing = false;

    // BelongsToMany dengan custom keys
    public function rombongans()
    {
        return \$this->belongsToMany(
            Rombongan::class, 
            'rombongan_santri', 
            'santri_stambuk',  // FK di pivot untuk model ini
            'rombongan_id',    // FK di pivot untuk related model
            'stambuk',         // PK di model ini
            'id'               // PK di related model
        )->withPivot('nomor_kursi', 'status_pembayaran')
         ->withTimestamps();
    }
}
```

### Controller dengan Authorization

```php
class RombonganController extends Controller
{
    public function create()
    {
        \$this->authorize('canManageRombongan');
        return view('rombongan.create');
    }

    public function store(Request \$request)
    {
        \$this->authorize('canManageRombongan');
        
        \$validated = \$request->validate([
            'nama' => 'required|string|max:255',
            // ...
        ]);

        Rombongan::create(\$validated);

        return redirect()->route('rombongan.index')
            ->with('success', 'Rombongan berhasil ditambahkan.');
    }
}
```

### Observer untuk Auto-Update Balance

```php
// app/Providers/AppServiceProvider.php
public function boot(): void
{
    FinanceTransaction::observe(FinanceTransactionObserver::class);
}

// app/Observers/FinanceTransactionObserver.php
public function created(FinanceTransaction \$transaction): void
{
    \$account = \$transaction->account;
    \$category = \$transaction->category;
    
    \$adjustment = \$category->type === 'income' 
        ? \$transaction->amount 
        : -\$transaction->amount;
    
    \$account->increment('current_balance', \$adjustment);
}
```

### Blade Component dengan @can Directive

```blade
{{-- resources/views/components/sidebar.blade.php --}}
<nav>
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <a href="{{ route('santri.index') }}">Data Santri</a>
    
    @can('isAdmin')
        <a href="{{ route('users.index') }}">Manajemen User</a>
        <a href="{{ route('settings.index') }}">Setting</a>
    @endcan
    
    @can('canManageFinance')
        <a href="{{ route('keuangan.create') }}">Input Transaksi</a>
    @endcan
</nav>
```

### Import Excel dengan Maatwebsite

```php
// app/Imports/SantriImport.php
class SantriImport implements ToModel, WithHeadingRow
{
    public function model(array \$row)
    {
        return new Santri([
            'stambuk' => \$row['stambuk'],
            'nama' => \$row['nama'],
            'provinsi' => \$row['provinsi'],
            'daerah' => \$row['daerah'],
            // ...
        ]);
    }
}

// Di Controller
Excel::import(new SantriImport, \$request->file('file'));
```

### Export PDF dengan DomPDF

```php
use Barryvdh\DomPDF\Facade\Pdf;

public function exportPdf()
{
    \$data = FinanceTransaction::with(['account', 'category'])->get();
    
    \$pdf = Pdf::loadView('keuangan.export-pdf', compact('data'))
        ->setPaper('a4', 'landscape');

    return \$pdf->download('laporan-keuangan.pdf');
}
```

---

## 12. Catatan Teknis

### Tailwind CSS via CDN

```html
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    'brand-bg': '#f0fdf4',
                    'brand-primary': '#15803d',
                    'brand-accent': '#d97706',
                },
                fontFamily: {
                    'sans': ['Poppins', 'sans-serif'],
                },
            }
        }
    }
</script>
```

### Alpine.js untuk Interaktivitas

```html
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<!-- Contoh Tab UI -->
<div x-data="{ activeTab: 'ringkasan' }">
    <button @click="activeTab = 'ringkasan'" 
            :class="{ 'bg-brand-primary text-white': activeTab === 'ringkasan' }">
        Ringkasan
    </button>
    
    <div x-show="activeTab === 'ringkasan'">
        <!-- Content -->
    </div>
</div>
```

### Chart.js untuk Visualisasi

```html
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<canvas id="myChart"></canvas>
<script>
    new Chart(document.getElementById('myChart'), {
        type: 'bar',
        data: {
            labels: ['Pontianak', 'Kubu Raya', ...],
            datasets: [{
                label: 'Jumlah Santri',
                data: [15, 12, ...],
                backgroundColor: 'rgba(21, 128, 61, 0.7)',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
        }
    });
</script>
```

### Session Flash Messages

```php
// Di Controller
return redirect()->route('santri.index')
    ->with('success', 'Santri berhasil ditambahkan.');
    
// Atau untuk error
return redirect()->back()
    ->with('error', 'Rombongan sudah penuh!');
```

```blade
{{-- Di Layout --}}
@if(session('success'))
    <div class="bg-green-50 text-green-800 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-50 text-red-800 px-4 py-3 rounded-lg">
        {{ session('error') }}
    </div>
@endif
```

---

## 13. Testing

```bash
# Jalankan semua test
php artisan test

# Jalankan test tertentu
php artisan test --filter=SantriTest

# Test dengan coverage
php artisan test --coverage
```

---

## 14. Deployment (aaPanel)

1. Upload file ke /www/wwwroot/ikpmpontianak.com
2. Set document root ke /www/wwwroot/ikpmpontianak.com/public
3. Konfigurasi .env dengan kredensial MySQL
4. Jalankan:
   ```bash
   php artisan migrate --force
   php artisan db:seed
   php artisan storage:link
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```
5. Set permission:
   ```bash
   chown -R www:www storage bootstrap/cache
   chmod -R 775 storage bootstrap/cache
   ```

---

## 15. Troubleshooting

### Error: "Call to undefined method authorize()"
Pastikan app/Http/Controllers/Controller.php menggunakan trait:
```php
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
```

### Error: "Gate 'isAdmin' not defined"
Pastikan AuthServiceProvider terdaftar di bootstrap/providers.php:
```php
return [
    App\Providers\AppServiceProvider::class,
    App\Providers\AuthServiceProvider::class,
];
```

### Error: "Duplicate entry for key 'unique_kursi_rombongan'"
Pastikan nomor kursi di-validasi sebelum attach:
```php
\$kursiTerpakai = DB::table('rombongan_santri')
    ->where('rombongan_id', \$rombongan->id)
    ->where('nomor_kursi', \$nomorKursi)
    ->exists();
```

### Clear Cache
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

---

## 16. Kontak & Kredit

Project ini dibuat menggunakan AI Coding Assistant (Claude/Cursor).

**Stack**: Laravel 12 + Tailwind CSS + Alpine.js + Chart.js

---

*Dokumentasi terakhir diperbarui: Januari 2026*
