<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\Organization;
use App\Models\Project;
use App\Models\User;
use App\Models\WikiBook;
use App\Models\WikiChapter;
use App\Models\WikiPage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WikiModuleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Book -> Chapter -> Page hierarchy creation.
     */
    public function test_wiki_book_chapter_page_hierarchy_creation(): void
    {
        $author = User::factory()->superAdmin()->create();
        $org = Organization::factory()->create();

        // 1. Create Book
        $bookResponse = $this->actingAs($author)->post(route('wikis.storeBook'), [
            'title' => 'Engineering Manual',
            'description' => 'Core technical guidelines',
            'owner_kind' => 'organization',
            'owner_id' => $org->id,
        ]);
        $bookResponse->assertRedirect();
        $this->assertDatabaseHas('wiki_books', ['title' => 'Engineering Manual', 'owner_id' => $org->id]);

        $book = WikiBook::where('title', 'Engineering Manual')->first();

        // 2. Create Chapter
        $chapResponse = $this->actingAs($author)->post(route('wikis.storeChapter', $book), [
            'title' => 'Chapter 1: Getting Started',
            'description' => 'Setup guide',
        ]);
        $chapResponse->assertRedirect();
        $this->assertDatabaseHas('wiki_chapters', ['wiki_book_id' => $book->id, 'title' => 'Chapter 1: Getting Started']);

        $chapter = WikiChapter::where('title', 'Chapter 1: Getting Started')->first();

        // 3. Create Page
        $pageResponse = $this->actingAs($author)->post(route('wikis.pages.store', $chapter), [
            'title' => 'Installation & Setup',
            'content' => "# Setup\n\n```mermaid\ngraph TD\n    A --> B\n```",
        ]);
        $pageResponse->assertRedirect();
        $this->assertDatabaseHas('wiki_pages', ['wiki_chapter_id' => $chapter->id, 'title' => 'Installation & Setup']);
    }

    /**
     * Test Polymorphic Wiki ownership for Organization, Project, and Private books.
     */
    public function test_polymorphic_wiki_ownership(): void
    {
        $user = User::factory()->superAdmin()->create();
        $org = Organization::factory()->create(['name' => 'Acme Corp']);
        $proj = Project::factory()->create(['name' => 'Project X']);

        // Org Book
        $orgBook = WikiBook::create([
            'author_id' => $user->id,
            'owner_type' => Organization::class,
            'owner_id' => $org->id,
            'title' => 'Org Book',
            'slug' => 'org-book',
        ]);

        // Project Book
        $projBook = WikiBook::create([
            'author_id' => $user->id,
            'owner_type' => Project::class,
            'owner_id' => $proj->id,
            'title' => 'Proj Book',
            'slug' => 'proj-book',
        ]);

        // Private Book
        $privateBook = WikiBook::create([
            'author_id' => $user->id,
            'owner_type' => null,
            'owner_id' => null,
            'title' => 'Private Book',
            'slug' => 'private-book',
            'is_private' => true,
        ]);

        $this->assertEquals(Organization::class, $orgBook->owner_type);
        $this->assertEquals($org->id, $orgBook->owner->id);

        $this->assertEquals(Project::class, $projBook->owner_type);
        $this->assertEquals($proj->id, $projBook->owner->id);

        $this->assertNull($privateBook->owner_type);
        $this->assertTrue($privateBook->is_private);
    }

    /**
     * Test Private Wiki sharing with specific users.
     */
    public function test_private_wiki_sharing_with_specific_users(): void
    {
        $author = User::factory()->create(['name' => 'Author']);
        $friend = User::factory()->create(['name' => 'Friend']);

        $privateBook = WikiBook::create([
            'author_id' => $author->id,
            'title' => 'Top Secret Docs',
            'slug' => 'top-secret-docs',
            'is_private' => true,
        ]);

        $shareResponse = $this->actingAs($author)->post(route('wikis.shareBook', $privateBook), [
            'user_id' => $friend->id,
        ]);

        $shareResponse->assertRedirect();
        $this->assertDatabaseHas('wiki_book_user', [
            'wiki_book_id' => $privateBook->id,
            'user_id' => $friend->id,
        ]);

        $this->assertTrue($friend->can('view', $privateBook));
    }

    /**
     * Test Soft Deletes and Activity Log tracking on WikiBooks, Chapters, and Pages.
     */
    public function test_soft_delete_and_activity_log_tracking_on_wikis(): void
    {
        $author = User::factory()->superAdmin()->create();
        $book = WikiBook::create([
            'author_id' => $author->id,
            'title' => 'Test Book',
            'slug' => 'test-book',
        ]);
        $chap = WikiChapter::create([
            'wiki_book_id' => $book->id,
            'title' => 'Test Chapter',
            'slug' => 'test-chapter',
        ]);
        $page = WikiPage::create([
            'wiki_chapter_id' => $chap->id,
            'author_id' => $author->id,
            'title' => 'Test Page',
            'slug' => 'test-page',
            'content' => 'Sample content',
        ]);

        // Delete Page
        $resPage = $this->actingAs($author)->delete(route('wikis.destroyPage', $page));
        $resPage->assertRedirect();
        $this->assertSoftDeleted($page);

        // Delete Chapter
        $resChap = $this->actingAs($author)->delete(route('wikis.destroyChapter', $chap));
        $resChap->assertRedirect();
        $this->assertSoftDeleted($chap);

        // Delete Book
        $resBook = $this->actingAs($author)->delete(route('wikis.destroyBook', $book));
        $resBook->assertRedirect();
        $this->assertSoftDeleted($book);

        // Verify Activity Logs recorded
        $this->assertDatabaseHas('activity_logs', ['action' => 'deleted', 'user_id' => $author->id]);
    }
}
