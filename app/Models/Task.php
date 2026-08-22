<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id',
        'milestone_id',
        'parent_id',
        'assigned_to',
        'title',
        'type',
        'description',
        'priority',
        'status',
        'start_date',
        'due_date',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'due_date' => 'date',
        ];
    }

    /**
     * Auto-generated Task Code attribute (e.g. PRAG-12, ARCOS-5).
     */
    protected function code(): Attribute
    {
        return Attribute::make(
            get: fn () => ($this->project?->abbreviation ?: 'TASK') . '-' . $this->id,
        );
    }

    /**
     * Parent Project.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Milestone.
     */
    public function milestone(): BelongsTo
    {
        return $this->belongsTo(Milestone::class);
    }

    /**
     * Legacy single assignee (first assignee or primary).
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Multiple Assignees attached to this Task.
     */
    public function assignees(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'task_user')->withTimestamps();
    }

    /**
     * Parent Task (if this is a subtask).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'parent_id');
    }

    /**
     * Child Subtasks under this Task.
     */
    public function subtasks(): HasMany
    {
        return $this->hasMany(Task::class, 'parent_id');
    }

    /**
     * Task Comments thread.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(TaskComment::class);
    }
}
