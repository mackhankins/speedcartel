<?php

namespace App\Livewire\Public;

use Livewire\Component;
use App\Models\Rider;
use App\Traits\WithSEO;

class Home extends Component
{
    use WithSEO;

    protected function getSEOTitle(): string
    {
        return 'Speed Cartel BMX Racing - Dominate The Track';
    }

    protected function getSEODescription(): ?string
    {
        return 'Speed Cartel BMX Racing Team is built on passion, precision, and pure adrenaline. Join us as we redefine what\'s possible on two wheels.';
    }

    protected function getSEOImage(): ?string
    {
        return asset('images/og-home.jpg');
    }

    public function getFeaturedRiders()
    {
        return Rider::inRandomOrder()
            ->limit(3)
            ->with(['plates' => function($query) {
                $query->where('is_current', true);
            }])
            ->get();
    }

    public function render()
    {
        $featuredRiders = $this->getFeaturedRiders();
        return view('livewire.public.home', [
            'featuredRiders' => $featuredRiders
        ])->layout('components.layouts.app', ['component' => $this]);
    }
}
