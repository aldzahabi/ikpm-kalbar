# IKPM - Sistem Informasi Manajemen Santri

Sistem informasi untuk mengelola data santri, ustad, dan alumni Pondok Modern Darussalam Gontor.

## 📋 Fitur Utama

### 1. Manajemen Santri
- CRUD data santri (tambah, lihat, edit, hapus)
- Filter berdasarkan status (santri, ustad, alumni)
- Filter berdasarkan kelas (1-6, 3 Intensif, Lulus) atau tahun untuk ustad
- Filter berdasarkan pondok cabang (Gontor 1-10+)
- Import data santri dari Excel
- Export data ke Excel
- Pencarian santri berdasarkan nama/stambuk

### 2. Sinkronisasi Otomatis Santri → User
- **Auto-create user**: Ketika santri berubah status menjadi ustad, sistem otomatis membuat akun user dengan:
  - Email: `[stambuk]@ikpm.local`
  - Password default: nomor stambuk
  - Role: Ustad
- **Auto-assign pondok cabang**: User ustad otomatis mendapat akses ke pondok cabang sesuai data santri
- **Auto-deactivate**: Ketika santri menjadi alumni, user otomatis dinonaktifkan (kecuali direksi)

### 3. Role-Based Access Control (RBAC)
- **Admin**: Akses penuh ke seluruh sistem
- **Ustad**: Hanya dapat mengelola santri di pondok cabang yang ditugaskan
- **Panitia**: Akses terbatas untuk kegiatan tertentu

### 4. Dashboard & Statistik
- Total santri, ustad, alumni
- Statistik per pondok cabang
- Statistik per kelas
- Grafik pertumbuhan santri

### 5. Activity Log
- Pencatatan semua aktivitas CRUD
- Tracking perubahan data santri
- Log pembuatan dan deaktivasi user

## 🛠️ Tech Stack

- **Framework**: Laravel 12
- **PHP**: 8.2+
- **Database**: MySQL/MariaDB
- **Frontend**: Blade + Alpine.js + Tailwind CSS
- **Server**: aaPanel (Nginx)

## 📁 Struktur Direktori

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── SantriController.php      # CRUD santri
│   │   ├── UserController.php        # Manajemen user
│   │   ├── DashboardController.php   # Dashboard statistik
│   │   └── AuthController.php        # Autentikasi
│   └── Middleware/
│       └── RoleMiddleware.php        # Cek akses role
├── Models/
│   ├── Santri.php                    # Model santri (custom PK: stambuk)
│   ├── User.php                      # Model user dengan pondok cabang
│   └── Role.php                      # Model role
├── Observers/
│   └── SantriObserver.php            # Auto-sync santri → user
├── Helpers/
│   └── ActivityLogHelper.php         # Logging aktivitas
└── Services/
    └── DashboardStatsCache.php       # Cache statistik dashboard
```

## ⚙️ Instalasi

### 1. Clone Repository
```bash
git clone https://github.com/username/ikpm.bereskan.com.git
cd ikpm.bereskan.com
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Konfigurasi Database
Edit file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ikpm_db
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 5. Migrasi Database
```bash
php artisan migrate
php artisan db:seed
```

### 6. Build Assets
```bash
npm run build
```

### 7. Jalankan Server
```bash
php artisan serve
```

Akses aplikasi di `http://localhost:8000`

## 📚 API Endpoints

### Autentikasi
| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/login` | Halaman login |
| POST | `/login` | Proses login |
| POST | `/logout` | Logout |

### Santri
| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/santri` | Daftar santri |
| GET | `/santri/create` | Form tambah santri |
| POST | `/santri` | Simpan santri baru |
| GET | `/santri/{stambuk}` | Detail santri |
| GET | `/santri/{stambuk}/edit` | Form edit santri |
| PUT | `/santri/{stambuk}` | Update santri |
| DELETE | `/santri/{stambuk}` | Hapus santri |
| POST | `/santri/import` | Import dari Excel |
| GET | `/santri/export` | Export ke Excel |

### Dashboard
| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/dashboard` | Dashboard utama |
| GET | `/dashboard/stats` | API statistik |

## 🔐 Model Santri

```php
// Primary Key: stambuk (string, bukan auto-increment)
protected $primaryKey = 'stambuk';
public $incrementing = false;
protected $keyType = 'string';

// Status santri
const STATUS_SANTRI = 'santri';
const STATUS_USTAD = 'ustad';
const STATUS_ALUMNI = 'alumni';

// Fillable fields
protected $fillable = [
    'stambuk',
    'nama',
    'nik',
    'tempat_lahir',
    'tgl_lahir',
    'jenis_kelamin',
    'alamat',
    'nama_ayah',
    'nama_ibu',
    'no_hp',
    'email',
    'status',
    'kelas',
    'pondok_cabang',
    'foto',
    'tahun_masuk',
];
```

## 🔄 Observer Pattern

### SantriObserver

Observer ini menangani sinkronisasi otomatis antara data santri dengan manajemen user:

```php
// Ketika santri baru dibuat dengan status ustad
public function created(Santri $santri): void
{
    if ($santri->status === Santri::STATUS_USTAD) {
        $this->createOrActivateUserForUstad($santri);
    }
}

// Ketika status santri berubah
public function updated(Santri $santri): void
{
    if ($santri->wasChanged('status')) {
        $this->handleStatusChange($santri);
    }
}
```

## 🎨 Dynamic Filter (Alpine.js)

Filter pada halaman index santri menggunakan Alpine.js untuk switching dinamis antara dropdown kelas dan tahun:

```html
<div x-data="{ status: '{{ request('status') }}' }">
    <!-- Status dropdown -->
    <select name="status" x-model="status">...</select>

    <!-- Kelas dropdown (shown when status != ustad) -->
    <select name="kelas" x-show="status !== 'ustad'">...</select>

    <!-- Tahun dropdown (shown when status == ustad) -->
    <select name="kelas" x-show="status === 'ustad'" x-cloak>...</select>
</div>
```

## 📝 Catatan Penting

1. **Primary Key Santri**: Menggunakan `stambuk` sebagai primary key (string), bukan auto-increment
2. **Email User Ustad**: Format `[stambuk]@ikpm.local`
3. **Password Default**: Sama dengan nomor stambuk
4. **Pondok Cabang**: Stored di pivot table `user_pondok_cabang`
5. **Cache Dashboard**: Menggunakan `DashboardStatsCache` untuk performa

## 🤝 Kontribusi

1. Fork repository
2. Buat branch fitur (`git checkout -b fitur-baru`)
3. Commit perubahan (`git commit -m 'Menambah fitur baru'`)
4. Push ke branch (`git push origin fitur-baru`)
5. Buat Pull Request

## 📄 Lisensi

Aplikasi ini dikembangkan untuk IKPM Kalbar. Hak cipta dilindungi.

---

Dikembangkan dengan ❤️ untuk IKPM Kalbar
