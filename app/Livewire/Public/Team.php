<?php

namespace App\Livewire\Public;

use Livewire\Component;
use App\Models\Rider;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use App\Traits\WithSEO;

#[Title('Our Team')]
class Team extends Component
{
    use WithPagination, WithSEO;
    
    public $search = '';
    public $skillLevel = '';
    public $classFilter = '';
    
    protected function getSEOTitle(): string
    {
        return 'Our Team - Speed Cartel BMX Racing';
    }

    protected function getSEODescription(): ?string
    {
        return 'Meet the talented riders of Speed Cartel BMX Racing Team. From novice to pro, our diverse team showcases the best talent in BMX racing.';
    }
    
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
        ])->layout('components.layouts.app', ['component' => $this]);
    }
}
