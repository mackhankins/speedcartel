<div class="flex justify-center mb-6">
    <div class="w-full max-w-sm">
        <div class="flex flex-col items-center">
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
                <div class="mt-2 text-sm text-gray-500 dark:text-gray-400 text-center">
                    {{ __('Uploading...') }}
                </div>
            </div>
            
            @error('profile_pic')
                <p class="mt-1 text-sm text-red-600 dark:text-red-500 text-center">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div> 