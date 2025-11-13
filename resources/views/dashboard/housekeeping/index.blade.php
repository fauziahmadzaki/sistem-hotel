@extends('layouts.dashboard')

@section('contents')
{{-- (BARU) Tambahkan x-data di wrapper untuk mengelola modal 'clean' --}}
<div x-data="{ cleanModalOpen: false, cleanFormAction: '' }">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Dashboard Tugas Housekeeping</h1>
    </div>

    {{-- ====================================================== --}}
    {{-- BAGIAN 1: TUGAS PENGECEKAN PRA-CHECKOUT                 --}}
    {{-- ====================================================== --}}
    <h2 class="text-xl font-semibold text-gray-800 mb-4">1. Pengecekan Pra-Checkout (Pending)</h2>
    <div class="bg-white shadow-sm rounded-lg overflow-hidden mb-8">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kamar
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tamu
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jadwal
                            Checkout</th>
                        @if(auth()->user()->role != 'housekeeper')
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Ditugaskan Ke</th>
                        @endif
                        <th scope="col"
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($pendingChecks as $check)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $check->reservation->room->room_name }}
                            </div>
                            <div class="text-sm text-gray-500">{{ $check->reservation->room->room_code }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $check->reservation->name }}</div>
                            <div class="text-sm text-gray-500">{{ $check->reservation->phone_number }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ \Carbon\Carbon::parse($check->reservation->checkout_date)->format('d M Y') }}
                        </td>
                        @if(auth()->user()->role != 'housekeeper')
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $check->housekeeper->name }}
                        </td>
                        @endif
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <a href="{{ route('dashboard.housekeeping.check.form', $check) }}"
                                class="text-blue-600 hover:text-blue-900">
                                Lakukan Pengecekan
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ auth()->user()->role != 'housekeeper' ? 5 : 4 }}"
                            class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            Tidak ada tugas pengecekan pending.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($pendingChecks->hasPages())
        <div class="p-4 bg-gray-50 border-t border-gray-200">
            {{ $pendingChecks->links('pagination::tailwind', ['paginator' => $pendingChecks, 'pageName' => 'check_page']) }}
        </div>
        @endif
    </div>

    {{-- ====================================================== --}}
    {{-- BAGIAN 2: TUGAS PEMBERSIHAN KAMAR (MAINTENANCE)        --}}
    {{-- ====================================================== --}}
    <h2 class="text-xl font-semibold text-gray-800 mb-4">2. Kamar Butuh Pembersihan (Maintenance)</h2>
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kamar
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe
                            Kamar</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($pendingCleanings as $room)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $room->room_name }}</div>
                            <div class="text-sm text-gray-500">{{ $room->room_code }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $room->roomType->room_type_name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Maintenance
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            {{-- (DIPERBARUI) Ganti <form> dengan <button type="button"> --}}
                            <button type="button"
                                @click.prevent="cleanModalOpen = true; cleanFormAction = '{{ route('dashboard.housekeeping.clean.process', $room) }}'"
                                class="text-green-600 hover:text-green-900">
                                Selesai Dibersihkan
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            Tidak ada kamar yang berstatus maintenance.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($pendingCleanings->hasPages())
        <div class="p-4 bg-gray-50 border-t border-gray-200">
            {{ $pendingCleanings->links('pagination::tailwind', ['paginator' => $pendingCleanings, 'pageName' => 'cleaning_page']) }}
        </div>
        @endif
    </div>

    {{-- (BARU) Modal Konfirmasi Selesai Bersih --}}
    <div x-show="cleanModalOpen" @keydown.escape.window="cleanModalOpen = false"
        class="fixed z-50 inset-0 overflow-y-auto" x-cloak>
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

            {{-- Overlay --}}
            <div x-show="cleanModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-500/70 bg-opacity-75 transition-opacity" @click="cleanModalOpen = false">
            </div>

            {{-- Konten Modal --}}
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="cleanModalOpen" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0  sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4  sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full z-10 relative">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 ">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                            {{-- Ikon Centang Hijau --}}
                            <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Konfirmasi Selesai Bersih
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Anda yakin kamar ini sudah bersih dan siap digunakan? Status kamar akan diubah
                                    menjadi 'Available'.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    {{-- Form ada di dalam modal --}}
                    <form :action="cleanFormAction" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Ya, Selesai
                        </button>
                    </form>
                    <button @click="cleanModalOpen = false" type="button"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection