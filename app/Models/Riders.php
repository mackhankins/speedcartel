<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Riders extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected function casts()
    {
        return [
            'birthdate' => 'date',
            'class' => 'array',
        ];
    }
}
