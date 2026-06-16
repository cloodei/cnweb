@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center rounded-lg bg-stone-900 px-3 py-2 text-sm font-semibold text-white shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-700 focus:ring-offset-2'
            : 'inline-flex items-center rounded-lg px-3 py-2 text-sm font-semibold text-stone-600 hover:bg-white hover:text-stone-950 focus:outline-none focus:ring-2 focus:ring-emerald-700 focus:ring-offset-2';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
