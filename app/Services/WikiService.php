<?php

namespace App\Services;

use App\Models\Organization;
use App\Models\Project;
use App\Models\User;
use App\Models\WikiBook;
use App\Models\WikiChapter;
use App\Models\WikiPage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class WikiService
{
    public function createBook(array $validated, User $user): WikiBook
    {
        $ownerType = null;
        $ownerId = null;

        if ($validated['owner_kind'] === 'organization' && !empty($validated['owner_id'])) {
            $ownerType = Organization::class;
            $ownerId = $validated['owner_id'];
        } elseif ($validated['owner_kind'] === 'project' && !empty($validated['owner_id'])) {
            $ownerType = Project::class;
            $ownerId = $validated['owner_id'];
        }

        return WikiBook::create([
            'author_id' => $user->id,
            'owner_type' => $ownerType,
            'owner_id' => $ownerId,
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'description' => $validated['description'] ?? null,
            'is_private' => $validated['owner_kind'] === 'private' || !empty($validated['is_private']),
        ]);
    }

    public function createChapter(WikiBook $book, array $validated, User $user): WikiChapter
    {
        if (!Gate::forUser($user)->allows('update', $book)) {
            abort(403, 'Unauthorized to add chapters to this Wiki Book.');
        }

        $maxOrder = $book->chapters()->max('order') ?? 0;

        return $book->chapters()->create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'description' => $validated['description'] ?? null,
            'order' => $maxOrder + 1,
        ]);
    }

    public function createPage(WikiChapter $chapter, array $validated, User $user): WikiPage
    {
        $chapter->load('book');
        if (!Gate::forUser($user)->allows('update', $chapter->book)) {
            abort(403, 'Unauthorized.');
        }

        $maxOrder = $chapter->pages()->max('order') ?? 0;

        return $chapter->pages()->create([
            'author_id' => $user->id,
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'content' => $validated['content'],
            'order' => $maxOrder + 1,
        ]);
    }

    public function updatePage(WikiPage $page, array $validated, User $user): void
    {
        $page->load('chapter.book');

        if (!Gate::forUser($user)->allows('update', $page->chapter->book)) {
            abort(403, 'Unauthorized.');
        }

        $page->update([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'content' => $validated['content'],
        ]);
    }

    public function shareBook(WikiBook $book, array $validated, User $user): void
    {
        if (!Gate::forUser($user)->allows('update', $book)) {
            abort(403, 'Unauthorized.');
        }

        $targetUser = User::findOrFail($validated['user_id']);
        $book->sharedUsers()->syncWithoutDetaching([$targetUser->id]);

        $user->logActivity('shared_wiki', "Shared private Wiki Book '{$book->title}' with {$targetUser->name}", $book);
    }
}
