<!-- Team Overview -->
<a href="{{ route('dashboard') }}"
    class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('dashboard') ? 'bg-red-100 text-red-900 dark:bg-red-900 dark:bg-opacity-50 dark:text-red-100' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-light-gray hover:text-gray-900 dark:hover:text-gray-100' }}">
    <svg class="mr-3 h-6 w-6 {{ request()->routeIs('dashboard') ? 'text-red-500 dark:text-red-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}"
        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
    </svg>
    Dashboard
</a>

<!-- Profile -->
<a href="{{ route('profile') }}"
    class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('profile') ? 'bg-red-100 text-red-900 dark:bg-red-900 dark:bg-opacity-50 dark:text-red-100' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-light-gray hover:text-gray-900 dark:hover:text-gray-100' }}">
    <svg class="mr-3 h-6 w-6 {{ request()->routeIs('profile') ? 'text-red-500 dark:text-red-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}"
        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
    </svg>
    Profile
</a>

<!-- Divider -->
<div class="border-t border-gray-200 dark:border-light-gray my-3"></div>

<!-- Riders -->
<a href="{{ route('riders') }}"
    class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('riders') ? 'bg-red-100 text-red-900 dark:bg-red-900 dark:bg-opacity-50 dark:text-red-100' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-light-gray hover:text-gray-900 dark:hover:text-gray-100' }}">
    <svg class="mr-3 h-6 w-6 {{ request()->routeIs('riders') ? 'text-red-500 dark:text-red-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}"
        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
    </svg>
    Riders
</a>

<!-- Training Schedule -->
<a href="#"
    class="group flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-light-gray hover:text-gray-900 dark:hover:text-gray-100">
    <svg class="mr-3 h-6 w-6 text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-300"
        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
    </svg>
    Training Schedule
</a>

<!-- Sponsors -->
<a href="{{ route('sponsors') }}"
    class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('sponsors') ? 'bg-red-100 text-red-900 dark:bg-red-900 dark:bg-opacity-50 dark:text-red-100' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-light-gray hover:text-gray-900 dark:hover:text-gray-100' }}">
    <svg class="mr-3 h-6 w-6 {{ request()->routeIs('sponsors') ? 'text-red-500 dark:text-red-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}"
        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    Sponsors
</a>

<!-- Contact -->
<a href="{{ route('contact') }}"
    class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('contact') ? 'bg-red-100 text-red-900 dark:bg-red-900 dark:bg-opacity-50 dark:text-red-100' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-light-gray hover:text-gray-900 dark:hover:text-gray-100' }}">
    <svg class="mr-3 h-6 w-6 {{ request()->routeIs('contact') ? 'text-red-500 dark:text-red-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-300' }}"
        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
    </svg>
    Contact
</a>