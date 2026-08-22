<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'organization_id',
        'name',
        'description',
        'abbreviation',
        'start_date',
        'due_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'due_date' => 'date',
    ];

    /**
     * Parent Organization.
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Project Users (with pivot role and position).
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_user')
                    ->withPivot('role', 'position')
                    ->withTimestamps();
    }

    /**
     * Project Milestones.
     */
    public function milestones(): HasMany
    {
        return $this->hasMany(Milestone::class);
    }

    /**
     * Project Tasks.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Project Wikis.
     */
    public function wikis(): HasMany
    {
        return $this->hasMany(Wiki::class);
    }

    /**
     * Project Polymorphic Wiki Books.
     */
    public function wikiBooks(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(WikiBook::class, 'owner');
    }

    /**
     * Project Kanban Column Statuses.
     */
    public function statuses(): HasMany
    {
        return $this->hasMany(ProjectStatus::class)->orderBy('order');
    }

    /**
     * Seed default Kanban columns if missing.
     * Mandatory statuses: New, In-Progress, Completed, On Hold.
     */
    public function ensureDefaultStatuses(): void
    {
        if ($this->statuses()->exists()) {
            return;
        }

        $defaults = [
            ['name' => 'New', 'slug' => 'new', 'color' => '#008b8b', 'is_mandatory' => true, 'order' => 1],
            ['name' => 'In-Progress', 'slug' => 'in-progress', 'color' => '#3b82f6', 'is_mandatory' => true, 'order' => 2],
            ['name' => 'In-Review', 'slug' => 'in-review', 'color' => '#8b5cf6', 'is_mandatory' => false, 'order' => 3],
            ['name' => 'Testing', 'slug' => 'testing', 'color' => '#ec4899', 'is_mandatory' => false, 'order' => 4],
            ['name' => 'Completed', 'slug' => 'completed', 'color' => '#10b981', 'is_mandatory' => true, 'order' => 5],
            ['name' => 'Reopened', 'slug' => 'reopened', 'color' => '#f97316', 'is_mandatory' => false, 'order' => 6],
            ['name' => 'On Hold', 'slug' => 'on-hold', 'color' => '#f59e0b', 'is_mandatory' => true, 'order' => 7],
            ['name' => 'Backlog', 'slug' => 'backlog', 'color' => '#64748b', 'is_mandatory' => false, 'order' => 8],
        ];

        foreach ($defaults as $data) {
            $this->statuses()->create($data);
        }
    }
}
