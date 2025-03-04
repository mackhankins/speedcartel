@props(['size' => 'md'])

@php
$sizes = [
    'sm' => [
        'container' => 'w-8 h-8',
        'text' => 'text-lg',
        'brand' => 'text-lg',
    ],
    'md' => [
        'container' => 'w-10 h-10',
        'text' => 'text-xl',
        'brand' => 'text-xl',
    ],
    'lg' => [
        'container' => 'w-12 h-12',
        'text' => 'text-2xl',
        'brand' => 'text-2xl',
    ],
];
@endphp

<div {{ $attributes->merge(['class' => 'flex items-center']) }}>
    <div class="flex-shrink-0 flex items-center">
        <div class="{{ $sizes[$size]['container'] }} bg-cartel-red rounded-full flex items-center justify-center font-orbitron font-bold {{ $sizes[$size]['text'] }} text-white">
            SC
        </div>
        <span class="ml-3 font-orbitron font-bold {{ $sizes[$size]['brand'] }} text-gray-900 dark:text-white">
            SPEED<span class="text-cartel-red">CARTEL</span>
        </span>
    </div>
</div> 