<div class="bg-white dark:bg-darker-gray min-h-screen">
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-r from-gray-800 to-gray-900 py-12 md:py-16">
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute inset-0 bg-black opacity-20"></div>
            <!-- Red accent line at the bottom -->
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-cartel-red"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl md:text-4xl font-bold text-white font-orbitron mb-3">OUR SPONSORS</h1>
            <p class="text-lg text-gray-200 max-w-2xl mx-auto">
                Meet the amazing partners who support Speed Cartel BMX Team and make our success possible.
            </p>
        </div>
    </div>

    <!-- Search Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white dark:bg-light-gray rounded-xl shadow-md p-6 mb-8">
            <div class="max-w-md mx-auto">
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search Sponsors</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" wire:model.live.debounce.300ms="search" id="search" class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 pr-3 py-2 border-gray-300 dark:border-gray-600 dark:bg-darker-gray dark:text-white rounded-md" placeholder="Search sponsors...">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sponsors Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        <div wire:loading class="w-full flex justify-center my-8">
            <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-cartel-red"></div>
        </div>

        <div wire:loading.remove>
            @if($sponsors->isEmpty() && !empty($search))
                <div class="text-center py-12 bg-white dark:bg-light-gray rounded-lg shadow">
                    <svg class="mx-auto h-16 w-16 text-gray-400 dark:text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No sponsors found</h3>
                    <p class="mt-1 text-gray-500 dark:text-gray-400">Try adjusting your search to find what you're looking for.</p>
                </div>
            @endif

            <!-- More compact grid with smaller cards -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 sm:gap-4 md:gap-5">
                <!-- Become a Sponsor Card (Always First) -->
                <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300 flex flex-col h-full border border-cartel-red">
                    <div class="p-4 sm:p-5 flex flex-col flex-grow">
                        <!-- Icon -->
                        <div class="flex justify-center mb-4">
                            <div class="w-16 h-16 bg-cartel-red rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Title -->
                        <h3 class="text-base sm:text-lg font-bold text-white text-center font-orbitron mb-2">
                            BECOME A SPONSOR
                        </h3>
                        
                        <!-- Description -->
                        <div class="mt-2 text-xs sm:text-sm text-gray-300 flex-grow hidden sm:block">
                            <p class="text-center mb-4">
                                Support our team and gain visibility for your brand. Sponsorship opportunities are available at various levels.
                            </p>
                        </div>
                        
                        <!-- Contact Button -->
                        <div class="mt-2 flex justify-center">
                            <a href="mailto:sponsors@speedcartel.com" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-gray-900 bg-white hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cartel-red transition-colors duration-200">
                                <svg class="h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Contact Us
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Sponsor Cards -->
                @foreach($sponsors as $sponsor)
                    <div class="bg-white dark:bg-light-gray rounded-xl overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300 flex flex-col h-full border border-gray-200 dark:border-gray-700 hover:border-cartel-red dark:hover:border-cartel-red">
                        <!-- Logo Container -->
                        <div class="relative h-32 sm:h-36 bg-gray-50 dark:bg-gray-800 flex items-center justify-center p-3">
                            @if($sponsor->logo_path)
                                <img 
                                    src="{{ asset('storage/' . $sponsor->logo_path) }}" 
                                    alt="{{ $sponsor->name }} logo" 
                                    class="max-h-full max-w-full object-contain"
                                    loading="lazy"
                                >
                            @else
                                <div class="w-16 h-16 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center">
                                    <span class="text-xl font-bold text-gray-500 dark:text-gray-400 font-orbitron">
                                        {{ strtoupper(substr($sponsor->name, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Sponsor Info -->
                        <div class="p-3 flex-grow flex flex-col">
                            <h3 class="text-sm sm:text-base font-bold text-gray-900 dark:text-white text-center font-orbitron line-clamp-1">
                                {{ $sponsor->name }}
                            </h3>
                            
                            @if($sponsor->company)
                                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 text-center mt-1 line-clamp-1">
                                    {{ $sponsor->company }}
                                </p>
                            @endif
                            
                            @if($sponsor->description)
                                <div class="mt-2 text-xs sm:text-sm text-gray-600 dark:text-gray-400 flex-grow hidden sm:block">
                                    <p class="line-clamp-2 overflow-hidden">{{ Str::limit($sponsor->description, 100) }}</p>
                                </div>
                            @endif
                            
                            @if($sponsor->website)
                                <div class="mt-3 flex justify-center">
                                    <a href="{{ $sponsor->website }}" target="_blank" class="inline-flex items-center text-xs sm:text-sm text-cartel-red hover:text-red-700 transition-colors duration-200">
                                        <svg class="h-3 w-3 sm:h-4 sm:w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                        Visit Website
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            
            @if(!$sponsors->isEmpty())
                <div class="mt-8">
                    <div class="w-full bg-white dark:bg-light-gray rounded-lg shadow px-4 py-3">
                        {{ $sponsors->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
