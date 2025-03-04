<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="min-h-screen bg-gray-50 dark:bg-darker-gray text-gray-900 dark:text-white"
      x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))"
      @toggle-theme.window="darkMode = !darkMode">
    <livewire:public.header />

    <main>
        {{ $slot }}
    </main>

    <livewire:public.footer />

    @livewireScripts
</body>

</html>
