<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Manajemen Pengguna') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    SRS Module 1: Kelola seluruh akun pengguna, hak akses, dan kredensial sistem.
                </p>
            </div>
            <div>
                <button 
                    type="button" 
                    @click="$dispatch('open-modal', 'modal-create-user')" 
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Pengguna
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-8" x-data="{ resetUserId: null, resetUserName: '' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Message Alerts -->
            @if (session('success'))
                <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 border border-green-200 flex items-center shadow-sm" role="alert">
                    <svg class="w-5 h-5 inline mr-3 flex-shrink-0 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200 flex items-center shadow-sm" role="alert">
                    <svg class="w-5 h-5 inline mr-3 flex-shrink-0 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200 shadow-sm">
                    <div class="font-medium">Terjadi kesalahan validasi data:</div>
                    <ul class="mt-1.5 list-disc list-inside text-xs">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Ringkasan Statistik -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-5 border border-gray-100 flex items-center">
                    <div class="p-3 rounded-xl bg-blue-50 text-blue-600 mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-500">Total Pengguna</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-5 border border-gray-100 flex items-center">
                    <div class="p-3 rounded-xl bg-purple-50 text-purple-600 mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-500">Administrator</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $stats['admin'] }}</div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-5 border border-gray-100 flex items-center">
                    <div class="p-3 rounded-xl bg-emerald-50 text-emerald-600 mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-500">User Biasa</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $stats['user'] }}</div>
                    </div>
                </div>
            </div>

            <!-- Card Tabel Pengguna -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <!-- Filter & Search Toolbar -->
                <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <h3 class="font-semibold text-lg text-gray-800">
                        Daftar Pengguna Sistem (FR-ADM-03)
                    </h3>

                    <form method="GET" action="{{ route('admin.users.index') }}" class="flex items-center gap-2">
                        <div class="relative">
                            <input 
                                type="text" 
                                name="search" 
                                value="{{ $search ?? '' }}" 
                                placeholder="Cari nama atau email..." 
                                class="w-64 pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500"
                            >
                            <svg class="w-4 h-4 absolute left-3 top-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <button type="submit" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">
                            Cari
                        </button>
                        @if ($search)
                            <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-500 hover:text-gray-700 underline">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>

                <!-- Table Content -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-50 text-xs uppercase font-semibold text-gray-500 border-b border-gray-100">
                            <tr>
                                <th scope="col" class="px-6 py-4">Pengguna</th>
                                <th scope="col" class="px-6 py-4">Role Akses</th>
                                <th scope="col" class="px-6 py-4">Terdaftar Sejak</th>
                                <th scope="col" class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($users as $user)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center text-sm mr-3">
                                                {{ strtoupper(substr($user->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900 flex items-center gap-2">
                                                    {{ $user->name }}
                                                    @if ($user->id === auth()->id())
                                                        <span class="text-[10px] bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full font-semibold">Anda</span>
                                                    @endif
                                                </div>
                                                <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($user->role === 'admin')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">
                                                Admin
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                                User Biasa
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-xs text-gray-500">
                                        {{ $user->created_at ? $user->created_at->format('d M Y, H:i') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <!-- Tombol Reset Password (FR-ADM-04) -->
                                        <button 
                                            type="button" 
                                            @click="resetUserId = {{ $user->id }}; resetUserName = '{{ addslashes($user->name) }}'; $dispatch('open-modal', 'modal-reset-password')" 
                                            class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-amber-700 bg-amber-50 hover:bg-amber-100 rounded-md transition"
                                            title="Reset Password"
                                        >
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                            </svg>
                                            Reset Password
                                        </button>

                                        <!-- Tombol Hapus User (FR-ADM-02) -->
                                        @if ($user->id !== auth()->id())
                                            <form 
                                                method="POST" 
                                                action="{{ route('admin.users.destroy', $user->id) }}" 
                                                class="inline-block" 
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ addslashes($user->name) }}? Pengguna ini tidak akan bisa login lagi ke sistem.')"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button 
                                                    type="submit" 
                                                    class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-red-700 bg-red-50 hover:bg-red-100 rounded-md transition"
                                                    title="Hapus Pengguna"
                                                >
                                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                                        Tidak ada akun pengguna yang ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($users->hasPages())
                    <div class="p-6 border-t border-gray-100">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- ================= MODAL TAMBAH PENGGUNA (FR-ADM-01) ================= -->
        <x-modal name="modal-create-user" focusable>
            <form method="POST" action="{{ route('admin.users.store') }}" class="p-6">
                @csrf

                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900">
                        Tambah Akun Pengguna Baru (FR-ADM-01)
                    </h3>
                    <button type="button" @click="$dispatch('close')" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="mt-4 space-y-4">
                    <div>
                        <x-input-label for="name" :value="__('Nama Lengkap')" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" placeholder="Nama pengguna" required />
                    </div>

                    <div>
                        <x-input-label for="email" :value="__('Alamat Email')" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" placeholder="email@contoh.com" required />
                    </div>

                    <div>
                        <x-input-label for="password" :value="__('Password Sementara')" />
                        <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" placeholder="Minimal 6 karakter" required />
                        <p class="text-xs text-gray-500 mt-1">Pengguna dapat mengganti password ini setelah login.</p>
                    </div>

                    <div>
                        <x-input-label for="role" :value="__('Role / Hak Akses (RBAC)')" />
                        <select id="role" name="role" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" required>
                            <option value="user" selected>User Biasa</option>
                            <option value="admin">Administrator</option>
                        </select>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <x-secondary-button type="button" @click="$dispatch('close')">
                        {{ __('Batal') }}
                    </x-secondary-button>

                    <x-primary-button>
                        {{ __('Simpan Pengguna') }}
                    </x-primary-button>
                </div>
            </form>
        </x-modal>

        <!-- ================= MODAL RESET PASSWORD (FR-ADM-04) ================= -->
        <x-modal name="modal-reset-password" focusable>
            <form method="POST" :action="`{{ url('admin/users') }}/${resetUserId}/reset-password`" class="p-6">
                @csrf
                @method('PATCH')

                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900">
                        Reset Password Akun (FR-ADM-04)
                    </h3>
                    <button type="button" @click="$dispatch('close')" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="mt-4 space-y-4">
                    <p class="text-sm text-gray-600">
                        Masukkan password baru untuk pengguna <strong class="text-gray-900" x-text="resetUserName"></strong>:
                    </p>

                    <div>
                        <x-input-label for="new_password" :value="__('Password Baru')" />
                        <x-text-input id="new_password" name="password" type="password" class="mt-1 block w-full" placeholder="Minimal 6 karakter" required />
                    </div>

                    <div>
                        <x-input-label for="password_confirmation" :value="__('Konfirmasi Password Baru')" />
                        <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" placeholder="Ulangi password baru" required />
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <x-secondary-button type="button" @click="$dispatch('close')">
                        {{ __('Batal') }}
                    </x-secondary-button>

                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-amber-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-amber-700 active:bg-amber-900 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                        {{ __('Reset Password') }}
                    </button>
                </div>
            </form>
        </x-modal>

    </div>
</x-app-layout>
