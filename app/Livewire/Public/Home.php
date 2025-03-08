<?php

namespace App\Livewire\Public;

use Livewire\Component;
use App\Models\Rider;

class Home extends Component
{
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
        ]);
    }
}
