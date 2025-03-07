<div>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-orbitron mb-8">Riders</h1>
            @unless($showForm)
                <x-button wire:click="create" primary label="Add New Rider" />
            @endunless
        </div>

        @if($showForm)
            <div class="bg-white dark:bg-darker-gray shadow rounded-2xl px-6 py-8 mb-8">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white font-orbitron mb-6">
                    {{ $editingRider ? 'Edit Rider' : 'Add New Rider' }}
                </h2>

                <form wire:submit="save" class="space-y-6">
                    <div class="grid grid-cols-1 gap-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="relative">
                                <x-input 
                                    wire:model.live="firstname" 
                                    label="First Name" 
                                    required 
                                    autocomplete="off"
                                    :disabled="$selectedRider && $status === 0"
                                />
                                @if(!$editingRider && count($searchResults) > 0)
                                    <div class="absolute z-50 w-full mt-1 bg-white dark:bg-darker-gray rounded-md shadow-lg border border-gray-200 dark:border-light-gray">
                                        @foreach($searchResults as $result)
                                            <button
                                                wire:click="selectExistingRider({{ $result['id'] }})"
                                                type="button"
                                                class="w-full px-4 py-2 text-left hover:bg-gray-100 dark:hover:bg-light-gray"
                                            >
                                                <div class="font-medium">{{ $result['full_name'] }}</div>
                                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                                    Born: {{ \Carbon\Carbon::parse($result['date_of_birth'])->format('M d, Y') }}
                                                </div>
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <x-input 
                                wire:model="lastname" 
                                label="Last Name" 
                                required 
                                :disabled="$selectedRider && $status === 0"
                            />

                            <x-input 
                                wire:model="nickname" 
                                label="Nickname"
                                :disabled="$selectedRider && $status === 0"
                            />

                            <x-input 
                                wire:model="date_of_birth" 
                                type="date" 
                                label="Date of Birth"
                                :disabled="$selectedRider && $status === 0"
                            />

                            <x-select
                                wire:model="class"
                                label="Class"
                                multiselect
                                :options="[
                                    ['name' => 'Class', 'value' => 'class'],
                                    ['name' => 'Cruiser', 'value' => 'cruiser']
                                ]"
                                option-label="name"
                                option-value="value"
                                :disabled="$selectedRider && $status === 0"
                            />

                            <x-select
                                wire:model="skill_level"
                                label="Skill Level"
                                :options="[
                                    ['name' => 'Beginner', 'value' => 'beginner'],
                                    ['name' => 'Intermediate', 'value' => 'intermediate'],
                                    ['name' => 'Expert', 'value' => 'expert'],
                                    ['name' => 'Pro', 'value' => 'pro'],
                                ]"
                                option-label="name"
                                option-value="value"
                                :disabled="$selectedRider && $status === 0"
                            />

                            <x-select
                                wire:model="relationship"
                                label="Relationship"
                                :options="[
                                    ['name' => 'Parent', 'value' => 'parent'],
                                    ['name' => 'Guardian', 'value' => 'guardian'],
                                    ['name' => 'Self', 'value' => 'self'],
                                    ['name' => 'Other', 'value' => 'other']
                                ]"
                                option-label="name"
                                option-value="value"
                            />

                            <div class="md:col-span-2">
                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        {{ __('Profile Photo') }}
                                    </label>
                                    
                                    <livewire:components.profile-picture-cropper
                                        wire:key="rider-profile-pic-{{ time() }}-{{ $editingRider ? $selectedRider->id : 'create' }}"
                                        :storagePath="'riders'"
                                        :aspectRatio="1"
                                        :viewportWidth="400"
                                        :viewportHeight="400"
                                        :filePrefix="'rider_'"
                                        :cropperType="'square'"
                                        :previewSize="24"
                                        :model="$selectedRider"
                                        :buttonText="$selectedRider && $selectedRider->profile_pic ? 'Change Photo' : 'Upload Photo'"
                                        :disabled="($selectedRider && !$editingRider) || ($editingRider && $status == 0)"
                                    />
                                    
                                    <div wire:loading wire:target="photo">
                                        <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                            {{ __('Uploading...') }}
                                        </div>
                                    </div>
                                    
                                    @error('profile_pic')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <x-button type="button" wire:click="resetForm" secondary>
                            Cancel
                        </x-button>
                        <x-button type="submit" primary>
                            {{ $editingRider ? 'Update Rider' : 'Add Rider' }}
                        </x-button>
                    </div>
                </form>
            </div>
        @else
            <div class="bg-white dark:bg-light-gray shadow rounded-2xl px-6 py-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($riders as $rider)
                        <div
                            class="group bg-white hover:bg-gray-50 dark:bg-darker-gray dark:hover:bg-dark-gray rounded-xl transition-all duration-200 border border-gray-100 dark:border-gray-700">
                            <div class="p-6">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="min-w-0">
                                        <h3 class="font-orbitron text-xl text-gray-900 dark:text-white truncate">
                                            {{ $rider->firstname }} {{ $rider->lastname }}
                                        </h3>
                                        @if($rider->nickname)
                                            <div class="text-sm text-cartel-red dark:text-cartel-red font-medium mt-0.5 truncate">
                                                {{ $rider->nickname }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex items-center space-x-1 flex-shrink-0">
                                        <x-button wire:click="edit({{ $rider->id }})" icon="pencil" primary flat size="sm" />
                                        <x-button wire:click="confirmDelete({{ $rider->id }})" icon="trash" negative flat
                                            size="sm" />
                                    </div>
                                </div>

                                <div class="mt-4 space-y-2 text-sm">
                                    <div class="flex items-center justify-between">
                                        <div class="text-gray-500 dark:text-gray-400">Age</div>
                                        <div class="text-gray-900 dark:text-white font-medium">{{ $rider->date_of_birth->age }}
                                            years</div>
                                    </div>

                                    @if($rider->class)
                                        <div class="flex items-center justify-between">
                                            <div class="text-gray-500 dark:text-gray-400">Class</div>
                                            <div class="text-gray-900 dark:text-white font-medium text-right">
                                                {{ is_array($rider->class) ? implode(', ', $rider->class) : $rider->class }}
                                            </div>
                                        </div>
                                    @endif

                                    @if($rider->skill_level)
                                        <div class="flex items-center justify-between">
                                            <div class="text-gray-500 dark:text-gray-400">Skill</div>
                                            <div class="text-gray-900 dark:text-white font-medium">{{ $rider->skill_level }}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
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