<?php

namespace App\Livewire\Public;

use Livewire\Component;
use App\Models\Rider;
use Livewire\Attributes\Title;
use App\Traits\WithSEO;
use Livewire\WithPagination;

#[Title('Rider Profile')]
class RiderProfile extends Component
{
    use WithSEO, WithPagination;

    public Rider $rider;
    public $displayCount = 5;

    public function mount(Rider $rider)
    {
        $this->rider = $rider;
    }

    public function loadMorePlates()
    {
        $this->displayCount += 5;
    }

    protected function getSEOTitle(): string
    {
        $name = $this->rider->full_name;
        if ($this->rider->nickname) {
            $name .= ' "' . $this->rider->nickname . '"';
        }
        return $name . ' - Speed Cartel BMX Racing';
    }

    protected function getSEODescription(): ?string
    {
        $description = "{$this->rider->full_name} is a {$this->rider->skill_level} level rider";
        
        if ($this->rider->class) {
            $classes = is_array($this->rider->class) ? implode(', ', array_map('ucfirst', $this->rider->class)) : ucfirst($this->rider->class);
            $description .= " competing in {$classes}";
        }
        
        if ($this->rider->homeTrack) {
            $description .= " at {$this->rider->homeTrack->name}";
        }
        
        $description .= " with Speed Cartel BMX Racing Team.";
        
        return $description;
    }

    protected function getSEOImage(): ?string
    {
        return $this->rider->profile_pic ? $this->rider->profile_photo_url : null;
    }

    public function render()
    {
        $currentPlate = $this->rider->plates->firstWhere('is_current', true);
        $otherPlates = $this->rider->plates
            ->where('is_current', false)
            ->sortByDesc('year')
            ->take($this->displayCount);

        $totalPlates = $this->rider->plates
            ->where('is_current', false)
            ->count();

        return view('livewire.public.rider-profile', [
            'skillLevelOptions' => Rider::$skillLevelOptions,
            'classOptions' => Rider::$classOptions,
            'currentPlate' => $currentPlate,
            'otherPlates' => $otherPlates,
            'totalPlates' => $totalPlates,
        ])->layout('components.layouts.app', ['component' => $this]);
    }
} 