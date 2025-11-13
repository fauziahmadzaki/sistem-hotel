@if (session('success') || session('error'))
{{-- Set variabel untuk styling dinamis --}}
@php
$isSuccess = session('success') ? true : false;
$title = $isSuccess ? 'Success' : 'Error';
$message = session('success') ?? session('error');

// Tentukan warna berdasarkan status
$bgColor = $isSuccess ? 'bg-green-50' : 'bg-red-50';
$borderColor = $isSuccess ? 'border-green-400' : 'border-red-400';
$iconColor = $isSuccess ? 'text-green-600' : 'text-red-600';
$titleColor = $isSuccess ? 'text-green-800' : 'text-red-800';
$textColor = $isSuccess ? 'text-green-700' : 'text-red-700';
@endphp

{{--
      Komponen Alpine.js
      - x-data: Inisialisasi state 'show = true'
      - x-init: Atur timer 5 detik untuk 'show = false'
      - x-show: Tampilkan elemen jika 'show = true'
      - x-transition: Atur animasi fade-in dan fade-out
    --}}
<div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show"
    x-transition:enter="transform ease-out duration-300 transition"
    x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
    x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
    x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0" {{-- (Revamped) Posisi: Pojok kanan atas, z-index tinggi --}}
    class="fixed top-5 right-5 z-[100] w-full max-w-sm rounded-lg shadow-lg pointer-events-auto {{ $bgColor }} border {{ $borderColor }}"
    role="alert">
    <div class="p-4">
        <div class="flex items-start">

            {{-- 1. Ikon Dinamis (Success atau Error) --}}
            <div class="flex-shrink-0">
                @if ($isSuccess)
                {{-- Ikon Ceklis (Success) --}}
                <svg class="h-6 w-6 {{ $iconColor }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                @else
                {{-- Ikon Peringatan (Error) --}}
                <svg class="h-6 w-6 {{ $iconColor }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                @endif
            </div>

            {{-- 2. Konten Teks (Title & Message) --}}
            <div class="ml-3 w-0 flex-1 pt-0.5">
                <p class="text-sm font-medium {{ $titleColor }}">{{ $title }}</p>
                <p class="mt-1 text-sm {{ $textColor }}">{{ $message }}</p>
            </div>

            {{-- 3. Tombol "Close" (Dismiss) --}}
            <div class="ml-4 flex-shrink-0 flex">
                <button @click="show = false" {{-- Tutup modal saat diklik --}}
                    class="inline-flex rounded-md bg-transparent text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500">
                    <span class="sr-only">Close</span>
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
@endif

{{--
  Script lama tidak lagi diperlukan, 
  karena Alpine.js sudah menangani logic-nya.
--}}