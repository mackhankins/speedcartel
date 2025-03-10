<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SocialiteController extends Controller
{
    public function redirect($provider)
    {
        // Validate provider
        if (!in_array($provider, ['google', 'facebook'])) {
            return redirect()->route('login')
                ->with('error', 'Invalid social provider.');
        }
        
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        try {
            // Validate provider
            if (!in_array($provider, ['google', 'facebook'])) {
                return redirect()->route('login')
                    ->with('error', 'Invalid social provider.');
            }
            
            $socialiteUser = Socialite::driver($provider)->user();
            
            // First try to find user by provider ID
            $user = User::where($provider.'_id', $socialiteUser->getId())->first();
            
            // If not found by provider ID, try to find by email
            if (!$user && $socialiteUser->getEmail()) {
                $user = User::where('email', $socialiteUser->getEmail())->first();
                
                // If user exists, update their provider ID
                if ($user) {
                    $user->update([
                        $provider.'_id' => $socialiteUser->getId()
                    ]);
                }
            }

            // If user still not found, create a new one
            if (!$user) {
                $user = User::create([
                    'name' => $socialiteUser->getName(),
                    'email' => $socialiteUser->getEmail(),
                    $provider.'_id' => $socialiteUser->getId(),
                    'password' => Hash::make(Str::random(24)),
                    'email_verified_at' => now(),
                ]);
            }

            Auth::login($user);

            return redirect()->intended(config('fortify.home', '/dashboard'));
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Something went wrong with ' . $provider . ' login: ' . $e->getMessage());
        }
    }
} 