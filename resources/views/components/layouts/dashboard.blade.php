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
      x-data="{ 
          mobileMenuOpen: false
      }"
      x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))"
      @toggle-theme.window="darkMode = !darkMode">
    <div class="min-h-screen bg-gray-100 dark:bg-darker-gray">
        <!-- Mobile menu button -->
        <div class="lg:hidden fixed bottom-4 right-4 z-50">
            <button @click="mobileMenuOpen = !mobileMenuOpen" 
                    class="inline-flex items-center justify-center p-3 rounded-full bg-red-600 hover:bg-red-700 text-white shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200">
                <span class="sr-only">Open menu</span>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <!-- Sidebar backdrop -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-600 bg-opacity-75 z-40 lg:hidden"
             @click="mobileMenuOpen = false">
        </div>

        <div class="flex">
            <!-- Sidebar -->
            <div x-show="mobileMenuOpen" 
                 x-transition:enter="transition ease-in-out duration-300 transform"
                 x-transition:enter-start="-translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in-out duration-300 transform"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="-translate-x-full"
                 class="fixed inset-y-0 left-0 z-40 w-64 bg-white dark:bg-darker-gray lg:hidden">
                <x-sidebar />
            </div>
            <div class="hidden lg:block lg:w-64 lg:flex-shrink-0">
                <x-sidebar />
            </div>

            <!-- Page Content -->
            <div class="flex-1">
                <main>
                    {{ $slot }}
                </main>
            </div>
        </div>
    </div>

    @livewireScripts
</body>
</html> 