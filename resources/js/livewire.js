// Initialize Alpine if it hasn't been initialized yet
if (window.Alpine && !window.Alpine.$data) {
    window.Alpine.start();
}

// Initialize Livewire if it hasn't been initialized yet
if (window.Livewire && !window.Livewire.$data) {
    // Check if Filament's Livewire is already initialized
    if (!window.Livewire.$data && !window.Filament) {
        window.Livewire.start();
    }
} 