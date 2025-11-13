@extends('layouts.dashboard')

@section('contents')
<div x-data="{ deleteModalOpen: false, deleteFormAction: '' }">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Kelola Fasilitas</h1>
        <a href="{{ route('dashboard.facilities.create') }}"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-violet-600 hover:bg-violet-700">
            + Tambah Fasilitas
        </a>
    </div>

    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Fasilitas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Digunakan di Tipe
                            Kamar</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($facilities as $facility)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $facility->facility_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $facility->room_types_count }}
                            Tipe Kamar</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <a href="{{ route('dashboard.facilities.edit', $facility) }}"
                                class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            <button typef="button"
                                @click.prevent="deleteModalOpen = true; deleteFormAction = '{{ route('dashboard.facilities.destroy', $facility) }}'"
                                class="text-red-600 hover:text-red-900">
                                Hapus
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            Belum ada fasilitas.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($facilities->hasPages())
        <div class="p-4 bg-gray-50 border-t border-gray-200">{{ $facilities->links() }}</div>
        @endif
    </div>

    {{-- Modal Konfirmasi Hapus --}}
    @include('partials._delete_modal')
</div>
@endsection