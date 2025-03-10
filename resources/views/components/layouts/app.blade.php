<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data :class="{ 'dark': $store.theme.dark }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    @php
        $seo = isset($component) && method_exists($component, 'getSEOData') 
            ? $component->getSEOData() 
            : new \RalphJSmit\Laravel\SEO\Support\SEOData(
                title: config('app.name', 'Speed Cartel BMX Racing'),
                description: 'Speed Cartel BMX Racing Team - Building champions and pushing the limits of BMX racing.',
                author: config('seo.default_author', 'Speed Cartel'),
                image: asset('images/default-og.jpg'),
                url: url()->current(),
                site_name: config('app.name', 'Speed Cartel BMX Racing'),
                type: 'website',
                locale: app()->getLocale(),
            );
    @endphp
    
    <title>{{ $seo->title }}</title>
    <meta name="description" content="{{ $seo->description }}">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="{{ $seo->type }}">
    <meta property="og:site_name" content="{{ $seo->site_name }}">
    <meta property="og:url" content="{{ $seo->url }}">
    <meta property="og:title" content="{{ $seo->title }}">
    <meta property="og:description" content="{{ $seo->description }}">
    <meta property="og:locale" content="{{ $seo->locale }}">
    @if($seo->image)
        <meta property="og:image" content="{{ $seo->image }}">
    @endif
    @if($seo->published_time)
        <meta property="article:published_time" content="{{ $seo->published_time->toIso8601String() }}">
    @endif
    @if($seo->modified_time)
        <meta property="article:modified_time" content="{{ $seo->modified_time->toIso8601String() }}">
    @endif
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ $seo->url }}">
    <meta name="twitter:title" content="{{ $seo->title }}">
    <meta name="twitter:description" content="{{ $seo->description }}">
    @if($seo->image)
        <meta name="twitter:image" content="{{ $seo->image }}">
    @endif
    
    @if($seo->author)
        <meta name="author" content="{{ $seo->author }}">
    @endif
    
    <!-- Canonical URL -->
    <link rel="canonical" href="{{ $seo->url }}" />

    <!-- Prevent flash of unstyled content in dark mode -->
    <script>
        // Apply dark mode immediately to prevent flash
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/theme.js'])
    @livewireStyles
    @stack('styles')
    @turnstileScripts()

</head>

<body class="min-h-screen bg-gray-50 dark:bg-darker-gray text-gray-900 dark:text-white transition-colors duration-200">
    <livewire:public.header />

    <main>
        {{ $slot }}
    </main>

    <livewire:public.footer />
    <x-dialog z-index="z-50" blur="md" />
    @wireUiScripts
    @livewireScripts
    @stack('scripts')
</body>

</html>