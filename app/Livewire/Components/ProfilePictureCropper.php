<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use WireUi\Traits\WireUiActions;

class ProfilePictureCropper extends Component
{
    use WithFileUploads, WireUiActions;

    // Public properties
    public $photo;
    public $croppedImage = null;
    public $tempImageUrl = null;
    public $showCroppieModal = false;
    
    // Configuration properties (can be passed from parent)
    public $storagePath = 'uploads';
    public $aspectRatio = 1; // Square by default
    public $viewportWidth = 400;
    public $viewportHeight = 400;
    public $boundaryWidth = '100%';
    public $boundaryHeight = 450;
    public $filePrefix = 'profile_';
    public $imageQuality = 0.9;
    public $cropperType = 'square'; // square or circle
    public $previewSize = 24; // Size of the preview circle/square in rem
    public $showPreview = true; // Whether to show the preview image
    public $buttonText = 'Select Photo';
    public $helpText = 'PNG, JPG or GIF up to 5MB';
    public $cropTitle = 'Crop Photo';
    public $saveButtonText = 'Save';
    public $cancelButtonText = 'Cancel';
    public $model = null; // Model instance to update directly
    public $modelPhotoField = 'profile_pic'; // Field name in the model
    public $disabled = false; // Whether the component is disabled
    
    // Events
    protected $listeners = ['imageCropped'];
    
    /**
     * Mount the component with optional configuration
     */
    public function mount(
        $storagePath = null, 
        $aspectRatio = null, 
        $viewportWidth = null, 
        $viewportHeight = null,
        $filePrefix = null,
        $cropperType = null,
        $previewSize = null,
        $showPreview = null,
        $buttonText = null,
        $helpText = null,
        $cropTitle = null,
        $saveButtonText = null,
        $cancelButtonText = null,
        $model = null,
        $modelPhotoField = null,
        $disabled = null
    ) {
        // Allow overriding default settings
        if ($storagePath) $this->storagePath = $storagePath;
        if ($aspectRatio) $this->aspectRatio = $aspectRatio;
        if ($viewportWidth) $this->viewportWidth = $viewportWidth;
        if ($viewportHeight) $this->viewportHeight = $viewportHeight;
        if ($filePrefix) $this->filePrefix = $filePrefix;
        if ($cropperType) $this->cropperType = $cropperType;
        if ($previewSize) $this->previewSize = $previewSize;
        if ($showPreview !== null) $this->showPreview = $showPreview;
        if ($buttonText) $this->buttonText = $buttonText;
        if ($helpText) $this->helpText = $helpText;
        if ($cropTitle) $this->cropTitle = $cropTitle;
        if ($saveButtonText) $this->saveButtonText = $saveButtonText;
        if ($cancelButtonText) $this->cancelButtonText = $cancelButtonText;
        if ($model) $this->model = $model;
        if ($modelPhotoField) $this->modelPhotoField = $modelPhotoField;
        if ($disabled !== null) $this->disabled = $disabled;
        
        // Ensure storage path exists
        if (!Storage::disk('public')->exists($this->storagePath)) {
            Storage::disk('public')->makeDirectory($this->storagePath);
        }
        
        // If we have a model with a profile picture, initialize the component with it
        if ($this->model && $this->model->{$this->modelPhotoField}) {
            $this->dispatch('profile-picture-init', url: asset('storage/' . $this->model->{$this->modelPhotoField}));
        }
    }
    
    /**
     * Handle file upload
     */
    public function updatedPhoto()
    {
        // If the component is disabled, don't allow uploads
        if ($this->disabled) {
            $this->notification()->warning(
                $title = 'Upload Disabled',
                $description = 'Pending Approval'
            );
            return;
        }
        
        $this->validate([
            'photo' => 'image|max:5120|dimensions:min_width=400,min_height=300',
        ]);

        try {
            // Generate a temporary URL for the uploaded image
            $this->tempImageUrl = $this->photo->temporaryUrl();
            
            // Open the cropping modal
            $this->showCroppieModal = true;
        } catch (\Exception $e) {
            $this->notification()->error(
                $title = 'Error',
                $description = 'There was an error processing your image. Please try again.'
            );
        }
    }
    
    /**
     * Save the cropped image
     */
    public function saveCroppedImage()
    {
        // If the component is disabled, don't allow saving
        if ($this->disabled) {
            $this->notification()->warning(
                $title = 'Upload Disabled',
                $description = 'Pending Approval'
            );
            return false;
        }
        
        if (!$this->croppedImage) {
            return false;
        }

        try {
            // Decode the base64 image
            $image_parts = explode(";base64,", $this->croppedImage);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);

            // Generate a unique filename
            $filename = $this->filePrefix . time() . '.' . $image_type;
            $path = $this->storagePath . '/' . $filename;
            
            // Delete old file if we have a model with a profile picture
            if ($this->model && $this->model->{$this->modelPhotoField}) {
                $oldPath = $this->model->{$this->modelPhotoField};
                
                // Delete the file if it exists
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            
            // Save the image to storage
            $saved = Storage::disk('public')->put($path, $image_base64);
            
            if (!$saved) {
                throw new \Exception('Failed to save image to storage');
            }
            
            // Verify the file exists
            if (!Storage::disk('public')->exists($path)) {
                throw new \Exception('File was not saved properly');
            }
            
            // If a model is provided, update it directly
            if ($this->model && method_exists($this->model, 'updateProfilePhoto')) {
                // Use the trait method if available
                $this->model->updateProfilePhoto($this->croppedImage, $this->storagePath, $this->filePrefix);
            } elseif ($this->model) {
                // Otherwise update the field directly
                $this->model->{$this->modelPhotoField} = $path;
                $this->model->save();
            }
            
            // Notify the user
            $this->notification()->success(
                $title = 'Image Cropped',
                $description = 'Your image has been cropped successfully.'
            );
            
            // Reset the component state
            $this->reset(['croppedImage', 'tempImageUrl', 'showCroppieModal']);
            
            // Always emit event with the saved path
            $this->dispatch('profile-picture-saved', path: $path);
            
            return $path;
        } catch (\Exception $e) {
            // Notify the user
            $this->notification()->error(
                $title = 'Error',
                $description = 'There was an error saving your cropped image. Please try again.'
            );
            
            return false;
        }
    }
    
    /**
     * Close the cropping modal
     */
    public function closeCroppieModal()
    {
        $this->reset(['croppedImage', 'tempImageUrl', 'showCroppieModal']);
    }
    
    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.components.profile-picture-cropper');
    }
}
