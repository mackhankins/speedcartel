<?php

namespace App\View\Components\Layouts;

use Illuminate\View\Component;
use Livewire\Component as LivewireComponent;

class App extends Component
{
    public $component;

    public function __construct($component = null)
    {
        $this->component = $component;
    }

    public function render()
    {
        return view('components.layouts.app');
    }
} 