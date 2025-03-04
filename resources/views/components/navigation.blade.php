<nav class="bg-white dark:bg-darker-gray shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="{{ route('home') }}" class="flex items-center">
                    <x-logo class="h-8 w-auto text-gray-900 dark:text-white" />
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('home') }}" 
                   class="text-sm font-medium text-gray-900 dark:text-white hover:text-red-600 dark:hover:text-red-400 transition-colors duration-200">
                    Home
                </a>
                <a href="{{ route('team') }}" 
                   class="text-sm font-medium text-gray-900 dark:text-white hover:text-red-600 dark:hover:text-red-400 transition-colors duration-200">
                    Team
                </a>
                <a href="{{ route('races') }}" 
                   class="text-sm font-medium text-gray-900 dark:text-white hover:text-red-600 dark:hover:text-red-400 transition-colors duration-200">
                    Races
                </a>
                <a href="{{ route('sponsors') }}" 
                   class="text-sm font-medium text-gray-900 dark:text-white hover:text-red-600 dark:hover:text-red-400 transition-colors duration-200">
                    Sponsors
                </a>
                <a href="{{ route('contact') }}" 
                   class="text-sm font-medium text-gray-900 dark:text-white hover:text-red-600 dark:hover:text-red-400 transition-colors duration-200">
                    Contact
                </a>
            </div>

            <!-- Auth Buttons and Cart -->
            <div class="hidden md:flex items-center space-x-4">
                <!-- Shopping Cart -->
                <a href="{{ route('cart') }}" class="relative text-gray-900 dark:text-white hover:text-red-600 dark:hover:text-red-400 transition-colors duration-200">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="absolute -top-2 -right-2 bg-red-600 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">
                        {{ $cartItemCount ?? 0 }}
                    </span>
                </a>

                <!-- User Menu -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center space-x-2 text-gray-900 dark:text-white hover:text-red-600 dark:hover:text-red-400 transition-colors duration-200">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </button>

                    <div x-show="open" 
                         @click.away="open = false"
                         class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-darker-gray ring-1 ring-black ring-opacity-5">
                        <div class="py-1">
                            @auth
                                <a href="{{ route('dashboard') }}" 
                                   class="block px-4 py-2 text-sm text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-light-gray">
                                    Dashboard
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" 
                                        class="w-full text-left px-4 py-2 text-sm text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-light-gray">
                                        Logout
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" 
                                   class="block px-4 py-2 text-sm text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-light-gray">
                                    Login
                                </a>
                                <a href="{{ route('register') }}" 
                                   class="block px-4 py-2 text-sm text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-light-gray">
                                    Register
                                </a>
                            @endauth
                            <div class="border-t border-gray-200 dark:border-gray-700">
                                <div class="px-4 py-2">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="sr-only peer" wire:model.live="darkMode">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 dark:peer-focus:ring-red-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-red-600"></div>
                                        <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white">Dark Mode</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button @click="isOpen = !isOpen" 
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-900 dark:text-white hover:text-red-600 dark:hover:text-red-400 focus:outline-none">
                    <span class="sr-only">Open main menu</span>
                    <!-- Icon when menu is closed -->
                    <svg class="block h-6 w-6" x-show="!isOpen" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <!-- Icon when menu is open -->
                    <svg class="block h-6 w-6" x-show="isOpen" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div x-show="isOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-2"
         class="md:hidden bg-white dark:bg-darker-gray">
        <div class="px-2 pt-2 pb-3 space-y-1">
            <!-- Cart Link -->
            <a href="{{ route('cart') }}" 
               class="flex items-center justify-between px-3 py-2 rounded-md text-base font-medium text-gray-900 dark:text-white hover:text-red-600 dark:hover:text-red-400 transition-colors duration-200">
                <div class="flex items-center">
                    <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Cart
                </div>
                <span class="bg-red-600 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">
                    {{ $cartItemCount ?? 0 }}
                </span>
            </a>

            <a href="{{ route('home') }}" 
               class="block px-3 py-2 rounded-md text-base font-medium text-gray-900 dark:text-white hover:text-red-600 dark:hover:text-red-400 transition-colors duration-200">
                Home
            </a>
            <a href="{{ route('team') }}" 
               class="block px-3 py-2 rounded-md text-base font-medium text-gray-900 dark:text-white hover:text-red-600 dark:hover:text-red-400 transition-colors duration-200">
                Team
            </a>
            <a href="{{ route('races') }}" 
               class="block px-3 py-2 rounded-md text-base font-medium text-gray-900 dark:text-white hover:text-red-600 dark:hover:text-red-400 transition-colors duration-200">
                Races
            </a>
            <a href="{{ route('sponsors') }}" 
               class="block px-3 py-2 rounded-md text-base font-medium text-gray-900 dark:text-white hover:text-red-600 dark:hover:text-red-400 transition-colors duration-200">
                Sponsors
            </a>
            <a href="{{ route('contact') }}" 
               class="block px-3 py-2 rounded-md text-base font-medium text-gray-900 dark:text-white hover:text-red-600 dark:hover:text-red-400 transition-colors duration-200">
                Contact
            </a>

            <div class="border-t border-gray-200 dark:border-gray-700 pt-2">
                @auth
                    <a href="{{ route('dashboard') }}" 
                       class="block px-3 py-2 rounded-md text-base font-medium text-gray-900 dark:text-white hover:text-red-600 dark:hover:text-red-400 transition-colors duration-200">
                        Dashboard
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                            class="w-full text-left px-3 py-2 rounded-md text-base font-medium text-gray-900 dark:text-white hover:text-red-600 dark:hover:text-red-400 transition-colors duration-200">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" 
                       class="block px-3 py-2 rounded-md text-base font-medium text-gray-900 dark:text-white hover:text-red-600 dark:hover:text-red-400 transition-colors duration-200">
                        Login
                    </a>
                    <a href="{{ route('register') }}" 
                       class="block px-3 py-2 rounded-md text-base font-medium text-white bg-red-600 hover:bg-red-700 transition-colors duration-200">
                        Register
                    </a>
                @endauth
                <div class="px-3 py-2">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer" wire:model.live="darkMode">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 dark:peer-focus:ring-red-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-red-600"></div>
                        <span class="ml-3 text-base font-medium text-gray-900 dark:text-white">Dark Mode</span>
                    </label>
                </div>
            </div>
        </div>
    </div>
</nav> 