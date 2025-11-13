@extends('layouts.dashboard')

@section('contents')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold text-gray-900">Riwayat Tugas Selesai (Pengecekan)</h1>
</div>

<div class="bg-white shadow-sm rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kamar
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tamu
                        (Reservasi)</th>
                    @if(auth()->user()->role != 'housekeeper')
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diperiksa
                        Oleh</th>
                    @endif
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Selesai
                        Pada</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($completedChecks as $check)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $check->reservation->room->room_name }}</div>
                        <div class="text-sm text-gray-500">{{ $check->reservation->room->room_code }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $check->reservation->name }}</div>
                        <div class="text-sm text-gray-500">Reservasi #{{ $check->reservation_id }}</div>
                    </td>
                    @if(auth()->user()->role != 'housekeeper')
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $check->housekeeper->name }}
                    </td>
                    @endif
                    <td class="px-6 py-4">
                        <span class="text-sm text-gray-700">{{ $check->notes ?? '-' }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $check->updated_at->format('d M Y, H:i') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ auth()->user()->role != 'housekeeper' ? 5 : 4 }}"
                        class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                        Belum ada riwayat tugas yang selesai.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($completedChecks->hasPages())
    <div class="p-4 bg-gray-50 border-t border-gray-200">
        {{ $completedChecks->links() }}
    </div>
    @endif
</div>
@endsection