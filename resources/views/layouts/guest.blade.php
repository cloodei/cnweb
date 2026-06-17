<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Travel Planner</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=atkinson-hyperlegible:400,700|literata:600,700|ibm-plex-mono:500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-stone-900 antialiased">
        <div class="flex min-h-screen flex-col items-center justify-center px-4 py-8">
            <div class="mb-6">
                <a href="/" class="inline-flex items-center gap-3 text-stone-900">
                    <span class="grid h-11 w-11 place-items-center rounded-lg border border-stone-300 bg-white shadow-sm">
                        <x-application-logo class="h-7 w-7 text-emerald-900" />
                    </span>
                    <span class="font-display text-2xl font-semibold">Travel Planner</span>
                </a>
            </div>

            <div class="surface-panel w-full max-w-md p-6 sm:p-8">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
