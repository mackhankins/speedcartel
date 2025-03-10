<nav class="bg-white dark:bg-dark-gray border-b border-gray-200 dark:border-cartel-red/30 sticky top-0 z-50"
    x-data="{ mobileMenuOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <a href="/" class="flex-shrink-0 flex items-center">
                    <div
                        class="w-10 h-10 bg-cartel-red rounded-full flex items-center justify-center font-orbitron font-bold text-xl text-white">
                        SC</div>
                    <span class="ml-3 font-orbitron font-bold text-xl dark:text-white">SPEED<span
                            class="text-cartel-red">CARTEL</span></span>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-1">
                <a href="/"
                    class="px-4 py-5 text-gray-700 dark:text-white font-orbitron font-medium hover:bg-gray-100 dark:hover:bg-light-gray transition-colors duration-200">HOME</a>
                <a href="{{ route('team') }}"
                    class="px-4 py-5 text-gray-700 dark:text-white font-orbitron font-medium hover:bg-gray-100 dark:hover:bg-light-gray transition-colors duration-200">TEAM</a>
                <a href="{{ route('races') }}"
                    class="px-4 py-5 text-gray-700 dark:text-white font-orbitron font-medium hover:bg-gray-100 dark:hover:bg-light-gray transition-colors duration-200">RACES</a>
                <a href="{{ route('sponsors') }}"
                    class="px-4 py-5 text-gray-700 dark:text-white font-orbitron font-medium hover:bg-gray-100 dark:hover:bg-light-gray transition-colors duration-200">SPONSORS</a>
                <a href="{{ route('contact') }}"
                    class="px-4 py-5 text-gray-700 dark:text-white font-orbitron font-medium hover:bg-gray-100 dark:hover:bg-light-gray transition-colors duration-200">CONTACT</a>

                <!-- Shopping Cart -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('cart') }}"
                        class="relative p-2 text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-light-gray rounded-lg">
                        <x-icon name="shopping-cart" class="w-6 h-6" />
                        <span
                            class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center mt-0.5">0</span>
                    </a>

                    <!-- Theme Toggle -->
                    <x-theme-toggle />
                </div>

                <!-- Auth Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="flex items-center px-4 py-2 text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-light-gray rounded-lg">
                        <x-icon name="user" class="h-5 w-5" />
                        <x-icon name="chevron-down" class="w-4 h-4 ml-1" />
                    </button>

                    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-48 bg-white dark:bg-darker-gray rounded-md shadow-lg">
                        @auth
                            <a href="{{ route('dashboard') }}"
                                class="block px-4 py-2 text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-light-gray">Dashboard</a>
                            <a href="{{ route('settings') }}"
                                class="block px-4 py-2 text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-light-gray">Settings</a>
                            <a href="{{ route('riders') }}"
                                class="block px-4 py-2 text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-light-gray">Riders</a>
                            
                            @can('access_manage_panel')
                            <a href="{{ url('/manage') }}"
                                class="block px-4 py-2 text-cartel-red font-medium hover:bg-gray-100 dark:hover:bg-light-gray">
                                Manage
                            </a>
                            @endcan
                            
                            <div class="border-t border-gray-100 dark:border-light-gray"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="block w-full text-left px-4 py-2 text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-light-gray">
                                    Logout
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}"
                                class="block px-4 py-2 text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-light-gray">Login</a>
                            <a href="{{ route('register') }}"
                                class="block px-4 py-2 text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-light-gray">Register</a>
                            <div class="border-t border-gray-100 dark:border-light-gray"></div>
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center space-x-2">
                <!-- Shopping Cart -->
                <a href="{{ route('cart') }}"
                    class="relative p-2 text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-light-gray rounded-lg">
                    <x-icon name="shopping-cart" class="w-6 h-6" />
                    <span
                        class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center mt-0.5">0</span>
                </a>

                <!-- Theme Toggle -->
                <x-theme-toggle />

                <button type="button" @click="mobileMenuOpen = !mobileMenuOpen"
                    class="text-gray-500 dark:text-gray-300 hover:text-gray-600 dark:hover:text-white"
                    aria-label="Toggle menu">
                    <x-icon name="bars-3" class="h-6 w-6" />
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform -translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform -translate-y-2"
        class="absolute inset-x-0 top-16 z-40 md:hidden bg-white dark:bg-darker-gray border-b border-gray-200 dark:border-light-gray"
        @click.away="mobileMenuOpen = false">
        <div class="px-2 pt-2 pb-3 space-y-1">
            <a href="/"
                class="block px-3 py-2 text-gray-700 dark:text-white font-medium hover:bg-gray-100 dark:hover:bg-light-gray rounded-md">Home</a>
            <a href="{{ route('team') }}"
                class="block px-3 py-2 text-gray-700 dark:text-white font-medium hover:bg-gray-100 dark:hover:bg-light-gray rounded-md">Team</a>
            <a href="{{ route('races') }}"
                class="block px-3 py-2 text-gray-700 dark:text-white font-medium hover:bg-gray-100 dark:hover:bg-light-gray rounded-md">Races</a>
            <a href="{{ route('sponsors') }}"
                class="block px-3 py-2 text-gray-700 dark:text-white font-medium hover:bg-gray-100 dark:hover:bg-light-gray rounded-md">Sponsors</a>
            <a href="{{ route('contact') }}"
                class="block px-3 py-2 text-gray-700 dark:text-white font-medium hover:bg-gray-100 dark:hover:bg-light-gray rounded-md">Contact</a>

            @auth
                <div class="border-t border-gray-200 dark:border-light-gray my-2"></div>
                <a href="{{ route('dashboard') }}"
                    class="block px-3 py-2 text-gray-700 dark:text-white font-medium hover:bg-gray-100 dark:hover:bg-light-gray rounded-md">Dashboard</a>
                <a href="{{ route('settings') }}"
                    class="block px-3 py-2 text-gray-700 dark:text-white font-medium hover:bg-gray-100 dark:hover:bg-light-gray rounded-md">Settings</a>
                <a href="{{ route('riders') }}"
                    class="block px-3 py-2 text-gray-700 dark:text-white font-medium hover:bg-gray-100 dark:hover:bg-light-gray rounded-md">Riders</a>
                
                @can('access_manage_panel')
                <a href="{{ url('/manage') }}"
                    class="block px-3 py-2 text-cartel-red font-medium hover:bg-gray-100 dark:hover:bg-light-gray rounded-md">
                    Manage
                </a>
                @endcan
                
                <div class="border-t border-gray-200 dark:border-light-gray my-2"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="block w-full text-left px-3 py-2 text-gray-700 dark:text-white font-medium hover:bg-gray-100 dark:hover:bg-light-gray rounded-md">
                        Logout
                    </button>
                </form>
            @else
                <div class="border-t border-gray-200 dark:border-light-gray my-2"></div>
                <a href="{{ route('login') }}"
                    class="block px-3 py-2 text-gray-700 dark:text-white font-medium hover:bg-gray-100 dark:hover:bg-light-gray rounded-md">Login</a>
                <a href="{{ route('register') }}"
                    class="block px-3 py-2 text-gray-700 dark:text-white font-medium hover:bg-gray-100 dark:hover:bg-light-gray rounded-md">Register</a>
            @endauth
        </div>
    </div>
</nav>