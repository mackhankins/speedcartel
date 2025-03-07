<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Rider;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;
use WireUi\Traits\WireUiActions;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

/**
 * @property-read User $user
 */
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
    public $temp_profile_pic = null;
    public $relationship = 'parent';
    public $status = 0;
    
    // Croppie related properties
    public $showCroppieModal = false;
    public $croppedImage = null;
    public $tempImageUrl = null;
    
    // Helper property for linter
    protected $user;

    // Add a listener for the profile-picture-saved event
    protected $listeners = ['profile-picture-saved' => 'handleProfilePictureSaved'];

    protected $rules = [
        'firstname' => 'required|min:2',
        'lastname' => 'required|min:2',
        'nickname' => 'nullable|string',
        'date_of_birth' => 'required|date|before:today',
        'class' => 'nullable|array',
        'skill_level' => 'nullable|in:beginner,intermediate,expert,pro',
        'profile_pic' => 'nullable|image|max:5120|dimensions:min_width=400,min_height=300', // 5MB max
        'relationship' => 'required|in:parent,guardian,self,other',
        'status' => 'integer|in:0,1'
    ];

    public function mount()
    {
        $this->loadRiders();
    }

    /**
     * Get the authenticated user
     * 
     * @return \App\Models\User
     */
    protected function getUser()
    {
        return Auth::user();
    }

    protected function loadRiders()
    {
        $this->riders = $this->getUser()->riders()->get();
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
            'relationship',
            'showForm',
            'editingRider',
            'searchResults',
            'temp_profile_pic'
        ]);
        
        // Reset the profile picture cropper
        $this->dispatch('profile-pic-reset');
        
        // Set default status to 0 for new riders
        $this->status = 0;
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
        // Get the current user's relationship with this rider
        $userRider = $this->getUser()->riders()->find($rider->id);
        if (!$userRider) {
            return;
        }

        // Refresh the rider to ensure we have the latest data
        $rider->refresh();

        $this->selectedRider = $rider;
        $this->editingRider = true;
        $this->firstname = $rider->firstname;
        $this->lastname = $rider->lastname;
        $this->nickname = $rider->nickname;
        $this->date_of_birth = $rider->date_of_birth->format('Y-m-d');
        $this->class = $rider->class ?? [];
        $this->skill_level = $rider->skill_level;
        $this->relationship = $userRider->pivot->relationship ?? 'parent';
        $this->status = $userRider->pivot->status ?? '0';
        
        // Store the profile_pic path in temp_profile_pic as well
        $this->temp_profile_pic = $rider->profile_pic;
        
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
        $existingRider = $this->getUser()->riders()->find($riderId);
        
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
            $this->status = 0; // New associations start with status 0
            $this->editingRider = false;
        }
        
        // Clear search results
        $this->searchResults = [];
    }

    public function selectRider($riderId)
    {
        $rider = Rider::findOrFail($riderId);
        
        // If the rider is already associated with the user, load for editing
        if ($existingRider = $this->getUser()->riders()->find($riderId)) {
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
        $this->status = 0;
    }

    protected function loadRiderData($rider)
    {
        // Get the current user's relationship with this rider
        $userRider = $this->getUser()->riders()->find($rider->id);
        
        $this->firstname = $rider->firstname;
        $this->lastname = $rider->lastname;
        $this->nickname = $rider->nickname;
        $this->date_of_birth = $rider->date_of_birth->format('Y-m-d');
        $this->class = $rider->class ?? [];
        $this->skill_level = $rider->skill_level;
        $this->relationship = $userRider?->pivot->relationship ?? 'parent';
        $this->status = $userRider?->pivot->status ?? '0';
        
        // Store the profile_pic path in temp_profile_pic as well
        $this->temp_profile_pic = $rider->profile_pic;
        
        // Log the profile_pic value for debugging
        \Log::info('Loading rider data with profile_pic: ' . $rider->profile_pic);
        \Log::info('Profile photo URL: ' . $rider->profile_photo_url);
    }

    /**
     * Handle the profile-picture-saved event
     */
    public function handleProfilePictureSaved($path)
    {
        \Log::info('Profile picture saved event received with path: ' . $path);
        $this->temp_profile_pic = $path;
        
        // If we're editing a rider, update it directly
        if ($this->selectedRider && $this->editingRider) {
            $this->selectedRider->profile_pic = $path;
            $this->selectedRider->save();
            
            // Refresh the rider to ensure we have the latest data
            $this->selectedRider->refresh();
            
            \Log::info('Updated rider profile_pic directly: ' . $path);
            \Log::info('Rider profile_pic after update: ' . $this->selectedRider->profile_pic);
            \Log::info('Rider profile_photo_url after update: ' . $this->selectedRider->profile_photo_url);
        }
    }

    public function save()
    {
        $this->validate();

        \Log::info('Saving rider', [
            'editing' => $this->editingRider,
            'selectedRider' => $this->selectedRider ? $this->selectedRider->id : null,
            'temp_profile_pic' => $this->temp_profile_pic
        ]);

        $riderData = [
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'nickname' => $this->nickname,
            'date_of_birth' => $this->date_of_birth,
            'class' => $this->class,
            'skill_level' => $this->skill_level,
        ];
        
        // Add the profile_pic to the rider data if we have a temporary path
        if ($this->temp_profile_pic) {
            $riderData['profile_pic'] = $this->temp_profile_pic;
            \Log::info('Adding profile_pic to rider data: ' . $this->temp_profile_pic);
        }

        if ($this->selectedRider) {
            $rider = $this->selectedRider;
            
            // Check current user's status with this rider
            $userRider = $this->getUser()->riders()->find($rider->id);
            if ($userRider && $userRider->pivot->status === 1) {
                // Log the rider data before update
                \Log::info('Updating rider with data: ', $riderData);
                
                $rider->update($riderData);
                
                // Verify the rider was updated
                $rider->refresh();
                \Log::info('Rider after update - ID: ' . $rider->id . ', profile_pic: ' . $rider->profile_pic);
            }
        } else {
            // Create new rider
            \Log::info('Creating new rider with data: ', $riderData);
            $rider = Rider::create($riderData);
            \Log::info('Created new rider with ID: ' . $rider->id . ', profile_pic: ' . $rider->profile_pic);
        }

        // Attach or update the relationship with the user
        $this->getUser()->riders()->syncWithoutDetaching([
            $rider->id => [
                'relationship' => $this->relationship,
                'status' => $this->status
            ]
        ]);
        \Log::info('Updated rider relationship for user: ' . $this->getUser()->id . ' with rider: ' . $rider->id);

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
        // Get the count of users associated with this rider
        $userCount = $rider->users()->count();

        if ($userCount <= 1) {
            // If this is the only user, delete the rider completely
            
            // Delete the profile picture if it exists
            if ($rider->profile_pic) {
                // Use the trait method if available
                if (method_exists($rider, 'deleteProfilePhoto')) {
                    $rider->deleteProfilePhoto();
                } else {
                    // Fallback to manual deletion
                    $path = $rider->profile_pic;
                    if (Storage::disk('public')->exists($path)) {
                        Storage::disk('public')->delete($path);
                    }
                }
            }
            
            // Now delete the rider
            $rider->delete();
            $message = 'Rider deleted successfully';
        } else {
            // If other users are associated, just detach from current user
            $this->getUser()->riders()->detach($rider->id);
            $message = 'Rider removed from your list';
        }

        $this->dispatch('notify', message: $message);
        $this->loadRiders();
    }

    public function render()
    {
        return view('livewire.dashboard.riders', [
            'riders' => $this->riders
        ])->layout('components.layouts.dashboard');
    }
}
