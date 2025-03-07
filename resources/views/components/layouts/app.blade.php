<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data :class="{ 'dark': $store.theme.dark }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/theme.js'])
    @livewireStyles
    @stack('styles')
</head>

<body class="min-h-screen bg-gray-50 dark:bg-darker-gray text-gray-900 dark:text-white">
    <livewire:public.header />

    <main>
        {{ $slot }}
    </main>

    <livewire:public.footer />
    <x-dialog z-index="z-50" blur="md" />
    @wireUiScripts
    @livewireScripts
    @stack('scripts')
</body>

</html>