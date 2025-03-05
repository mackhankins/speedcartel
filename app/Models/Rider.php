<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class Rider extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'nickname',
        'birthdate',
        'class',
        'skill',
        'profile_pic',
        'date_of_birth',
        'gender',
        'height',
        'weight',
        'medical_conditions',
        'allergies',
        'medications',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'is_approved',
        'approved_at',
    ];

    protected $casts = [
        'birthdate' => 'date',
        'class' => 'json',
        'date_of_birth' => 'date',
        'is_approved' => 'boolean',
        'approved_at' => 'datetime',
    ];

    public function getProfilePicUrlAttribute()
    {
        if (!$this->profile_pic) {
            return null;
        }
        return Storage::disk('public')->url($this->profile_pic);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeForUser(Builder $query, User $user): Builder
    {
        return $query->where('user_id', $user->id);
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('is_approved', true);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('is_approved', false);
    }
}
