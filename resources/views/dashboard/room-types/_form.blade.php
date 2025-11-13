@csrf
@php
$roomType = $roomType ?? null;
$attachedFacilities = $attachedFacilities ?? []; // Dari controller (untuk edit)
@endphp

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    {{-- Kolom Kiri: Info Dasar --}}
    <div class="md:col-span-2 bg-white p-6 rounded-lg shadow-sm">
        <div class="space-y-4">
            <div>
                <label for="room_type_name" class="block text-sm font-medium text-gray-700">Nama Tipe Kamar</label>
                <input type="text" name="room_type_name" id="room_type_name"
                    value="{{ old('room_type_name', $roomType?->room_type_name) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 sm:text-sm"
                    required>
                @error('room_type_name') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi (Opsional)</label>
                <textarea name="description" id="description" rows="4"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 sm:text-sm">{{ old('description', $roomType?->description) }}</textarea>
                @error('description') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>

    {{-- Kolom Kanan: Fasilitas --}}
    <div class="md:col-span-1 bg-white p-6 rounded-lg shadow-sm">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Fasilitas Tipe Kamar</h3>
        <div class="space-y-3 max-h-60 overflow-y-auto pr-2">
            @forelse ($facilities as $facility)
            <div class="flex items-center">
                <input id="facility-{{ $facility->id }}" name="facilities[]" type="checkbox" value="{{ $facility->id }}"
                    @checked(in_array($facility->id, old('facilities', $attachedFacilities)))
                class="h-4 w-4 text-violet-600 border-gray-300 rounded focus:ring-violet-500">
                <label for="facility-{{ $facility->id }}"
                    class="ml-3 text-sm text-gray-700">{{ $facility->facility_name }}</label>
            </div>
            @empty
            <p class="text-sm text-gray-500">Silakan tambah data fasilitas terlebih dahulu.</p>
            @endforelse
        </div>
        @error('facilities') <span class="text-sm text-red-600 mt-4 d-block">{{ $message }}</span> @enderror
    </div>

</div>

<div class="flex justify-end pt-6">
    <a href="{{ route('dashboard.room-types.index') }}"
        class="py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
        Batal
    </a>
    <button type="submit"
        class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-violet-600 hover:bg-violet-700">
        {{ $roomType ? 'Update Tipe Kamar' : 'Simpan Tipe Kamar' }}
    </button>
</div>