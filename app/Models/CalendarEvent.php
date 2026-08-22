<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CalendarEvent extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'organizer_id',
        'title',
        'description',
        'start_time',
        'end_time',
        'is_super_admin_event',
        'color',
    ];

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
            'is_super_admin_event' => 'boolean',
        ];
    }

    /**
     * Event Organizer User.
     */
    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    /**
     * Event / Meeting Attendees.
     */
    public function attendees(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'calendar_event_user')->withTimestamps();
    }
}
