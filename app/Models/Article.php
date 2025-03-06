<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use Spatie\Tags\HasTags;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Article extends Model
{
    use HasTags, HasSEO;

    protected $fillable = [
        'title',
        'slug',
        'photo',
        'excerpt',
        'content',
        'published_at',
        'author_id',
        'status',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
