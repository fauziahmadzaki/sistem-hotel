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

<div x-data="{ deleteModalOpen: false, deleteFormAction: '' }">
    <div class="no-print flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Laporan Keuangan (Semua Transaksi)</h1>
        <div class="flex space-x-2">
            <a href="{{ route('dashboard.transactions.report') }}"
                class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50">
                Laporan Tutup Kasir
            </a>
            <a href="{{ route('dashboard.transactions.create') }}"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-violet-600 hover:bg-violet-700">
                + Catat Transaksi
            </a>
            {{-- (BARU) Tombol Cetak --}}
            <button type="button" @click.prevent="window.print()"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                Cetak Laporan
            </button>
        </div>
    </div>

    {{-- Form Filter (Sembunyikan saat print) --}}
    <div class="no-print mb-6 bg-white p-4 rounded-lg shadow-sm">
        <form action="{{ route('dashboard.transactions.index') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700">Dari Tanggal</label>
                    <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700">Sampai Tanggal</label>
                    <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                </div>
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700">Tipe</label>
                    <select name="type" id="type"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                        <option value="">Semua Tipe</option>
                        <option value="income" @selected(request('type')=='income' )>Pemasukan</option>
                        <option value="expense" @selected(request('type')=='expense' )>Pengeluaran</option>
                    </select>
                </div>
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700">Kategori</label>
                    <select name="category" id="category"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                        <option value="">Semua Kategori</option>
                        <option value="rental" @selected(request('category')=='rental' )>Sewa Kamar</option>
                        <option value="deposit" @selected(request('category')=='deposit' )>Deposit</option>
                        <option value="maintenance" @selected(request('category')=='maintenance' )>Maintenance</option>
                        <option value="other" @selected(request('category')=='other' )>Lain-lain</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end mt-4">
                <a href="{{ route('dashboard.transactions.index') }}"
                    class="py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Reset</a>
                <button type="submit"
                    class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-violet-600 hover:bg-violet-700">
                    Filter
                </button>
            </div>
        </form>
    </div>

    {{-- Tabel Transaksi (Area Cetak) --}}
    <div class="print-area bg-white shadow-sm rounded-lg overflow-hidden">

        {{-- (BARU) Judul untuk Halaman Cetak --}}
        <div class="hidden print:block p-4">
            <h1 class="text-2xl font-semibold text-gray-900">Laporan Keuangan</h1>
            <p class="text-sm text-gray-600">
                Periode: {{ request('start_date', 'Semua') }} s/d {{ request('end_date', 'Semua') }}
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deskripsi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Pemasukan (Rp)</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Pengeluaran (Rp)
                        </th>
                        <th class="no-print px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($transactions as $tx)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $tx->created_at->format('d M Y, H:i') }}</td>
                        <td class="px-6 py-4 max-w-sm">
                            <p class="text-sm text-gray-900 truncate" title="{{ $tx->description }}">
                                {{ $tx->description }}</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ucfirst($tx->category) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                            @if($tx->type == 'income')
                            <span class="text-green-600">+ {{ number_format($tx->amount, 0, ',', '.') }}</span>
                            @else
                            -
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                            @if($tx->type == 'expense')
                            <span class="text-red-600">- {{ number_format($tx->amount, 0, ',', '.') }}</span>
                            @else
                            -
                            @endif
                        </td>
                        <td class="no-print px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <a href="{{ route('dashboard.transactions.edit', $tx) }}"
                                class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            <button type="button"
                                @click.prevent="deleteModalOpen = true; deleteFormAction = '{{ route('dashboard.transactions.destroy', $tx) }}'"
                                class="text-red-600 hover:text-red-900">
                                Hapus
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            Tidak ada data transaksi.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                {{-- Footer Total --}}
                <tfoot class="bg-gray-100">
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-right text-sm font-bold text-gray-700 uppercase">Total
                            (dari filter)</td>
                        <td class="px-6 py-4 text-right text-sm font-bold text-green-700">+ Rp
                            {{ number_format($totalIncome, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right text-sm font-bold text-red-700">- Rp
                            {{ number_format($totalExpense, 0, ',', '.') }}</td>
                        <td class="no-print px-6 py-4 text-right text-sm font-bold text-gray-900">
                            @if($netTotal >= 0)
                            <span class="text-green-700">Rp {{ number_format($netTotal, 0, ',', '.') }}</span>
                            @else
                            <span class="text-red-700">-Rp {{ number_format(abs($netTotal), 0, ',', '.') }}</span>
                            @endif
                        </td>
                        {{-- (BARU) Kolom Aksi di Footer (kosong) --}}
                        <td class="no-print px-6 py-4"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @if ($transactions->hasPages())
        <div class="no-print p-4 bg-gray-50 border-t border-gray-200">{{ $transactions->links() }}</div>
        @endif
    </div>

    {{-- Modal Konfirmasi Hapus --}}
    @include('partials._delete_modal')
</div>
@endsection