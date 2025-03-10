<?php

namespace App\Livewire\Public;

use Livewire\Component;
use Livewire\Attributes\Rule;
use RyanChandler\LaravelCloudflareTurnstile\Rules\Turnstile;
use Illuminate\Support\Facades\Mail;
use App\Traits\WithSEO;
use WireUi\Traits\WireUiActions;

class Contact extends Component
{
    use WithSEO, WireUiActions;

    #[Rule('required|min:2|max:100')]
    public $name = '';

    #[Rule('required|email|max:100')]
    public $email = '';

    #[Rule('required|min:5|max:100')]
    public $subject = '';

    #[Rule('required|min:10|max:2000')]
    public $message = '';

    public $turnstile = '';
    
    // Honeypot field - should remain empty
    public $website = '';

    protected function getSEOTitle(): string
    {
        return 'Contact Us - Speed Cartel BMX Racing';
    }

    protected function getSEODescription(): ?string
    {
        return 'Get in touch with Speed Cartel BMX Racing Team. We\'d love to hear from you!';
    }

    public function submit()
    {
        // Check if honeypot field is filled (bot detected)
        if (!empty($this->website)) {
            // Silently return success but don't process the form
            $this->reset(['name', 'email', 'subject', 'message', 'turnstile', 'website']);
            
            $this->notification()->success(
                'Message Sent!',
                'Thank you for contacting us. We\'ll get back to you soon.'
            );
            
            return;
        }
        
        $this->validate([
            'name' => 'required|min:2|max:100',
            'email' => 'required|email|max:100',
            'subject' => 'required|min:5|max:100',
            'message' => 'required|min:10|max:2000',
            'turnstile' => ['required', app(Turnstile::class)],
        ]);

        // Send email
        try {
            // In a real application, you would send an email here
            // Mail::to('contact@speedcartel.com')->send(new \App\Mail\ContactForm($this->name, $this->email, $this->subject, $this->message));
            
            // For now, we'll just simulate a successful email send
            $this->reset(['name', 'email', 'subject', 'message', 'turnstile', 'website']);
            
            $this->notification()->success(
                'Message Sent!',
                'Thank you for contacting us. We\'ll get back to you soon.'
            );
        } catch (\Exception $e) {
            $this->notification()->error(
                'Error',
                'There was a problem sending your message. Please try again later.'
            );
        }
    }

    public function render()
    {
        return view('livewire.public.contact')
            ->layout('components.layouts.app', [
                'title' => 'Contact Us',
                'description' => 'Get in touch with Speed Cartel BMX Racing Team. We\'d love to hear from you!',
                'component' => $this
            ]);
    }
}
