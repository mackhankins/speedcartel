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
use App\Livewire\Public\Team;
use App\Livewire\Public\Races;
use App\Livewire\Public\RiderProfile;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Notifications\CustomVerifyEmail;
use Laravel\Fortify\Fortify;
use App\Livewire\TeamProfile;
use App\Livewire\Public\Sponsors;
use App\Livewire\Public\Contact;

// Public Routes
Route::get('/', Home::class)->name('home');
Route::get('/team', Team::class)->name('team');
Route::get('/team/rider/{rider}', RiderProfile::class)->name('team.rider');
Route::get('/races', Races::class)->name('races');
Route::get('/sponsors', Sponsors::class)->name('sponsors');
Route::get('/contact', Contact::class)->name('contact');
Route::get('/cart', Cart::class)->name('cart');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('login', Login::class)->name('login');
    Route::get('register', Register::class)->name('register');
    
    // Password Reset Routes
    Route::get('forgot-password', ForgotPassword::class)->name('password.request');
    Route::get('reset-password/{token}', ResetPassword::class)->name('password.reset');
    
    // Social Login Routes
    Route::get('login/{provider}', [SocialiteController::class, 'redirect'])->name('login.social');
    Route::get('login/{provider}/callback', [SocialiteController::class, 'callback'])->name('login.social.callback');
});

// Two-Factor Authentication Routes
Route::get('/two-factor-challenge', function () {
    return view('auth.two-factor-challenge');
})->middleware(['auth'])->name('two-factor.challenge');

Route::post('/two-factor-challenge', function (Request $request) {
    $request->validate([
        'code' => 'required|string|size:6',
    ]);

    $user = $request->user();
    
    if (!$user->two_factor_secret || !$user->two_factor_confirmed_at) {
        return back()->withErrors(['code' => 'Two-factor authentication is not enabled.']);
    }

    $google2fa = new \PragmaRX\Google2FA\Google2FA();
    $secret = decrypt($user->two_factor_secret);
    $valid = $google2fa->verifyKey($secret, $request->code);

    if (!$valid) {
        return back()->withErrors(['code' => 'Invalid authentication code.']);
    }

    $user->two_factor_confirmed_at = now();
    $user->save();
    
    return redirect()->intended(config('fortify.home'));
})->middleware(['auth'])->name('two-factor.verify');

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
    Route::get('/dashboard/riders', App\Livewire\Dashboard\Riders::class)->name('riders');
    Route::get('/dashboard/settings', \App\Livewire\Dashboard\Settings::class)->name('settings');
    
    Route::get('2fa', function() {
        return redirect()->route('dashboard');
    })->name('2fa');
});

// Email Verification Routes
Route::get('/email/verify', \App\Livewire\Auth\VerifyEmail::class)
    ->middleware(['auth'])
    ->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Custom route for Livewire to resend verification emails
Route::post('/email/resend-verification', function (Request $request) {
    $user = $request->user();
    $user->notify(new \App\Notifications\CustomVerifyEmail());
    return response()->json(['success' => true]);
})->middleware(['auth', 'throttle:6,1'])->name('verification.resend');
