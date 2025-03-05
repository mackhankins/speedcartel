<flux:button x-data x-on:click="$flux.dark = ! $flux.dark" variant="ghost" size="sm" square class="group"
    aria-label="Toggle theme">
    <flux:icon.sun x-show="! $flux.dark" variant="mini"
        class="h-5 w-5 text-gray-700 dark:text-white transition-transform group-hover:rotate-12" />
    <flux:icon.moon x-show="$flux.dark" variant="mini"
        class="h-5 w-5 text-gray-700 dark:text-white transition-transform group-hover:-rotate-12" />
</flux:button>
