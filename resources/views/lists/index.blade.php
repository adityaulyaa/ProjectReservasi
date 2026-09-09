<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Manajemen Proyek & Daftar') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Kelola proyek pribadi dan kolaborasi tim Anda</p>
            </div>
            <a href="{{ route('lists.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Buat Proyek Baru
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- Alert Notification --}}
            @if (session('success'))
                <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-md text-emerald-800 text-sm shadow-sm flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            {{-- Section 1: Daftar Milik Saya --}}
            <div>
                <div class="flex items-center space-x-2 mb-4">
                    <div class="w-2.5 h-6 bg-indigo-600 rounded-full"></div>
                    <h3 class="text-lg font-bold text-gray-900">Proyek Pribadi Saya (Owner)</h3>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                        {{ $ownedLists->count() }}
                    </span>
                </div>

                @if ($ownedLists->isEmpty())
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center">
                        <div class="mx-auto w-12 h-12 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-500 mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h4 class="font-medium text-gray-900 mb-1">Belum Ada Proyek</h4>
                        <p class="text-sm text-gray-500 max-w-md mx-auto mb-4">Anda belum membuat proyek pribadi. Mulai buat proyek pertama Anda sekarang untuk mengelompokkan tugas.</p>
                        <a href="{{ route('lists.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-xs font-semibold rounded-md hover:bg-indigo-700 transition">
                            + Buat Proyek Baru
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($ownedLists as $list)
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 hover:shadow-md transition-all duration-200 flex flex-col justify-between overflow-hidden group">
                                <div class="p-6">
                                    <div class="flex items-start justify-between gap-2 mb-2">
                                        <h4 class="font-bold text-gray-900 text-lg group-hover:text-indigo-600 transition-colors line-clamp-1">
                                            <a href="{{ route('lists.show', $list) }}">{{ $list->name }}</a>
                                        </h4>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 shrink-0">
                                            Owner
                                        </span>
                                    </div>
                                    <p class="text-gray-600 text-sm mb-4 line-clamp-2 min-h-[2.5rem]">
                                        {{ $list->description ?: 'Tidak ada deskripsi.' }}
                                    </p>
                                    <div class="flex items-center text-xs text-gray-500 space-x-4">
                                        <div class="flex items-center space-x-1">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                            </svg>
                                            <span>{{ $list->members_count }} Member</span>
                                        </div>
                                        <div class="flex items-center space-x-1">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span>{{ $list->created_at->format('d M Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="px-6 py-3 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                                    <a href="{{ route('lists.show', $list) }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition">
                                        Lihat Detail &rarr;
                                    </a>
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('lists.edit', $list) }}" class="text-xs text-gray-500 hover:text-gray-700 font-medium px-2 py-1 rounded hover:bg-gray-200/60 transition">
                                            Edit
                                        </a>
                                        <form action="{{ route('lists.destroy', $list) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus proyek ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-rose-500 hover:text-rose-700 font-medium px-2 py-1 rounded hover:bg-rose-50 transition">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Section 2: Daftar Kolaborasi --}}
            <div class="pt-4 border-t border-gray-200">
                <div class="flex items-center space-x-2 mb-4">
                    <div class="w-2.5 h-6 bg-emerald-500 rounded-full"></div>
                    <h3 class="text-lg font-bold text-gray-900">Proyek Kolaborasi (Member)</h3>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                        {{ $collaborationLists->count() }}
                    </span>
                </div>

                @if ($collaborationLists->isEmpty())
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center">
                        <div class="mx-auto w-12 h-12 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-500 mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h4 class="font-medium text-gray-900 mb-1">Belum Ada Proyek Kolaborasi</h4>
                        <p class="text-sm text-gray-500 max-w-md mx-auto">Anda belum diundang ke proyek manapun sebagai anggota. Pemilik proyek dapat menambahkan Anda menggunakan email atau username Anda.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($collaborationLists as $list)
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 hover:shadow-md transition-all duration-200 flex flex-col justify-between overflow-hidden group">
                                <div class="p-6">
                                    <div class="flex items-start justify-between gap-2 mb-2">
                                        <h4 class="font-bold text-gray-900 text-lg group-hover:text-emerald-600 transition-colors line-clamp-1">
                                            <a href="{{ route('lists.show', $list) }}">{{ $list->name }}</a>
                                        </h4>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 shrink-0">
                                            Member
                                        </span>
                                    </div>
                                    <p class="text-gray-600 text-sm mb-4 line-clamp-2 min-h-[2.5rem]">
                                        {{ $list->description ?: 'Tidak ada deskripsi.' }}
                                    </p>
                                    <div class="flex items-center justify-between text-xs text-gray-500 pt-2 border-t border-gray-50">
                                        <span class="font-medium text-gray-700">
                                            Owner: <span class="text-indigo-600">{{ $list->owner->name }}</span>
                                        </span>
                                        <div class="flex items-center space-x-1">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                            </svg>
                                            <span>{{ $list->members_count }} Member</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="px-6 py-3 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                                    <a href="{{ route('lists.show', $list) }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-800 transition">
                                        Buka Proyek &rarr;
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
