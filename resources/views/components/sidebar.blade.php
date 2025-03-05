@props(['mobileMenuOpen' => false])

<div wire:ignore x-data="{ open: false }" @toggle-nav.window="open = !open" class="flex-1">
    <!-- Mobile Sidebar -->
    <div x-show="open" class="lg:hidden fixed inset-0 z-40">
        <!-- Overlay -->
        <div @click="open = false" class="fixed inset-0 bg-gray-600 dark:bg-darker-gray bg-opacity-75"></div>

        <!-- Mobile Sidebar Content -->
        <div class="relative flex flex-col max-w-xs w-full bg-white dark:bg-dark-gray">
            <div class="absolute top-0 right-0 -mr-12 pt-2">
                <button @click="open = false" class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                    <span class="sr-only">Close sidebar</span>
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                <nav class="px-4 space-y-1">
                    @include('components.sidebar-links')
                </nav>
            </div>
        </div>
    </div>

    <!-- Desktop Sidebar -->
    <nav class="flex-1 flex flex-col bg-white dark:bg-dark-gray border-r border-gray-200 dark:border-light-gray h-screen">
        <div class="flex-1 flex flex-col">
            <div class="flex-1 flex flex-col pt-5 pb-4">
                <div class="flex-1 px-4 space-y-1">
                    @include('components.sidebar-links')
                </div>
            </div>
        </div>
    </nav>
</div> 