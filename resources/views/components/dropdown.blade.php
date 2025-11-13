@props(['align' => 'right', 'width' => '44'])

@php
$alignmentClasses = match ($align) {
'left' => 'origin-top-left left-0',
'top' => 'origin-top',
default => 'origin-top-right right-0',
};

$widthClasses = match ($width) {
'44' => 'w-44',
'48' => 'w-48',
'56' => 'w-56',
default => 'w-44',
};
@endphp

{{-- Komponen ini menggunakan Alpine.js untuk state 'open' --}}
<div class="relative" x-data="{ open: false }" @click.away="open = false">

    {{-- Bagian ini adalah 'Trigger' (tombol yang Anda klik) --}}
    <div @click="open = !open">
        {{ $trigger }}
    </div>

    {{-- Bagian ini adalah 'Content' (menu yang muncul) --}}
    <div x-show="open" x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute {{ $alignmentClasses }} {{ $widthClasses }} mt-2 rounded-lg shadow-lg z-50"
        style="display: none;" @click="open = false"> {{-- Tambahkan ini agar dropdown tertutup saat item diklik --}}

        {{-- Slot default untuk menampung link/item --}}
        <div class="rounded-lg ring-1 ring-black ring-opacity-5 bg-white py-1">
            {{ $slot }}
        </div>
    </div>
</div>