<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class WikiChapter extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'wiki_book_id',
        'title',
        'slug',
        'description',
        'order',
    ];

    /**
     * Parent Wiki Book.
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(WikiBook::class, 'wiki_book_id');
    }

    /**
     * Pages in this Chapter.
     */
    public function pages(): HasMany
    {
        return $this->hasMany(WikiPage::class, 'wiki_chapter_id')->orderBy('order');
    }
}
