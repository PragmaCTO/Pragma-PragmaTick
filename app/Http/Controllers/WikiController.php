<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Project;
use App\Models\User;
use App\Models\WikiBook;
use App\Models\WikiChapter;
use App\Models\WikiPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class WikiController extends Controller
{
    /**
     * Display a listing of accessible Wiki Books.
     */
    public function index()
    {
        /** @var User $user */
        $user = auth()->user();

        if ($user->isSuperAdmin()) {
            $books = WikiBook::with(['owner', 'author', 'chapters.pages'])->get();
            $organizations = Organization::all();
            $projects = Project::all();
        } else {
            $userOrgIds = $user->organizations()->pluck('organizations.id');
            $userProjIds = $user->projects()->pluck('projects.id');

            $books = WikiBook::where(function ($q) use ($user, $userOrgIds, $userProjIds) {
                $q->where(fn($q1) => $q1->where('owner_type', Organization::class)->whereIn('owner_id', $userOrgIds))
                  ->orWhere(fn($q2) => $q2->where('owner_type', Project::class)->whereIn('owner_id', $userProjIds))
                  ->orWhere('author_id', $user->id)
                  ->orWhereHas('sharedUsers', fn($q3) => $q3->where('users.id', $user->id));
            })->with(['owner', 'author', 'chapters.pages'])->get();

            $organizations = Organization::whereIn('id', $userOrgIds)->get();
            $projects = Project::whereIn('id', $userProjIds)->get();
        }

        $allUsers = User::orderBy('name')->get();

        return view('wikis.index', compact('books', 'organizations', 'projects', 'user', 'allUsers'));
    }

    /**
     * Store a newly created WikiBook.
     */
    public function storeBook(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            abort(403, 'Unauthorized.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'owner_kind' => 'required|in:organization,project,private',
            'owner_id' => 'nullable|integer',
            'is_private' => 'nullable|boolean',
        ]);

        $ownerType = null;
        $ownerId = null;

        if ($validated['owner_kind'] === 'organization' && !empty($validated['owner_id'])) {
            $ownerType = Organization::class;
            $ownerId = $validated['owner_id'];
        } elseif ($validated['owner_kind'] === 'project' && !empty($validated['owner_id'])) {
            $ownerType = Project::class;
            $ownerId = $validated['owner_id'];
        }

        $book = WikiBook::create([
            'author_id' => $user->id,
            'owner_type' => $ownerType,
            'owner_id' => $ownerId,
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'description' => $validated['description'] ?? null,
            'is_private' => $validated['owner_kind'] === 'private' || !empty($validated['is_private']),
        ]);

        $user->logActivity('created', "Created Wiki Book '{$book->title}'", $book);

        return redirect()->route('wikis.showBook', $book)->with('success', "Wiki Book '{$book->title}' created.");
    }

    /**
     * Display a WikiBook tree overview (Chapters & Pages).
     */
    public function showBook(WikiBook $book)
    {
        $user = auth()->user();
        if ($user && !Gate::allows('view', $book)) {
            abort(403, 'Unauthorized to access this Wiki Book.');
        }

        $book->load(['owner', 'author', 'chapters.pages.author', 'sharedUsers']);
        $allUsers = User::orderBy('name')->get();

        return view('wikis.show_book', compact('book', 'allUsers', 'user'));
    }

    /**
     * Store a new Chapter under a WikiBook.
     */
    public function storeChapter(Request $request, WikiBook $book)
    {
        $user = auth()->user();
        if ($user && !Gate::allows('update', $book)) {
            abort(403, 'Unauthorized to add chapters to this Wiki Book.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $maxOrder = $book->chapters()->max('order') ?? 0;

        $chapter = $book->chapters()->create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'description' => $validated['description'] ?? null,
            'order' => $maxOrder + 1,
        ]);

        $user->logActivity('created', "Created Wiki Chapter '{$chapter->title}' in book {$book->title}", $chapter);

        return redirect()->back()->with('success', "Chapter '{$chapter->title}' added.");
    }

    /**
     * Show form to create a new page under a Chapter.
     */
    public function createPage(WikiChapter $chapter)
    {
        $user = auth()->user();
        $chapter->load('book');
        if ($user && !Gate::allows('update', $chapter->book)) {
            abort(403, 'Unauthorized to add pages to this Chapter.');
        }

        return view('wikis.pages.edit', [
            'chapter' => $chapter,
            'page' => new WikiPage(),
            'user' => $user,
        ]);
    }

    /**
     * Store a new Wiki Page.
     */
    public function storePage(Request $request, WikiChapter $chapter)
    {
        $user = auth()->user();
        $chapter->load('book');
        if ($user && !Gate::allows('update', $chapter->book)) {
            abort(403, 'Unauthorized.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $maxOrder = $chapter->pages()->max('order') ?? 0;

        $page = $chapter->pages()->create([
            'author_id' => $user->id,
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'content' => $validated['content'],
            'order' => $maxOrder + 1,
        ]);

        $user->logActivity('created', "Created Wiki Page '{$page->title}'", $page);

        return redirect()->route('wikis.showPage', $page)->with('success', "Page '{$page->title}' published.");
    }

    /**
     * Display a rendered Wiki Page with native Mermaid.js charts.
     */
    public function showPage(WikiPage $page)
    {
        $page->load(['chapter.book.owner', 'author']);
        $user = auth()->user();

        if ($user && !Gate::allows('view', $page->chapter->book)) {
            abort(403, 'Unauthorized to view this Wiki Page.');
        }

        return view('wikis.pages.show', compact('page', 'user'));
    }

    /**
     * Edit a Wiki Page.
     */
    public function editPage(WikiPage $page)
    {
        $page->load(['chapter.book']);
        $user = auth()->user();

        if ($user && !Gate::allows('update', $page->chapter->book)) {
            abort(403, 'Unauthorized to edit this Wiki Page.');
        }

        return view('wikis.pages.edit', [
            'chapter' => $page->chapter,
            'page' => $page,
            'user' => $user,
        ]);
    }

    /**
     * Update an existing Wiki Page.
     */
    public function updatePage(Request $request, WikiPage $page)
    {
        $user = auth()->user();
        $page->load('chapter.book');

        if ($user && !Gate::allows('update', $page->chapter->book)) {
            abort(403, 'Unauthorized.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $page->update([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'content' => $validated['content'],
        ]);

        $user->logActivity('updated', "Updated Wiki Page '{$page->title}'", $page);

        return redirect()->route('wikis.showPage', $page)->with('success', "Page updated successfully.");
    }

    /**
     * Share private book with specific users.
     */
    public function shareBook(Request $request, WikiBook $book)
    {
        $user = auth()->user();
        if ($user && !Gate::allows('update', $book)) {
            abort(403, 'Unauthorized.');
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $targetUser = User::findOrFail($validated['user_id']);
        $book->sharedUsers()->syncWithoutDetaching([$targetUser->id]);

        $user->logActivity('shared_wiki', "Shared private Wiki Book '{$book->title}' with {$targetUser->name}", $book);

        return redirect()->back()->with('success', "Shared Wiki Book with {$targetUser->name}.");
    }

    /**
     * Soft delete WikiBook.
     */
    public function destroyBook(WikiBook $book)
    {
        $user = auth()->user();
        if ($user && !Gate::allows('delete', $book)) {
            abort(403, 'Unauthorized to delete this Wiki Book.');
        }

        $title = $book->title;
        $book->delete();
        $user->logActivity('deleted', "Soft-deleted Wiki Book '{$title}'", $book);

        return redirect()->route('wikis.index')->with('success', "Wiki Book '{$title}' soft-deleted.");
    }

    /**
     * Soft delete WikiChapter.
     */
    public function destroyChapter(WikiChapter $chapter)
    {
        $user = auth()->user();
        $chapter->load('book');

        if ($user && !Gate::allows('delete', $chapter->book)) {
            abort(403, 'Unauthorized to delete this chapter.');
        }

        $title = $chapter->title;
        $chapter->delete();
        $user->logActivity('deleted', "Soft-deleted Wiki Chapter '{$title}'", $chapter);

        return redirect()->back()->with('success', "Chapter '{$title}' soft-deleted.");
    }

    /**
     * Soft delete WikiPage.
     */
    public function destroyPage(WikiPage $page)
    {
        $user = auth()->user();
        $page->load('chapter.book');

        if ($user && !Gate::allows('delete', $page->chapter->book)) {
            abort(403, 'Unauthorized to delete this page.');
        }

        $title = $page->title;
        $bookId = $page->chapter->wiki_book_id;
        $page->delete();

        $user->logActivity('deleted', "Soft-deleted Wiki Page '{$title}'", $page);

        return redirect()->route('wikis.showBook', $bookId)->with('success', "Page '{$title}' soft-deleted.");
    }
}
