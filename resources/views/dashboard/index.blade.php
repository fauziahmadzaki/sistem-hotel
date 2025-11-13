@extends('layouts.dashboard')

@section('contents')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold text-gray-900">Dashboard Superadmin (Global)</h1>
</div>

{{-- Statistik Global --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    {{-- Kolom Laba/Rugi --}}
    <div class="md:col-span-1 space-y-6">
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <h3 class="text-lg font-medium text-gray-900">Total Pemasukan</h3>
            <p class="mt-2 text-3xl font-bold text-green-600">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <h3 class="text-lg font-medium text-gray-900">Total Pengeluaran</h3>
            <p class="mt-2 text-3xl font-bold text-red-600">Rp {{ number_format($totalExpense, 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- Kolom Laba Bersih --}}
    <div class="md:col-span-2">
        <div class="bg-gradient-to-r from-violet-600 to-indigo-600 text-white p-8 rounded-lg shadow-lg">
            <h3 class="text-2xl font-semibold">Laba Bersih (Net Profit)</h3>
            <p class="mt-4 text-5xl font-bold">
                @if($netProfit >= 0)
                Rp {{ number_format($netProfit, 0, ',', '.') }}
                @else
                -Rp {{ number_format(abs($netProfit), 0, ',', '.') }}
                @endif
            </p>
            <p class="mt-2 opacity-90">(Total Pemasukan - Total Pengeluaran)</p>
        </div>
    </div>
</div>

{{-- Statistik Operasional & Aset --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-8">
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <h3 class="text-lg font-medium text-gray-900">Reservasi Selesai</h3>
        <p class="mt-2 text-3xl font-bold text-blue-600">{{ number_format($totalReservationsCompleted) }}</p>
        <p class="text-sm text-gray-500">Total transaksi selesai</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <h3 class="text-lg font-medium text-gray-900">Tamu Dilayani</h3>
        <p class="mt-2 text-3xl font-bold text-blue-600">{{ number_format($totalGuestsServed) }}</p>
        <p class="text-sm text-gray-500">Total tamu dari reservasi selesai</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <h3 class="text-lg font-medium text-gray-900">Jumlah Kamar</h3>
        <p class="mt-2 text-3xl font-bold text-gray-700">{{ $totalRooms }}</AF>
        <p class="text-sm text-gray-500">Total unit kamar</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <h3 class="text-lg font-medium text-gray-900">Jumlah Staf</h3>
        <p class="mt-2 text-3xl font-bold text-gray-700">{{ $totalStaff }}</AF>
        <p class="text-sm text-gray-500">(Admin & Housekeeper)</p>
    </div>
</div>
@endsection