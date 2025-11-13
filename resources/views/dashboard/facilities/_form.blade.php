@csrf
@php $facility = $facility ?? null; @endphp

<div class="bg-white p-6 rounded-lg shadow-sm">
    <div class="space-y-4">
        <div>
            <label for="facility_name" class="block text-sm font-medium text-gray-700">Nama Fasilitas</label>
            <input type="text" name="facility_name" id="facility_name"
                value="{{ old('facility_name', $facility?->facility_name) }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 sm:text-sm"
                required>
            @error('facility_name') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="flex justify-end pt-6">
        <a href="{{ route('dashboard.facilities.index') }}"
            class="py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            Batal
        </a>
        <button type="submit"
            class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-violet-600 hover:bg-violet-700">
            {{ $facility ? 'Update Fasilitas' : 'Simpan Fasilitas' }}
        </button>
    </div>
</div>