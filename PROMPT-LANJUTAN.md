# Prompt Lanjutan untuk AI Coding Assistant

Gunakan prompt ini ketika melanjutkan pengembangan project IKPM Kalbar di AI coding assistant lain (Claude, Cursor, Copilot, dll).

---

## Context Prompt

```
Saya ingin melanjutkan pengembangan aplikasi IKPM Kalbar. Ini adalah SaaS Management System untuk manajemen data Santri, Alumni, dan Logistik Perpulangan.

## Tech Stack
- Laravel 12 (PHP 8.2+)
- MySQL
- Blade Templates
- Tailwind CSS via CDN (TANPA Node.js/NPM)
- Alpine.js via CDN
- Chart.js via CDN
- barryvdh/laravel-dompdf (PDF export)
- maatwebsite/excel (Excel import/export)

## Design System
- Theme: Islamic Pastel Green
- Colors: brand-bg (#f0fdf4), brand-primary (#15803d), brand-accent (#d97706)
- Font: Poppins (Google Fonts)
- Clean minimalist design, no dark mode

## Pola Coding
1. Semua view menggunakan Blade Templates (bukan Livewire/Inertia)
2. CRUD menggunakan standard Laravel Resource Controllers
3. Authorization menggunakan Gates (AuthServiceProvider) + @can directive di Blade
4. Multi-account finance dengan Observer untuk auto-update balance
5. Santri menggunakan custom primary key 'stambuk' (string 5 digit)
6. CSRF protection di semua form
7. Flash messages untuk feedback (session('success'), session('error'))

## Role System
- admin: Full access
- ustad: Access keuangan + view santri
- panitia: Access rombongan + view santri

## Gate Definitions
- isAdmin: hanya admin
- isBendahara: admin + ustad
- isPanitia: admin + panitia
- canManageUsers: admin only
- canManageFinance: admin + ustad
- canManageRombongan: admin + panitia

## Modul yang Sudah Ada
1. Authentication (Login/Logout)
2. Dashboard (Chart.js visualisasi)
3. Data Santri (CRUD + Import Excel + Mass Promotion)
4. Rombongan/Perpulangan (CRUD + Plot Santri + Export PDF Manifest)
5. Keuangan Multi-Account (CRUD + Observer + Tab UI + Export Excel/PDF)
6. Manajemen User (CRUD + Role assignment)
7. Profil User (Edit nama, no HP, password)
8. Landing Page CMS
9. Custom Error Pages (403, 404)

## Konvensi
- View files: resources/views/{module}/{action}.blade.php
- Controller methods return redirect dengan flash message
- Gunakan $this->authorize('gateName') di controller untuk authorization
- Blade @can('gateName') untuk conditional UI rendering

Silakan baca file DOKUMENTASI.md untuk informasi lengkap tentang database schema, routing, dan pola kode.
```

---

## Contoh Prompt untuk Task Spesifik

### Menambah Fitur Baru

```
Tolong buatkan fitur [NAMA FITUR] untuk project IKPM Kalbar.

Spesifikasi:
- [Deskripsi fitur]
- [Database schema jika ada]
- [Route yang dibutuhkan]
- [Role yang bisa akses]

Pastikan:
1. Gunakan Blade template (bukan Livewire)
2. Terapkan Gates untuk authorization
3. Ikuti design system (Islamic Pastel Green theme)
4. CSRF protection di semua form
```

### Memperbaiki Bug

```
Ada bug di project IKPM Kalbar:

[Deskripsi bug]

File terkait:
- [path/to/file]

Langkah reproduksi:
1. [step 1]
2. [step 2]

Expected behavior: [apa yang seharusnya terjadi]
Actual behavior: [apa yang terjadi]

Tolong perbaiki bug ini.
```

### Refactoring

```
Tolong refactor [bagian yang ingin di-refactor] di project IKPM Kalbar.

Alasan refactor:
- [alasan 1]
- [alasan 2]

Pastikan tidak mengubah behavior yang sudah ada dan ikuti pola coding yang sudah diterapkan (Gates, Blade, dll).
```

---

## Quick Reference Commands

```bash
# Clear all cache
php artisan config:clear && php artisan route:clear && php artisan view:clear && php artisan cache:clear

# Fresh migrate + seed
php artisan migrate:fresh --seed

# Run seeder saja
php artisan db:seed

# Create migration
php artisan make:migration create_xxx_table

# Create model with migration
php artisan make:model ModelName -m

# Create controller
php artisan make:controller NameController

# Create resource controller
php artisan make:controller NameController --resource

# Route list
php artisan route:list

# Tinker (interactive shell)
php artisan tinker
```

---

## Database Quick Info

| Model | Table | Primary Key | Notes |
|-------|-------|-------------|-------|
| User | users | id | Has role_id FK |
| Role | roles | id | slug: admin, ustad, panitia |
| Santri | santris | stambuk (string) | Custom PK, not auto-increment |
| Rombongan | rombongans | id | |
| FinanceAccount | finance_accounts | id | 3 accounts: Kas IKPM, Perpulangan, Forbis |
| FinanceCategory | finance_categories | id | type: income/expense |
| FinanceTransaction | finance_transactions | id | Observer auto-updates account balance |

---

*File ini dibuat untuk memudahkan handoff ke AI assistant lain.*
