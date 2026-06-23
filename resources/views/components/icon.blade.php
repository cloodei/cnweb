@props(['name'])

<svg {{ $attributes->merge(['class' => 'h-5 w-5']) }} xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    @switch($name)
        @case('arrow-left')
            <path d="m12 19-7-7 7-7" />
            <path d="M19 12H5" />
            @break

        @case('arrow-right')
            <path d="M5 12h14" />
            <path d="m12 5 7 7-7 7" />
            @break

        @case('calendar')
            <rect x="3" y="4" width="18" height="18" rx="2" />
            <path d="M16 2v4" />
            <path d="M8 2v4" />
            <path d="M3 10h18" />
            @break

        @case('clock')
            <circle cx="12" cy="12" r="9" />
            <path d="M12 7v5l3 2" />
            @break

        @case('file-text')
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z" />
            <path d="M14 2v6h6" />
            <path d="M8 13h8" />
            <path d="M8 17h6" />
            @break

        @case('flag')
            <path d="M5 22V4" />
            <path d="M5 4h11l-1 4 1 4H5" />
            @break

        @case('image')
            <rect x="3" y="3" width="18" height="18" rx="2" />
            <circle cx="9" cy="9" r="2" />
            <path d="m21 15-3.5-3.5a2 2 0 0 0-2.8 0L5 21" />
            @break

        @case('link')
            <path d="M10 13a5 5 0 0 0 7.1 0l2-2a5 5 0 0 0-7.1-7.1l-1.1 1.1" />
            <path d="M14 11a5 5 0 0 0-7.1 0l-2 2A5 5 0 0 0 12 20.1l1.1-1.1" />
            @break

        @case('lock')
            <rect x="4" y="11" width="16" height="10" rx="2" />
            <path d="M8 11V7a4 4 0 0 1 8 0v4" />
            @break

        @case('map')
            <path d="m9 18-6 3V6l6-3 6 3 6-3v15l-6 3-6-3Z" />
            <path d="M9 3v15" />
            <path d="M15 6v15" />
            @break

        @case('map-pin')
            <path d="M12 21s6-5.2 6-10A6 6 0 1 0 6 11c0 4.8 6 10 6 10Z" />
            <circle cx="12" cy="11" r="2" />
            @break

        @case('note')
            <path d="M4 4h16v16H4z" />
            <path d="M8 8h8" />
            <path d="M8 12h8" />
            <path d="M8 16h5" />
            @break

        @case('pencil')
            <path d="M12 20h9" />
            <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z" />
            @break

        @case('plus')
            <path d="M12 5v14" />
            <path d="M5 12h14" />
            @break

        @case('route')
            <circle cx="6" cy="19" r="3" />
            <circle cx="18" cy="5" r="3" />
            <path d="M12 19h2a4 4 0 0 0 0-8h-4a4 4 0 0 1 0-8h2" />
            @break

        @case('search')
            <circle cx="11" cy="11" r="7" />
            <path d="m20 20-3.5-3.5" />
            @break

        @case('sparkles')
            <path d="M12 3l1.7 4.3L18 9l-4.3 1.7L12 15l-1.7-4.3L6 9l4.3-1.7Z" />
            <path d="M19 15l.8 2.2L22 18l-2.2.8L19 21l-.8-2.2L16 18l2.2-.8Z" />
            <path d="M5 14l.8 2.2L8 17l-2.2.8L5 20l-.8-2.2L2 17l2.2-.8Z" />
            @break

        @case('trash')
            <path d="M3 6h18" />
            <path d="M8 6V4h8v2" />
            <path d="m6 6 1 15h10l1-15" />
            <path d="M10 11v6" />
            <path d="M14 11v6" />
            @break

        @case('user')
            <circle cx="12" cy="8" r="4" />
            <path d="M4 21a8 8 0 0 1 16 0" />
            @break

        @case('x')
            <path d="M18 6 6 18" />
            <path d="m6 6 12 12" />
            @break

        @default
            <circle cx="12" cy="12" r="9" />
            <path d="M12 8v4" />
            <path d="M12 16h.01" />
    @endswitch
</svg>
