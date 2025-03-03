<div class="min-h-screen">
    <!-- Modern Hero Section with Video Background -->
    <div>
        <section class="relative overflow-hidden min-h-screen flex items-center">
            <!-- Video Background -->
            <div class="absolute inset-0 z-0">
                <div class="absolute inset-0 bg-black/60 z-10"></div>
                <video class="absolute inset-0 w-full h-full object-cover" autoplay loop muted playsinline>
                    <source src="{{ asset('/videos/herbst_version2.mp4') }}" type="video/mp4">
                    <!-- Fallback image if video fails to load -->
                    <img src="https://placehold.co/1920x1080" alt="BMX Background" class="w-full h-full object-cover">
                </video>
            </div>

            <!-- CSS-based noise texture overlay -->
            <div class="absolute inset-0 z-20 opacity-30 mix-blend-overlay"
                style="background-image: url('data:image/svg+xml,%3Csvg viewBox=%220 0 200 200%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22noiseFilter%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.65%22 numOctaves=%223%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url%28%23noiseFilter%29%22/%3E%3C/svg%3E');">
            </div>

            <div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-28 relative z-30">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-12 items-center">
                    <div class="md:col-span-7 space-y-8">
                        <div
                            class="inline-block px-3 py-1 rounded-full bg-cartel-red/20 border border-cartel-red/30 backdrop-blur-sm">
                            <p class="text-sm font-medium text-cartel-red tracking-wide uppercase font-orbitron">Speed
                                Cartel BMX Racing</p>
                        </div>

                        <h1 class="font-orbitron font-black text-6xl md:text-7xl lg:text-8xl leading-none text-white">
                            <span class="inline-block animate-[fadeInUp_0.5s_ease-out]">DOMINATE</span><br>
                            <span class="inline-block animate-[fadeInUp_0.7s_ease-out]">THE</span>
                            <span class="text-cartel-red relative inline-block animate-[fadeInUp_0.9s_ease-out]">
                                TRACK
                                <span
                                    class="absolute bottom-1 left-0 w-full h-1 bg-cartel-red animate-[widthGrow_1.2s_ease-out]"></span>
                            </span>
                        </h1>

                        <p class="text-gray-300 text-lg md:text-xl max-w-lg leading-relaxed">
                            Speed Cartel BMX Racing Team is built on passion, precision, and pure adrenaline. Join us as
                            we redefine what's possible on two wheels.
                        </p>

                        <div class="flex flex-col sm:flex-row gap-5">
                            <a href="#"
                                class="group flex items-center justify-center bg-cartel-red hover:bg-red-700 text-white font-orbitron font-bold py-4 px-8 rounded-lg transition duration-300 ease-out">
                                JOIN THE TEAM
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 ml-2 transform group-hover:translate-x-1 transition" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                            <a href="#"
                                class="group flex items-center justify-center bg-white/10 hover:bg-white/20 text-white font-orbitron font-bold py-4 px-8 border border-white/20 rounded-lg backdrop-blur-sm transition duration-300">
                                UPCOMING RACES
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 opacity-70" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </a>
                        </div>

                        <!-- Social proof -->
                        <div class="flex flex-wrap gap-8 pt-6 border-t border-white/20">
                            <div>
                                <p class="text-3xl font-bold text-white">25+</p>
                                <p class="text-gray-400 text-sm">Championships</p>
                            </div>
                            <div>
                                <p class="text-3xl font-bold text-white">50+</p>
                                <p class="text-gray-400 text-sm">Team Athletes</p>
                            </div>
                            <div>
                                <p class="text-3xl font-bold text-white">12</p>
                                <p class="text-gray-400 text-sm">Countries</p>
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-5 relative md:flex md:items-center md:justify-end">
                        <!-- Floating card element -->
                        <div
                            class="relative bg-black/40 backdrop-blur-md p-6 rounded-2xl border border-white/10 shadow-2xl max-w-md mx-auto md:mx-0">
                            <div class="absolute -top-3 -left-3 w-6 h-6 rounded-full bg-cartel-red"></div>
                            <div class="absolute -bottom-3 -right-3 w-6 h-6 rounded-full bg-cartel-red"></div>

                            <h3 class="font-orbitron text-xl font-bold text-white mb-3">Next Championship</h3>

                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 rounded-full bg-cartel-red/20 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-cartel-red" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-white font-medium">March 15, 2025</p>
                                    <p class="text-gray-400 text-sm">San Diego, CA</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-12 h-12 rounded-full bg-cartel-red/20 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-cartel-red" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-white font-medium">Registration Opens</p>
                                    <p class="text-gray-400 text-sm">In 3 days, 14 hours</p>
                                </div>
                            </div>

                            <a href="#"
                                class="block w-full text-center bg-cartel-red hover:bg-red-700 text-white font-medium py-3 px-4 rounded-lg transition duration-300">
                                Register Now
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Featured Riders Section -->
        <section class="bg-white dark:bg-dark-gray py-24">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="font-orbitron font-bold text-3xl md:text-4xl text-gray-900 dark:text-white">FEATURED
                        <span class="text-cartel-red">RIDERS</span>
                    </h2>
                    <div class="w-24 h-1 bg-cartel-red mx-auto mt-4"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Rider Card 1 -->
                    <div
                        class="bg-gray-100 dark:bg-darker-gray rounded-lg overflow-hidden shadow-lg border border-gray-200 dark:border-light-gray hover:border-cartel-red transition-all duration-300 transform hover:-translate-y-2">
                        <img src="https://placehold.co/400x300" alt="Rider 1" class="w-full h-64 object-cover">
                        <div class="p-6">
                            <h3 class="font-orbitron font-bold text-xl mb-2 text-gray-900 dark:text-white">ALEX
                                "AFTERBURNER" RODRIGUEZ</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">Elite Men's Class • 5x National Champion
                            </p>
                            <div class="flex justify-between items-center">
                                <span class="text-cartel-red font-orbitron">#SC001</span>
                                <a href="#"
                                    class="text-sm text-gray-900 dark:text-white hover:text-cartel-red dark:hover:text-cartel-red transition">VIEW
                                    PROFILE →</a>
                            </div>
                        </div>
                    </div>

                    <!-- Rider Card 2 -->
                    <div
                        class="bg-gray-100 dark:bg-darker-gray rounded-lg overflow-hidden shadow-lg border border-gray-200 dark:border-light-gray hover:border-cartel-red transition-all duration-300 transform hover:-translate-y-2">
                        <img src="https://placehold.co/400x300" alt="Rider 2" class="w-full h-64 object-cover">
                        <div class="p-6">
                            <h3 class="font-orbitron font-bold text-xl mb-2 text-gray-900 dark:text-white">SAMANTHA
                                "SLIPSTREAM" CHEN</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">Elite Women's Class • World Cup Finalist
                            </p>
                            <div class="flex justify-between items-center">
                                <span class="text-cartel-red font-orbitron">#SC002</span>
                                <a href="#"
                                    class="text-sm text-gray-900 dark:text-white hover:text-cartel-red dark:hover:text-cartel-red transition">VIEW
                                    PROFILE →</a>
                            </div>
                        </div>
                    </div>

                    <!-- Rider Card 3 -->
                    <div
                        class="bg-gray-100 dark:bg-darker-gray rounded-lg overflow-hidden shadow-lg border border-gray-200 dark:border-light-gray hover:border-cartel-red transition-all duration-300 transform hover:-translate-y-2">
                        <img src="https://placehold.co/400x300" alt="Rider 3" class="w-full h-64 object-cover">
                        <div class="p-6">
                            <h3 class="font-orbitron font-bold text-xl mb-2 text-gray-900 dark:text-white">MARCUS
                                "VELOCITY" JACKSON</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">Junior Elite • Rising Star</p>
                            <div class="flex justify-between items-center">
                                <span class="text-cartel-red font-orbitron">#SC003</span>
                                <a href="#"
                                    class="text-sm text-gray-900 dark:text-white hover:text-cartel-red dark:hover:text-cartel-red transition">VIEW
                                    PROFILE →</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-12">
                    <a href="#"
                        class="inline-block bg-transparent hover:bg-gray-200 dark:hover:bg-light-gray text-gray-900 dark:text-white font-orbitron font-bold py-3 px-8 border-2 border-cartel-red rounded-md transition duration-300 ease-in-out">
                        VIEW ALL TEAM MEMBERS
                    </a>
                </div>
            </div>
        </section>

        <!-- Latest News Section -->
        <section class="py-24 bg-gradient-to-b from-gray-50 to-white dark:from-darker-gray dark:to-dark-gray">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center mb-16">
                    <h2 class="font-orbitron font-bold text-3xl md:text-4xl text-gray-900 dark:text-white">LATEST <span
                            class="text-cartel-red">NEWS</span></h2>
                    <a href="#"
                        class="hidden md:block text-gray-900 dark:text-white hover:text-cartel-red transition font-orbitron">VIEW
                        ALL NEWS →</a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- News Item 1 -->
                    <div
                        class="bg-white dark:bg-light-gray rounded-lg overflow-hidden shadow-lg flex flex-col h-full transform hover:-translate-y-1 transition-all duration-300">
                        <img src="https://placehold.co/600x300" alt="News 1" class="w-full h-48 object-cover">
                        <div class="p-6 flex-grow">
                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mb-3">
                                <span>March 1, 2025</span>
                                <span class="mx-2">•</span>
                                <span>Race Results</span>
                            </div>
                            <h3 class="font-orbitron font-bold text-xl mb-3 text-gray-900 dark:text-white">SPEED CARTEL
                                DOMINATES AT NATIONAL CHAMPIONSHIP</h3>
                            <p class="text-gray-700 dark:text-gray-300 mb-4">Team Speed Cartel riders secured three
                                podium spots at this weekend's National Championship series, cementing our position as
                                the team to beat this season.</p>
                            <a href="#"
                                class="text-cartel-red hover:text-gray-900 dark:hover:text-white transition mt-auto inline-block">READ
                                MORE →</a>
                        </div>
                    </div>

                    <!-- News Item 2 -->
                    <div
                        class="bg-white dark:bg-light-gray rounded-lg overflow-hidden shadow-lg flex flex-col h-full transform hover:-translate-y-1 transition-all duration-300">
                        <img src="https://placehold.co/600x300" alt="News 2" class="w-full h-48 object-cover">
                        <div class="p-6 flex-grow">
                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mb-3">
                                <span>February 15, 2025</span>
                                <span class="mx-2">•</span>
                                <span>Team News</span>
                            </div>
                            <h3 class="font-orbitron font-bold text-xl mb-3 text-gray-900 dark:text-white">NEW
                                SPONSORSHIP DEAL WITH VELOCITY COMPONENTS</h3>
                            <p class="text-gray-700 dark:text-gray-300 mb-4">We're excited to announce our new
                                partnership with Velocity Components, bringing cutting-edge BMX technology to our riders
                                for the upcoming season.</p>
                            <a href="#"
                                class="text-cartel-red hover:text-gray-900 dark:hover:text-white transition mt-auto inline-block">READ
                                MORE →</a>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-12 md:hidden">
                    <a href="#"
                        class="inline-block text-gray-900 dark:text-white hover:text-cartel-red transition font-orbitron">
                        VIEW ALL NEWS →
                    </a>
                </div>
            </div>
        </section>

        <!-- Upcoming Race Calendar -->
        <section class="bg-white dark:bg-darker-gray py-24">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="font-orbitron font-bold text-3xl md:text-4xl text-gray-900 dark:text-white">RACE <span
                            class="text-cartel-red">CALENDAR</span></h2>
                    <div class="w-24 h-1 bg-cartel-red mx-auto mt-4"></div>
                </div>

                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                        <thead class="bg-gray-100 dark:bg-light-gray">
                            <tr>
                                <th scope="col"
                                    class="py-3.5 pl-4 pr-3 text-left text-sm font-orbitron text-gray-900 dark:text-white sm:pl-6">
                                    DATE</th>
                                <th scope="col"
                                    class="px-3 py-3.5 text-left text-sm font-orbitron text-gray-900 dark:text-white">
                                    EVENT</th>
                                <th scope="col"
                                    class="px-3 py-3.5 text-left text-sm font-orbitron text-gray-900 dark:text-white">
                                    LOCATION</th>
                                <th scope="col"
                                    class="px-3 py-3.5 text-left text-sm font-orbitron text-gray-900 dark:text-white">
                                    CATEGORY</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                    <span class="sr-only">Details</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-800 bg-white dark:bg-dark-gray">
                            <tr>
                                <td
                                    class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-white sm:pl-6">
                                    Mar 15, 2025</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-700 dark:text-gray-300">West
                                    Coast Showdown</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-700 dark:text-gray-300">San
                                    Diego, CA</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-700 dark:text-gray-300">
                                    National</td>
                                <td class="whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                    <a href="#" class="text-cartel-red hover:text-red-400">Details</a>
                                </td>
                            </tr>
                            <tr>
                                <td
                                    class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-white sm:pl-6">
                                    Apr 2-3, 2025</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-700 dark:text-gray-300">BMX
                                    Super Series</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-700 dark:text-gray-300">Austin,
                                    TX</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-700 dark:text-gray-300">
                                    International</td>
                                <td class="whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                    <a href="#" class="text-cartel-red hover:text-red-400">Details</a>
                                </td>
                            </tr>
                            <tr>
                                <td
                                    class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-white sm:pl-6">
                                    Apr 22, 2025</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-700 dark:text-gray-300">Metro
                                    City Challenge</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-700 dark:text-gray-300">
                                    Chicago, IL</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-700 dark:text-gray-300">
                                    Regional</td>
                                <td class="whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                    <a href="#" class="text-cartel-red hover:text-red-400">Details</a>
                                </td>
                            </tr>
                            <tr>
                                <td
                                    class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-white sm:pl-6">
                                    May 8-10, 2025</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-700 dark:text-gray-300">World
                                    Cup Qualifier</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-700 dark:text-gray-300">
                                    Orlando, FL</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-700 dark:text-gray-300">World
                                    Cup</td>
                                <td class="whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                    <a href="#" class="text-cartel-red hover:text-red-400">Details</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- Sponsors Section -->
        <section class="bg-gray-50 dark:bg-dark-gray py-24">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="font-orbitron font-bold text-3xl md:text-4xl text-gray-900 dark:text-white">OUR <span
                            class="text-cartel-red">SPONSORS</span></h2>
                    <div class="w-24 h-1 bg-cartel-red mx-auto mt-4"></div>
                    <p class="mt-6 text-gray-700 dark:text-gray-300 max-w-2xl mx-auto">
                        We're proud to partner with these amazing brands who support our vision and help us push the
                        limits of BMX racing.
                    </p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 items-center">
                    <!-- Sponsor logos - replace with actual logos -->
                    <div class="bg-white dark:bg-light-gray p-6 rounded-lg flex items-center justify-center h-32">
                        <div class="text-gray-700 dark:text-gray-300 font-bold">SPONSOR 1</div>
                    </div>
                    <div class="bg-white dark:bg-light-gray p-6 rounded-lg flex items-center justify-center h-32">
                        <div class="text-gray-700 dark:text-gray-300 font-bold">SPONSOR 2</div>
                    </div>
                    <div class="bg-white dark:bg-light-gray p-6 rounded-lg flex items-center justify-center h-32">
                        <div class="text-gray-700 dark:text-gray-300 font-bold">SPONSOR 3</div>
                    </div>
                    <div class="bg-white dark:bg-light-gray p-6 rounded-lg flex items-center justify-center h-32">
                        <div class="text-gray-700 dark:text-gray-300 font-bold">SPONSOR 4</div>
                    </div>
                </div>

                <div class="text-center mt-12">
                    <a href="#"
                        class="inline-block bg-transparent hover:bg-gray-200 dark:hover:bg-light-gray text-gray-900 dark:text-white font-orbitron font-bold py-3 px-8 border-2 border-cartel-red rounded-md transition duration-300 ease-in-out">
                        BECOME A SPONSOR
                    </a>
                </div>
            </div>
        </section>
    </div>
</div>
