@extends('layouts.dashboard')

@section('contents')
<style>
    @media print {
        body * {
            visibility: hidden;
        }

        .print-area,
        .print-area * {
            visibility: visible;
        }

        .print-area {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            padding: 0;
        }

        .no-print {
            display: none !important;
        }
    }
</style>

<div class="no-print flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold text-gray-900">Laporan Tutup Kasir (Harian/Shift)</h1>
    <div class="flex space-x-2">
        <a href="{{ route('dashboard.transactions.index') }}"
            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50">
            Kembali ke Semua Transaksi
        </a>
        {{-- (BARU) Tombol Cetak --}}
        <button type="button" @click.prevent="window.print()"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
            Cetak Laporan
        </button>
    </div>
</div>

{{-- Filter Tanggal & Waktu --}}
<div class="no-print mb-6 bg-white p-4 rounded-lg shadow-sm">
    <form action="{{ route('dashboard.transactions.report') }}" method="GET">
        <div class="flex items-end space-x-4">
            <div class="flex-1">
                <label for="date" class="block text-sm font-medium text-gray-700">Tanggal Laporan</label>
                <input type="date" name="date" id="date" value="{{ $date->format('Y-m-d') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
            </div>
            {{-- (BARU) Filter Waktu --}}
            <div class="flex-1">
                <label for="start_time" class="block text-sm font-medium text-gray-700">Dari Jam</label>
                <input type="time" name="start_time" id="start_time" value="{{ old('start_time', $startTime) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
            </div>
            <div class="flex-1">
                <label for="end_time" class="block text-sm font-medium text-gray-700">Sampai Jam</label>
                <input type="time" name="end_time" id="end_time" value="{{ old('end_time', $endTime) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
            </div>
            <button type="submit"
                class="inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-violet-600 hover:bg-violet-700">
                Lihat Laporan
            </button>
        </div>
    </form>
</div>

{{-- Laporan (Dibungkus dengan .print-area) --}}
<div class="print-area bg-white shadow-sm rounded-lg overflow-hidden">
    <div class="p-6">
        {{-- (DIPERBARUI) Judul Laporan --}}
        <h2 class="text-xl font-semibold text-gray-800 mb-1">Rekap Keuangan</h2>
        <p class="text-sm text-gray-600 mb-4">
            Untuk: {{ $date->format('d F Y') }}, dari jam {{ $startTime }} s/d {{ $endTime }}
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            {{-- Kolom Pemasukan --}}
            <div class="space-y-4">
                <h3 class="text-lg font-medium text-green-700 border-b border-gray-200 pb-2">Pemasukan (Income)</h3>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Sewa Kamar</span>
                    <span class="font-medium text-gray-900">Rp {{ number_format($rentalIncome, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Deposit Diterima</span>
                    <span class="font-medium text-gray-900">Rp {{ number_format($depositIncome, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Denda</span>
                    <span class="font-medium text-gray-900">Rp {{ number_format($finesIncome, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Maintenance (Income)</span>
                    <span class="font-medium text-gray-900">Rp
                        {{ number_format($maintenanceIncome, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Lain-lain</span>
                    <span class="font-medium text-gray-900">Rp {{ number_format($otherIncome, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-lg font-semibold pt-2 border-t">
                    <span class="text-green-800">Total Pemasukan</span>
                    <span class="text-green-800">Rp {{ number_format($totalIncome, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Kolom Pengeluaran --}}
            <div class="space-y-4">
                <h3 class="text-lg font-medium text-red-700 border-b border-gray-200 pb-2">Pengeluaran (Expense)</h3>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Deposit Dikembalikan</span>
                    <span class="font-medium text-gray-900">Rp {{ number_format($depositExpense, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Biaya Maintenance</span>
                    <span class="font-medium text-gray-900">Rp
                        {{ number_format($maintenanceExpense, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Lain-lain</span>
                    <span class="font-medium text-gray-900">Rp {{ number_format($otherExpense, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-lg font-semibold pt-2 border-t">
                    <span class="text-red-800">Total Pengeluaran</span>
                    <span class="text-red-800">- Rp {{ number_format($totalExpense, 0, ',', '.') }}</span>
                </div>
            </div>

        </div>

        {{-- Total Akhir --}}
        <div class="mt-8 pt-6 border-t border-gray-300">
            <div class="flex justify-between text-2xl font-bold">
                <span class="text-gray-900">Laba Bersih (Net Profit)</span>
                @if($netProfit >= 0)
                <span class="text-green-700">Rp {{ number_format($netProfit, 0, ',', '.') }}</span>
                @else
                <span class="text-red-700">-Rp {{ number_format(abs($netProfit), 0, ',', '.') }}</span>
                @endif
            </div>
            <p class="text-sm text-gray-500 mt-2 text-right">(Total Pemasukan - Total Pengeluaran)</p>
        </div>
    </div>
</div>
@endsection