<?php

namespace App\Livewire\Public;

use Livewire\Component;
use App\Models\Rider;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

#[Title('Our Team')]
class Team extends Component
{
    use WithPagination;
    
    public $search = '';
    public $skillLevel = '';
    public $classFilter = '';
    
    // Reset pagination when filters change
    public function updatedSearch()
    {
        $this->resetPage();
    }
    
    public function updatedSkillLevel()
    {
        $this->resetPage();
    }
    
    public function updatedClassFilter()
    {
        $this->resetPage();
    }
    
    public function resetFilters()
    {
        $this->search = '';
        $this->skillLevel = '';
        $this->classFilter = '';
        $this->resetPage();
    }
    
    public function render()
    {
        $riders = Rider::query()
            ->when($this->search, function ($query) {
                return $query->where(function ($query) {
                    $query->where('firstname', 'like', '%' . $this->search . '%')
                        ->orWhere('lastname', 'like', '%' . $this->search . '%')
                        ->orWhere('nickname', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->skillLevel, function ($query) {
                return $query->where('skill_level', $this->skillLevel);
            })
            ->when($this->classFilter, function ($query) {
                return $query->whereJsonContains('class', $this->classFilter);
            })
            ->orderBy('firstname')
            ->paginate(12);
            
        return view('livewire.public.team', [
            'riders' => $riders,
            'skillLevels' => Rider::$skillLevelOptions,
            'classes' => Rider::$classOptions
        ])->layout('components.layouts.app');
    }
}
