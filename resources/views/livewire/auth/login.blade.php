<div>
    <h2 class="text-2xl font-bold text-center text-gray-900 dark:text-white font-orbitron">
        Sign in to your account
    </h2>

    <div class="mt-8">
        <x-social-auth mode="login" />
    </div>

    <form wire:submit.prevent="login" class="mt-8 space-y-6">
        <x-input wire:model="email" label="Email address" type="email" placeholder="you@example.com" required />

        <x-password wire:model="password" label="Password" placeholder="••••••••" required />

        <div class="flex items-center justify-between">
            <x-checkbox wire:model="remember" label="Remember me" />

            <div class="text-sm">
                <a href="{{ route('password.request') }}" class="font-medium text-red-600 hover:text-red-500">
                    Forgot your password?
                </a>
            </div>
        </div>

        <x-button type="submit" class="w-full" primary label="Sign in" />
    </form>

    <div class="mt-6 text-center text-sm text-gray-600 dark:text-gray-400">
        Don't have an account?
        <a href="{{ route('register') }}" class="font-medium text-red-600 hover:text-red-500">
            Create one now
        </a>
    </div>
</div>