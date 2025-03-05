<x-layouts.auth>
    <div>
        <h2 class="text-2xl font-bold text-center text-gray-900 dark:text-white font-orbitron">
            Two-Factor Authentication
        </h2>
            
        <form method="POST" action="{{ route('two-factor.verify') }}" class="mt-8 space-y-6">
            @csrf
                
            <div>
                <label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Authentication Code
                </label>
                <div class="mt-1">
                    <input id="code" type="text" name="code" required 
                        class="block w-full px-4 py-3 bg-white dark:bg-light-gray border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-red-400/30 dark:focus:ring-red-500/50 focus:border-red-400 dark:focus:border-red-500 focus:outline-none rounded-xl shadow-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500"
                        placeholder="Enter 6-digit code"
                        autofocus>
                </div>
                @error('code')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <button type="submit" 
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Verify
                </button>
            </div>
        </form>
    </div>
</x-layouts.auth> 