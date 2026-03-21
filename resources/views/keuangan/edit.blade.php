@extends('layouts.app')

@section('title', 'Edit Transaksi - IKPM Gontor Pontianak')
@section('page-title', 'Edit Transaksi')
@section('page-subtitle', 'Ubah data transaksi keuangan')

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-green-100 p-6">
        <form action="{{ route('keuangan.update', $transaction) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Akun -->
                <div>
                    <label for="finance_account_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih Akun <span class="text-red-500">*</span>
                    </label>
                    <select name="finance_account_id" id="finance_account_id" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-200 focus:border-brand-primary transition-colors text-gray-800">
                        <option value="">-- Pilih Akun --</option>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}" {{ old('finance_account_id', $transaction->finance_account_id) == $account->id ? 'selected' : '' }}>
                                {{ $account->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('finance_account_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kategori -->
                <div>
                    <label for="finance_category_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <select name="finance_category_id" id="finance_category_id" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-200 focus:border-brand-primary transition-colors text-gray-800">
                        <option value="">-- Pilih Kategori --</option>
                        <optgroup label="Pemasukan">
                            @foreach($categories->where('type', 'income') as $category)
                                <option value="{{ $category->id }}" {{ old('finance_category_id', $transaction->finance_category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Pengeluaran">
                            @foreach($categories->where('type', 'expense') as $category)
                                <option value="{{ $category->id }}" {{ old('finance_category_id', $transaction->finance_category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </optgroup>
                    </select>
                    @error('finance_category_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nominal -->
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                        Nominal (Rp) <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="number" 
                        name="amount" 
                        id="amount" 
                        step="0.01" 
                        min="0.01" 
                        value="{{ old('amount', $transaction->amount) }}" 
                        required 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-200 focus:border-brand-primary transition-colors text-gray-800"
                    >
                    @error('amount')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Transaksi -->
                <div>
                    <label for="transaction_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Transaksi <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="date" 
                        name="transaction_date" 
                        id="transaction_date" 
                        value="{{ old('transaction_date', $transaction->transaction_date->format('Y-m-d')) }}" 
                        required 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-200 focus:border-brand-primary transition-colors text-gray-800"
                    >
                    @error('transaction_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Reference ID -->
                <div>
                    <label for="reference_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Reference ID (Opsional)
                    </label>
                    <input 
                        type="text" 
                        name="reference_id" 
                        id="reference_id" 
                        value="{{ old('reference_id', $transaction->reference_id) }}" 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-200 focus:border-brand-primary transition-colors text-gray-800"
                    >
                    @error('reference_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Keterangan -->
            <div class="mt-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Keterangan
                </label>
                <textarea 
                    name="description" 
                    id="description" 
                    rows="4" 
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-200 focus:border-brand-primary transition-colors text-gray-800"
                >{{ old('description', $transaction->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tombol -->
            <div class="mt-8 flex items-center justify-end space-x-4">
                <a href="{{ route('keuangan.index') }}" class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-brand-primary text-white rounded-lg hover:bg-green-700 transition-colors focus:outline-none focus:ring-2 focus:ring-green-200 focus:ring-offset-2">
                    Update Transaksi
                </button>
            </div>
        </form>
    </div>
@endsection
