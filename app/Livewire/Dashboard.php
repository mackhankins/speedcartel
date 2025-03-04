<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\User;

class Dashboard extends Component
{
    #[Title('Team Dashboard')]
    public function render()
    {
        $stats = [
            'total_users' => User::count(),
            'total_races' => 0, // Replace with actual race count when model is created
            'next_race' => null, // Replace with next race when model is created
        ];

        return view('livewire.dashboard', [
            'stats' => $stats
        ])->layout('components.layouts.dashboard');
    }
} 