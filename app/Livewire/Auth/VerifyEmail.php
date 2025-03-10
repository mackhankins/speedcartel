<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use App\Notifications\CustomVerifyEmail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

#[Layout('components.layouts.auth')]
class VerifyEmail extends Component
{
    public $buttonState = 'default'; // default, sending, sent
    public $errorMessage = null;

    public function resendVerificationEmail()
    {
        $this->buttonState = 'sending';
        $this->errorMessage = null;
        
        try {
            $user = Auth::user();
            
            // Directly create and send our custom notification
            \Illuminate\Support\Facades\Notification::send($user, new \App\Notifications\CustomVerifyEmail());
            
            $this->buttonState = 'sent';
        } catch (\Exception $e) {
            $this->buttonState = 'default';
            $this->errorMessage = $e->getMessage();
        }
    }

    public function render()
    {
        return view('livewire.auth.verify-email');
    }
} 