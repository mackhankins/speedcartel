<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }"
      :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Authentication</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @wireUiScripts
</head>
<body class="min-h-screen bg-gray-50 dark:bg-darker-gray text-gray-900 dark:text-white"
      x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))"
      @toggle-theme.window="darkMode = !darkMode">

    <div class="min-h-screen flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <!-- Logo -->
        <div class="mb-6">
            <a href="/" class="inline-block">
                <x-logo size="lg" class="justify-center" />
            </a>
        </div>

        <!-- Main Content -->
        <div class="w-full max-w-md">
            <div class="bg-white dark:bg-light-gray shadow-2xl rounded-2xl px-8 py-10 ring-1 ring-red-500/20 focus-within:ring-2 focus-within:ring-red-500">
                {{ $slot }}
            </div>
        </div>

        <!-- Footer Links -->
        <div class="mt-12 flex items-center justify-center space-x-6 text-sm text-gray-500 dark:text-gray-400">
            <a href="{{ route('home') }}" class="hover:text-gray-900 dark:hover:text-white transition-colors duration-200">
                Back to home
            </a>
            <span class="text-gray-300 dark:text-gray-600">|</span>
            <button @click="darkMode = !darkMode" class="hover:text-gray-900 dark:hover:text-white transition-colors duration-200">
                <span x-show="!darkMode" class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                    Dark
                </span>
                <span x-show="darkMode" class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707"/>
                    </svg>
                    Light
                </span>
            </button>
        </div>
    </div>
    @livewireScripts
</body>
</html>
