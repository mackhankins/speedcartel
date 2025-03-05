<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
        <h2 class="text-2xl font-bold text-center text-gray-900 dark:text-white mb-6">Two-Factor Authentication</h2>
        
        <form wire:submit="verify">
            @csrf
            
            <div class="mt-4">
                <label for="code" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Authentication Code</label>
                <input id="code" type="text" wire:model="code" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required autofocus>
                @error('code') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            @if($error)
                <div class="mt-2 text-red-500 text-sm">{{ $error }}</div>
            @endif

            <div class="flex items-center justify-end mt-4">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Verify
                </button>
            </div>
        </form>
    </div>
</div> 