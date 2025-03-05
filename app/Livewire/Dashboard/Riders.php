<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Rider;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;

class Riders extends Component
{
    public $showForm = false;
    public $editingRider = null;
    
    // Form properties
    public $first_name = '';
    public $last_name = '';
    public $nickname = '';
    public $birthdate = '';
    public $class = '';
    public $skill = '';
    public $profile_pic = '';

    public function mount()
    {
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
            'first_name',
            'last_name',
            'nickname',
            'birthdate',
            'class',
            'skill',
            'profile_pic',
        ]);
        $this->editingRider = null;
        $this->showForm = false;
    }

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(Rider $rider)
    {
        $this->editingRider = $rider;
        $this->first_name = $rider->first_name;
        $this->last_name = $rider->last_name;
        $this->nickname = $rider->nickname;
        $this->birthdate = $rider->birthdate->format('Y-m-d');
        $this->class = $rider->class;
        $this->skill = $rider->skill;
        $this->profile_pic = $rider->profile_pic;
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'nickname' => 'nullable|string|max:255',
            'birthdate' => 'required|date|before:today',
            'class' => 'nullable|string|max:255',
            'skill' => 'nullable|string|max:255',
            'profile_pic' => 'required|string|max:255',
        ]);

        $data = [
            'user_id' => Auth::id(),
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'nickname' => $this->nickname,
            'birthdate' => $this->birthdate,
            'class' => $this->class,
            'skill' => $this->skill,
            'profile_pic' => $this->profile_pic,
        ];

        if ($this->editingRider) {
            $this->editingRider->update($data);
            $message = 'Rider updated successfully';
        } else {
            Rider::create($data);
            $message = 'Rider added successfully';
        }

        $this->resetForm();
        $this->dispatch('notify', message: $message);
    }

    public function delete(Rider $rider)
    {
        $rider->delete();
        $this->dispatch('notify', message: 'Rider deleted successfully');
    }

    public function render()
    {
        return view('livewire.dashboard.riders', [
            'riders' => Rider::forUser(Auth::user())
                ->latest()
                ->get()
        ])->layout('components.layouts.dashboard');
    }
} 