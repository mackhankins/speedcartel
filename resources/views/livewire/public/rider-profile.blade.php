<div>
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-r from-gray-800 to-gray-900 h-40 sm:h-52">
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute inset-0 bg-black opacity-20"></div>
            <!-- Red accent line at the bottom -->
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-cartel-red"></div>
        </div>
        <!-- Profile Picture -->
        <div class="absolute -bottom-20 left-1/2 transform -translate-x-1/2">
            <div class="relative w-40 h-40 rounded-full border-4 border-white dark:border-darker-gray overflow-hidden bg-gray-200 dark:bg-gray-700">
                @if($rider->profile_pic)
                    <img
                        src="{{ $rider->profile_photo_url }}"
                        alt="{{ $rider->full_name }}"
                        class="w-full h-full object-cover"
                    >
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-20 h-20 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="mt-24 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Basic Info -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white font-orbitron">
                {{ $rider->firstname }} {{ $rider->lastname }}
            </h1>
            @if($rider->nickname)
                <div class="text-xl text-gray-600 dark:text-gray-400 font-medium mt-2">
                    "<span class="text-cartel-red">{{ $rider->nickname }}</span>"
                </div>
            @endif
        </div>

        <!-- Rider Info Section -->
        <div class="bg-white dark:bg-darker-gray rounded-xl shadow-sm overflow-hidden mb-12">
            <div class="grid grid-cols-1 lg:grid-cols-3 divide-y lg:divide-y-0 lg:divide-x divide-gray-100 dark:divide-gray-700">
                <!-- Rider Details -->
                <div class="col-span-2 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Rider Details</h2>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                            <div class="text-gray-500 dark:text-gray-400 font-medium">Age</div>
                            <div class="text-gray-900 dark:text-white">{{ $rider->date_of_birth->age }} years</div>
                        </div>

                        @if($rider->skill_level)
                            <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                                <div class="text-gray-500 dark:text-gray-400 font-medium">Skill Level</div>
                                <div class="text-gray-900 dark:text-white">{{ $skillLevelOptions[$rider->skill_level] }}</div>
                            </div>
                        @endif

                        @if($rider->class)
                            <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                                <div class="text-gray-500 dark:text-gray-400 font-medium">Class</div>
                                <div class="text-gray-900 dark:text-white">
                                    {{ collect($rider->class)->map(fn($c) => $classOptions[$c])->join(', ') }}
                                </div>
                            </div>
                        @endif

                        @if($rider->homeTrack)
                            <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                                <div class="text-gray-500 dark:text-gray-400 font-medium">Home Track</div>
                                <div class="text-gray-900 dark:text-white">{{ $rider->homeTrack->name }}</div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Social Media Links -->
                @if($rider->social_profiles && count(array_filter((array)$rider->social_profiles)) > 0)
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Follow {{ $rider->firstname }}</h2>
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-1 gap-3">
                            @if(!empty($rider->social_profiles['instagram']))
                                <a href="https://instagram.com/{{ ltrim($rider->social_profiles['instagram'], '@') }}"
                                   target="_blank"
                                   class="flex items-center space-x-2 p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-pink-200 hover:bg-pink-50 dark:hover:border-pink-900 dark:hover:bg-pink-900/20 group transition-all">
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-pink-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm text-gray-600 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white">Instagram</span>
                                </a>
                            @endif

                            @if(!empty($rider->social_profiles['facebook']))
                                <a href="{{ Str::startsWith($rider->social_profiles['facebook'], 'http') ? $rider->social_profiles['facebook'] : 'https://facebook.com/' . $rider->social_profiles['facebook'] }}"
                                   target="_blank"
                                   class="flex items-center space-x-2 p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-blue-200 hover:bg-blue-50 dark:hover:border-blue-900 dark:hover:bg-blue-900/20 group transition-all">
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm text-gray-600 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white">Facebook</span>
                                </a>
                            @endif

                            @if(!empty($rider->social_profiles['twitter']))
                                <a href="https://twitter.com/{{ ltrim($rider->social_profiles['twitter'], '@') }}"
                                   target="_blank"
                                   class="flex items-center space-x-2 p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-blue-200 hover:bg-blue-50 dark:hover:border-blue-900 dark:hover:bg-blue-900/20 group transition-all">
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                                    </svg>
                                    <span class="text-sm text-gray-600 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white">Twitter</span>
                                </a>
                            @endif

                            @if(!empty($rider->social_profiles['tiktok']))
                                <a href="https://tiktok.com/@{{ ltrim($rider->social_profiles['tiktok'], '@') }}"
                                   target="_blank"
                                   class="flex items-center space-x-2 p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-gray-400 hover:bg-gray-50 dark:hover:border-gray-600 dark:hover:bg-gray-800 group transition-all">
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-black dark:group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z" />
                                    </svg>
                                    <span class="text-sm text-gray-600 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white">TikTok</span>
                                </a>
                            @endif

                            @if(!empty($rider->social_profiles['youtube']))
                                <a href="{{ Str::startsWith($rider->social_profiles['youtube'], 'http') ? $rider->social_profiles['youtube'] : 'https://youtube.com/c/' . $rider->social_profiles['youtube'] }}"
                                   target="_blank"
                                   class="flex items-center space-x-2 p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-red-200 hover:bg-red-50 dark:hover:border-red-900 dark:hover:bg-red-900/20 group transition-all">
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-red-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" d="M19.812 5.418c.861.23 1.538.907 1.768 1.768C21.998 8.746 22 12 22 12s0 3.255-.418 4.814a2.504 2.504 0 0 1-1.768 1.768c-1.56.419-7.814.419-7.814.419s-6.255 0-7.814-.419a2.505 2.505 0 0 1-1.768-1.768C2 15.255 2 12 2 12s0-3.255.417-4.814a2.507 2.507 0 0 1 1.768-1.768C5.744 5 11.998 5 11.998 5s6.255 0 7.814.418ZM15.194 12 10 15V9l5.194 3Z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm text-gray-600 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white">YouTube</span>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Race Plates Section -->
        <div class="bg-white dark:bg-darker-gray rounded-xl shadow-sm overflow-hidden mb-12">
            <div class="p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Plates</h2>
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                <!-- Current Plate (if exists) -->
                @if($currentPlate)
                    <div class="flex items-center justify-between py-3">
                        <div class="flex items-center gap-2">
                            <span class="font-mono text-lg font-bold text-gray-900 dark:text-white">#{{ $currentPlate->number }}</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-100">
                                Current
                            </span>
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            {{ ucfirst($currentPlate->type) }} {{ $currentPlate->year }}
                        </div>
                    </div>
                @endif

                <!-- Other Plates -->

                    @forelse($otherPlates as $plate)
                        <div class="flex items-center justify-between py-3">
                            <span class="font-mono text-lg font-bold text-gray-900 dark:text-white">#{{ $plate->number }}</span>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                {{ ucfirst($plate->type) }} {{ $plate->year }}
                            </div>
                        </div>
                    @empty
                        @if(!$currentPlate)
                            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                No plates registered
                            </div>
                        @endif
                    @endforelse

                    @if($otherPlates->count() < $totalPlates)
                        <div class="pt-4 text-center">
                            <button
                                wire:click="loadMorePlates"
                                type="button"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-darker-gray hover:bg-gray-50 dark:hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cartel-red"
                            >
                                <svg
                                    class="-ml-1 mr-2 h-5 w-5 text-gray-400"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                                Load More
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
