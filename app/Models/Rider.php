<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Support\Facades\Storage;
use App\Traits\HasProfilePhoto;

class Rider extends Model
{
    use HasFactory, HasProfilePhoto;

    protected $fillable = [
        'firstname',
        'lastname',
        'nickname',
        'date_of_birth',
        'class',
        'skill_level',
        'profile_pic'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'class' => 'array'
    ];

    protected $pivotCasts = [
        'status' => 'string'
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        // Delete the profile picture when the rider is deleted
        static::deleting(function ($rider) {
            if ($rider->profile_pic && method_exists($rider, 'deleteProfilePhoto')) {
                $rider->deleteProfilePhoto();
            }
        });
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'rideables')
            ->withPivot('relationship', 'status')
            ->withTimestamps();
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->firstname} {$this->lastname}";
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($query) use ($term) {
            $query->where('firstname', 'like', "%{$term}%")
                  ->orWhere('lastname', 'like', "%{$term}%");
        });
    }
}
