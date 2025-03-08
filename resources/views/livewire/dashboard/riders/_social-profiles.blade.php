<div class="mt-8" x-data="{ socialOpen: false }">
    <button 
        type="button" 
        @click="socialOpen = !socialOpen" 
        class="flex items-center justify-between w-full px-4 py-3 bg-gray-100 dark:bg-gray-700 rounded-lg text-left focus:outline-none focus:ring-2 focus:ring-primary-500"
    >
        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Social Profiles</h3>
        <svg 
            class="w-5 h-5 text-gray-500 dark:text-gray-400 transition-transform duration-200" 
            :class="{'rotate-180': socialOpen}"
            fill="none" 
            stroke="currentColor" 
            viewBox="0 0 24 24"
        >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>
    
    <div x-show="socialOpen" x-collapse x-cloak>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
            <x-input 
                wire:model="social_profiles.instagram" 
                label="Instagram Username"
                placeholder="@username"
                :disabled="$selectedRider && $status === 0"
            />
            
            <x-input 
                wire:model="social_profiles.facebook" 
                label="Facebook Profile"
                placeholder="username or profile URL"
                :disabled="$selectedRider && $status === 0"
            />
            
            <x-input 
                wire:model="social_profiles.twitter" 
                label="Twitter/X Username"
                placeholder="@username"
                :disabled="$selectedRider && $status === 0"
            />
            
            <x-input 
                wire:model="social_profiles.tiktok" 
                label="TikTok Username"
                placeholder="@username"
                :disabled="$selectedRider && $status === 0"
            />
            
            <x-input 
                wire:model="social_profiles.youtube" 
                label="YouTube Channel"
                placeholder="Channel name or URL"
                :disabled="$selectedRider && $status === 0"
            />
        </div>
    </div>
</div> 