<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;

class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    public function login()
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::clear($this->throttleKey());
            session()->regenerate();

            $user = Auth::user();
            
            if ($user->two_factor_secret) {
                return redirect()->route('two-factor.challenge');
            }
            
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        RateLimiter::hit($this->throttleKey());

        $this->addError('email', trans('auth.failed'));
    }

    public function ensureIsNotRateLimited()
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new \Illuminate\Auth\Events\Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    public function throttleKey()
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }

    /**
     * Redirect to Google OAuth
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToGoogle()
    {
        return redirect()->route('login.social', ['provider' => 'google']);
    }

    /**
     * Redirect to Facebook OAuth
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToFacebook()
    {
        return redirect()->route('login.social', ['provider' => 'facebook']);
    }

    public function render()
    {
        return view('livewire.auth.login')
            ->layout('components.layouts.auth');
    }
} 