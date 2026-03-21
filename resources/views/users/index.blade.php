@extends('layouts.app')

@section('title', 'Manajemen User - IKPM Gontor Pontianak')
@section('page-title', 'Manajemen User')
@section('page-subtitle', 'Kelola data user dan role')

@section('content')
    <!-- Header dengan Tombol Tambah -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 sm:mb-6 gap-3 sm:gap-4">
        <div>
            <h2 class="text-lg sm:text-2xl font-bold text-gray-800">Manajemen User</h2>
            <p class="text-xs sm:text-sm text-gray-600 mt-0.5 sm:mt-1">Total: {{ $users->total() }} user</p>
        </div>
        <a 
            href="{{ route('users.create') }}" 
            class="w-full sm:w-auto inline-flex items-center justify-center space-x-1.5 sm:space-x-2 px-4 py-2 sm:py-2.5 bg-brand-primary text-white rounded-lg hover:bg-green-700 transition-colors focus:outline-none focus:ring-2 focus:ring-green-200 focus:ring-offset-2 text-sm"
        >
            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Tambah User</span>
        </a>
    </div>

    <!-- Mobile Card View -->
    <div class="block sm:hidden space-y-3 mb-4">
        @forelse($users as $index => $user)
        <div class="bg-white rounded-xl shadow-sm border border-green-100 p-4">
            <!-- Header: Avatar, Name, Status -->
            <div class="flex items-start space-x-3 mb-3">
                <div class="w-10 h-10 bg-brand-primary rounded-full flex items-center justify-center flex-shrink-0">
                    <span class="text-white font-semibold text-sm">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </span>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-sm font-semibold text-gray-800 truncate">{{ $user->name }}</h3>
                    <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                </div>
                @if($user->is_active)
                    <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-green-100 text-green-800 flex-shrink-0">
                        Aktif
                    </span>
                @else
                    <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-gray-100 text-gray-600 flex-shrink-0">
                        Non-aktif
                    </span>
                @endif
            </div>
            
            <!-- Body: Role, Phone, Pondok -->
            <div class="mb-3 space-y-2">
                <div class="flex flex-wrap items-center gap-2">
                    @if($user->role)
                        @if($user->role->slug == 'admin')
                            <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-red-100 text-red-800">Admin</span>
                        @elseif($user->role->slug == 'ustad')
                            <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-green-100 text-green-800">Ustad</span>
                        @elseif($user->role->slug == 'panitia')
                            <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-blue-100 text-blue-800">Panitia</span>
                        @else
                            <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-gray-100 text-gray-800">{{ $user->role->name }}</span>
                        @endif
                    @endif
                    @if($user->no_hp)
                        <span class="text-xs text-gray-500">{{ $user->no_hp }}</span>
                    @endif
                </div>
                
                @if($user->role && $user->role->slug == 'ustad')
                    @php $userPondok = $user->pondokCabang(); @endphp
                    @if(count($userPondok) > 0)
                        <div class="flex flex-wrap gap-1">
                            @foreach($userPondok as $pondok)
                                <span class="px-1.5 py-0.5 text-[10px] font-medium rounded bg-purple-100 text-purple-700">G{{ $pondok }}</span>
                            @endforeach
                        </div>
                    @else
                        <span class="text-[10px] text-red-500">Belum ada pondok ditugaskan</span>
                    @endif
                @endif
            </div>
            
            <!-- Footer: Actions -->
            <div class="flex items-center justify-end space-x-2 pt-3 border-t border-gray-100">
                <a 
                    href="{{ route('users.edit', $user->id) }}" 
                    class="p-2 text-amber-600 hover:text-amber-800 hover:bg-amber-50 rounded-lg transition-colors"
                    title="Edit"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </a>
                <form 
                    action="{{ route('users.destroy', $user->id) }}" 
                    method="POST" 
                    class="inline"
                    onsubmit="return confirm('Hapus user {{ $user->name }}?')"
                >
                    @csrf
                    @method('DELETE')
                    <button 
                        type="submit" 
                        class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors"
                        title="Hapus"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl shadow-sm border border-green-100 p-8 text-center">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <p class="text-sm font-medium text-gray-600 mb-3">Belum ada user yang terdaftar</p>
            <a href="{{ route('users.create') }}" class="inline-flex items-center space-x-2 px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-green-700 transition-colors text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Tambah User</span>
            </a>
        </div>
        @endforelse
    </div>
    
    <!-- Desktop Table View -->
    <div class="hidden sm:block bg-white rounded-xl shadow-sm border border-green-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-brand-bg">
                    <tr>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-brand-primary uppercase tracking-wider">No</th>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-brand-primary uppercase tracking-wider">Nama</th>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-brand-primary uppercase tracking-wider hidden lg:table-cell">Email</th>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-brand-primary uppercase tracking-wider">Role</th>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-brand-primary uppercase tracking-wider hidden lg:table-cell">No HP</th>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-semibold text-brand-primary uppercase tracking-wider">Status</th>
                        <th class="px-4 lg:px-6 py-3 text-right text-xs font-semibold text-brand-primary uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($users as $index => $user)
                    <tr class="hover:bg-brand-bg transition-colors {{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">
                        <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-xs lg:text-sm text-gray-700">
                            {{ $users->firstItem() + $index }}
                        </td>
                        <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 lg:w-10 lg:h-10 bg-brand-primary rounded-full flex items-center justify-center mr-2 lg:mr-3 flex-shrink-0">
                                    <span class="text-white font-semibold text-xs lg:text-sm">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </span>
                                </div>
                                <div class="min-w-0">
                                    <div class="text-xs lg:text-sm font-medium text-gray-800 truncate max-w-[100px] lg:max-w-none">{{ $user->name }}</div>
                                    <div class="text-xs text-gray-500 truncate max-w-[100px] lg:hidden">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-xs lg:text-sm text-gray-700 hidden lg:table-cell">
                            {{ $user->email }}
                        </td>
                        <td class="px-4 lg:px-6 py-3 lg:py-4">
                            <div class="flex flex-col space-y-1">
                                @if($user->role)
                                    @if($user->role->slug == 'admin')
                                        <span class="px-2 lg:px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 w-fit">
                                            Admin
                                        </span>
                                    @elseif($user->role->slug == 'ustad')
                                        <span class="px-2 lg:px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 w-fit">
                                            Ustad
                                        </span>
                                        @php $userPondok = $user->pondokCabang(); @endphp
                                        @if(count($userPondok) > 0)
                                            <div class="flex flex-wrap gap-1 max-w-[150px]">
                                                @foreach($userPondok as $pondok)
                                                    <span class="px-1.5 py-0.5 text-[10px] font-medium rounded bg-purple-100 text-purple-700">G{{ $pondok }}</span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-[10px] text-red-500">Belum ada pondok</span>
                                        @endif
                                    @elseif($user->role->slug == 'panitia')
                                        <span class="px-2 lg:px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 w-fit">
                                            Panitia
                                        </span>
                                    @else
                                        <span class="px-2 lg:px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 w-fit">
                                            {{ $user->role->name }}
                                        </span>
                                    @endif
                                @else
                                    <span class="px-2 lg:px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-500 w-fit">
                                        -
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-xs lg:text-sm text-gray-700 hidden lg:table-cell">
                            {{ $user->no_hp ?? '-' }}
                        </td>
                        <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap">
                            @if($user->is_active)
                                <span class="px-2 lg:px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Aktif
                                </span>
                            @else
                                <span class="px-2 lg:px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-600">
                                    Non-aktif
                                </span>
                            @endif
                        </td>
                        <td class="px-4 lg:px-6 py-3 lg:py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-1 lg:space-x-2">
                                <a 
                                    href="{{ route('users.edit', $user->id) }}" 
                                    class="p-1.5 text-amber-600 hover:text-amber-800 hover:bg-amber-50 rounded-lg transition-colors"
                                    title="Edit"
                                >
                                    <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <form 
                                    action="{{ route('users.destroy', $user->id) }}" 
                                    method="POST" 
                                    class="inline"
                                    onsubmit="return confirm('Hapus user {{ $user->name }}?')"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button 
                                        type="submit" 
                                        class="p-1.5 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors"
                                        title="Hapus"
                                    >
                                        <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center">
                            <div class="text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <p class="text-sm font-medium">Belum ada user yang terdaftar</p>
                                <a href="{{ route('users.create') }}" class="inline-block mt-3 px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-green-700 transition-colors text-sm">
                                    Tambah User Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($users->hasPages())
        <div class="px-4 lg:px-6 py-3 lg:py-4 border-t border-gray-100 bg-brand-bg">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-2">
                <div class="text-xs sm:text-sm text-gray-600">
                    {{ $users->firstItem() }}-{{ $users->lastItem() }} dari {{ $users->total() }}
                </div>
                <div class="flex items-center space-x-2">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Mobile Pagination -->
    @if($users->hasPages())
    <div class="block sm:hidden mt-4 px-2">
        {{ $users->links() }}
    </div>
    @endif
@endsection
