<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Laravel\Fortify\Actions\GenerateNewRecoveryCodesAction;

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
        if (!auth()->user()->two_factor_secret) {
            $this->showQrCode = true;
            $this->enableTwoFactor();
        } else {
            $this->showConfirmForm = true;
        }
    }

    public function enableTwoFactor()
    {
        $action = new EnableTwoFactorAuthentication;
        $action(auth()->user());
        $this->qrCode = auth()->user()->twoFactorQrCodeSvg();
        $this->recoveryCodes = auth()->user()->recoveryCodes();
    }

    public function confirmTwoFactor()
    {
        $action = new ConfirmTwoFactorAuthentication;
        $action(auth()->user(), $this->code);
        $this->confirmed = true;
        $this->showConfirmForm = false;
        $this->showRecoveryCodes = true;
    }

    public function disableTwoFactor()
    {
        $action = new DisableTwoFactorAuthentication;
        $action(auth()->user());
        $this->showQrCode = true;
        $this->showConfirmForm = false;
        $this->confirmed = false;
        $this->enableTwoFactor();
    }

    public function generateNewRecoveryCodes()
    {
        $action = new GenerateNewRecoveryCodesAction;
        $action(auth()->user());
        $this->recoveryCodes = auth()->user()->recoveryCodes();
    }

    public function render()
    {
        return view('livewire.auth.two-factor-auth');
    }
}

<div class="min-h-screen flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900 dark:text-white">
                Two-Factor Authentication
            </h2>
        </div>

        @if ($showQrCode)
            <div class="bg-white dark:bg-light-gray shadow rounded-lg p-6">
                <div class="text-center">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Set up Two-Factor Authentication</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        Scan this QR code with your authenticator app to set up two-factor authentication.
                    </p>
                    <div class="flex justify-center mb-4">
                        {!! $qrCode !!}
                    </div>
                    <button wire:click="$set('showConfirmForm', true)" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        I've scanned the QR code
                    </button>
                </div>
            </div>
        @endif

        @if ($showConfirmForm)
            <div class="bg-white dark:bg-light-gray shadow rounded-lg p-6">
                <div class="text-center">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Confirm Two-Factor Authentication</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        Enter the code from your authenticator app to confirm two-factor authentication.
                    </p>
                    <div class="mt-4">
                        <input type="text" wire:model="code" class="appearance-none block w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm dark:bg-dark-gray dark:text-white" placeholder="Enter 6-digit code">
                    </div>
                    <button wire:click="confirmTwoFactor" class="mt-4 w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Confirm
                    </button>
                </div>
            </div>
        @endif

        @if ($showRecoveryCodes)
            <div class="bg-white dark:bg-light-gray shadow rounded-lg p-6">
                <div class="text-center">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Recovery Codes</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        Store these recovery codes in a secure place. You can use them to access your account if you lose your authenticator device.
                    </p>
                    <div class="bg-gray-50 dark:bg-dark-gray p-4 rounded-md mb-4">
                        <div class="grid grid-cols-2 gap-2">
                            @foreach ($recoveryCodes as $code)
                                <div class="font-mono text-sm text-gray-900 dark:text-white">{{ $code }}</div>
                            @endforeach
                        </div>
                    </div>
                    <div class="flex justify-center space-x-4">
                        <button wire:click="generateNewRecoveryCodes" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Generate New Codes
                        </button>
                        <button wire:click="disableTwoFactor" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Disable 2FA
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div> 