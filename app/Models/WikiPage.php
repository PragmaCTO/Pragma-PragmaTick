<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class WikiPage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'wiki_chapter_id',
        'author_id',
        'title',
        'slug',
        'content',
        'order',
    ];

    /**
     * Parent Chapter.
     */
    public function chapter(): BelongsTo
    {
        return $this->belongsTo(WikiChapter::class, 'wiki_chapter_id');
    }

    /**
     * Page Author.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
