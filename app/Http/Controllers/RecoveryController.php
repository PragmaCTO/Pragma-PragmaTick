<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\ActivityLog;
use App\Models\ChecklistItem;
use App\Models\Milestone;
use App\Models\Organization;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\WikiBook;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RecoveryController extends Controller
{
    /**
     * Enforce strict Super Admin restriction.
     */
    protected function checkSuperAdmin(): User
    {
        /** @var User $user */
        $user = auth()->user();

        if (!$user || !$user->isSuperAdmin()) {
            abort(403, 'Forbidden: The System Recovery & Trash Bin module is strictly restricted to Super Admins only.');
        }

        return $user;
    }

    /**
     * Display System Recovery & Trash Bin browser (Super Admin only).
     */
    public function index(Request $request)
    {
        $user = $this->checkSuperAdmin();

        $actionFilter = $request->input('action');
        $logQuery = ActivityLog::with(['user', 'subject'])->latest();

        if ($actionFilter) {
            $logQuery->where('action', $actionFilter);
        }

        $logs = $logQuery->paginate(15);

        // Aggregate Soft-Deleted Records Browser
        $deletedRecords = collect();
        foreach (Organization::onlyTrashed()->get() as $item) {
            $deletedRecords->push(['type' => 'organization', 'id' => $item->id, 'name' => $item->name, 'deleted_at' => $item->deleted_at]);
        }
        foreach (Project::onlyTrashed()->get() as $item) {
            $deletedRecords->push(['type' => 'project', 'id' => $item->id, 'name' => $item->name, 'deleted_at' => $item->deleted_at]);
        }
        foreach (Milestone::onlyTrashed()->get() as $item) {
            $deletedRecords->push(['type' => 'milestone', 'id' => $item->id, 'name' => $item->title, 'deleted_at' => $item->deleted_at]);
        }
        foreach (Task::onlyTrashed()->get() as $item) {
            $deletedRecords->push(['type' => 'task', 'id' => $item->id, 'name' => $item->title, 'deleted_at' => $item->deleted_at]);
        }
        foreach (WikiBook::onlyTrashed()->get() as $item) {
            $deletedRecords->push(['type' => 'wikibook', 'id' => $item->id, 'name' => $item->title, 'deleted_at' => $item->deleted_at]);
        }
        foreach (User::onlyTrashed()->get() as $item) {
            $deletedRecords->push(['type' => 'user', 'id' => $item->id, 'name' => $item->name, 'deleted_at' => $item->deleted_at]);
        }
        foreach (ChecklistItem::onlyTrashed()->get() as $item) {
            $deletedRecords->push(['type' => 'checklist', 'id' => $item->id, 'name' => $item->title, 'deleted_at' => $item->deleted_at]);
        }
        foreach (Comment::onlyTrashed()->get() as $item) {
            $deletedRecords->push(['type' => 'comment', 'id' => $item->id, 'name' => Str::limit($item->content, 20), 'deleted_at' => $item->deleted_at]);
        }

        return view('recovery.index', compact('logs', 'deletedRecords', 'actionFilter', 'user'));
    }

    /**
     * Physical restoration of a soft-deleted record (Super Admin only).
     */
    public function restore(Request $request)
    {
        $user = $this->checkSuperAdmin();

        $typeInput = $request->input('type') ?? $request->input('model_type');
        $idInput = $request->input('id');

        if (!$typeInput || !$idInput) {
            abort(400, 'Missing type or id for restoration.');
        }

        $modelMap = [
            'organization' => Organization::class,
            'project' => Project::class,
            'milestone' => Milestone::class,
            'task' => Task::class,
            'wikibook' => WikiBook::class,
            'user' => User::class,
            'checklist' => ChecklistItem::class,
            'comment' => Comment::class,
        ];

        $typeKey = strtolower($typeInput);
        if (!array_key_exists($typeKey, $modelMap)) {
            abort(400, 'Invalid model type for restoration.');
        }

        $modelClass = $modelMap[$typeKey];
        $record = $modelClass::onlyTrashed()->findOrFail($idInput);

        $name = $record->name ?? $record->title ?? "ID #{$record->id}";

        // Execute Physical Restoration
        $record->restore();

        $user->logActivity('restored', "Restored soft-deleted {$typeKey} '{$name}'", $record);

        return redirect()->back()->with('success', "Successfully restored {$typeKey} '{$name}'.");
    }
}
