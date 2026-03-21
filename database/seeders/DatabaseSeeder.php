<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Santri;
use App\Models\Rombongan;
use App\Models\FinanceAccount;
use App\Models\FinanceCategory;
use App\Models\FinanceTransaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // 1. Seed Roles (pastikan sudah ada)
        $this->command->info('Checking roles...');
        $adminRole = Role::where('slug', 'admin')->first();
        $ustadRole = Role::where('slug', 'ustad')->first();
        $panitiaRole = Role::where('slug', 'panitia')->first();

        if (!$adminRole || !$ustadRole || !$panitiaRole) {
            $this->command->warn('Roles belum ada. Pastikan migration roles sudah dijalankan.');
            return;
        }

        // 2. Seed Finance Accounts & Categories (skip jika sudah ada)
        $this->command->info('Seeding Finance Accounts & Categories...');
        $this->call(FinanceSeeder::class); // FinanceSeeder sudah handle updateOrInsert

        // 3. Seed Users (skip jika sudah ada)
        $this->command->info('Seeding Users...');
        
        // Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@ikpm.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'),
                'role_id' => $adminRole->id,
                'no_hp' => '081234567890',
                'is_active' => true,
            ]
        );

        // Bendahara (Ustad)
        $bendahara = User::firstOrCreate(
            ['email' => 'bendahara@ikpm.com'],
            [
                'name' => 'Ustad Bendahara',
                'password' => Hash::make('password123'),
                'role_id' => $ustadRole->id,
                'no_hp' => '081234567891',
                'is_active' => true,
            ]
        );

        // 3 Ustad Pembimbing
        $ustadNames = ['Ustad Ahmad Fauzi', 'Ustad Muhammad Rizki', 'Ustad Abdul Rahman'];
        $ustadUsers = [];
        foreach ($ustadNames as $index => $name) {
            $email = strtolower(str_replace(' ', '', $name)) . '@ikpm.com';
            $ustadUsers[] = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => Hash::make('password123'),
                    'role_id' => $ustadRole->id,
                    'no_hp' => '08123456789' . (2 + $index),
                    'is_active' => true,
                ]
            );
        }

        // 4. Seed Santri (50 data) - skip jika sudah ada
        $this->command->info('Seeding Santri (50 data)...');
        
        if (Santri::count() >= 50) {
            $this->command->info('Santri sudah ada, skipping...');
        } else {
            $daerahKalbar = [
                'Pontianak', 'Kubu Raya', 'Mempawah', 'Sambas', 'Bengkayang',
                'Landak', 'Sanggau', 'Sekadau', 'Sintang', 'Kapuas Hulu',
                'Melawi', 'Ketapang', 'Kayong Utara', 'Sukadana'
            ];

            $kelasOptions = ['1', '2', '3', '3Int', '4', '5', '6'];
            
            $santriData = [];
            $existingStambuks = Santri::pluck('stambuk')->toArray();
            $existingNiks = Santri::whereNotNull('nik')->pluck('nik')->toArray();
            
            for ($i = 1; $i <= 50; $i++) {
                $stambuk = str_pad($i, 5, '0', STR_PAD_LEFT);
                
                // Skip jika stambuk sudah ada
                if (in_array($stambuk, $existingStambuks)) {
                    continue;
                }
                
                $status = $i <= 40 ? 'santri' : ($i <= 45 ? 'alumni' : 'alumnus');
                $kelas = $status === 'santri' ? $faker->randomElement($kelasOptions) : null;
                
                // Generate unique NIK
                do {
                    $nik = $faker->numerify('################');
                } while (in_array($nik, $existingNiks));
                $existingNiks[] = $nik;
                
                $santriData[] = [
                    'stambuk' => $stambuk,
                    'nik' => $nik,
                    'tempat_lahir' => $faker->city(),
                    'tanggal_lahir' => $faker->date('Y-m-d', '-10 years'),
                    'nama_ayah' => $faker->name('male'),
                    'nama' => $faker->name(),
                    'provinsi' => 'Kalimantan Barat',
                    'daerah' => $faker->randomElement($daerahKalbar),
                    'alamat' => $faker->address(),
                    'status' => $status,
                    'kelas' => $kelas,
                    'kenaikan_kelas' => $kelas ? ($faker->randomElement(['naik', 'tidak_naik', null])) : null,
                    'user_id' => $faker->randomElement($ustadUsers)->id,
                    'created_at' => now()->subDays(rand(1, 365)),
                    'updated_at' => now()->subDays(rand(1, 365)),
                ];
            }

            // Insert santri dalam batch
            if (!empty($santriData)) {
                foreach (array_chunk($santriData, 10) as $chunk) {
                    Santri::insert($chunk);
                }
            }
        }

        // 5. Seed Rombongan (2 Bus) - skip jika sudah ada
        $this->command->info('Seeding Rombongan...');
        
        $rombongan1 = Rombongan::firstOrCreate(
            ['nama' => 'Rombongan Pontianak'],
            [
                'moda_transportasi' => 'Bus',
                'waktu_berangkat' => now()->addDays(30),
                'titik_kumpul' => 'Terminal Bus Pontianak',
                'kapasitas' => 50,
                'biaya_per_orang' => 150000,
                'status' => 'open',
            ]
        );

        $rombongan2 = Rombongan::firstOrCreate(
            ['nama' => 'Rombongan Kubu Raya'],
            [
                'moda_transportasi' => 'Bus',
                'waktu_berangkat' => now()->addDays(35),
                'titik_kumpul' => 'Terminal Bus Kubu Raya',
                'kapasitas' => 45,
                'biaya_per_orang' => 120000,
                'status' => 'open',
            ]
        );

        // 6. Seed Rombongan-Santri (Plot beberapa santri ke rombongan) - skip jika sudah ada
        $this->command->info('Seeding Rombongan-Santri...');
        
        $santriAktif = Santri::where('status', 'santri')->limit(30)->get();
        
        // Plot 20 santri ke rombongan 1 (skip jika sudah ter-attach)
        $rombongan1Santris = $rombongan1->santris()->pluck('stambuk')->toArray();
        
        foreach ($santriAktif->take(20) as $santri) {
            if (!in_array($santri->stambuk, $rombongan1Santris)) {
                // Ambil semua nomor kursi yang sudah terpakai dari database
                $rombongan1KursiTerpakai = DB::table('rombongan_santri')
                    ->where('rombongan_id', $rombongan1->id)
                    ->whereNotNull('nomor_kursi')
                    ->pluck('nomor_kursi')
                    ->toArray();
                
                // Cari nomor kursi yang belum terpakai
                $nomorKursi = null;
                for ($i = 1; $i <= 50; $i++) {
                    $kandidatKursi = str_pad($i, 2, '0', STR_PAD_LEFT);
                    if (!in_array($kandidatKursi, $rombongan1KursiTerpakai)) {
                        $nomorKursi = $kandidatKursi;
                        break;
                    }
                }
                
                if ($nomorKursi) {
                    try {
                        $rombongan1->santris()->attach($santri->stambuk, [
                            'nomor_kursi' => $nomorKursi,
                            'status_pembayaran' => $faker->randomElement(['belum_lunas', 'lunas']),
                            'catatan' => $faker->optional()->sentence(),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    } catch (\Exception $e) {
                        // Skip jika masih ada duplicate (race condition)
                        $this->command->warn("Skipping duplicate kursi for santri {$santri->stambuk}");
                    }
                }
            }
        }

        // Plot 15 santri ke rombongan 2 (skip jika sudah ter-attach)
        $rombongan2Santris = $rombongan2->santris()->pluck('stambuk')->toArray();
        
        foreach ($santriAktif->skip(20)->take(15) as $santri) {
            if (!in_array($santri->stambuk, $rombongan2Santris)) {
                // Ambil semua nomor kursi yang sudah terpakai dari database
                $rombongan2KursiTerpakai = DB::table('rombongan_santri')
                    ->where('rombongan_id', $rombongan2->id)
                    ->whereNotNull('nomor_kursi')
                    ->pluck('nomor_kursi')
                    ->toArray();
                
                // Cari nomor kursi yang belum terpakai
                $nomorKursi = null;
                for ($i = 1; $i <= 45; $i++) {
                    $kandidatKursi = str_pad($i, 2, '0', STR_PAD_LEFT);
                    if (!in_array($kandidatKursi, $rombongan2KursiTerpakai)) {
                        $nomorKursi = $kandidatKursi;
                        break;
                    }
                }
                
                if ($nomorKursi) {
                    try {
                        $rombongan2->santris()->attach($santri->stambuk, [
                            'nomor_kursi' => $nomorKursi,
                            'status_pembayaran' => $faker->randomElement(['belum_lunas', 'lunas']),
                            'catatan' => $faker->optional()->sentence(),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    } catch (\Exception $e) {
                        // Skip jika masih ada duplicate (race condition)
                        $this->command->warn("Skipping duplicate kursi for santri {$santri->stambuk}");
                    }
                }
            }
        }

        // 7. Seed Finance Transactions (20 transaksi) - skip jika sudah ada cukup banyak
        $this->command->info('Seeding Finance Transactions (20 transaksi)...');
        
        if (FinanceTransaction::count() >= 20) {
            $this->command->info('Finance Transactions sudah cukup, skipping...');
        } else {
            $accounts = FinanceAccount::all();
            $categories = FinanceCategory::all();
            $allUsers = User::all();

            if ($accounts->isEmpty() || $categories->isEmpty() || $allUsers->isEmpty()) {
                $this->command->warn('Finance Accounts, Categories, or Users tidak ditemukan. Pastikan seeder dijalankan dengan benar.');
                return;
            }

            $incomeCategories = $categories->where('type', 'income');
            $expenseCategories = $categories->where('type', 'expense');

            // Transaksi untuk 30 hari terakhir
            $transactionsToCreate = 20 - FinanceTransaction::count();
            for ($i = 0; $i < $transactionsToCreate; $i++) {
                $account = $faker->randomElement($accounts);
                $isIncome = $faker->boolean(60); // 60% income, 40% expense
                
                if ($isIncome) {
                    $category = $faker->randomElement($incomeCategories);
                    $amount = $faker->numberBetween(50000, 500000);
                } else {
                    $category = $faker->randomElement($expenseCategories);
                    $amount = $faker->numberBetween(30000, 300000);
                }

                FinanceTransaction::create([
                    'finance_account_id' => $account->id,
                    'finance_category_id' => $category->id,
                    'amount' => $amount,
                    'transaction_date' => $faker->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
                    'description' => $faker->sentence(),
                    'reference_id' => $faker->optional()->bothify('REF-####-####'),
                    'user_id' => $faker->randomElement($allUsers)->id,
                    'created_at' => $faker->dateTimeBetween('-30 days', 'now'),
                    'updated_at' => $faker->dateTimeBetween('-30 days', 'now'),
                ]);
            }
        }

        // Update account balances (Observer akan handle ini, tapi kita pastikan balance ter-update)
        $accounts = FinanceAccount::all();
        foreach ($accounts as $account) {
            $balance = FinanceTransaction::where('finance_account_id', $account->id)
                ->join('finance_categories', 'finance_transactions.finance_category_id', '=', 'finance_categories.id')
                ->selectRaw('SUM(CASE WHEN finance_categories.type = "income" THEN finance_transactions.amount ELSE -finance_transactions.amount END) as balance')
                ->value('balance') ?? 0;
            
            $account->update(['current_balance' => $balance]);
        }

        $this->command->info('Database seeding completed successfully!');
        $this->command->info('Login credentials:');
        $this->command->info('  - Super Admin: admin@ikpm.com / password123');
        $this->command->info('  - Bendahara: bendahara@ikpm.com / password123');
        $this->command->info('  - Ustad: [nama]@ikpm.com / password123');
    }
}
