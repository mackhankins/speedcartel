<button x-data x-on:click="$store.theme.toggle()"
    class="p-2 text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-light-gray rounded-lg group"
    aria-label="Toggle theme">
    <x-icon name="sun" class="w-5 h-5 block dark:hidden" mini />
    <x-icon name="moon" class="w-5 h-5 hidden dark:block" mini />
</button>