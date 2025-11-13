@extends('layouts.dashboard')

@section('contents')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold text-gray-900">Proses Checkout: Reservasi #{{ $reservation->id }}</h1>
</div>

@if ($errors->any())
<div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
    <strong class="font-bold">Oops! </strong>
    <span class="block sm:inline">Terjadi kesalahan. Silakan periksa input Anda.</span>
</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    {{-- Kolom Kiri: Detail Tamu & Kamar (Tetap tampil) --}}
    <div class="md:col-span-1 space-y-6">
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Detail Tamu</h3>
            <div class="space-y-2 text-sm">
                <p><strong class="text-gray-600">Nama:</strong> {{ $reservation->name }}</p>
                <p><strong class="text-gray-600">Telepon:</strong> {{ $reservation->phone_number }}</p>
                <p><strong class="text-gray-600">No. ID:</strong> {{ $reservation->identity }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Detail Kamar</h3>
            <div class="space-y-2 text-sm">
                <p><strong class="text-gray-600">Kamar:</strong> {{ $reservation->room->room_name }}</p>
                <p><strong class="text-gray-600">Check-in:</strong>
                    {{ \Carbon\Carbon::parse($reservation->checkin_date)->format('d M Y') }}
                </p>
                <p><strong class="text-gray-600">Check-out:</strong>
                    {{ \Carbon\Carbon::parse($reservation->checkout_date)->format('d M Y') }}
                </p>
                <p><strong class="text-gray-600">Durasi:</strong> {{ $durationInDays }} hari</p>
            </div>
        </div>
    </div>

    {{-- Kolom Kanan: Keuangan & Form (Sekarang Dinamis) --}}
    <div class="md:col-span-2">

        {{-- ====================================================== --}}
        {{-- KONDISI 1: PENGECEKAN BELUM DIMINTA                  --}}
        {{-- ====================================================== --}}
        @if (!$checkStatus)
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Langkah 1: Minta Pengecekan Kamar</h3>
            <p class="text-sm text-gray-600 mb-4">Kamar harus diperiksa oleh housekeeping sebelum checkout dapat
                diproses. Pilih housekeeper untuk ditugaskan.</p>

            <form action="{{ route('dashboard.reservations.request_check', $reservation) }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="housekeeper_id" class="block text-sm font-medium text-gray-700">Pilih
                            Housekeeper</label>
                        <select name="housekeeper_id" id="housekeeper_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 sm:text-sm"
                            required>
                            <option value="" disabled selected>-- Pilih Staf Housekeeping --</option>
                            @forelse ($housekeepers as $housekeeper)
                            <option value="{{ $housekeeper->id }}">{{ $housekeeper->name }}</option>
                            @empty
                            <option value="" disabled>Tidak ada staf housekeeping terdaftar</option>
                            @endforelse
                        </select>
                        @error('housekeeper_id') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex justify-end pt-4">
                        <a href="{{ route('dashboard.reservations.index') }}"
                            class="py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            Kembali
                        </a>
                        <button type="submit"
                            class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Kirim Permintaan Cek
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- ====================================================== --}}
        {{-- KONDISI 2: MENUNGGU PENGECEKAN                       --}}
        {{-- ====================================================== --}}
        @elseif ($checkStatus->status == 'needs_to_be_done')
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <h3 class="text-lg font-medium text-yellow-800 mb-4">Langkah 2: Menunggu Pengecekan</h3>
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.332-.21 3.03-1.742 3.03H4.42c-1.532 0-2.493-1.698-1.742-3.03l5.58-9.92zM10 13a1 1 0 100-2 1 1 0 000 2zm-1-4a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            Menunggu konfirmasi pengecekan dari: <strong
                                class="font-medium">{{ $checkStatus->housekeeper->name }}</strong>.
                        </p>
                        <p class="mt-2 text-sm text-yellow-700">
                            Halaman ini akan terbuka untuk checkout setelah status pengecekan "Done". Silakan refresh
                            halaman untuk mengecek status terbaru.
                        </p>
                    </div>
                </div>
            </div>
            <div class="flex justify-end pt-6">
                <a href="{{ route('dashboard.reservations.index') }}"
                    class="py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    Kembali
                </a>
            </div>
        </div>

        {{-- ====================================================== --}}
        {{-- KONDISI 3: PENGECEKAN SELESAI ('done')               --}}
        {{-- ====================================================== --}}
        @else
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Langkah 3: Rincian Pembayaran & Checkout</h3>

            {{-- (BARU) Menampilkan Catatan Housekeeping --}}
            <div class="mb-4 bg-green-50 border-l-4 border-green-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800">Pengecekan Selesai (oleh
                            {{ $checkStatus->housekeeper->name }})
                        </h3>
                        <div class="mt-2 text-sm text-green-700">
                            <p>
                                {{ $checkStatus->notes ?? 'Tidak ada catatan kerusakan atau kehilangan.' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Checkout Final (sama seperti sebelumnya) --}}
            <form action="{{ route('dashboard.reservations.checkout.process', $reservation) }}" method="POST">
                @csrf
                {{-- Rincian Biaya --}}
                <div class="space-y-3 mb-6 border-b pb-6">
                    <div class="flex justify-between text-lg">
                        <span class="text-gray-700">Total Biaya Kamar ({{ $durationInDays }} hari x Rp
                            {{ number_format($reservation->room->room_price, 0, ',', '.') }})</span>
                        <span class="font-medium text-gray-900">Rp {{ number_format($roomCost, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-lg">
                        <span class="text-gray-700">Deposit (Jaminan) Dibayar</span>
                        <span class="font-medium text-green-600">- Rp
                            {{ number_format($reservation->deposit, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-xl font-semibold">
                        <span class="text-gray-900">Subtotal (Tagihan - Deposit)</span>
                        <span class="text-gray-900">
                            {{ $totalDue >= 0 ? 'Rp ' : '-Rp ' }} {{ number_format(abs($totalDue), 0, ',', '.') }}
                            @if($totalDue < 0) <span class="text-sm font-normal text-green-600">(Uang Kembali)</span>
                        @endif
                        </span>
                    </div>
                </div>

                {{-- Form Input Denda & Pembayaran --}}
                <div class="space-y-4">
                    <div>
                        <label for="fines" class="block text-sm font-medium text-gray-700">
                            Tambah Denda (Berdasarkan Cek Housekeeping)
                        </label>
                        <input type="number" name="fines" id="fines" value="{{ old('fines', $reservation->fines) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 sm:text-sm"
                            placeholder="0" min="0" step="10000">
                        <p class="mt-1 text-xs text-gray-500">Denda ini akan ditambahkan ke total tagihan.</p>
                        @error('fines') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700">Metode Pembayaran
                            (Pelunasan)</label>
                        <select name="payment_method" id="payment_method"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 sm:text-sm"
                            required>
                            <option value="cash" @selected(old('payment_method', $reservation->payment_method) ==
                                'cash')>Cash</option>
                            <option value="transfer" @selected(old('payment_method', $reservation->payment_method) ==
                                'transfer')>Transfer Bank</option>
                            <option value="card" @selected(old('payment_method', $reservation->payment_method) ==
                                'card')>Kartu Kredit/Debit</option>
                        </select>
                        @error('payment_method') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700">Catatan Checkout
                            (Opsional)</label>
                        <textarea name="notes" id="notes" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 sm:text-sm">{{ old('notes', $reservation->notes) }}</textarea>
                        @error('notes') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end pt-4">
                        <a href="{{ route('dashboard.reservations.index') }}"
                            class="py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            Kembali
                        </a>
                        <button type="submit"
                            class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Selesaikan dan Checkout
                        </button>
                    </div>
                </div>
            </form>
        </div>
        @endif
    </div>

</div>
@endsection