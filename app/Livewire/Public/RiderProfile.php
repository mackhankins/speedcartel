<?php

namespace App\Livewire\Public;

use Livewire\Component;
use App\Models\Rider;
use Livewire\Attributes\Title;

#[Title('Rider Profile')]
class RiderProfile extends Component
{
    public Rider $rider;

    public function mount(Rider $rider)
    {
        $this->rider = $rider;
    }

    public function render()
    {
        return view('livewire.rider-profile', [
            'skillLevelOptions' => Rider::$skillLevelOptions,
            'classOptions' => Rider::$classOptions,
        ])->layout('components.layouts.app');
    }
} 