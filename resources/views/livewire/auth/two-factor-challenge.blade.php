<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
        <h2 class="text-2xl font-bold text-center text-gray-900 dark:text-white mb-6">Two-Factor Authentication</h2>

        <form wire:submit="verify">
            @csrf

            <div class="mt-4">
                <x-input wire:model="code" label="Authentication Code" placeholder="Enter your code" required
                    autofocus />
            </div>

            @if($error)
                <div class="mt-2 text-red-500 text-sm">{{ $error }}</div>
            @endif

            <div class="flex items-center justify-end mt-4">
                <x-button type="submit" primary label="Verify" />
            </div>
        </form>
    </div>
</div>