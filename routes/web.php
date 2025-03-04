<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Public\Home;
use App\Livewire\Dashboard;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\ResetPassword;
use App\Http\Controllers\SocialiteController;
use App\Livewire\Auth\TwoFactorAuth;
use App\Livewire\Auth\Profile;
use App\Livewire\Public\Cart;

// Public Routes
Route::get('/', Home::class)->name('home');
Route::get('/team', function() { return view('coming-soon'); })->name('team');
Route::get('/races', function() { return view('coming-soon'); })->name('races');
Route::get('/sponsors', function() { return view('coming-soon'); })->name('sponsors');
Route::get('/contact', function() { return view('coming-soon'); })->name('contact');
Route::get('/cart', Cart::class)->name('cart');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('login', Login::class)->name('login');
    Route::get('register', Register::class)->name('register');
    
    // Password Reset Routes
    Route::get('forgot-password', ForgotPassword::class)->name('password.request');
    Route::get('reset-password/{token}', ResetPassword::class)->name('password.reset');
    
    // Social Login Routes (temporary redirects)
    Route::get('login/google', function() {
        return redirect()->route('login');
    })->name('login.google');
    
    Route::get('login/facebook', function() {
        return redirect()->route('login');
    })->name('login.facebook');
});

Route::post('/logout', function() {
    Auth::logout();
    return redirect()->to('/');
})->name('logout');

// Protected Routes
Route::middleware([
    'auth',
    'verified',
])->group(function () {
    // Dashboard Routes
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/dashboard/profile', \App\Livewire\Dashboard\Profile::class)->name('profile');
    
    Route::get('/settings', function() {
        return redirect()->route('dashboard');
    })->name('settings');
    Route::get('2fa', function() {
        return redirect()->route('dashboard');
    })->name('2fa');
});
