<div>
    <h2 class="text-2xl font-bold text-center text-gray-900 dark:text-white font-orbitron">
        Sign in to your account
    </h2>

    <div class="mt-8">
        <x-social-auth mode="login" />
    </div>

    <form wire:submit.prevent="login" class="mt-8 space-y-6">
        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Email address
            </label>
            <div class="mt-1">
                <input wire:model="email" id="email" type="email" required 
                    class="block w-full px-4 py-3 bg-white dark:bg-light-gray border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-red-400/30 dark:focus:ring-red-500/50 focus:border-red-400 dark:focus:border-red-500 focus:outline-none rounded-xl shadow-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500"
                    placeholder="you@example.com">
            </div>
            @error('email')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Password
            </label>
            <div class="mt-1">
                <input wire:model="password" id="password" type="password" required 
                    class="block w-full px-4 py-3 bg-white dark:bg-light-gray border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-red-400/30 dark:focus:ring-red-500/50 focus:border-red-400 dark:focus:border-red-500 focus:outline-none rounded-xl shadow-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500"
                    placeholder="••••••••">
            </div>
            @error('password')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me and Forgot Password -->
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <input wire:model="remember" id="remember" type="checkbox" 
                    class="h-4 w-4 border-gray-300 dark:border-gray-600 text-red-400 dark:text-red-600 focus:ring-2 focus:ring-red-400/30 dark:focus:ring-red-500/50 focus:outline-none rounded">
                <label for="remember" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                    Remember me
                </label>
            </div>

            <div class="text-sm">
                <a href="{{ route('password.request') }}" class="font-medium text-red-600 hover:text-red-500">
                    Forgot your password?
                </a>
            </div>
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit" 
                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                Sign in
            </button>
        </div>
    </form>

    <!-- Register Link -->
    <div class="mt-6 text-center text-sm text-gray-600 dark:text-gray-400">
        Don't have an account? 
        <a href="{{ route('register') }}" class="font-medium text-red-600 hover:text-red-500">
            Create one now
        </a>
    </div>
</div> 