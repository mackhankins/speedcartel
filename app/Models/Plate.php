<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plate extends Model
{
    use HasFactory;

    protected $fillable = [
        'rider_id',
        'type',
        'number',
        'year',
        'is_current'
    ];

    /**
     * Available plate type options
     */
    public static $typeOptions = [
        ['name' => 'District', 'value' => 'district'],
        ['name' => 'State', 'value' => 'state'],
        ['name' => 'National', 'value' => 'national'],
        ['name' => 'NAG', 'value' => 'nag'],
        ['name' => 'World', 'value' => 'world']
    ];

    /**
     * Get the rider that owns the plate.
     */
    public function rider()
    {
        return $this->belongsTo(Rider::class);
    }
} 