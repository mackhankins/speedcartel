<div class="bg-white dark:bg-darker-gray">
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-r from-gray-800 to-gray-900 py-12 md:py-16">
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute inset-0 bg-black opacity-20"></div>
            <!-- Red accent line at the bottom -->
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-cartel-red"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl md:text-4xl font-bold text-white font-orbitron mb-3">Our Team</h1>
            <p class="text-lg text-gray-200 max-w-2xl mx-auto">
                Meet the talented riders who represent Speed Cartel. Our team consists of dedicated athletes across various skill levels and classes.
            </p>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white dark:bg-light-gray rounded-xl shadow-md p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" wire:model.live.debounce.300ms="search" id="search" class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 pr-3 py-2 border-gray-300 dark:border-gray-600 dark:bg-darker-gray dark:text-white rounded-md" placeholder="Search riders...">
                    </div>
                </div>
                
                <!-- Skill Level Filter -->
                <div>
                    <label for="skillLevel" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Skill Level</label>
                    <select wire:model.live="skillLevel" id="skillLevel" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-darker-gray dark:text-white focus:outline-none focus:ring-primary-500 focus:border-primary-500 rounded-md">
                        <option value="">All Skill Levels</option>
                        @foreach($skillLevels as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Class Filter -->
                <div>
                    <label for="classFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Class</label>
                    <select wire:model.live="classFilter" id="classFilter" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-darker-gray dark:text-white focus:outline-none focus:ring-primary-500 focus:border-primary-500 rounded-md">
                        <option value="">All Classes</option>
                        @foreach($classes as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Results Count -->
        <div class="mb-6 flex justify-between items-center">
            <p class="text-gray-600 dark:text-gray-400">
                Showing {{ $riders->count() }} of {{ $riders->total() }} riders
            </p>
            
            <!-- Clear Filters -->
            @if($search || $skillLevel || $classFilter)
                <button 
                    wire:click="resetFilters"
                    class="text-sm text-gray-600 hover:text-cartel-red dark:text-gray-400 dark:hover:text-cartel-red transition border border-gray-300 dark:border-gray-600 rounded px-3 py-1 hover:border-cartel-red"
                >
                    Clear filters
                </button>
            @endif
        </div>

        <!-- Riders Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 sm:gap-4 md:gap-5 lg:gap-6 mb-8">
            @forelse($riders as $rider)
                <div class="bg-white dark:bg-light-gray rounded-xl overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300 flex flex-col">
                    <!-- Rider Image -->
                    <div class="relative h-40 sm:h-48 md:h-56 lg:h-64 bg-gray-200 dark:bg-gray-700">
                        @if($rider->profile_pic)
                            <img 
                                src="{{ $rider->profile_photo_url }}" 
                                alt="{{ $rider->full_name }}" 
                                class="w-full h-full object-cover"
                            >
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-200 dark:bg-gray-700">
                                <svg class="w-16 h-16 sm:w-20 sm:h-20 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                        @endif
                        
                        <!-- Skill Level Badge -->
                        @if($rider->skill_level)
                            <div class="absolute top-4 right-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $rider->skill_level === 'novice' ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : '' }}
                                    {{ $rider->skill_level === 'intermediate' ? 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100' : '' }}
                                    {{ $rider->skill_level === 'expert' ? 'bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100' : '' }}
                                    {{ $rider->skill_level === 'pro' ? 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' : '' }}
                                ">
                                    {{ ucfirst($rider->skill_level) }}
                                </span>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Rider Info -->
                    <div class="p-3 sm:p-4 md:p-5 flex-grow">
                        <div class="min-h-[3.5rem] mb-2">
                            <h3 class="text-base sm:text-lg md:text-xl font-bold text-gray-900 dark:text-white font-orbitron">
                                {{ $rider->full_name }}
                            </h3>
                            
                            @if($rider->nickname)
                                <p class="text-sm md:text-base text-gray-600 dark:text-gray-400 font-medium mt-0.5 h-5">
                                    "<span class="text-cartel-red">{{ $rider->nickname }}</span>"
                                </p>
                            @else
                                <div class="h-5"></div>
                            @endif
                        </div>
                        
                        <div class="mt-2 md:mt-3 space-y-1 md:space-y-2">
                            <!-- Age -->
                            <div class="flex items-center text-xs sm:text-sm text-gray-600 dark:text-gray-400">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $rider->date_of_birth->age }} years old
                            </div>
                            
                            <!-- Classes -->
                            @if($rider->class && count($rider->class) > 0)
                                <div class="flex items-center text-xs sm:text-sm text-gray-600 dark:text-gray-400">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    {{ is_array($rider->class) ? implode(', ', array_map('ucfirst', $rider->class)) : ucfirst($rider->class) }}
                                </div>
                            @endif
                            
                            <!-- Home Track -->
                            @if($rider->home_track_name)
                                <div class="flex items-center text-xs sm:text-sm text-gray-600 dark:text-gray-400">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    {{ $rider->home_track_name }}
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Social Links -->
                    @if($rider->social_profiles && count(array_filter($rider->social_profiles)) > 0)
                        <div class="px-3 sm:px-4 md:px-5 py-2 sm:py-3 bg-gray-50 dark:bg-darker-gray border-t border-gray-100 dark:border-gray-700">
                            <div class="flex space-x-2 sm:space-x-3">
                                @if(!empty($rider->social_profiles['instagram']))
                                    <a href="https://instagram.com/{{ ltrim($rider->social_profiles['instagram'], '@') }}" target="_blank" class="text-gray-400 hover:text-pink-600 transition">
                                        <span class="sr-only">Instagram</span>
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                        </svg>
                                    </a>
                                @endif
                                
                                @if(!empty($rider->social_profiles['facebook']))
                                    <a href="https://facebook.com/{{ $rider->social_profiles['facebook'] }}" target="_blank" class="text-gray-400 hover:text-blue-600 transition">
                                        <span class="sr-only">Facebook</span>
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                        </svg>
                                    </a>
                                @endif
                                
                                @if(!empty($rider->social_profiles['twitter']))
                                    <a href="https://twitter.com/{{ ltrim($rider->social_profiles['twitter'], '@') }}" target="_blank" class="text-gray-400 hover:text-blue-400 transition">
                                        <span class="sr-only">Twitter</span>
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/>
                                        </svg>
                                    </a>
                                @endif
                                
                                @if(!empty($rider->social_profiles['tiktok']))
                                    <a href="https://tiktok.com/@{{ ltrim($rider->social_profiles['tiktok'], '@') }}" target="_blank" class="text-gray-400 hover:text-black transition">
                                        <span class="sr-only">TikTok</span>
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>
                                        </svg>
                                    </a>
                                @endif
                                
                                @if(!empty($rider->social_profiles['youtube']))
                                    <a href="{{ strpos($rider->social_profiles['youtube'], 'http') === 0 ? $rider->social_profiles['youtube'] : 'https://youtube.com/c/' . $rider->social_profiles['youtube'] }}" target="_blank" class="text-gray-400 hover:text-red-600 transition">
                                        <span class="sr-only">YouTube</span>
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            @empty
                <div class="col-span-full py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No riders found</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Try adjusting your search or filter criteria.</p>
                    <div class="mt-6">
                        <button wire:click="resetFilters" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-white bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 hover:border-cartel-red focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cartel-red">
                            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Reset filters
                        </button>
                    </div>
                </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        <div class="mt-8">
            {{ $riders->links() }}
        </div>
    </div>
</div>
