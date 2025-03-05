<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Auth;

class TwoFactorChallenge extends Component
{
    public $code = '';
    public $error = '';

    public function mount()
    {
        if (!Auth::user()->two_factor_secret) {
            return redirect()->route('dashboard');
        }
    }

    public function verify()
    {
        $this->validate([
            'code' => 'required|string|size:6',
        ]);

        $valid = $this->validateCode($this->code);

        if ($valid) {
            session()->put('two_factor_verified', true);
            return redirect()->intended(config('fortify.home'));
        }

        $this->error = 'Invalid authentication code.';
    }

    protected function validateCode($code)
    {
        $google2fa = app('pragmarx.google2fa');
        return $google2fa->verifyKey(Auth::user()->two_factor_secret, $code);
    }

    public function render()
    {
        return view('livewire.auth.two-factor-challenge');
    }
} 