<?php

namespace App\Livewire\Public;

use App\Models\Event;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Tags\Tag;

class Races extends Component
{
    use WithPagination;

    public $search = '';
    public $filter = 'upcoming';
    public $month = '';
    public $year = '';
    public $selectedTag = '';
    
    protected $queryString = [
        'search' => ['except' => ''],
        'filter' => ['except' => 'upcoming'],
        'month' => ['except' => ''],
        'year' => ['except' => ''],
        'selectedTag' => ['except' => ''],
    ];

    public function mount()
    {
        // Set default month and year to current if not specified
        if (empty($this->month)) {
            $this->month = Carbon::now()->month;
        }
        
        if (empty($this->year)) {
            $this->year = Carbon::now()->year;
        }
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilter()
    {
        $this->resetPage();
    }

    public function updatedMonth()
    {
        $this->resetPage();
    }

    public function updatedYear()
    {
        $this->resetPage();
    }

    public function updatedSelectedTag()
    {
        $this->resetPage();
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    public function setTag($tag)
    {
        $this->selectedTag = $tag;
        $this->resetPage();
    }

    public function getMonthsProperty()
    {
        return [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December',
        ];
    }

    public function getYearsProperty()
    {
        $currentYear = Carbon::now()->year;
        return range($currentYear - 1, $currentYear + 1);
    }

    public function getEventTagsProperty()
    {
        return Tag::getWithType('races')->pluck('name')->toArray();
    }

    public function getEventsProperty()
    {
        $query = Event::query()
            ->where('status', 'confirmed')
            ->orderBy('start_date', 'asc');

        // Apply search filter
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('location', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        // Apply time filter
        if ($this->filter === 'upcoming') {
            $query->where('start_date', '>=', Carbon::now()->startOfDay());
        } elseif ($this->filter === 'past') {
            $query->where('end_date', '<', Carbon::now()->startOfDay());
        } elseif ($this->filter === 'month') {
            $query->whereMonth('start_date', $this->month)
                  ->whereYear('start_date', $this->year);
        }

        // Apply tag filter
        if (!empty($this->selectedTag)) {
            $query->withAnyTags([$this->selectedTag], 'races');
        }

        // Paginate with 10 items per page
        return $query->paginate(10);
    }

    public function render()
    {
        return view('livewire.public.races', [
            'events' => $this->events,
            'months' => $this->months,
            'years' => $this->years,
            'eventTags' => $this->eventTags,
        ])
        ->layout('components.layouts.app', [
            'title' => 'Race Events',
            'description' => 'Upcoming and past BMX race events',
        ]);
    }
}
