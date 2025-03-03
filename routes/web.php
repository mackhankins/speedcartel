<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Public\Home;

// Public Routes
Route::get('/', Home::class)->name('home');
Route::get('/team', function() { return view('coming-soon'); })->name('team');
Route::get('/races', function() { return view('coming-soon'); })->name('races');
Route::get('/sponsors', function() { return view('coming-soon'); })->name('sponsors');
Route::get('/contact', function() { return view('coming-soon'); })->name('contact');

// Auth Routes
Route::get('/login', function() {
    return redirect()->to('/app/login');
})->name('login');

Route::get('/register', function() {
    return redirect()->to('/app/register');
})->name('register');

Route::post('/logout', function() {
    auth()->logout();
    return redirect()->to('/');
})->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function() {
        return redirect()->to('/admin');
    })->name('dashboard');

    Route::get('/profile', function() {
        return redirect()->to('/admin/profile');
    })->name('profile');

    Route::get('/settings', function() {
        return redirect()->to('/admin/settings');
    })->name('settings');
});
