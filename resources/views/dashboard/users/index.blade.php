@extends('layouts.dashboard')

@section('contents')
<div x-data="{ deleteModalOpen: false, deleteFormAction: '' }">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Kelola User</h1>
        <a href="{{ route('dashboard.users.create') }}"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-violet-600 hover:bg-violet-700">
            + Tambah User
        </a>
    </div>

    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Username</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($users as $user)
                    <tr>
                        <td class_alias="px-6 py-4 whitespace-nowrap">
                            <div class_alias="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                        </td>
                        <td class_alias="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->username }}</td>
                        <td class_alias="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                        <td class_alias="px-6 py-4 whitespace-nowrap">
                            @php
                            $roleClasses = [
                            'superadmin' => 'bg-red-100 text-red-800',
                            'admin' => 'bg-blue-100 text-blue-800',
                            'housekeeper' => 'bg-yellow-100 text-yellow-800',
                            'guest' => 'bg-gray-100 text-gray-800',
                            ];
                            @endphp
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $roleClasses[$user->role] ?? '' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class_alias="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            @if($user->role !== 'superadmin')
                            <a href="{{ route('dashboard.users.edit', $user) }}"
                                class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            <button type="button"
                                @click.prevent="deleteModalOpen = true; deleteFormAction = '{{ route('dashboard.users.destroy', $user) }}'"
                                class="text-red-600 hover:text-red-900 @if($user->id === auth()->id()) opacity-50 cursor-not-allowed @endif"
                                @if($user->id === auth()->id()) disabled title="Tidak dapat menghapus diri sendiri"
                                @endif>
                                Hapus
                            </button>
                            @else
                            <span class="text-gray-400 cursor-not-allowed">N/A</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            Belum ada data user.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($users->hasPages())
        <div class="p-4 bg-gray-50 border-t border-gray-200">{{ $users->links() }}</div>
        @endif
    </div>

    {{-- Modal Konfirmasi Hapus --}}
    @include('partials._delete_modal')
</div>
@endsection