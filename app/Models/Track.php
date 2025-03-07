<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Track extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'postal_code',
        'country',
        'email',
        'phone',
        'local_contact_id',
    ];

    /**
     * Get the local contact associated with the track.
     */
    public function localContact(): BelongsTo
    {
        return $this->belongsTo(User::class, 'local_contact_id');
    }
}
