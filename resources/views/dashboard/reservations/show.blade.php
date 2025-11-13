@extends('layouts.dashboard')

@section('contents')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold text-gray-900">Detail Reservasi #{{ $reservation->id }}</h1>
    <div class="flex space-x-2">
        <a href="{{ route('dashboard.reservations.index') }}"
            class="py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            Kembali
        </a>
        <a href="{{ route('dashboard.reservations.invoice', $reservation) }}" target="_blank"
            class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
            Cetak Invoice
        </a>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    {{-- Kolom Kiri: Detail Tamu & Kamar --}}
    <div class="md:col-span-1 space-y-6">
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Detail Tamu</h3>
            <div class="space-y-2 text-sm">
                <p><strong class="text-gray-600">Nama:</strong> {{ $reservation->name }}</p>
                <p><strong class="text-gray-600">Telepon:</strong> {{ $reservation->phone_number }}</p>
                <p><strong class="text-gray-600">No. ID:</strong> {{ $reservation->identity }}</p>
                @if($reservation->user)
                <p><strong class="text-gray-600">Akun:</strong> {{ $reservation->user->name }} ({{
                    $reservation->user->email }})</p>
                @endif
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Detail Kamar</h3>
            <div class="space-y-2 text-sm">
                <p><strong class="text-gray-600">Kamar:</strong> {{ $reservation->room->room_name }} ({{
                    $reservation->room->room_code }})</p>
                <p><strong class="text-gray-600">Tipe:</strong> {{ $reservation->room->roomType->room_type_name }}</p>
                <p><strong class="text-gray-600">Check-in:</strong>
                    {{ \Carbon\Carbon::parse($reservation->checkin_date)->format('d M Y') }}</p>
                <p><strong class="text-gray-600">Check-out:</strong>
                    {{ \Carbon\Carbon::parse($reservation->checkout_date)->format('d M Y') }}</p>
                <p><strong class="text-gray-600">Durasi:</strong> {{ $durationInDays }} hari</p>
            </div>
        </div>
    </div>

    {{-- Kolom Kanan: Keuangan & Status --}}
    <div class="md:col-span-2 space-y-6">
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Rincian Keuangan</h3>
            <div class="space-y-3">
                <div class="flex justify-between text-md">
                    <span class="text-gray-700">Biaya Kamar ({{ $durationInDays }} hari x Rp
                        {{ number_format($reservation->room->room_price, 0, ',', '.') }})</span>
                    <span class="font-medium text-gray-900">Rp {{ number_format($roomCost, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-md">
                    <span class="text-gray-700">Denda</span>
                    <span class="font-medium text-gray-900">Rp
                        {{ number_format($reservation->fines, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-lg font-semibold pt-2 border-t">
                    <span class="text-gray-900">Total Tagihan</span>
                    <span class="text-gray-900">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-md">
                    <span class="text-gray-700">Deposit (Jaminan) Dibayar</span>
                    <span class="font-medium text-green-600">- Rp
                        {{ number_format($reservation->deposit, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-xl font-bold pt-2 border-t">
                    @if ($balanceDue >= 0)
                    <span class="text-red-700">Sisa Tagihan</span>
                    <span class="text-red-700">Rp {{ number_format($balanceDue, 0, ',', '.') }}</span>
                    @else
                    <span class="text-green-700">Uang Kembali (Refund)</span>
                    <span class="text-green-700">Rp {{ number_format(abs($balanceDue), 0, ',', '.') }}</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Status Reservasi & Kamar</h3>

            {{-- Status Reservasi --}}
            <div class="mb-4">
                <strong class="text-gray-600 text-sm">Status Reservasi:</strong>
                @php
                $statusClasses = [
                'checkin' => 'bg-blue-100 text-blue-800',
                'pending' => 'bg-yellow-100 text-yellow-800',
                'completed' => 'bg-green-100 text-green-800',
                'cancelled' => 'bg-red-100 text-red-800',
                ];
                $statusText = [
                'checkin' => 'Check-in',
                'pending' => 'Pending (Menunggu Cek Housekeeping)',
                'completed' => 'Selesai (Sudah Checkout)',
                'cancelled' => 'Dibatalkan',
                ];
                @endphp
                <span
                    class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $statusClasses[$reservation->status] ?? 'bg-gray-100 text-gray-800' }}">
                    {{ $statusText[$reservation->status] ?? $reservation->status }}
                </span>
            </div>

            {{-- Status Pengecekan Housekeeping --}}
            <div>
                <strong class="text-gray-600 text-sm mb-2 block">Status Pengecekan Housekeeping:</strong>
                @if (!$checkStatus)
                <div class="bg-gray-50 border-l-4 border-gray-400 p-4">
                    <p class="text-sm text-gray-700">Pengecekan belum diminta.</p>
                </div>
                @elseif ($checkStatus->status == 'needs_to_be_done')
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                    <p class="text-sm text-yellow-700">Menunggu konfirmasi dari: <strong
                            class="font-medium">{{ $checkStatus->housekeeper->name }}</strong>.</p>
                </div>
                @else
                <div class="bg-green-50 border-l-4 border-green-400 p-4">
                    <h3 class="text-sm font-medium text-green-800">Pengecekan Selesai (oleh
                        {{ $checkStatus->housekeeper->name }})</h3>
                    <div class="mt-2 text-sm text-green-700">
                        <p>{{ $checkStatus->notes ?? 'Tidak ada catatan kerusakan atau kehilangan.' }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection