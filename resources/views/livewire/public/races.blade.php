<div class="bg-white dark:bg-darker-gray min-h-screen">
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-r from-gray-800 to-gray-900 py-12 md:py-16">
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute inset-0 bg-black opacity-20"></div>
            <!-- Red accent line at the bottom -->
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-cartel-red"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl md:text-4xl font-bold text-white font-orbitron mb-3">RACE EVENTS</h1>
            <p class="text-lg text-gray-200 max-w-2xl mx-auto">
                Find upcoming BMX races and events. Plan your race schedule and never miss an opportunity to compete.
            </p>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white dark:bg-light-gray rounded-xl shadow-md p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" wire:model.live.debounce.300ms="search" id="search" class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 pr-3 py-2 border-gray-300 dark:border-gray-600 dark:bg-darker-gray dark:text-white rounded-md" placeholder="Search events...">
                    </div>
                </div>

                <!-- Time Filter -->
                <div>
                    <x-native-select
                        label="Time Period"
                        wire:model.live="filter"
                    >
                        <option value="upcoming">Upcoming Events</option>
                        <option value="past">Past Events</option>
                        <option value="month">By Month</option>
                    </x-native-select>
                </div>

                <!-- Event Type Filter -->
                <div>
                    <x-native-select
                        label="Event Type"
                        wire:model.live="selectedTag"
                    >
                        <option value="">All Types</option>
                        @foreach($eventTags as $tag)
                            <option value="{{ $tag }}">{{ ucwords($tag) }}</option>
                        @endforeach
                    </x-native-select>
                </div>

                <!-- Month/Year Selectors (only show when filter is 'month') -->
                <div x-data="{}" x-show="$wire.filter === 'month'" x-transition>
                    <div class="grid grid-cols-2 gap-2">
                        <x-native-select
                            label="Month"
                            wire:model.live="month"
                        >
                            @foreach($months as $key => $monthName)
                                <option value="{{ $key }}">{{ $monthName }}</option>
                            @endforeach
                        </x-native-select>
                        
                        <x-native-select
                            label="Year"
                            wire:model.live="year"
                        >
                            @foreach($years as $yearValue)
                                <option value="{{ $yearValue }}">{{ $yearValue }}</option>
                            @endforeach
                        </x-native-select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Events List -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        <div wire:loading class="w-full flex justify-center my-8">
            <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-cartel-red"></div>
        </div>

        <div wire:loading.remove>
            @if($events->isEmpty())
                <div class="text-center py-12 bg-white dark:bg-light-gray rounded-lg shadow">
                    <svg class="mx-auto h-16 w-16 text-gray-400 dark:text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No events found</h3>
                    <p class="mt-1 text-gray-500 dark:text-gray-400">Try adjusting your search or filter to find what you're looking for.</p>
                </div>
            @else
                <div class="grid gap-6">
                    @foreach($events as $event)
                        <div class="bg-white dark:bg-light-gray rounded-lg shadow-md overflow-hidden border border-gray-200 dark:border-gray-700 hover:border-cartel-red dark:hover:border-cartel-red transition-all duration-300">
                            <div class="md:flex">
                                <!-- Date Column -->
                                <div class="bg-gray-100 dark:bg-gray-800 p-4 md:p-6 flex flex-col items-center justify-center md:w-48 text-center">
                                    <div class="font-orbitron text-lg text-gray-500 dark:text-gray-400">
                                        {{ $event->start_date->format('M') }}
                                    </div>
                                    <div class="font-orbitron text-4xl font-bold text-cartel-red">
                                        {{ $event->start_date->format('d') }}
                                    </div>
                                    <div class="font-orbitron text-lg text-gray-500 dark:text-gray-400">
                                        {{ $event->start_date->format('Y') }}
                                    </div>
                                    
                                    @if($event->start_date->format('Y-m-d') !== $event->end_date->format('Y-m-d'))
                                        <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                            through
                                        </div>
                                        <div class="font-orbitron text-lg text-gray-500 dark:text-gray-400">
                                            {{ $event->end_date->format('M d') }}
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Event Details -->
                                <div class="p-6 md:flex-1">
                                    <div class="flex flex-col md:flex-row md:justify-between md:items-start">
                                        <div>
                                            <h3 class="font-orbitron text-xl font-bold text-gray-900 dark:text-white">
                                                {{ $event->title }}
                                            </h3>
                                            
                                            <div class="mt-2 flex items-center text-gray-600 dark:text-gray-400">
                                                <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                <span>{{ $event->location }}</span>
                                            </div>
                                            
                                            @if($event->tags->isNotEmpty())
                                                <div class="mt-3 flex flex-wrap gap-2">
                                                    @foreach($event->tags as $tag)
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-cartel-red bg-opacity-10 text-cartel-red">
                                                            {{ ucwords($tag->name) }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="mt-4 md:mt-0">
                                            @if($event->url)
                                                <a href="{{ $event->url }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-cartel-red hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cartel-red">
                                                    Event Details
                                                    <svg class="ml-2 -mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                    </svg>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    @if($event->description)
                                        <div class="mt-4 text-gray-600 dark:text-gray-400">
                                            <p class="line-clamp-2">{{ $event->description }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-8">
                    <div class="w-full bg-white dark:bg-light-gray rounded-lg shadow px-4 py-3">
                        {{ $events->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

