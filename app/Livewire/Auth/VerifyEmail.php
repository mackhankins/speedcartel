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
            
            // Log some debug information
            Log::info('Attempting to send verification email', [
                'user_id' => $user->id,
                'email' => $user->email,
                'app_url' => config('app.url')
            ]);
            
            // Directly create and send our custom notification
            \Illuminate\Support\Facades\Notification::send($user, new \App\Notifications\CustomVerifyEmail());
            
            $this->buttonState = 'sent';
            Log::info('Verification email sent successfully');
        } catch (\Exception $e) {
            Log::error('Failed to send verification email', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $this->buttonState = 'default';
            $this->errorMessage = $e->getMessage();
        }
    }

    public function render()
    {
        return view('livewire.auth.verify-email');
    }
} 