<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'color_code',
    ];

    /**
     * Organization Users (with pivot role and position).
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'organization_user')
                    ->withPivot('role', 'position')
                    ->withTimestamps();
    }

    /**
     * Organization Projects.
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Organization Wikis (WikiBooks).
     */
    public function wikis(): HasMany
    {
        return $this->hasMany(Wiki::class);
    }

    /**
     * Organization Polymorphic Wiki Books.
     */
    public function wikiBooks(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(WikiBook::class, 'owner');
    }
}
