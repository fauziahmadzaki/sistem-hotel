@csrf
@php $room = $room ?? null; @endphp

<div class="bg-white p-6 rounded-lg shadow-sm">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Kolom Kiri --}}
        <div class="space-y-4">
            <div>
                <label for="room_name" class="block text-sm font-medium text-gray-700">Nama Kamar</label>
                <input type="text" name="room_name" id="room_name" value="{{ old('room_name', $room?->room_name) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 sm:text-sm"
                    required placeholder="Contoh: Deluxe Room 101">
                @error('room_name') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="room_code" class="block text-sm font-medium text-gray-700">Kode Kamar</label>
                <input type="text" name="room_code" id="room_code" value="{{ old('room_code', $room?->room_code) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 sm:text-sm"
                    required placeholder="Contoh: DLX-101">
                @error('room_code') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="room_type_id" class="block text-sm font-medium text-gray-700">Tipe Kamar</label>
                <select name="room_type_id" id="room_type_id"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 sm:text-sm"
                    required>
                    <option value="" disabled selected>-- Pilih Tipe Kamar --</option>
                    @foreach ($roomTypes as $type)
                    <option value="{{ $type->id }}" @selected(old('room_type_id', $room?->room_type_id) == $type->id)>
                        {{ $type->room_type_name }}
                    </option>
                    @endforeach
                </select>
                @error('room_type_id') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>
        </div>

        {{-- Kolom Kanan --}}
        <div class="space-y-4">
            <div>
                <label for="room_price" class="block text-sm font-medium text-gray-700">Harga per Malam (Rp)</label>
                <input type="number" name="room_price" id="room_price"
                    value="{{ old('room_price', $room?->room_price) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 sm:text-sm"
                    min="0" required placeholder="Contoh: 500000">
                @error('room_price') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="room_capacity" class="block text-sm font-medium text-gray-700">Kapasitas Tamu</label>
                <input type="number" name="room_capacity" id="room_capacity"
                    value="{{ old('room_capacity', $room?->room_capacity) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 sm:text-sm"
                    min="1" required placeholder="Contoh: 2">
                @error('room_capacity') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>
        </div>

    </div>

    {{-- Deskripsi (Lebar Penuh) --}}
    <div class="mt-6">
        <label for="room_description" class="block text-sm font-medium text-gray-700">Deskripsi Kamar (Opsional)</label>
        <textarea name="room_description" id="room_description" rows="4"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 sm:text-sm">{{ old('room_description', $room?->room_description) }}</textarea>
        @error('room_description') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
    </div>

    <div class="flex justify-end pt-6">
        <a href="{{ route('dashboard.rooms.index') }}"
            class="py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            Batal
        </a>
        <button type="submit"
            class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-violet-600 hover:bg-violet-700">
            {{ $room ? 'Update Kamar' : 'Simpan Kamar' }}
        </button>
    </div>
</div>