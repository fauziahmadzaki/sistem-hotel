<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $reservation->id }}</title>
    {{-- Kita akan menggunakan Tailwind via CDN di sini agar CSS-nya independen --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.bunny.net/css?family=instrument-sans:400,500,600');

        body {
            font-family: 'Instrument Sans', sans-serif;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                -webkit-print-color-adjust: exact;
                /* Memaksa print warna background */
                print-color-adjust: exact;
            }
        }
    </style>
</head>

<body class="bg-gray-100">

    {{-- Tombol Aksi (Hanya tampil di layar) --}}
    <div class="no-print max-w-4xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between">
            <a href="{{ route('dashboard.reservations.show', $reservation) }}"
                class="text-sm font-medium text-gray-600 hover:text-gray-900">&larr; Kembali ke Detail</a>
            <button type="button" onclick="window.print()"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-violet-600 hover:bg-violet-700">
                Cetak Invoice
            </button>
        </div>
    </div>

    {{-- Area Cetak --}}
    <div class="print-area max-w-4xl mx-auto bg-white shadow-lg rounded-lg my-8">
        <div class="p-8 sm:p-12">
            {{-- Header Invoice --}}
            <div class="flex justify-between items-start pb-6 border-b border-gray-200">
                <div>
                    <h1 class="text-3xl font-bold text-violet-600">Hotelio</h1>
                    <p class="text-sm text-gray-500">Jalan Merdeka No. 123, Kota, 12345</p>
                    <p class="text-sm text-gray-500">admin@hotelio.com</p>
                </div>
                <div class="text-right">
                    <h2 class="text-3xl font-semibold text-gray-800">INVOICE</h2>
                    <p class="text-sm text-gray-500">No: #{{ $reservation->id }}</p>
                    <p class="text-sm text-gray-500">Tanggal: {{ $reservation->updated_at->format('d M Y') }}</p>
                </div>
            </div>

            {{-- Detail Tamu & Reservasi --}}
            <div class="grid grid-cols-2 gap-6 mt-8">
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Tagihan Kepada:</h3>
                    <p class="text-lg font-medium text-gray-900">{{ $reservation->name }}</p>
                    <p class="text-sm text-gray-600">{{ $reservation->phone_number }}</p>
                    <p class="text-sm text-gray-600">ID: {{ $reservation->identity }}</p>
                </div>
                <div class="text-right">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Detail Menginap:</h3>
                    <p class="text-sm text-gray-900">Check-in:
                        {{ \Carbon\Carbon::parse($reservation->checkin_date)->format('d M Y') }}</p>
                    <p class="text-sm text-gray-900">Check-out:
                        {{ \Carbon\Carbon::parse($reservation->checkout_date)->format('d M Y') }}</p>
                    <p class="text-sm text-gray-900">Durasi: {{ $durationInDays }} Hari</p>
                </div>
            </div>

            {{-- Tabel Rincian Biaya --}}
            <div class="mt-10">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Deskripsi</th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Harga Satuan</th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jumlah</th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-gray-900">{{ $reservation->room->room_name }}</p>
                                <p class="text-sm text-gray-500">{{ $reservation->room->roomType->room_type_name }}</p>
                            </td>
                            <td class="px-6 py-4 text-right text-sm text-gray-700">Rp
                                {{ number_format($reservation->room->room_price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right text-sm text-gray-700">x {{ $durationInDays }} hari</td>
                            <td class="px-6 py-4 text-right text-sm font-medium text-gray-900">Rp
                                {{ number_format($roomCost, 0, ',', '.') }}</td>
                        </tr>
                        @if($reservation->fines > 0)
                        <tr>
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-gray-900">Denda</p>
                                <p class="text-sm text-gray-500">Kerusakan atau kehilangan (sesuai catatan housekeeping)
                                </p>
                            </td>
                            <td class="px-6 py-4 text-right text-sm text-gray-700">-</td>
                            <td class="px-6 py-4 text-right text-sm text-gray-700">x 1</td>
                            <td class="px-6 py-4 text-right text-sm font-medium text-gray-900">Rp
                                {{ number_format($reservation->fines, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            {{-- Total Kalkulasi --}}
            <div class="mt-8 flex justify-end">
                <div class="w-full max-w-md space-y-4">
                    <div class="flex justify-between text-lg">
                        <span class="text-gray-700">Subtotal</span>
                        <span class="font-medium text-gray-900">Rp
                            {{ number_format($totalTagihan, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-lg">
                        <span class="text-gray-700">Deposit Dibayar</span>
                        <span class="font-medium text-green-600">- Rp
                            {{ number_format($reservation->deposit, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-2xl font-bold pt-4 border-t border-gray-200">
                        @if ($balanceDue >= 0)
                        <span class="text-gray-900">Total Tagihan</span>
                        <span class="text-red-600">Rp {{ number_format($balanceDue, 0, ',', '.') }}</span>
                        @else
                        <span class="text-gray-900">Total Pengembalian</span>
                        <span class="text-green-600">Rp {{ number_format(abs($balanceDue), 0, ',', '.') }}</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Footer Invoice --}}
            <div class="mt-12 pt-6 border-t border-gray-200 text-center">
                <p class="text-sm text-gray-500">Terima kasih telah menginap di Hotelio.</p>
            </div>
        </div>
    </div>

</body>

</html>