<div class="bg-white shadow-sm rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tamu</th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kamar
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jadwal
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deposit
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($reservations as $reservation)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $reservation->name }}</div>
                        <div class="text-sm text-gray-500">{{ $reservation->phone_number }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $reservation->room->room_name }}</div>
                        <div class="text-sm text-gray-500">{{ $reservation->room->room_code }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">
                            <span class="font-medium">In:</span>
                            {{ \Carbon\Carbon::parse($reservation->checkin_date)->format('d M Y') }}
                        </div>
                        <div class="text-sm text-gray-900">
                            <span class="font-medium">Out:</span>
                            {{ \Carbon\Carbon::parse($reservation->checkout_date)->format('d M Y') }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                        $statusClasses = [
                        'checkin' => 'bg-blue-100 text-blue-800',
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'completed' => 'bg-green-100 text-green-800',
                        'cancelled' => 'bg-red-100 text-red-800',
                        ];
                        $statusText = [
                        'checkin' => 'Check-in',
                        'pending' => 'Pending',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                        ];
                        @endphp
                        <span
                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses[$reservation->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $statusText[$reservation->status] ?? $reservation->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        Rp {{ number_format($reservation->deposit, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                        <a href="{{ route('dashboard.reservations.show', $reservation) }}"
                            class="text-gray-600 hover:text-gray-900">Detail</a>
                        @if ($pageType === 'active')
                        <a href="{{ route('dashboard.reservations.checkout.form', $reservation) }}"
                            class="text-green-600 hover:text-green-900">Checkout</a>
                        <a href="{{ route('dashboard.reservations.edit', $reservation) }}"
                            class="text-indigo-600 hover:text-indigo-900">Edit</a>
                        <button type="button"
                            @click.prevent="cancelModalOpen = true; cancelFormAction = '{{ route('dashboard.reservations.cancel', $reservation) }}'"
                            class="text-red-600 hover:text-red-900">
                            Batal
                        </button>
                        @else
                        <span class="text-gray-400">N/A</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                        Tidak ada data reservasi.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($reservations->hasPages())
    <div class="p-4 bg-gray-50 border-t border-gray-200">
        {{ $reservations->links() }}
    </div>
    @endif
</div>