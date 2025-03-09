<?php

namespace App\Livewire\Public;

use App\Models\Sponsor;
use Livewire\Component;
use Livewire\WithPagination;

class Sponsors extends Component
{
    use WithPagination;

    public $search = '';

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function getSponsorsProperty()
    {
        $query = Sponsor::query()
            ->where('status', 'active')
            ->orderBy('name');

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('company', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        return $query->paginate(12);
    }

    public function render()
    {
        return view('livewire.public.sponsors', [
            'sponsors' => $this->sponsors,
        ])
        ->layout('components.layouts.app', [
            'title' => 'Our Sponsors',
            'description' => 'Meet the sponsors who support Speed Cartel BMX Team',
        ]);
    }
}
