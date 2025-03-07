<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Rider;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;
use WireUi\Traits\WireUiActions;
use Illuminate\Support\Facades\Storage;

#[Title('Manage Riders')]
class Riders extends Component
{
    use WithFileUploads, WireUiActions;

    public $showForm = false;
    public $search = '';
    public $searchResults = [];
    public $selectedRider = null;
    public $riders = [];
    public $editingRider = false;

    // Form fields
    public $firstname = '';
    public $lastname = '';
    public $nickname = '';
    public $date_of_birth = '';
    public $class = [];
    public $skill_level = '';
    public $profile_pic;
    public $temp_profile_pic;
    public $relationship = 'parent';
    public $status = 'pending';

    protected $rules = [
        'firstname' => 'required|min:2',
        'lastname' => 'required|min:2',
        'nickname' => 'nullable|string',
        'date_of_birth' => 'required|date|before:today',
        'class' => 'nullable|array',
        'skill_level' => 'nullable|in:beginner,intermediate,expert,pro',
        'profile_pic' => 'nullable|image|max:5120|dimensions:min_width=400,min_height=300', // 5MB max
        'relationship' => 'required|in:parent,guardian,self,other',
    ];

    public function mount()
    {
        $this->loadRiders();
    }

    protected function loadRiders()
    {
        $this->riders = Auth::user()->riders()->get();
    }

    public function resetForm()
    {
        $this->reset([
            'selectedRider',
            'firstname',
            'lastname',
            'nickname',
            'date_of_birth',
            'class',
            'skill_level',
            'profile_pic',
            'temp_profile_pic',
            'relationship',
            'showForm',
            'editingRider',
            'searchResults'
        ]);
    }

    public function create()
    {
        $this->resetForm();
        $this->editingRider = false;
        $this->showForm = true;
        $this->searchResults = [];
    }

    public function edit(Rider $rider)
    {
        $this->selectedRider = $rider;
        $this->editingRider = true;
        $this->firstname = $rider->firstname;
        $this->lastname = $rider->lastname;
        $this->nickname = $rider->nickname;
        $this->date_of_birth = $rider->date_of_birth->format('Y-m-d');
        $this->class = $rider->class ?? [];
        $this->skill_level = $rider->skill_level;
        $this->showForm = true;
    }

    public function updatedSearch()
    {
        if (strlen($this->search) < 2) {
            $this->searchResults = [];
            return;
        }

        $this->searchResults = Rider::search($this->search)
            ->take(5)
            ->get()
            ->map(function ($rider) {
                return [
                    'id' => $rider->id,
                    'full_name' => $rider->full_name,
                    'date_of_birth' => $rider->date_of_birth,
                ];
            })
            ->toArray();
    }

    public function updatedFirstname($value)
    {
        if ($this->editingRider) {
            return;
        }

        if (strlen($value) < 2) {
            $this->searchResults = [];
            return;
        }

        $this->searchResults = Rider::where('firstname', 'like', "%{$value}%")
            ->orWhere('lastname', 'like', "%{$value}%")
            ->take(5)
            ->get()
            ->map(function ($rider) {
                return [
                    'id' => $rider->id,
                    'full_name' => $rider->full_name,
                    'date_of_birth' => $rider->date_of_birth,
                ];
            })
            ->toArray();
    }

    public function selectExistingRider($riderId)
    {
        $rider = Rider::findOrFail($riderId);
        
        // Check if the rider is already associated with the user
        $existingRider = Auth::user()->riders()->find($riderId);
        
        if ($existingRider) {
            // If rider exists, load it for editing
            $this->selectedRider = $existingRider;
            $this->loadRiderData($existingRider);
            $this->editingRider = true;
        } else {
            // If it's a new association, pre-fill the form with existing data
            $this->selectedRider = $rider;
            $this->firstname = $rider->firstname;
            $this->lastname = $rider->lastname;
            $this->nickname = $rider->nickname;
            $this->date_of_birth = $rider->date_of_birth->format('Y-m-d');
            $this->class = $rider->class ?? [];
            $this->skill_level = $rider->skill_level;
            $this->editingRider = false;
            $this->status = 'pending';
        }
        
        // Clear search results
        $this->searchResults = [];
    }

    public function selectRider($riderId)
    {
        $rider = Rider::findOrFail($riderId);
        
        // If the rider is already associated with the user, load for editing
        if ($existingRider = Auth::user()->riders()->find($riderId)) {
            $this->selectedRider = $existingRider;
            $this->loadRiderData($existingRider);
            $this->showForm = true;
            return;
        }

        // If it's a new association, pre-fill the form with existing data
        $this->selectedRider = $rider;
        $this->loadRiderData($rider);
        $this->showForm = true;
        
        // Reset the status to pending for new associations
        $this->status = 'pending';
    }

    protected function loadRiderData($rider)
    {
        $this->firstname = $rider->firstname;
        $this->lastname = $rider->lastname;
        $this->nickname = $rider->nickname;
        $this->date_of_birth = $rider->date_of_birth->format('Y-m-d');
        $this->class = $rider->class ?? [];
        $this->skill_level = $rider->skill_level;
        $this->relationship = $rider->pivot?->relationship ?? 'parent';
    }

    public function save()
    {
        $this->validate();

        $riderData = [
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'nickname' => $this->nickname,
            'date_of_birth' => $this->date_of_birth,
            'class' => $this->class,
            'skill_level' => $this->skill_level,
        ];

        // Handle profile picture upload
        if ($this->profile_pic) {
            $riderData['profile_pic'] = $this->profile_pic->store('profile-pics', 'public');
        }

        if ($this->selectedRider) {
            $rider = $this->selectedRider;
            
            // Delete old profile pic if uploading a new one
            if ($this->profile_pic && $rider->profile_pic) {
                Storage::disk('public')->delete($rider->profile_pic);
            }
            
            $rider->update($riderData);
        } else {
            $rider = Rider::create($riderData);
        }

        // Attach or update the relationship with the user
        Auth::user()->riders()->syncWithoutDetaching([
            $rider->id => [
                'relationship' => $this->relationship,
                'status' => $this->selectedRider && $rider->pivot ? $rider->pivot->status : 'pending'
            ]
        ]);

        $this->notification()->success(
            $title = $this->selectedRider ? 'Rider Updated' : 'Rider Added',
            $description = 'The rider has been successfully ' . ($this->selectedRider ? 'updated.' : 'added.')
        );

        $this->resetForm();
        $this->loadRiders();
    }

    public function confirmDelete(Rider $rider)
    {
        $this->dialog()->confirm([
            'title' => 'Delete Rider',
            'description' => "Are you sure you want to delete {$rider->firstname} {$rider->lastname}?",
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

    public function updatedProfilePic()
    {
        if ($this->profile_pic) {
            $this->temp_profile_pic = $this->profile_pic->store('temp', 'public');
        }
    }

    public function render()
    {
        return view('livewire.dashboard.riders', [
            'riders' => $this->riders
        ])->layout('components.layouts.dashboard');
    }
}
