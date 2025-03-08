<div>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-orbitron mb-8">Riders</h1>
            @unless($showForm)
                <x-button wire:click="create" primary label="Add New Rider" />
            @endunless
        </div>

        @if($showForm)
            @include('livewire.dashboard.riders._form')
        @else
            <div class="bg-white dark:bg-light-gray shadow rounded-2xl px-6 py-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($riders as $rider)
                        @include('livewire.dashboard.riders._card', ['rider' => $rider])
                    @empty
                        <div class="col-span-full text-center py-12">
                            <p class="text-gray-500 dark:text-gray-400">No riders added yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        @endif
    </div>
    
    @push('scripts')
    <script>
        // This space is available for any future scripts
    </script>
    @endpush
</div>