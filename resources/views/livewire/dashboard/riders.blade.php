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
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input wire:model="first_name" id="first_name" type="text" label="First Name" />
                            <x-error for="first_name" class="mt-2" />
                        </div>

                        <div>
                            <x-input wire:model="last_name" id="last_name" type="text" label="Last Name" />
                            <x-error for="last_name" class="mt-2" />
                        </div>

                        <div>
                            <x-input wire:model="nickname" id="nickname" type="text" label="Nickname" />
                            <x-error for="nickname" class="mt-2" />
                        </div>

                        <div>
                            <x-input wire:model="birthdate" id="birthdate" type="date" label="Birthdate" />
                            <x-error for="birthdate" class="mt-2" />
                        </div>

                        <div>
                            <x-select wire:model="class" id="class" label="Class" placeholder="Select classes" multiselect>
                                <x-select.option label="Class" value="class" />
                                <x-select.option label="Cruiser" value="cruiser" />
                            </x-select>
                            <x-error for="class" class="mt-2" />
                        </div>

                        <div>
                            <x-select wire:model="skill" id="skill" label="Skill Level" placeholder="Select skill level">
                                <x-select.option label="Beginner" value="beginner" />
                                <x-select.option label="Intermediate" value="intermediate" />
                                <x-select.option label="Expert" value="expert" />
                                <x-select.option label="Pro" value="pro" />
                            </x-select>
                            <x-error for="skill" class="mt-2" />
                        </div>

                        <div>
                            <x-label for="profile_pic" value="Profile Picture" />
                            <div class="mt-1">
                                <div x-data="{
                                    photoPreview: @if($temp_profile_pic) '{{ Storage::url($temp_profile_pic) }}' @else null @endif,
                                    isDragging: false,
                                    handleFiles(event) {
                                        const file = event.target.files[0] || event.dataTransfer.files[0];
                                        if (!file) return;

                                        if (!file.type.startsWith('image/')) {
                                            $wire.dispatch('notify', { message: 'Please upload an image file.', type: 'error' });
                                            return;
                                        }

                                        this.photoPreview = URL.createObjectURL(file);
                                        $wire.upload('profile_pic', file);
                                    }
                                }"
                                @profile-pic-reset.window="photoPreview = null"
                                >
                                    <div class="relative">
                                        <input
                                            type="file"
                                            class="sr-only"
                                            wire:model="profile_pic"
                                            x-ref="photo"
                                            @change="handleFiles($event)"
                                            accept="image/*"
                                        >

                                        <div
                                            @click="$refs.photo.click()"
                                            @dragover.prevent="isDragging = true"
                                            @dragleave.prevent="isDragging = false"
                                            @drop.prevent="isDragging = false; handleFiles($event)"
                                            :class="{ 'border-cartel-red bg-red-50 dark:bg-red-900/20': isDragging }"
                                            class="relative flex justify-center px-6 py-10 cursor-pointer border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-xl hover:border-cartel-red dark:hover:border-cartel-red transition-colors duration-200"
                                        >
                                            <template x-if="!photoPreview">
                                                <div class="text-center">
                                                    <x-icon name="camera" class="mx-auto h-12 w-12 text-gray-400" />
                                                    <div class="mt-4 flex text-sm text-gray-600 dark:text-gray-400">
                                                        <span class="relative cursor-pointer rounded-md font-medium text-cartel-red hover:text-red-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-cartel-red">
                                                            Upload a file
                                                        </span>
                                                        <p class="pl-1">or drag and drop</p>
                                                    </div>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                                        PNG, JPG, GIF up to 5MB
                                                    </p>
                                                </div>
                                            </template>

                                            <template x-if="photoPreview">
                                                <div class="absolute inset-0 flex items-center justify-center rounded-xl overflow-hidden">
                                                    <img :src="photoPreview" class="max-h-full" />
                                                    <button
                                                        @click.stop="photoPreview = null; $wire.set('profile_pic', null); $wire.set('temp_profile_pic', null)"
                                                        type="button"
                                                        class="absolute top-2 right-2 p-1 rounded-full bg-red-600 text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                                    >
                                                        <x-icon name="x-mark" class="w-4 h-4" />
                                                    </button>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                                <x-error for="profile_pic" class="mt-2" />
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
                                            {{ $rider->first_name }} {{ $rider->last_name }}
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
                                        <div class="text-gray-900 dark:text-white font-medium">{{ $rider->birthdate->age }}
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

                                    @if($rider->skill)
                                        <div class="flex items-center justify-between">
                                            <div class="text-gray-500 dark:text-gray-400">Skill</div>
                                            <div class="text-gray-900 dark:text-white font-medium">{{ $rider->skill }}</div>
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
</div>
