@extends('layouts.dashboard')

@section('contents')
<h1 class="text-2xl font-semibold text-gray-900 mb-6">Profil Saya & Pengaturan</h1>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    {{-- KOLOM KIRI: EDIT PROFIL --}}
    <div class="md:col-span-2">
        <form action="{{ route('dashboard.profile.update') }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Profil</h3>
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 sm:text-sm"
                            required>
                        @error('name') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                        <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 sm:text-sm"
                            required>
                        @error('username') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Alamat Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 sm:text-sm"
                            required>
                        @error('email') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="flex justify-end pt-6">
                    <button type="submit"
                        class="inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-violet-600 hover:bg-violet-700">
                        Simpan Perubahan Profil
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- KOLOM KANAN: GANTI PASSWORD --}}
    <div class="md:col-span-1">
        <form action="{{ route('dashboard.profile.password') }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Ubah Password</h3>
                <div class="space-y-4">
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700">Password
                            Lama</label>
                        <input type="password" name="current_password" id="current_password"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 sm:text-sm"
                            required>
                        @error('current_password') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                        <input type="password" name="password" id="password"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 sm:text-sm"
                            required>
                        @error('password') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi
                            Password Baru</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 sm:text-sm"
                            required>
                    </div>
                </div>
                <div class="flex justify-end pt-6">
                    <button type="submit"
                        class="inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-violet-600 hover:bg-violet-700">
                        Update Password
                    </button>
                </div>
            </div>
        </form>
    </div>

</div>
@endsection