@csrf
@php $transaction = $transaction ?? null; @endphp

<div class="bg-white p-6 rounded-lg shadow-sm">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi Transaksi</label>
            <input type="text" name="description" id="description"
                value="{{ old('description', $transaction?->description) }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 sm:text-sm"
                required placeholder="Contoh: Pembelian ATK Kantor">
            @error('description') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="amount" class="block text-sm font-medium text-gray-700">Jumlah (Rp)</label>
            <input type="number" name="amount" id="amount" value="{{ old('amount', $transaction?->amount) }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 sm:text-sm"
                min="0.01" step="0.01" required placeholder="Contoh: 50000">
            @error('amount') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="type" class="block text-sm font-medium text-gray-700">Tipe Transaksi</label>
            <select name="type" id="type"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 sm:text-sm"
                required>
                <option value="income" @selected(old('type', $transaction?->type) == 'income')>Pemasukan (Income)
                </option>
                <option value="expense" @selected(old('type', $transaction?->type) == 'expense')>Pengeluaran (Expense)
                </option>
            </select>
            @error('type') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="category" class="block text-sm font-medium text-gray-700">Kategori</label>
            <select name="category" id="category"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 sm:text-sm"
                required>
                <option value="other" @selected(old('category', $transaction?->category) == 'other')>Lain-lain (Other)
                </option>
                <option value="maintenance" @selected(old('category', $transaction?->category) ==
                    'maintenance')>Maintenance</option>
                {{-- Kategori di bawah ini sebaiknya otomatis, tapi bisa di-enable jika perlu manual input --}}
                <option value="rental" @selected(old('category', $transaction?->category) == 'rental') disabled>Sewa
                    Kamar (Otomatis)</option>
                <option value="deposit" @selected(old('category', $transaction?->category) == 'deposit')
                    disabled>Deposit (Otomatis)</option>
            </select>
            @error('category') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
        </div>

    </div>

    <div class="flex justify-end pt-6">
        <a href="{{ route('dashboard.transactions.index') }}"
            class="py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            Batal
        </a>
        <button type="submit"
            class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-violet-600 hover:bg-violet-700">
            {{ $transaction ? 'Update Transaksi' : 'Simpan Transaksi' }}
        </button>
    </div>
</div>