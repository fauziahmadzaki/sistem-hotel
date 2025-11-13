@extends('layouts.dashboard')

@section('contents')
<div x-data="{ deleteModalOpen: false, deleteFormAction: '' }">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Kelola Kamar</h1>
        <a href="{{ route('dashboard.rooms.create') }}"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-violet-600 hover:bg-violet-700">
            + Tambah Kamar
        </a>
    </div>

    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kamar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe Kamar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($rooms as $room)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $room->room_name }}</div>
                            <div class="text-sm text-gray-500">{{ $room->room_code }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $room->roomType->room_type_name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp
                            {{ number_format($room->room_price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                            $statusClasses = [
                            'available' => 'bg-green-100 text-green-800',
                            'booked' => 'bg-yellow-100 text-yellow-800',
                            'maintenance' => 'bg-red-100 text-red-800',
                            ];
                            $statusText = [
                            'available' => 'Available',
                            'booked' => 'Booked',
                            'maintenance' => 'Maintenance',
                            ];
                            @endphp
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses[$room->room_status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $statusText[$room->room_status] ?? $room->room_status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <a href="{{ route('dashboard.rooms.edit', $room) }}"
                                class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            <button typef="button"
                                @click.prevent="deleteModalOpen = true; deleteFormAction = '{{ route('dashboard.rooms.destroy', $room) }}'"
                                class="text-red-600 hover:text-red-900 @if($room->room_status !== 'available') opacity-50 cursor-not-allowed @endif"
                                @if($room->room_status !== 'available') disabled title="Hanya kamar 'Available' yang
                                bisa dihapus" @endif>
                                Hapus
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            Belum ada kamar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($rooms->hasPages())
        <div class="p-4 bg-gray-50 border-t border-gray-200">{{ $rooms->links() }}</div>
        @endif
    </div>

    {{-- Modal Konfirmasi Hapus --}}
    @include('partials._delete_modal')
</div>
@endsection