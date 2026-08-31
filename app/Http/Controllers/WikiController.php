<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWikiRequest;
use App\Http\Requests\UpdateWikiRequest;
use App\Models\Organization;
use App\Models\Project;
use App\Models\User;
use App\Models\WikiBook;
use App\Models\WikiChapter;
use App\Models\WikiPage;
use App\Services\WikiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class WikiController extends Controller
{
    protected WikiService $wikiService;

    public function __construct(WikiService $wikiService)
    {
        $this->wikiService = $wikiService;
    }

    /**
     * Display a listing of accessible Wiki Books.
     */
    public function index()
    {
        /** @var User $user */
        $user = auth()->user();

        if ($user->isSuperAdmin()) {
            $books = WikiBook::with(['owner', 'author', 'chapters.pages'])->withCount('chapters')->get();
            $organizations = Organization::all();
            $projects = Project::with('organization')->get();
        } else {
            $adminOrgIds = $user->organizations()->wherePivot('role', 'org_admin')->pluck('organizations.id');
            $userProjIds = $user->projects()->pluck('projects.id');
            $orgProjectIds = Project::whereIn('organization_id', $adminOrgIds)->pluck('id');
            
            $allAuthorizedProjIds = $userProjIds->merge($orgProjectIds)->unique();

            $books = WikiBook::where(function ($q) use ($user, $adminOrgIds, $allAuthorizedProjIds) {
                $q->where(fn($q1) => $q1->where('owner_type', Organization::class)->whereIn('owner_id', $adminOrgIds))
                  ->orWhere(fn($q2) => $q2->where('owner_type', Project::class)->whereIn('owner_id', $allAuthorizedProjIds))
                  ->orWhere('author_id', $user->id)
                  ->orWhereHas('sharedUsers', fn($q3) => $q3->where('users.id', $user->id));
            })->with(['owner', 'author', 'chapters.pages'])->withCount('chapters')->get();

            $projects = Project::with('organization')->whereIn('id', $allAuthorizedProjIds)->get();
            $organizations = Organization::whereIn('id', $adminOrgIds)
                ->orWhereIn('id', $projects->pluck('organization_id'))
                ->get();
        }

        $allUsers = User::select('id', 'name', 'email')->orderBy('name')->get();

        return view('wikis.index', compact('books', 'organizations', 'projects', 'user', 'allUsers'));
    }

    /**
     * Store a newly created WikiBook.
     */
    public function storeBook(StoreWikiRequest $request)
    {
        $book = $this->wikiService->createBook($request->validated(), auth()->user());
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
        $allUsers = User::select('id', 'name', 'email')->orderBy('name')->get();

        return view('wikis.show_book', compact('book', 'allUsers', 'user'));
    }

    /**
     * Store a new Chapter under a WikiBook.
     */
    public function storeChapter(StoreWikiRequest $request, WikiBook $book)
    {
        $chapter = $this->wikiService->createChapter($book, $request->validated(), auth()->user());
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
    public function storePage(StoreWikiRequest $request, WikiChapter $chapter)
    {
        $page = $this->wikiService->createPage($chapter, $request->validated(), auth()->user());
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
    public function updatePage(UpdateWikiRequest $request, WikiPage $page)
    {
        $this->wikiService->updatePage($page, $request->validated(), auth()->user());
        return redirect()->route('wikis.showPage', $page)->with('success', "Page updated successfully.");
    }

    /**
     * Share private book with specific users.
     */
    public function shareBook(UpdateWikiRequest $request, WikiBook $book)
    {
        $this->wikiService->shareBook($book, $request->validated(), auth()->user());
        return redirect()->back()->with('success', "Shared Wiki Book successfully.");
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
        $book->delete(); // Observer handles logging
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
        $chapter->delete(); // Observer handles logging
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
        $page->delete(); // Observer handles logging

        return redirect()->route('wikis.showBook', $bookId)->with('success', "Page '{$title}' soft-deleted.");
    }
}
