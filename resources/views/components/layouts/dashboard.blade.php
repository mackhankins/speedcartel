<x-layouts.app>
    <div x-data="{ mobileMenuOpen: false }" class="min-h-screen flex flex-col">
        <!-- Mobile sidebar handle -->
        <div class="lg:hidden fixed left-0 top-1/2 -translate-y-1/2 z-50">
            <button @click="mobileMenuOpen = !mobileMenuOpen"
                class="flex items-center justify-center w-2 h-32 bg-red-500/40 hover:bg-red-500 hover:w-8 text-white shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200 rounded-r-lg group">
                <svg class="w-5 h-5 opacity-50 group-hover:opacity-100 transition-opacity duration-200" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    class="flex items-center justify-center w-1.5 h-24 bg-red-500/20 hover:bg-red-500 hover:w-6
                    text-white shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500
                    transition-all duration-200 rounded-r-md group">
                    <span class="sr-only">Toggle sidebar</span>
                    <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity duration-200" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
            </button>
        </div>

        <!-- Sidebar backdrop -->
        <div x-show="mobileMenuOpen" x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-600 bg-opacity-75 z-40 lg:hidden"
            @click="mobileMenuOpen = false">
        </div>

        <div class="flex-1 flex flex-grow">
            <!-- Sidebar -->
            <div x-show="mobileMenuOpen" x-transition:enter="transition ease-in-out duration-300 transform"
                x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in-out duration-300 transform"
                x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
                class="fixed inset-y-0 left-0 z-40 w-64 bg-white dark:bg-darker-gray pt-16 lg:hidden">
                <x-sidebar />
            </div>
            <div class="hidden lg:flex lg:flex-shrink-0">
                <div
                    class="flex flex-col w-64 bg-white dark:bg-darker-gray border-l-none border-r-[1px] border-r-gray-200 dark:border-none">
                    <x-sidebar />
                </div>
            </div>

            <!-- Page Content -->
            <div class="flex-1 m-4 sm:m-8">
                <main>
                    {{ $slot }}
                </main>
            </div>
        </div>
    </div>

</x-layouts.app>