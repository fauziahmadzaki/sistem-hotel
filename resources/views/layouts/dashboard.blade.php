@php
$role = auth()->user()->role;
// Definisikan role untuk kemudahan pengecekan
$isSuperAdmin = $role === 'superadmin';
$isAdmin = in_array($role, ['superadmin', 'admin']);
$isHousekeeper = in_array($role, ['superadmin', 'housekeeper']);

$routes = [
'superadmin' => 'dashboard.superadmin.index',
'admin' => 'dashboard.admin.index',
'housekeeper' => 'dashboard.housekeeping.index',
];

$baseRoute = $routes[$role];
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard | {{ ucFirst($role) }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body x-data="{ sidebarOpen: false }">
    <x-toast></x-toast>
    <nav
        class="fixed top-0 left-0 w-full bg-white border-b border-gray-200 shadow-sm z-40 flex justify-between items-center px-5 py-3 h-[56px]">
        <h1 class="font-bold text-violet-600 text-lg">
            Hotelio {{ ucfirst($role) }}
        </h1>

        <div class="flex items-center gap-4">

            <x-dropdown align="right" width="44">
                {{-- Trigger Button --}}
                <x-slot name="trigger">
                    <button class="flex items-center gap-2 focus:outline-none">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=7c3aed&color=fff"
                            alt="Avatar {{ Auth::user()->name }}" class="w-8 h-8 rounded-full shadow-sm">
                        <span class="hidden sm:block font-medium text-gray-700">{{ Auth::user()->name }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500 hidden sm:block"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                </x-slot>

                {{-- (DIPERBARUI) Dropdown Content --}}
                <x-dropdown-link href="{{ route('dashboard.profile.index') }}">
                    Profil Saya
                </x-dropdown-link>
                <x-dropdown-link href="{{ route('dashboard.profile.index') }}">
                    Pengaturan
                </x-dropdown-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full px-4 py-2 text-sm text-left text-red-600 hover:bg-red-50">
                        Keluar
                    </button>
                </form>
            </x-dropdown>

            <button @click.stop="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-md hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-700" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </nav>

    {{-- Overlay untuk mobile --}}
    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition:enter="transition-opacity ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/50 z-20 lg:hidden" x-cloak>
    </div>

    {{-- (DIPERBARUI) Sidebar dengan state, logic, dan link lengkap --}}
    <aside id="sidebar" x-data="{ openAccordions: {} }"
        class="fixed top-[56px] left-0 z-30 bg-white w-64  border-r border-gray-200 shadow-sm flex flex-col justify-between transform lg:translate-x-0 transition-transform duration-200"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" x-cloak>
        <div class="flex h-screen flex-col justify-between border-e border-gray-100 bg-white">
            <div class="px-4 py-6 overflow-y-auto">

                <ul class="mt-6 space-y-1">

                    {{-- ===================== --}}
                    {{-- GRUP UTAMA            --}}
                    {{-- ===================== --}}
                    <li class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Utama</li>

                    {{-- Tampilkan Dashboard Admin/Kasir --}}
                    @if ($isAdmin)
                    <x-sidebar-link href="{{ route($baseRoute) }}" :active="request()->routeIs($baseRoute)">
                        Dashboard
                    </x-sidebar-link>
                    @endif

                    {{-- Tampilkan Dashboard Housekeeper --}}
                    @if ($role == 'housekeeper')
                    <x-sidebar-link href="{{ route('dashboard.housekeeping.index') }}"
                        :active="request()->routeIs('dashboard.housekeeping.index')">
                        Dashboard Tugas
                    </x-sidebar-link>
                    @endif

                    @if ($isAdmin)
                    <li class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider mt-4">Operasional
                    </li>

                    <x-sidebar-accordion name="reservasi">
                        <x-slot name="trigger">Reservasi</x-slot>
                        <x-sidebar-accordion-link href="{{ route('dashboard.reservations.index') }}"
                            :active="request()->routeIs('dashboard.reservations.index') || request()->routeIs('dashboard.reservations.edit') || request()->routeIs('dashboard.reservations.create') || request()->routeIs('dashboard.reservations.checkout.form')">
                            Reservasi Aktif
                        </x-sidebar-accordion-link>
                        <x-sidebar-accordion-link href="{{ route('dashboard.reservations.completed') }}"
                            :active="request()->routeIs('dashboard.reservations.completed')">
                            Reservasi Selesai
                        </x-sidebar-accordion-link>
                        <x-sidebar-accordion-link href="{{ route('dashboard.reservations.cancelled') }}"
                            :active="request()->routeIs('dashboard.reservations.cancelled')">
                            Reservasi Dibatalkan
                        </x-sidebar-accordion-link>
                    </x-sidebar-accordion>

                    <x-sidebar-accordion name="keuangan">
                        <x-slot name="trigger">Keuangan (Kasir)</x-slot>
                        <x-sidebar-accordion-link href="{{ route('dashboard.transactions.report') }}"
                            :active="request()->routeIs('dashboard.transactions.report')">
                            Laporan Tutup Kasir
                        </x-sidebar-accordion-link>
                        <x-sidebar-accordion-link href="{{ route('dashboard.transactions.index') }}"
                            :active="request()->routeIs('dashboard.transactions.index') || request()->routeIs('dashboard.transactions.edit')">
                            Semua Transaksi
                        </x-sidebar-accordion-link>
                        <x-sidebar-accordion-link href="{{ route('dashboard.transactions.create') }}"
                            :active="request()->routeIs('dashboard.transactions.create')">
                            Catat Transaksi Manual
                        </x-sidebar-accordion-link>
                    </x-sidebar-accordion>
                    @endif


                    {{-- ===================== --}}
                    {{-- GRUP HOUSEKEEPING     --}}
                    {{-- ===================== --}}
                    @if ($isHousekeeper)
                    <li class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider mt-4">Housekeeping
                    </li>
                    <x-sidebar-accordion name="housekeeping">
                        <x-slot name="trigger">Tugas Kamar</x-slot>
                        <x-sidebar-accordion-link href="{{ route('dashboard.housekeeping.index') }}"
                            :active="request()->routeIs('dashboard.housekeeping.index') || request()->routeIs('dashboard.housekeeping.check.form')">
                            Tugas Pending
                        </x-sidebar-accordion-link>
                        <x-sidebar-accordion-link href="{{ route('dashboard.housekeeping.completed') }}"
                            :active="request()->routeIs('dashboard.housekeeping.completed')">
                            Tugas Selesai
                        </x-sidebar-accordion-link>
                    </x-sidebar-accordion>
                    @endif


                    {{-- ===================== --}}
                    {{-- GRUP MASTER DATA      --}}
                    {{-- ===================== --}}
                    @if ($isSuperAdmin)
                    <li class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider mt-4">Master Data
                    </li>

                    <x-sidebar-accordion name="manajemen_hotel">
                        <x-slot name="trigger">Manajemen Hotel</x-slot>
                        <x-sidebar-accordion-link href="{{ route('dashboard.rooms.index') }}"
                            :active="request()->routeIs('dashboard.rooms.*')">
                            Manajemen Kamar
                        </x-sidebar-accordion-link>
                        <x-sidebar-accordion-link href="{{ route('dashboard.room-types.index') }}"
                            :active="request()->routeIs('dashboard.room-types.*')">
                            Tipe Kamar
                        </x-sidebar-accordion-link>
                        <x-sidebar-accordion-link href="{{ route('dashboard.facilities.index') }}"
                            :active="request()->routeIs('dashboard.facilities.*')">
                            Fasilitas
                        </x-sidebar-accordion-link>
                    </x-sidebar-accordion>

                    <x-sidebar-link href="{{ route('dashboard.users.index') }}"
                        :active="request()->routeIs('dashboard.users.*')">
                        Manajemen User
                    </x-sidebar-link>
                    @endif


                    {{-- ===================== --}}
                    {{-- GRUP AKUN (Semua)     --}}
                    {{-- ===================== --}}
                    <li class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider mt-4">Akun</li>
                    <x-sidebar-accordion name="akun">
                        <x-slot name="trigger">Pengaturan Akun</x-slot>

                        <x-sidebar-accordion-link href="{{ route('dashboard.profile.index') }}"
                            :active="request()->routeIs('dashboard.profile.index')">
                            Profil Saya
                        </x-sidebar-accordion-link>

                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="block w-full text-left rounded-lg px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50">
                                    Logout
                                </button>
                            </form>
                        </li>
                    </x-sidebar-accordion>

                </ul>
            </div>

            {{-- Footer --}}
            <div classK="border-t border-gray-100 p-4">
                <div class="flex items-center gap-3">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=7c3aed&color=fff"
                        alt="User avatar" class="size-9 rounded-full object-cover">
                    <div>
                        <p class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                    </div>
                </div>
            </div>
        </div>
    </aside>

    <main @click="if (sidebarOpen) sidebarOpen = false"
        class="lg:ml-64 mt-[56px] p-10 bg-gray-50 min-h-screen transition-all duration-200 space-y-5">
        @yield('contents')
    </main>

    <script src="//unpkg.com/alpinejs" defer></script>

</body>

</html>