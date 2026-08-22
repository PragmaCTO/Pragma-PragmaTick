<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Milestone extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id',
        'title',
        'description',
        'start_date',
        'due_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'due_date' => 'date',
        ];
    }

    /**
     * Parent Project.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Multiple Assignees attached to this Milestone.
     */
    public function assignees(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'milestone_user')->withTimestamps();
    }

    /**
     * Tasks in this Milestone.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
