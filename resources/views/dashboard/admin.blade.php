@extends('layouts.dashboard')

@section('contents')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold text-gray-900">Dashboard Kasir (Front Office)</h1>
    {{-- Shortcut untuk Walk-in (Reservasi Baru) --}}
    <a href="{{ route('dashboard.reservations.create') }}"
        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-violet-600 hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500">
        + Registrasi Tamu (Walk-in)
    </a>
</div>

{{-- (BARU) Widget Statistik Cepat --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <h3 class="text-lg font-medium text-gray-900">Tamu Aktif (In-House)</h3>
        <p class="mt-2 text-3xl font-bold text-violet-600">{{ $stats['active_guests'] }}</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <h3 class="text-lg font-medium text-gray-900">Kamar Tersedia (Clean)</h3>
        <p class="mt-2 text-3xl font-bold text-green-600">{{ $stats['available_rooms'] }}</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <h3 class="text-lg font-medium text-gray-900">Kamar Kotor (Maintenance)</h3>
        <p class="mt-2 text-3xl font-bold text-red-600">{{ $stats['dirty_rooms'] }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

    {{-- ============================================= --}}
    {{-- (DIPERBARUI) KOLOM 1: TAMU MENGINAP SAAT INI   --}}
    {{-- ============================================= --}}
    <div>
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Tamu Menginap Saat Ini (In-House)</h2>
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tamu</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kamar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jadwal Checkout</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($currentCheckins as $reservation)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $reservation->name }}</div>
                                <div class="text-sm text-gray-500">{{ $reservation->phone_number }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $reservation->room->room_name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($reservation->checkout_date)->format('d M Y') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                Tidak ada tamu yang sedang menginap.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ============================================= --}}
    {{-- KOLOM 2: SIAP CHECKOUT HARI INI               --}}
    {{-- ============================================= --}}
    <div>
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Siap Checkout Hari Ini</h2>
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tamu</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kamar</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status Cek</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($pendingCheckouts as $reservation)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $reservation->name }}</div>
                                <div class="text-sm text-gray-500">{{ $reservation->phone_number }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $reservation->room->room_name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                @if ($reservation->housekeepingCheck && $reservation->housekeepingCheck->status ==
                                'done')
                                {{-- 1. Siap Checkout (Sudah dicek Housekeeping) --}}
                                <a href="{{ route('dashboard.reservations.checkout.form', $reservation) }}"
                                    class="text-green-600 hover:text-green-900 font-semibold">
                                    Siap Checkout
                                </a>
                                @elseif ($reservation->housekeepingCheck)
                                {{-- 2. Menunggu Housekeeping --}}
                                <a href="{{ route('dashboard.reservations.checkout.form', $reservation) }}"
                                    class="text-yellow-600 hover:text-yellow-900">
                                    Menunggu Cek
                                </a>
                                @else
                                {{-- 3. Belum dicek (Kasir harus request) --}}
                                <a href="{{ route('dashboard.reservations.checkout.form', $reservation) }}"
                                    class="text-red-600 hover:text-red-900">
                                    Minta Pengecekan
                                </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                Tidak ada jadwal checkout untuk hari ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection