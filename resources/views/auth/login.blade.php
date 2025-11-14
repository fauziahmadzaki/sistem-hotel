<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login | {{ config('app.name') }}</title>

    {{-- Fonts (Sama seperti layout admin) --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    {{-- Style (diperlukan untuk Alpine) --}}
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    {{-- Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full font-sans antialiased">

    {{-- Toast Notifikasi (Untuk menampilkan error login) --}}
    <x-toast></x-toast>

    <div class="flex min-h-full items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md space-y-8">
            {{-- Header --}}
            <div>
                <h1 class="text-center text-4xl font-bold text-violet-600">
                    {{ config('app.name') }}
                </h1>
                <h2 class="mt-2 text-center text-lg text-gray-600">
                    Selamat datang! Silakan login ke akun Anda.
                </h2>
            </div>

            {{-- Form Card --}}
            <div class="bg-white p-8 shadow-sm rounded-lg">
                <form class="space-y-6" action="{{ route('login') }}" method="POST">
                    @csrf

                    {{-- Input Username --}}
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700">
                            Username
                        </label>
                        <div class="mt-1">
                            <input id="username" name="username" type="text" value="{{ old('username') }}"
                                autocomplete="username" required
                                class="block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-violet-500 focus:outline-none focus:ring-violet-500 sm:text-sm"
                                placeholder="Masukkkan username Anda">
                        </div>
                        @error('username')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>


                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            Password
                        </label>
                        <div class="mt-1">
                            <input id="password" name="password" type="password" autocomplete="current-password"
                                required
                                class="block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-violet-500 focus:outline-none focus:ring-violet-500 sm:text-sm"
                                placeholder="Masukkan password Anda">
                        </div>
                        @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tombol Submit --}}
                    <div>
                        <button type="submit"
                            class="flex w-full justify-center rounded-md border border-transparent bg-violet-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2">
                            Login
                        </button>
                    </div>
                </form>
            </div>

            <p class="text-center text-sm text-gray-500">
                Lupa password? Hubungi Superadmin.
            </p>
        </div>
    </div>
    <script src="//unpkg.com/alpinejs" defer></script>
</body>

</html>