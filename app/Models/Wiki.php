<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wiki extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'organization_id',
        'project_id',
        'author_id',
        'title',
        'slug',
        'content',
    ];

    /**
     * Author of the Wiki.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Organization (if organization level wiki).
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Project (if project level wiki).
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
