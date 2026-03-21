<!-- Filter Section -->
<div class="bg-gray-50 rounded-lg p-3 sm:p-4 mb-4 sm:mb-6">
    <form action="{{ route('keuangan.index', ['tab' => $activeTab]) }}" method="GET" class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-2 sm:gap-3 lg:gap-4">
        <input type="hidden" name="tab" value="{{ $activeTab }}">
        <div>
            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Kategori</label>
            <select name="category_id" class="w-full px-2.5 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-200 focus:border-brand-primary transition-colors text-gray-800 text-xs sm:text-sm">
                <option value="">Semua</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Tipe</label>
            <select name="type" class="w-full px-2.5 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-200 focus:border-brand-primary transition-colors text-gray-800 text-xs sm:text-sm">
                <option value="">Semua</option>
                <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>Pemasukan</option>
                <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>Pengeluaran</option>
            </select>
        </div>
        <div>
            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Dari</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-2.5 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-200 focus:border-brand-primary transition-colors text-gray-800 text-xs sm:text-sm">
        </div>
        <div>
            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Sampai</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-2.5 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-200 focus:border-brand-primary transition-colors text-gray-800 text-xs sm:text-sm">
        </div>
        <div class="col-span-2 md:col-span-4 flex items-center space-x-2 sm:space-x-3 pt-1">
            <button type="submit" class="flex-1 sm:flex-none px-4 sm:px-6 py-2 bg-brand-primary text-white rounded-lg hover:bg-green-700 transition-colors text-xs sm:text-sm font-medium">
                Filter
            </button>
            <a href="{{ route('keuangan.index', ['tab' => $activeTab]) }}" class="flex-1 sm:flex-none px-4 sm:px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-xs sm:text-sm font-medium text-center">
                Reset
            </a>
        </div>
    </form>
</div>

<!-- Mobile Card View -->
<div class="block sm:hidden space-y-3">
    @forelse($transactions as $transaction)
        <div class="bg-white border border-gray-200 rounded-lg p-3 shadow-sm">
            <div class="flex items-start justify-between mb-2">
                <div>
                    <span class="text-xs text-gray-500">{{ $transaction->transaction_date->format('d/m/Y') }}</span>
                    <span class="mx-1 text-gray-300">•</span>
                    <span class="text-xs text-gray-500">{{ $transaction->account->name }}</span>
                </div>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $transaction->category->type == 'income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $transaction->category->name }}
                </span>
            </div>
            <p class="text-sm text-gray-800 mb-2 line-clamp-2">{{ $transaction->description }}</p>
            @if($transaction->reference_id)
                <p class="text-xs text-gray-500 mb-2">Ref: {{ $transaction->reference_id }}</p>
            @endif
            <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                <span class="text-base font-bold {{ $transaction->category->type == 'income' ? 'text-emerald-600' : 'text-red-600' }}">
                    {{ $transaction->category->type == 'income' ? '+' : '-' }}Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                </span>
                @can('canManageFinance')
                <div class="flex items-center space-x-3">
                    <a href="{{ route('keuangan.edit', $transaction) }}" class="p-1.5 text-amber-600 hover:text-amber-800 hover:bg-amber-50 rounded-lg" title="Edit">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </a>
                    <form action="{{ route('keuangan.destroy', $transaction) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-1.5 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg" title="Hapus">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                </div>
                @endcan
            </div>
            <p class="text-xs text-gray-400 mt-2">Oleh: {{ $transaction->user->name }}</p>
        </div>
    @empty
        <div class="text-center py-8 text-gray-500">
            <svg class="w-10 h-10 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p class="text-sm">Tidak ada transaksi ditemukan.</p>
        </div>
    @endforelse
</div>

<!-- Desktop Table View -->
<div class="hidden sm:block overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-brand-bg">
            <tr>
                <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-brand-primary uppercase tracking-wider">Tanggal</th>
                <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-brand-primary uppercase tracking-wider">Akun</th>
                <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-brand-primary uppercase tracking-wider">Kategori</th>
                <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-brand-primary uppercase tracking-wider">Keterangan</th>
                <th class="px-4 lg:px-6 py-3 text-right text-xs font-medium text-brand-primary uppercase tracking-wider">Nominal</th>
                <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-brand-primary uppercase tracking-wider hidden lg:table-cell">Input By</th>
                <th class="px-4 lg:px-6 py-3 text-center text-xs font-medium text-brand-primary uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-100">
            @forelse($transactions as $transaction)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-xs lg:text-sm text-gray-800">
                        {{ $transaction->transaction_date->format('d/m/Y') }}
                    </td>
                    <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-xs lg:text-sm text-gray-800">
                        {{ $transaction->account->name }}
                    </td>
                    <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $transaction->category->type == 'income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $transaction->category->name }}
                        </span>
                    </td>
                    <td class="px-4 lg:px-6 py-3 lg:py-4 text-xs lg:text-sm text-gray-800 max-w-[200px]">
                        <div class="truncate">{{ Str::limit($transaction->description, 40) }}</div>
                        @if($transaction->reference_id)
                            <div class="text-xs text-gray-500">Ref: {{ $transaction->reference_id }}</div>
                        @endif
                    </td>
                    <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-xs lg:text-sm font-semibold text-right {{ $transaction->category->type == 'income' ? 'text-emerald-600' : 'text-red-600' }}">
                        {{ $transaction->category->type == 'income' ? '+' : '-' }}Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                    </td>
                    <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-xs lg:text-sm text-gray-600 hidden lg:table-cell">
                        {{ $transaction->user->name }}
                    </td>
                    <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-center text-sm">
                        @can('canManageFinance')
                        <div class="flex items-center justify-center space-x-1.5 lg:space-x-2">
                            <a href="{{ route('keuangan.edit', $transaction) }}" class="p-1.5 text-amber-600 hover:text-amber-800 hover:bg-amber-50 rounded-lg" title="Edit">
                                <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            <form action="{{ route('keuangan.destroy', $transaction) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg" title="Hapus">
                                    <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                        @else
                        <span class="text-xs text-gray-400">-</span>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                        <svg class="w-10 h-10 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="text-sm">Tidak ada transaksi ditemukan.</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if($transactions->hasPages())
    <div class="px-3 sm:px-6 py-3 sm:py-4 border-t border-gray-100">
        {{ $transactions->links() }}
    </div>
@endif
