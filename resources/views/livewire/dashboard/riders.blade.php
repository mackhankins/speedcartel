<div>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white font-orbitron">Riders</h1>
            @unless($showForm)
            <button type="button" wire:click="create"
                class="flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                Add New Rider
            </button>
            @endunless
        </div>

        @if($showForm)
        <div class="bg-white dark:bg-light-gray shadow rounded-2xl px-6 py-8 mb-8">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white font-orbitron mb-6">
                {{ $editingRider ? 'Edit Rider' : 'Add New Rider' }}
            </h2>

            <form wire:submit="save" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">First Name</label>
                        <div class="mt-1">
                            <input type="text" wire:model="first_name" id="first_name" 
                                class="block w-full px-4 py-3 bg-white dark:bg-light-gray border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-red-400/30 dark:focus:ring-red-500/50 focus:border-red-400 dark:focus:border-red-500 focus:outline-none rounded-xl shadow-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500">
                            @error('first_name') <span class="mt-2 text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Last Name</label>
                        <div class="mt-1">
                            <input type="text" wire:model="last_name" id="last_name" 
                                class="block w-full px-4 py-3 bg-white dark:bg-light-gray border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-red-400/30 dark:focus:ring-red-500/50 focus:border-red-400 dark:focus:border-red-500 focus:outline-none rounded-xl shadow-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500">
                            @error('last_name') <span class="mt-2 text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="nickname" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nickname</label>
                        <div class="mt-1">
                            <input type="text" wire:model="nickname" id="nickname" 
                                class="block w-full px-4 py-3 bg-white dark:bg-light-gray border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-red-400/30 dark:focus:ring-red-500/50 focus:border-red-400 dark:focus:border-red-500 focus:outline-none rounded-xl shadow-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500">
                            @error('nickname') <span class="mt-2 text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="birthdate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Birthdate</label>
                        <div class="mt-1">
                            <input type="date" wire:model="birthdate" id="birthdate" 
                                class="block w-full px-4 py-3 bg-white dark:bg-light-gray border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-red-400/30 dark:focus:ring-red-500/50 focus:border-red-400 dark:focus:border-red-500 focus:outline-none rounded-xl shadow-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500">
                            @error('birthdate') <span class="mt-2 text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="class" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Class</label>
                        <div class="mt-1">
                            <input type="text" wire:model="class" id="class" 
                                class="block w-full px-4 py-3 bg-white dark:bg-light-gray border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-red-400/30 dark:focus:ring-red-500/50 focus:border-red-400 dark:focus:border-red-500 focus:outline-none rounded-xl shadow-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500">
                            @error('class') <span class="mt-2 text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="skill" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Skill Level</label>
                        <div class="mt-1">
                            <input type="text" wire:model="skill" id="skill" 
                                class="block w-full px-4 py-3 bg-white dark:bg-light-gray border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-red-400/30 dark:focus:ring-red-500/50 focus:border-red-400 dark:focus:border-red-500 focus:outline-none rounded-xl shadow-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500">
                            @error('skill') <span class="mt-2 text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="profile_pic" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Profile Picture URL</label>
                        <div class="mt-1">
                            <input type="text" wire:model="profile_pic" id="profile_pic" 
                                class="block w-full px-4 py-3 bg-white dark:bg-light-gray border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-red-400/30 dark:focus:ring-red-500/50 focus:border-red-400 dark:focus:border-red-500 focus:outline-none rounded-xl shadow-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500">
                            @error('profile_pic') <span class="mt-2 text-sm text-red-600">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" wire:click="resetForm" 
                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-dark-gray hover:bg-gray-50 dark:hover:bg-light-gray focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Cancel
                    </button>
                    <button type="submit" 
                        class="px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        {{ $editingRider ? 'Update Rider' : 'Add Rider' }}
                    </button>
                </div>
            </form>
        </div>
        @endif

        <div class="bg-white dark:bg-light-gray shadow rounded-2xl px-6 py-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($riders as $rider)
                <div class="bg-gray-50 dark:bg-dark-gray rounded-xl p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex-grow">
                            <div class="flex items-center">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                    {{ $rider->first_name }} {{ $rider->last_name }}
                                    @if($rider->nickname)
                                        <span class="text-gray-500 dark:text-gray-400">({{ $rider->nickname }})</span>
                                    @endif
                                </h3>
                            </div>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ $rider->birthdate->age }} years old
                            </p>
                            @if($rider->class || $rider->skill)
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ $rider->class }} • {{ $rider->skill }}
                            </p>
                            @endif
                        </div>
                        <div class="ml-4 flex-shrink-0 flex space-x-2">
                            <button type="button" wire:click="edit({{ $rider->id }})" 
                                class="text-sm text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-500">
                                Edit
                            </button>
                            <button type="button" wire:click="delete({{ $rider->id }})" 
                                class="text-sm text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-500">
                                Delete
                            </button>
                        </div>
                    </div>

                    @if($rider->profile_pic)
                    <div class="mt-4">
                        <img src="{{ $rider->profile_pic }}" alt="{{ $rider->first_name }}'s profile picture" 
                            class="w-24 h-24 rounded-full object-cover">
                    </div>
                    @endif
                </div>
                @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-500 dark:text-gray-400">No riders added yet.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div> 