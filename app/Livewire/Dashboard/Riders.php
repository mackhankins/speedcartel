<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Rider;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use WireUi\Traits\WireUiActions;

class Riders extends Component
{
    use WithFileUploads, WireUiActions;

    public $showForm = false;
    public $editingRider = null;

    // Form properties
    public $first_name = '';
    public $last_name = '';
    public $nickname = '';
    public $birthdate = '';
    public $class = [];
    public $skill = '';
    public $profile_pic = null;
    public $temp_profile_pic = null;

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
            'temp_profile_pic',
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
        $this->temp_profile_pic = $rider->profile_pic;
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'nickname' => 'nullable|string|max:255',
            'birthdate' => 'required|date|before:today',
            'class' => 'nullable|array',
            'class.*' => 'string|in:class,cruiser',
            'skill' => 'nullable|string|max:255',
            'profile_pic' => 'nullable|image|max:5120', // 5MB max
        ]);

        $data = [
            'user_id' => Auth::id(),
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'nickname' => $this->nickname,
            'birthdate' => $this->birthdate,
            'class' => $this->class,
            'skill' => $this->skill,
        ];

        if ($this->editingRider) {
            // If we're editing and temp_profile_pic is null, it means we want to delete the image
            if (is_null($this->temp_profile_pic) && $this->editingRider->profile_pic) {
                Storage::disk('public')->delete($this->editingRider->profile_pic);
                $data['profile_pic'] = null;
            }
            // If we have a new profile pic, upload it and delete the old one
            elseif ($this->profile_pic) {
                if ($this->editingRider->profile_pic) {
                    Storage::disk('public')->delete($this->editingRider->profile_pic);
                }
                $data['profile_pic'] = $this->profile_pic->store('profile-pics', 'public');
            }
            // Otherwise keep the existing profile pic
            elseif ($this->temp_profile_pic) {
                $data['profile_pic'] = $this->temp_profile_pic;
            }

            $this->editingRider->update($data);
            $message = 'Rider updated successfully';
        } else {
            if ($this->profile_pic) {
                $data['profile_pic'] = $this->profile_pic->store('profile-pics', 'public');
            }
            Rider::create($data);
            $message = 'Rider added successfully';
        }

        $this->resetForm();
        $this->dispatch('notify', message: $message);
    }

    public function confirmDelete(Rider $rider)
    {
        $this->dialog()->confirm([
            'title' => 'Delete Rider',
            'description' => "Are you sure you want to delete {$rider->first_name} {$rider->last_name}?",
            'accept' => [
                'label' => 'Delete',
                'method' => 'delete',
                'params' => $rider,
                'color' => 'negative'
            ],
            'reject' => [
                'label' => 'Cancel',
                'color' => 'slate'
            ]
        ]);
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
