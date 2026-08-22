<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'name',
        'slug',
        'color',
        'is_mandatory',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'is_mandatory' => 'boolean',
            'order' => 'integer',
        ];
    }

    /**
     * Parent project.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
