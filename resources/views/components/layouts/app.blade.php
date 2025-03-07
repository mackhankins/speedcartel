<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data :class="{ 'dark': $store.theme.dark }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    <!-- Prevent flash of unstyled content in dark mode -->
    <script>
        // Apply dark mode immediately to prevent flash
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/theme.js'])
    @livewireStyles
    @stack('styles')
</head>

<body class="min-h-screen bg-gray-50 dark:bg-darker-gray text-gray-900 dark:text-white transition-colors duration-200">
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