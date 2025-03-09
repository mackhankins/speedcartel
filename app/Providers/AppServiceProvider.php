<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if(config('app.env') !== 'local') {
            URL::forceScheme('https');
        }

        // For local development with HTTPS
        if(request()->isSecure()) {
            URL::forceScheme('https');
        }

        // Use Tailwind CSS for pagination
        Paginator::useTailwind();
        
        // Override the default VerifyEmail notification
        $this->app->bind(\Illuminate\Auth\Notifications\VerifyEmail::class, \App\Notifications\CustomVerifyEmail::class);
    }
}
