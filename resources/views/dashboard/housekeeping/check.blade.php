@extends('layouts.dashboard')

@section('contents')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold text-gray-900">Laporan Cek Kamar: {{ $reservation->room->room_name }}</h1>
</div>

@if ($errors->any())
<div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
    <strong class="font-bold">Oops! </strong>
    <span class="block sm:inline">Terjadi kesalahan.</span>
</div>
@endif

<form action="{{ route('dashboard.housekeeping.check.process', $check) }}" method="POST">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Kolom Kiri: Detail Reservasi --}}
        <div class="md:col-span-1 space-y-6">
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Detail Reservasi</h3>
                <div class="space-y-2 text-sm">
                    <p><strong class="text-gray-600">No. Reservasi:</strong> #{{ $reservation->id }}</p>
                    <p><strong class="text-gray-600">Tamu:</strong> {{ $reservation->name }}</p>
                    <p><strong class="text-gray-600">Telepon:</strong> {{ $reservation->phone_number }}</p>
                    <p><strong class="text-gray-600">Check-in:</strong>
                        {{ \Carbon\Carbon::parse($reservation->checkin_date)->format('d M Y') }}</p>
                    <p><strong class="text-gray-600">Check-out:</strong>
                        {{ \Carbon\Carbon::parse($reservation->checkout_date)->format('d M Y') }}</p>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Form Laporan --}}
        <div class="md:col-span-2">
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Form Laporan Pengecekan</h3>
                <div class="space-y-4">

                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700">Catatan Kerusakan /
                            Kehilangan (Opsional)</label>
                        <textarea name="notes" id="notes" rows="6"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 sm:text-sm"
                            placeholder="Tuliskan jika ada kerusakan barang, barang yang hilang, atau semua aman terkendali. Catatan ini akan diteruskan ke resepsionis untuk penagihan denda.">{{ old('notes') }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Jika tidak ada masalah, Anda bisa mengosongkan ini atau
                            tulis "OK".</p>
                        @error('notes') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end pt-4">
                        <a href="{{ route('dashboard.housekeeping.index') }}"
                            class="py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            Kembali
                        </a>
                        <button type="submit"
                            class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Selesaikan Pengecekan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection