<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait HasProfilePhoto
{
    /**
     * Get the URL of the profile photo.
     *
     * @return string
     */
    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_pic) {
            // Check if the file exists with the exact path
            if (Storage::disk('public')->exists($this->profile_pic)) {
                return asset('storage/' . $this->profile_pic);
            }

            // Check if the file exists with just the basename (in case the path is stored differently)
            $basename = basename($this->profile_pic);
            $directory = dirname($this->profile_pic);

            if (Storage::disk('public')->exists($directory . '/' . $basename)) {
                return asset('storage/' . $directory . '/' . $basename);
            }

            // Check if any files exist in the directory
            $files = Storage::disk('public')->files($directory);

            if (!empty($files)) {
                // Use the most recent file
                $latestFile = end($files);

                // Update the profile_pic field to point to the existing file
                $this->profile_pic = $latestFile;
                $this->save();

                return asset('storage/' . $latestFile);
            }

            // If we can't find the file, clear the profile_pic field and return default
            $this->profile_pic = null;
            $this->save();
            return $this->getDefaultAvatarUrl();
        }

        // Return a default image if no profile_pic is set
        return $this->getDefaultAvatarUrl();
    }

    /**
     * Update the user's profile photo.
     *
     * @param  string  $base64Image
     * @param  string  $directory
     * @param  string  $prefix
     * @return void
     */
    public function updateProfilePhoto($base64Image, $directory = 'profile-photos', $prefix = 'profile_')
    {
        // Ensure the directory exists
        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }

        try {
            // Decode the base64 image
            $image_parts = explode(";base64,", $base64Image);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);

            // Generate a unique filename
            $filename = $prefix . time() . '.' . $image_type;
            $path = $directory . '/' . $filename;

            // Delete the old profile photo if it exists
            $this->deleteProfilePhoto();

            // Save the image to storage
            $saved = Storage::disk('public')->put($path, $image_base64);

            if (!$saved) {
                throw new \Exception('Failed to save image to storage');
            }

            // Verify the file exists
            if (!Storage::disk('public')->exists($path)) {
                throw new \Exception('File was not saved properly');
            }

            // Update the model with the new path
            $this->profile_pic = $path;
            $this->save();

            // Refresh the model to ensure we have the latest data
            $this->refresh();

            return $path;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Delete the user's profile photo.
     *
     * @return void
     */
    public function deleteProfilePhoto()
    {
        if (!$this->profile_pic) {
            return;
        }

        $oldPath = $this->profile_pic;

        // Try to delete with the exact path
        if (Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }

        // Also try with just the basename in the same directory
        $directory = dirname($oldPath);
        $oldBasename = basename($oldPath);
        if (Storage::disk('public')->exists($directory . '/' . $oldBasename)) {
            Storage::disk('public')->delete($directory . '/' . $oldBasename);
        }

        // Check if the directory is empty and delete it if it is
        $files = Storage::disk('public')->files($directory);
        if (empty($files)) {
            $directories = Storage::disk('public')->directories($directory);
            if (empty($directories)) {
                // Only delete the directory if it's empty (no files or subdirectories)
                Storage::disk('public')->deleteDirectory($directory);
            }
        }

        // Clear the profile_pic field
        $this->profile_pic = null;

        // Only save if the model exists in the database
        if ($this->exists) {
            $this->save();
        }
    }

    /**
     * Get the default avatar URL.
     *
     * @return string
     */
    protected function getDefaultAvatarUrl()
    {
        // Return a data URI for the default avatar with a background color
        return 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="24" height="24" fill="#f3f4f6"/><path d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>');
    }
}
