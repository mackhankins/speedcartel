<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Rider extends Model
{
    use HasFactory;

    protected $fillable = [
        'firstname',
        'lastname',
        'nickname',
        'date_of_birth',
        'class',
        'skill_level',
        'profile_pic',
        'blood_type',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'class' => 'array'
    ];

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
