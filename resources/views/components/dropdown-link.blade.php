@props(['href'])

<a href="{{ $href }}" {{ $attributes->merge(['class' => 'block w-full px-4 py-2 text-sm text-left text-gray-700
    hover:bg-gray-100']) }}>
    {{ $slot }}
</a>