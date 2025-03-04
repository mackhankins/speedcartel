<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Laravel\Fortify\Actions\GenerateNewRecoveryCodes;

class TwoFactorAuth extends Component
{
    public $qrCode;
    public $recoveryCodes;
    public $code;
    public $showRecoveryCodes = false;
    public $showQrCode = false;
    public $showConfirmForm = false;
    public $confirmed = false;

    public function mount()
    {
        if (!Auth::user()->two_factor_secret) {
            $this->showQrCode = true;
            $this->enableTwoFactor();
        } else {
            $this->showConfirmForm = true;
        }
    }

    public function enableTwoFactor()
    {
        $action = new EnableTwoFactorAuthentication();
        $action(Auth::user());
        $this->qrCode = Auth::user()->twoFactorQrCodeSvg();
        $this->recoveryCodes = Auth::user()->recoveryCodes();
    }

    public function confirmTwoFactor()
    {
        $action = new ConfirmTwoFactorAuthentication();
        $action(Auth::user(), $this->code);
        $this->confirmed = true;
        $this->showConfirmForm = false;
        $this->showRecoveryCodes = true;
    }

    public function disableTwoFactor()
    {
        $action = new DisableTwoFactorAuthentication();
        $action(Auth::user());
        $this->showQrCode = true;
        $this->showConfirmForm = false;
        $this->confirmed = false;
        $this->enableTwoFactor();
    }

    public function generateNewRecoveryCodes()
    {
        $action = new GenerateNewRecoveryCodes();
        $action(Auth::user());
        $this->recoveryCodes = Auth::user()->recoveryCodes();
    }

    public function render()
    {
        return view('livewire.auth.two-factor-auth');
    }
} 