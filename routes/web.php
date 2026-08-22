<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MilestoneController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectStatusController;
use App\Http\Controllers\RecoveryController;
use App\Http\Controllers\TaskCommentController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WikiController;
use Illuminate\Support\Facades\Route;

// Emergency Cache Clearing Utility (Pure Native PHP - No DOMDocument or Termwind needed)
Route::get('/clear-cache', function () {
    // 1. Remove bootstrap cached config/route files
    $cachedFiles = [
        base_path('bootstrap/cache/config.php'),
        base_path('bootstrap/cache/routes-v7.php'),
        base_path('bootstrap/cache/events.php'),
        base_path('bootstrap/cache/packages.php'),
        base_path('bootstrap/cache/services.php'),
    ];

    foreach ($cachedFiles as $file) {
        if (file_exists($file)) {
            @unlink($file);
        }
    }

    // 2. Clean compiled Blade views
    $viewPath = storage_path('framework/views');
    if (file_exists($viewPath)) {
        foreach (glob("$viewPath/*.php") as $vFile) {
            @unlink($vFile);
        }
    }

    // 3. Clean cache storage data
    $cachePath = storage_path('framework/cache/data');
    if (file_exists($cachePath)) {
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($cachePath, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($files as $fileinfo) {
            $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
            @$todo($fileinfo->getRealPath());
        }
    }

    return response('Native PHP Cache Cleaned Successfully! All cached config, views, and routes have been purged. You can now log in.', 200);
});

// Authentication Routes (Guest Only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated Application Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Home / Overview Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Super Admin & Org Admin User Administration Routes
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Organization Module Routes
    Route::get('/organizations', [OrganizationController::class, 'index'])->name('organizations.index');
    Route::post('/organizations', [OrganizationController::class, 'store'])->name('organizations.store');
    Route::put('/organizations/{organization}', [OrganizationController::class, 'update'])->name('organizations.update');
    Route::get('/organizations/{organization}', [OrganizationController::class, 'show'])->name('organizations.show');
    Route::post('/organizations/{organization}/members', [OrganizationController::class, 'addMember'])->name('organizations.addMember');
    Route::delete('/organizations/{organization}/members/{targetUser}', [OrganizationController::class, 'removeMember'])->name('organizations.removeMember');
    Route::delete('/organizations/{organization}', [OrganizationController::class, 'destroy'])->name('organizations.destroy');

    // Project Module Routes
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
    Route::post('/projects/{project}/members', [ProjectController::class, 'addMember'])->name('projects.addMember');
    Route::delete('/projects/{project}/members/{targetUser}', [ProjectController::class, 'removeMember'])->name('projects.removeMember');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');

    // Kanban & Tabular Task Engine Routes
    Route::get('/projects/{project}/kanban', [TaskController::class, 'kanban'])->name('projects.kanban');
    Route::post('/projects/{project}/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::post('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

    // Milestone Routes
    Route::post('/projects/{project}/milestones', [MilestoneController::class, 'store'])->name('milestones.store');
    Route::put('/milestones/{milestone}', [MilestoneController::class, 'update'])->name('milestones.update');
    Route::delete('/milestones/{milestone}', [MilestoneController::class, 'destroy'])->name('milestones.destroy');

    // Task Comments Route
    Route::post('/tasks/{task}/comments', [TaskCommentController::class, 'store'])->name('comments.store');

    // Kanban Custom Status Columns Routes
    Route::post('/projects/{project}/statuses', [ProjectStatusController::class, 'store'])->name('statuses.store');
    Route::delete('/statuses/{status}', [ProjectStatusController::class, 'destroy'])->name('statuses.destroy');

    // Multi-Tiered Wiki Module Routes (Book -> Chapter -> Page)
    Route::get('/wikis', [WikiController::class, 'index'])->name('wikis.index');
    Route::post('/wikis/books', [WikiController::class, 'storeBook'])->name('wikis.storeBook');
    Route::get('/wikis/books/{book}', [WikiController::class, 'showBook'])->name('wikis.showBook');
    Route::post('/wikis/books/{book}/chapters', [WikiController::class, 'storeChapter'])->name('wikis.storeChapter');
    Route::post('/wikis/books/{book}/share', [WikiController::class, 'shareBook'])->name('wikis.shareBook');
    Route::delete('/wikis/books/{book}', [WikiController::class, 'destroyBook'])->name('wikis.destroyBook');

    Route::get('/wikis/chapters/{chapter}/pages/create', [WikiController::class, 'createPage'])->name('wikis.pages.create');
    Route::post('/wikis/chapters/{chapter}/pages', [WikiController::class, 'storePage'])->name('wikis.pages.store');
    Route::delete('/wikis/chapters/{chapter}', [WikiController::class, 'destroyChapter'])->name('wikis.destroyChapter');

    Route::get('/wikis/pages/{page}', [WikiController::class, 'showPage'])->name('wikis.showPage');
    Route::get('/wikis/pages/{page}/edit', [WikiController::class, 'editPage'])->name('wikis.pages.edit');
    Route::put('/wikis/pages/{page}', [WikiController::class, 'updatePage'])->name('wikis.pages.update');
    Route::delete('/wikis/pages/{page}', [WikiController::class, 'destroyPage'])->name('wikis.destroyPage');

    // Calendar Scheduling Routes
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
    Route::post('/calendar', [CalendarController::class, 'store'])->name('calendar.store');
    Route::get('/calendar/{event}', [CalendarController::class, 'show'])->name('calendar.show');
    Route::delete('/calendar/{event}', [CalendarController::class, 'destroy'])->name('calendar.destroy');

    // External Contacts Routes (Strictly Restricted to Super Admins Only)
    Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');
    Route::post('/contacts', [ContactController::class, 'store'])->name('contacts.store');
    Route::put('/contacts/{contact}', [ContactController::class, 'update'])->name('contacts.update');
    Route::delete('/contacts/{contact}', [ContactController::class, 'destroy'])->name('contacts.destroy');

    // Personal Private Checklist Routes
    Route::get('/checklist', [ChecklistController::class, 'index'])->name('checklist.index');
    Route::post('/checklist', [ChecklistController::class, 'store'])->name('checklist.store');
    Route::put('/checklist/{item}', [ChecklistController::class, 'update'])->name('checklist.update');
    Route::post('/checklist/{item}/status', [ChecklistController::class, 'updateStatus'])->name('checklist.updateStatus');
    Route::delete('/checklist/{item}', [ChecklistController::class, 'destroy'])->name('checklist.destroy');

    // System Recovery & Activity Logs Routes (Strictly Restricted to Super Admins Only)
    Route::get('/recovery', [RecoveryController::class, 'index'])->name('recovery.index');
    Route::post('/recovery/restore', [RecoveryController::class, 'restore'])->name('recovery.restore');

    // User Settings Route
    Route::get('/settings', [ProfileController::class, 'edit'])->name('settings.edit');
    Route::put('/settings', [ProfileController::class, 'update'])->name('settings.update');
});
