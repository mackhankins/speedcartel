<div>
    <h2 class="text-2xl font-bold text-center text-gray-900 dark:text-white font-orbitron">
        Create your account
    </h2>
    <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
        Join our community and start your journey
    </p>

    <div class="mt-8">
        <x-social-auth mode="register" />
    </div>

    <form wire:submit.prevent="register" class="mt-8 space-y-6">
        <x-input wire:model="name" label="Full name" placeholder="Enter your full name" required />

        <x-input wire:model="email" label="Email address" type="email" placeholder="you@example.com" required />

        <x-password wire:model="password" label="Password" placeholder="••••••••" required />

        <x-password wire:model="password_confirmation" label="Confirm password" placeholder="••••••••" required />

        <x-button type="submit" class="w-full" primary label="Create account" />
    </form>

    <!-- Login Link -->
    <div class="mt-6 text-center text-sm text-gray-600 dark:text-gray-400">
        Already have an account?
        <a href="{{ route('login') }}" class="font-medium text-red-600 hover:text-red-500">
            Sign in instead
        </a>
    </div>
</div>