<div class="mt-8" x-data="{ platesOpen: false }">
    <button 
        type="button" 
        @click="platesOpen = !platesOpen" 
        class="flex items-center justify-between w-full px-4 py-3 bg-gray-100 dark:bg-gray-700 rounded-lg text-left focus:outline-none focus:ring-2 focus:ring-primary-500"
    >
        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Plates</h3>
        <svg 
            class="w-5 h-5 text-gray-500 dark:text-gray-400 transition-transform duration-200" 
            :class="{'rotate-180': platesOpen}"
            fill="none" 
            stroke="currentColor" 
            viewBox="0 0 24 24"
        >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>
    
    <div x-show="platesOpen" x-collapse x-cloak>
        <div class="mt-4 space-y-4">
            @foreach($plates as $index => $plate)
                <div class="relative bg-white dark:bg-darker-gray rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                    <div class="grid grid-cols-1 gap-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="grid grid-cols-2 gap-2">
                                <x-select
                                    wire:model="plates.{{ $index }}.type"
                                    label="Plate Type"
                                    :options="\App\Models\Plate::$typeOptions"
                                    option-label="name"
                                    option-value="value"
                                    :disabled="$selectedRider && $status === 0"
                                />
                                
                                <x-select
                                    wire:model="plates.{{ $index }}.year"
                                    label="Year"
                                    :options="collect(range(date('Y'), date('Y') - 25))->map(fn($year) => ['name' => $year, 'value' => $year])->toArray()"
                                    option-label="name"
                                    option-value="value"
                                    :disabled="$selectedRider && $status === 0"
                                />
                            </div>
                            
                            <x-input 
                                wire:model="plates.{{ $index }}.number"
                                label="Plate Number"
                                class="md:col-span-2"
                                :disabled="$selectedRider && $status === 0"
                            />
                        </div>
                        
                        <x-radio 
                            wire:model="current_plate_index" 
                            value="{{ $index }}"
                            label="Current Plate"
                            :disabled="$selectedRider && $status === 0"
                        />
                    </div>
                    
                    @if(count($plates) > 1)
                        <button 
                            type="button"
                            wire:click="removePlate({{ $index }})"
                            class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition-colors"
                            :disabled="$selectedRider && $status === 0"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    @endif
                </div>
            @endforeach

            <div class="flex justify-center">
                <x-button 
                    type="button"
                    wire:click="addPlate"
                    secondary
                    :disabled="$selectedRider && $status === 0"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Another Plate
                </x-button>
            </div>
        </div>
    </div>
</div> 