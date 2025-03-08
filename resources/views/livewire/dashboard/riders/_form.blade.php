<div class="bg-white dark:bg-darker-gray shadow rounded-2xl px-6 py-8 mb-8">
    <h2 class="text-xl font-bold text-gray-900 dark:text-white font-orbitron mb-6">
        {{ $editingRider ? 'Edit Rider' : 'Add New Rider' }}
    </h2>

    <form wire:submit="save" class="space-y-6">
        <!-- Profile Photo Section at the Top -->
        @include('livewire.dashboard.riders._profile-photo')

        <div class="grid grid-cols-1 gap-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @include('livewire.dashboard.riders._basic-info')
            </div>
        </div>

        @include('livewire.dashboard.riders._plates')
        @include('livewire.dashboard.riders._social-profiles')

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