<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    //
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'location',
        'start_date',
        'end_date',
        'is_all_day',
        'recurrence_rule',
        'color',
        'user_id',
        'status',
        'url',
        'timezone',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_all_day' => 'boolean',
    ];

    /**
     * Get the user that owns the event.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include events within a date range.
     */
    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->where(function($q) use ($startDate, $endDate) {
            // Events that start within the date range
            $q->whereBetween('start_date', [$startDate, $endDate])
            // Events that end within the date range
            ->orWhereBetween('end_date', [$startDate, $endDate])
            // Events that span the entire date range
            ->orWhere(function($q2) use ($startDate, $endDate) {
                $q2->where('start_date', '<', $startDate)
                    ->where('end_date', '>', $endDate);
            });
        });
    }
}