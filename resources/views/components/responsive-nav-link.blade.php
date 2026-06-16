@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full rounded-lg bg-stone-900 px-3 py-2 text-start text-sm font-semibold text-white focus:outline-none focus:ring-2 focus:ring-emerald-700 focus:ring-offset-2'
            : 'block w-full rounded-lg px-3 py-2 text-start text-sm font-semibold text-stone-600 hover:bg-stone-100 hover:text-stone-950 focus:outline-none focus:ring-2 focus:ring-emerald-700 focus:ring-offset-2';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
