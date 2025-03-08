<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Rider;
use Livewire\Attributes\Title;

#[Title('Team')]
class TeamProfile extends Component
{
    public $riders = [];
    public $filters = [
        'skill_level' => '',
        'class' => '',
    ];

    public function mount()
    {
        $this->loadRiders();
    }

    public function loadRiders()
    {
        $query = Rider::query()
            ->whereHas('users', function ($query) {
                $query->where('status', 1); // Only approved riders
            });

        if ($this->filters['skill_level']) {
            $query->where('skill_level', $this->filters['skill_level']);
        }

        if ($this->filters['class']) {
            $query->whereJsonContains('class', $this->filters['class']);
        }

        $this->riders = $query->get();
    }

    public function updatedFilters()
    {
        $this->loadRiders();
    }

    public function render()
    {
        return view('livewire.team-profile', [
            'skillLevelOptions' => Rider::$skillLevelOptions,
            'classOptions' => Rider::$classOptions,
        ])->layout('components.layouts.app');
    }
} 