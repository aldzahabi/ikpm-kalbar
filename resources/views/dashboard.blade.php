@extends('layouts.app')

@section('title', 'Dashboard - IKPM Gontor Pontianak')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Pusat Monitoring Real-time')


@section('content')
    <!-- Widget Statistik Cepat -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-6 sm:mb-8">
        <!-- Total Santri Aktif -->
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl shadow-lg p-4 sm:p-6 text-white hover:shadow-xl transition-shadow">
            <div class="flex items-start sm:items-center justify-between">
                <div class="min-w-0 flex-1">
                    <p class="text-emerald-100 text-xs sm:text-sm font-medium mb-1 truncate">Total Santri</p>
                    <p class="text-2xl sm:text-3xl lg:text-4xl font-bold">{{ number_format($totalSantri) }}</p>
                    <p class="text-emerald-100 text-xs mt-1 sm:mt-2 hidden sm:block">Santri aktif saat ini</p>
                </div>
                <div class="w-10 h-10 sm:w-14 sm:h-14 lg:w-16 lg:h-16 bg-white/20 rounded-lg sm:rounded-xl flex items-center justify-center backdrop-blur-sm flex-shrink-0 ml-2">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8 lg:w-10 lg:h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
        </div>
        
        <!-- Saldo Kas IKPM -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-4 sm:p-6 text-white hover:shadow-xl transition-shadow">
            <div class="flex items-start sm:items-center justify-between">
                <div class="min-w-0 flex-1">
                    <p class="text-blue-100 text-xs sm:text-sm font-medium mb-1 truncate">Saldo IKPM</p>
                    <p class="text-lg sm:text-2xl lg:text-3xl font-bold truncate">Rp {{ number_format($saldoKasIKPM / 1000, 0) }}K</p>
                    <p class="text-blue-100 text-xs mt-1 sm:mt-2 hidden sm:block">Kas Operasional</p>
                </div>
                <div class="w-10 h-10 sm:w-14 sm:h-14 lg:w-16 lg:h-16 bg-white/20 rounded-lg sm:rounded-xl flex items-center justify-center backdrop-blur-sm flex-shrink-0 ml-2">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8 lg:w-10 lg:h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
        
        <!-- Saldo Perpulangan -->
        <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl shadow-lg p-4 sm:p-6 text-white hover:shadow-xl transition-shadow">
            <div class="flex items-start sm:items-center justify-between">
                <div class="min-w-0 flex-1">
                    <p class="text-amber-100 text-xs sm:text-sm font-medium mb-1 truncate">Perpulangan</p>
                    <p class="text-lg sm:text-2xl lg:text-3xl font-bold truncate">Rp {{ number_format($saldoPerpulangan / 1000, 0) }}K</p>
                    <p class="text-amber-100 text-xs mt-1 sm:mt-2 hidden sm:block">Uang Umat/Tiket</p>
                </div>
                <div class="w-10 h-10 sm:w-14 sm:h-14 lg:w-16 lg:h-16 bg-white/20 rounded-lg sm:rounded-xl flex items-center justify-center backdrop-blur-sm flex-shrink-0 ml-2">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8 lg:w-10 lg:h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                </div>
            </div>
        </div>
        
        <!-- Progress Rombongan -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-4 sm:p-6 text-white hover:shadow-xl transition-shadow">
            <div>
                <p class="text-purple-100 text-xs sm:text-sm font-medium mb-1">Progress Bus</p>
                <p class="text-xl sm:text-2xl lg:text-3xl font-bold">{{ number_format($progressRombongan['terdaftar']) }}<span class="text-base sm:text-lg lg:text-xl">/{{ number_format($progressRombongan['total']) }}</span></p>
                <div class="mt-2 sm:mt-3">
                    <div class="w-full bg-white/20 rounded-full h-2">
                        <div class="bg-white h-2 rounded-full transition-all duration-300" style="width: {{ $progressRombongan['persentase'] }}%"></div>
                    </div>
                    <p class="text-purple-100 text-xs mt-1">{{ $progressRombongan['persentase'] }}% terdaftar</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Grafik Visual -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-6 sm:mb-8">
        <!-- Grafik Bar: Sebaran Santri per Daerah -->
        <div class="bg-white rounded-xl shadow-sm border border-green-100 p-4 sm:p-6">
            <h3 class="text-sm sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4">Sebaran Santri per Daerah</h3>
            <div class="relative h-[200px] sm:h-[250px] lg:h-[280px]">
                <canvas id="chartSebaranDaerah"></canvas>
            </div>
        </div>
        
        <!-- Grafik Line: Arus Kas Bulanan -->
        <div class="bg-white rounded-xl shadow-sm border border-green-100 p-4 sm:p-6">
            <h3 class="text-sm sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4">Arus Kas Bulanan</h3>
            <div class="relative h-[200px] sm:h-[250px] lg:h-[280px]">
                <canvas id="chartArusKas"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Tabel Aktivitas Terbaru & Santri Terbaru -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-6 sm:mb-8">
        <!-- Aktivitas Terbaru -->
        <div class="bg-white rounded-xl shadow-sm border border-green-100 overflow-hidden">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-100 bg-brand-bg">
                <h3 class="text-sm sm:text-lg font-semibold text-gray-800">Aktivitas Terbaru</h3>
                <p class="text-xs sm:text-sm text-gray-500 mt-0.5 sm:mt-1 hidden sm:block">Log aktivitas sistem terakhir</p>
            </div>
            <div class="p-3 sm:p-6 max-h-[300px] sm:max-h-[400px] overflow-y-auto">
                @forelse($aktivitasTerbaru as $log)
                    <div class="flex items-start space-x-3 sm:space-x-4 py-2.5 sm:py-3 border-b border-gray-100 last:border-0">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-brand-bg rounded-full flex items-center justify-center">
                                @if($log->action === 'create')
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                @elseif($log->action === 'update')
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                @endif
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs sm:text-sm text-gray-800 line-clamp-2">{{ $log->description }}</p>
                            <p class="text-xs text-gray-500 mt-0.5 sm:mt-1">
                                {{ $log->user->name ?? 'System' }} • {{ $log->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6 sm:py-8 text-gray-500">
                        <svg class="w-10 h-10 sm:w-12 sm:h-12 mx-auto mb-2 sm:mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="text-xs sm:text-sm">Belum ada aktivitas</p>
                    </div>
                @endforelse
            </div>
        </div>
        
        <!-- Santri Terbaru -->
        <div class="bg-white rounded-xl shadow-sm border border-green-100 overflow-hidden">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-100 bg-brand-bg">
                <h3 class="text-sm sm:text-lg font-semibold text-gray-800">Santri Terbaru</h3>
                <p class="text-xs sm:text-sm text-gray-500 mt-0.5 sm:mt-1 hidden sm:block">Daftar santri yang baru terdaftar</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 sm:px-4 py-2 sm:py-3 text-left text-xs font-semibold text-gray-700 uppercase">Nama</th>
                            <th class="px-3 sm:px-4 py-2 sm:py-3 text-left text-xs font-semibold text-gray-700 uppercase hidden sm:table-cell">Kelas</th>
                            <th class="px-3 sm:px-4 py-2 sm:py-3 text-left text-xs font-semibold text-gray-700 uppercase">Asal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($santriTerbaru->take(5) as $santri)
                        <tr class="hover:bg-brand-bg transition-colors">
                            <td class="px-3 sm:px-4 py-2.5 sm:py-3">
                                <div class="text-xs sm:text-sm font-medium text-gray-800 truncate max-w-[120px] sm:max-w-none">{{ $santri->nama }}</div>
                                <div class="text-xs text-gray-500">{{ $santri->stambuk }}</div>
                            </td>
                            <td class="px-3 sm:px-4 py-2.5 sm:py-3 text-xs sm:text-sm text-gray-700 hidden sm:table-cell">{{ $santri->kelas ?? '-' }}</td>
                            <td class="px-3 sm:px-4 py-2.5 sm:py-3 text-xs sm:text-sm text-gray-700 truncate max-w-[80px] sm:max-w-none">{{ $santri->daerah }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-4 py-6 sm:py-8 text-center text-gray-500 text-xs sm:text-sm">Belum ada data santri</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($santriTerbaru->count() > 0)
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-100 bg-brand-bg">
                <a href="{{ route('santri.index') }}" class="text-xs sm:text-sm font-medium text-brand-primary hover:text-green-700">
                    Lihat Semua →
                </a>
            </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Chart Sebaran Santri per Daerah (Bar Chart)
    const ctxSebaran = document.getElementById('chartSebaranDaerah').getContext('2d');
    const sebaranLabels = @json(array_column($sebaranDaerah, 'daerah'));
    const sebaranData = @json(array_column($sebaranDaerah, 'jumlah'));
    
    const chartSebaranDaerah = new Chart(ctxSebaran, {
        type: 'bar',
        data: {
            labels: sebaranLabels,
            datasets: [{
                label: 'Jumlah Santri',
                data: sebaranData,
                backgroundColor: 'rgba(21, 128, 61, 0.7)',
                borderColor: 'rgba(21, 128, 61, 1)',
                borderWidth: 1,
                borderRadius: 6,
                maxBarThickness: 60,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 1.8,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 10,
                    titleFont: {
                        size: 13
                    },
                    bodyFont: {
                        size: 12
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        maxTicksLimit: 6,
                        font: {
                            size: 11
                        }
                    },
                    grid: {
                        display: true,
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        maxRotation: 30,
                        minRotation: 0,
                        font: {
                            size: 10
                        }
                    }
                }
            }
        }
    });

    // Chart Arus Kas Bulanan (Line Chart)
    const ctxKas = document.getElementById('chartArusKas').getContext('2d');
    const arusKasLabels = @json(array_column($arusKasComplete, 'day'));
    const arusKasIncome = @json(array_column($arusKasComplete, 'income'));
    const arusKasExpense = @json(array_column($arusKasComplete, 'expense'));
    
    // Format labels untuk hanya menampilkan setiap 5 hari atau hari penting
    const formattedLabels = arusKasLabels.map((day, index) => {
        if (day % 5 === 0 || day === 1 || day === arusKasLabels.length) {
            return day;
        }
        return '';
    });
    
    const chartArusKas = new Chart(ctxKas, {
        type: 'line',
        data: {
            labels: arusKasLabels,
            datasets: [
                {
                    label: 'Pemasukan',
                    data: arusKasIncome,
                    borderColor: 'rgba(21, 128, 61, 1)',
                    backgroundColor: 'rgba(21, 128, 61, 0.1)',
                    tension: 0.3,
                    fill: true,
                    pointRadius: 3,
                    pointHoverRadius: 5,
                    borderWidth: 2,
                },
                {
                    label: 'Pengeluaran',
                    data: arusKasExpense,
                    borderColor: 'rgba(220, 38, 38, 1)',
                    backgroundColor: 'rgba(220, 38, 38, 0.1)',
                    tension: 0.3,
                    fill: true,
                    pointRadius: 3,
                    pointHoverRadius: 5,
                    borderWidth: 2,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 1.8,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        boxWidth: 12,
                        padding: 10,
                        font: {
                            size: 11
                        },
                        usePointStyle: true
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 10,
                    titleFont: {
                        size: 12
                    },
                    bodyFont: {
                        size: 11
                    },
                    callbacks: {
                        label: function(context) {
                            const value = context.parsed.y;
                            let formatted = '';
                            if (value >= 1000000) {
                                formatted = 'Rp ' + (value / 1000000).toFixed(2) + ' Jt';
                            } else if (value >= 1000) {
                                formatted = 'Rp ' + (value / 1000).toFixed(0) + ' Rb';
                            } else {
                                formatted = 'Rp ' + value.toLocaleString('id-ID');
                            }
                            return context.dataset.label + ': ' + formatted;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            if (value >= 1000000) {
                                return (value / 1000000).toFixed(1) + 'Jt';
                            } else if (value >= 1000) {
                                return (value / 1000).toFixed(0) + 'Rb';
                            }
                            return value.toLocaleString('id-ID');
                        },
                        maxTicksLimit: 6,
                        font: {
                            size: 10
                        }
                    },
                    grid: {
                        display: true,
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        maxTicksLimit: 10,
                        font: {
                            size: 10
                        },
                        callback: function(value, index) {
                            // Hanya tampilkan label setiap beberapa hari
                            const day = arusKasLabels[index];
                            if (day % 5 === 0 || day === 1 || day === arusKasLabels.length) {
                                return day;
                            }
                            return '';
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
