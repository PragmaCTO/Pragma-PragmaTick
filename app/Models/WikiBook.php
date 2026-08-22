<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class WikiBook extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'author_id',
        'owner_type',
        'owner_id',
        'title',
        'slug',
        'description',
        'is_private',
    ];

    protected function casts(): array
    {
        return [
            'is_private' => 'boolean',
        ];
    }

    /**
     * Polymorphic owner relationship (Organization, Project, or null for Private/Unassigned).
     */
    public function owner(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Author of the Wiki Book.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Chapters in this Wiki Book.
     */
    public function chapters(): HasMany
    {
        return $this->hasMany(WikiChapter::class, 'wiki_book_id')->orderBy('order');
    }

    /**
     * Users explicitly granted access to private Wiki Book.
     */
    public function sharedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'wiki_book_user')->withTimestamps();
    }
}
