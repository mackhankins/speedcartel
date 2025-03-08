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
    :options="collect($classOptions)->map(function($label, $value) {
        return ['name' => $label, 'value' => $value];
    })->values()->toArray()"
    option-label="name"
    option-value="value"
    :disabled="$selectedRider && $status === 0"
/>

<x-select
    wire:model="skill_level"
    label="Skill Level"
    :options="collect($skillLevelOptions)->map(function($label, $value) {
        return ['name' => $label, 'value' => $value];
    })->values()->toArray()"
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

<div>
    <x-select
        wire:model="home_track"
        label="Home Track"
        :options="$tracks"
        option-label="name"
        option-value="value"
        :disabled="$selectedRider && $status === 0"
    />
</div> 