<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center space-x-3">
                <a href="{{ route('lists.index') }}" class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <div class="flex items-center space-x-2">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                            {{ $list->name }}
                        </h2>
                        @if ($list->owner_id === Auth::id())
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Owner</span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Member</span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-500 mt-0.5">Dibuat oleh {{ $list->owner->name }} pada {{ $list->created_at->format('d M Y') }}</p>
                </div>
            </div>

            @can('update', $list)
                <div class="flex items-center space-x-2">
                    <a href="{{ route('lists.edit', $list) }}" class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition">
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Proyek
                    </a>
                    @can('delete', $list)
                        <form action="{{ route('lists.destroy', $list) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus proyek ini secara permanen?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-rose-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-rose-700 active:bg-rose-900 transition">
                                Hapus
                            </button>
                        </form>
                    @endcan
                </div>
            @endcan
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Flash Messages --}}
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

            @if ($errors->has('error'))
                <div class="p-4 bg-rose-50 border-l-4 border-rose-500 rounded-r-md text-rose-800 text-sm shadow-sm">
                    {{ $errors->first('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Left Column: Detail Proyek & Tugas --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Detail Proyek Card --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-base font-bold text-gray-900 mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Informasi Proyek
                        </h3>
                        <div class="prose max-w-none text-gray-600 text-sm leading-relaxed bg-gray-50 p-4 rounded-lg border border-gray-100">
                            {{ $list->description ?: 'Tidak ada deskripsi untuk proyek ini.' }}
                        </div>
                    </div>

                    {{-- Section Task (Placeholder untuk Programmer 3) --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                                Daftar Tugas (Task Management)
                            </h3>
                            <span class="text-xs bg-gray-100 text-gray-600 px-2.5 py-1 rounded-full font-medium">Module 3</span>
                        </div>
                        
                        <div class="border-2 border-dashed border-gray-200 rounded-lg p-6 text-center bg-gray-50/50">
                            <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-sm font-medium text-gray-600">Area Tugas Proyek ini siap digunakan oleh Programmer 3 (Task Management).</p>
                            <p class="text-xs text-gray-400 mt-1">Relasi model <code class="bg-gray-200 px-1 py-0.5 rounded">ProjectList->tasks()</code> sudah disiapkan.</p>
                        </div>
                    </div>
                </div>

                {{-- Right Column: Kolaborasi & Keanggotaan --}}
                <div class="space-y-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                Keanggotaan Proyek
                            </h3>
                            <span class="text-xs bg-indigo-50 text-indigo-700 font-semibold px-2 py-0.5 rounded-full">
                                {{ $list->members->count() + 1 }} Orang
                            </span>
                        </div>

                        {{-- Form Tambah Member (Hanya untuk Owner) --}}
                        @can('addMember', $list)
                            <div class="mb-6 pb-6 border-b border-gray-100">
                                <h4 class="text-xs font-semibold uppercase text-gray-400 tracking-wider mb-2">Tambah Member Baru</h4>
                                <form action="{{ route('lists.members.add', $list) }}" method="POST" class="space-y-3">
                                    @csrf
                                    <div>
                                        <div class="flex gap-2">
                                            <input type="text" name="identifier" class="text-sm block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Email atau Username user..." required>
                                            <button type="submit" class="px-3 py-2 bg-indigo-600 text-white text-xs font-semibold rounded-md hover:bg-indigo-700 transition shrink-0">
                                                + Tambah
                                            </button>
                                        </div>
                                        <x-input-error class="mt-1" :messages="$errors->get('identifier')" />
                                    </div>
                                    <p class="text-[11px] text-gray-500">Cari pengguna berdasarkan Email atau Nama pengguna.</p>
                                </form>
                            </div>
                        @endcan

                        {{-- Lista Anggota --}}
                        <div class="space-y-3">
                            <h4 class="text-xs font-semibold uppercase text-gray-400 tracking-wider">Daftar Anggota</h4>

                            {{-- Item Owner --}}
                            <div class="flex items-center justify-between p-2.5 rounded-lg bg-amber-50/60 border border-amber-100">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-full bg-amber-500 text-white flex items-center justify-center text-xs font-bold shrink-0">
                                        {{ strtoupper(substr($list->owner->name, 0, 1)) }}
                                    </div>
                                    <div class="overflow-hidden">
                                        <p class="text-xs font-bold text-gray-900 truncate">{{ $list->owner->name }}</p>
                                        <p class="text-[11px] text-gray-500 truncate">{{ $list->owner->email }}</p>
                                    </div>
                                </div>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-200 text-amber-900 shrink-0">
                                    Owner
                                </span>
                            </div>

                            {{-- Item Members --}}
                            @forelse ($list->members as $member)
                                <div class="flex items-center justify-between p-2.5 rounded-lg bg-gray-50 border border-gray-100 hover:bg-gray-100/80 transition">
                                    <div class="flex items-center space-x-3 min-w-0">
                                        <div class="w-8 h-8 rounded-full bg-indigo-500 text-white flex items-center justify-center text-xs font-bold shrink-0">
                                            {{ strtoupper(substr($member->name, 0, 1)) }}
                                        </div>
                                        <div class="truncate">
                                            <p class="text-xs font-semibold text-gray-900 truncate">{{ $member->name }}</p>
                                            <p class="text-[11px] text-gray-500 truncate">{{ $member->email }}</p>
                                        </div>
                                    </div>

                                    @can('removeMember', $list)
                                        <form action="{{ route('lists.members.remove', [$list, $member]) }}" method="POST" onsubmit="return confirm('Keluarkan {{ $member->name }} dari proyek ini?');" class="shrink-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1 text-gray-400 hover:text-rose-600 rounded transition" title="Keluarkan Member">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            @empty
                                <p class="text-xs text-gray-500 italic py-2 text-center">Belum ada member lain yang ditambahkan.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
