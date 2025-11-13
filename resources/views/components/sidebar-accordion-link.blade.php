@props(['href', 'active' => false])

@php
// Tentukan kelas CSS berdasarkan status aktif atau tidak
$classes = ($active ?? false)
? 'flex items-center gap-2 rounded-lg bg-violet-100 px-4 py-2 text-sm font-medium text-violet-700' // Kelas saat aktif
: 'flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100
hover:text-gray-700'; // Kelas normal
@endphp
@props(['href', 'active' => false])

@php
$classes = ($active ?? false)
? 'block rounded-lg px-4 py-2 text-sm font-medium text-violet-600' // Kelas saat aktif
: 'block rounded-lg px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700'; // Kelas normal
@endphp

<li>
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
</li>