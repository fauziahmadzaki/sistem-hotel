@extends('layouts.dashboard')

@section('contents')
<div x-data="{ deleteModalOpen: false, deleteFormAction: '' }">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Kelola Tipe Kamar</h1>
        <a href="{{ route('dashboard.room-types.create') }}"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-violet-600 hover:bg-violet-700">
            + Tambah Tipe Kamar
        </a>
    </div>

    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Tipe Kamar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah Fasilitas
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah Kamar</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($roomTypes as $roomType)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $roomType->room_type_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $roomType->facilities_count }}
                            Fasilitas</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $roomType->rooms_count }} Kamar
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <a href="{{ route('dashboard.room-types.edit', $roomType) }}"
                                class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            <button typef="button"
                                @click.prevent="deleteModalOpen = true; deleteFormAction = '{{ route('dashboard.room-types.destroy', $roomType) }}'"
                                class="text-red-600 hover:text-red-900">
                                Hapus
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            Belum ada tipe kamar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($roomTypes->hasPages())
        <div class="p-4 bg-gray-50 border-t border-gray-200">{{ $roomTypes->links() }}</div>
        @endif
    </div>

    {{-- Modal Konfirmasi Hapus --}}
    @include('partials._delete_modal')
</div>
@endsection