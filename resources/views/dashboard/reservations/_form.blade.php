@csrf
@php
$reservation = $reservation ?? null;
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    {{-- Kolom Kiri: Detail Tamu --}}
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Detail Tamu</h3>
        <div class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap Tamu</label>
                <input type="text" name="name" id="name" value="{{ old('name', $reservation?->name) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 sm:text-sm">
                @error('name') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="identity" class="block text-sm font-medium text-gray-700">No. Identitas
                    (KTP/Passport)</label>
                <input type="text" name="identity" id="identity" value="{{ old('identity', $reservation?->identity) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 sm:text-sm"
                    maxlength="16">
                @error('identity') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="phone_number" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                <input type="text" name="phone_number" id="phone_number"
                    value="{{ old('phone_number', $reservation?->phone_number) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 sm:text-sm">
                @error('phone_number') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>

    {{-- Kolom Kanan: Detail Reservasi --}}
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Detail Reservasi</h3>
        <div class="space-y-4">
            <div>
                <label for="room_id" class="block text-sm font-medium text-gray-700">Pilih Kamar</label>
                <select name="room_id" id="room_id"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 sm:text-sm">
                    <option value="" disabled selected>-- Pilih Kamar --</option>
                    @foreach ($rooms as $room)
                    <option value="{{ $room->id }}" @selected(old('room_id', $reservation?->room_id) == $room->id)>
                        {{ $room->room_name }} ({{ $room->room_code }}) - Rp
                        {{ number_format($room->room_price, 0, ',', '.') }}
                    </option>
                    @endforeach
                </select>
                @error('room_id') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="checkin_date" class="block text-sm font-medium text-gray-700">Check-in</label>

                    <input type="date" name="checkin_date" id="checkin_date"
                        value="{{ old('checkin_date', $reservation?->checkin_date) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-500"
                        required @if($reservation) disabled @endif>
                    @error('checkin_date') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="checkout_date" class="block text-sm font-medium text-gray-700">Check-out</label>

                    <input type="date" name="checkout_date" id="checkout_date"
                        value="{{ old('checkout_date', $reservation?->checkout_date) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-500"
                        required @if($reservation) disabled @endif>
                    @error('checkout_date') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label for="total_guests" class="block text-sm font-medium text-gray-700">Jumlah Tamu</label>
                <input type="number" name="total_guests" id="total_guests"
                    value="{{ old('total_guests', $reservation?->total_guests ?? 1) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 sm:text-sm"
                    min="1">
                @error('total_guests') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="deposit" class="block text-sm font-medium text-gray-700">Deposit Jaminan (Min.
                    300.000)</label>
                <input type="number" name="deposit" id="deposit"
                    value="{{ old('deposit', $reservation?->deposit ?? 300000) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-500"
                    min="300000" step="10000" required @if($reservation) disabled @endif>
                @error('deposit') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="payment_method" class="block text-sm font-medium text-gray-700">Metode Pembayaran
                    (Deposit)</label>
                <select name="payment_method" id="payment_method"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 sm:text-sm">
                    <option value="cash" @selected(old('payment_method', $reservation?->payment_method) == 'cash')>Cash
                    </option>
                    <option value="transfer" @selected(old('payment_method', $reservation?->payment_method) ==
                        'transfer')>Transfer Bank</option>
                    <option value="card" @selected(old('payment_method', $reservation?->payment_method) == 'card')>Kartu
                        Kredit/Debit</option>
                </select>
                @error('payment_method') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700">Catatan (Opsional)</label>
                <textarea name="notes" id="notes" rows="3"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 sm:text-sm">{{ old('notes', $reservation?->notes) }}</textarea>
                @error('notes') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>
</div>

<div class="flex justify-end pt-6">
    <a href="{{ route('dashboard.reservations.index') }}"
        class="py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
        Batal
    </a>
    <button type="submit"
        class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-violet-600 hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500">
        {{ $reservation ? 'Update Reservasi' : 'Simpan Reservasi' }}
    </button>
</div>