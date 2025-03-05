<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Rider extends Model
{
    protected $fillable = [
        'user_id',
        'name',
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
        'date_of_birth' => 'date',
        'is_approved' => 'boolean',
        'approved_at' => 'datetime',
    ];

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