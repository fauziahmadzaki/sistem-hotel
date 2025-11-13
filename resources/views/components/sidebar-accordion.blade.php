@props(['name'])

<li>
    {{--
      PERBAIKAN: 
      Ubah @click untuk menggunakan object 'openAccordions'
      Logika '!' (not) akan mengubah true -> false atau false -> true (atau undefined -> true)
    --}}
    <div @click="openAccordions['{{ $name }}'] = !openAccordions['{{ $name }}']"
        class="flex cursor-pointer items-center justify-between rounded-lg px-4 py-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700">

        <span class="text-sm font-medium">
            {{ $trigger }}
        </span>

        {{--
          PERBAIKAN:
          Ubah :class untuk memeriksa state 'openAccordions'
        --}}
        <span class="shrink-0 transition duration-300"
            :class="openAccordions['{{ $name }}'] ? '-rotate-180' : 'rotate-0'">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                    clip-rule="evenodd" />
            </svg>
        </span>
    </div>

    {{--
      PERBAIKAN:
      Ubah x-show untuk memeriksa state 'openAccordions'
    --}}
    <ul x-show="openAccordions['{{ $name }}']" x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95" class="mt-2 space-y-1 px-4" x-cloak>

        {{ $slot }}
    </ul>
</li>