<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FinanceSeeder extends Seeder
{
    public function run(): void
    {
        // Seed Finance Accounts (skip jika sudah ada)
        $accounts = [
            [
                'name' => 'Kas Operasional IKPM',
                'description' => 'Kas utama untuk operasional harian IKPM Gontor Pontianak',
                'current_balance' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kas Perpulangan',
                'description' => 'Kas khusus untuk manajemen perpulangan santri',
                'current_balance' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kas Forbis',
                'description' => 'Kas untuk kegiatan Forum Bisnis dan Usaha',
                'current_balance' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($accounts as $account) {
            DB::table('finance_accounts')->updateOrInsert(
                ['name' => $account['name']],
                $account
            );
        }

        // Seed Finance Categories
        $categories = [
            // Income Categories
            ['name' => 'Iuran Anggota', 'type' => 'income', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tiket Santri', 'type' => 'income', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hasil Usaha', 'type' => 'income', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Donasi', 'type' => 'income', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lain-lain (Pemasukan)', 'type' => 'income', 'created_at' => now(), 'updated_at' => now()],
            
            // Expense Categories
            ['name' => 'Sewa Bus', 'type' => 'expense', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Operasional', 'type' => 'expense', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kegiatan', 'type' => 'expense', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Gaji & Honor', 'type' => 'expense', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lain-lain (Pengeluaran)', 'type' => 'expense', 'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($categories as $category) {
            DB::table('finance_categories')->updateOrInsert(
                ['name' => $category['name'], 'type' => $category['type']],
                $category
            );
        }
    }
}
