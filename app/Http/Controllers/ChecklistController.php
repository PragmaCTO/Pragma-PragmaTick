<?php

namespace App\Http\Controllers;

use App\Models\ChecklistItem;
use App\Models\User;
use Illuminate\Http\Request;

class ChecklistController extends Controller
{
    /**
     * Display private personal checklist (Tabular & Kanban views).
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = auth()->user();

        $viewMode = $request->input('view', 'kanban'); // 'kanban' or 'table'

        $items = $user->checklistItems()->latest()->get();

        // 4 Strict Kanban Statuses: To-Do, In-Progress, Completed, Delayed
        $statuses = ['To-Do', 'In-Progress', 'Completed', 'Delayed'];
        $itemsByStatus = [];

        foreach ($statuses as $st) {
            $itemsByStatus[$st] = $items->where('status', $st);
        }

        return view('checklist.index', compact('items', 'itemsByStatus', 'statuses', 'viewMode', 'user'));
    }

    /**
     * Store new private checklist item.
     */
    public function store(Request $request)
    {
        /** @var User $user */
        $user = auth()->user();
        if (!$user) abort(403, 'Unauthorized.');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:To-Do,In-Progress,Completed,Delayed',
        ]);

        $item = $user->checklistItems()->create($validated);
        $user->logActivity('created', "Created checklist item '{$item->title}'", $item);

        return redirect()->back()->with('success', "Checklist item '{$item->title}' created.");
    }

    /**
     * Update existing checklist item.
     */
    public function update(Request $request, ChecklistItem $item)
    {
        /** @var User $user */
        $user = auth()->user();
        if (!$user || $item->user_id !== $user->id) {
            abort(403, 'Unauthorized.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:To-Do,In-Progress,Completed,Delayed',
        ]);

        $item->update($validated);
        $user->logActivity('updated', "Updated checklist item '{$item->title}'", $item);

        return redirect()->back()->with('success', "Checklist item '{$item->title}' updated.");
    }

    /**
     * AJAX drag and drop status update for Kanban board.
     */
    public function updateStatus(Request $request, ChecklistItem $item)
    {
        /** @var User $user */
        $user = auth()->user();
        if (!$user || $item->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'status' => 'required|in:To-Do,In-Progress,Completed,Delayed',
        ]);

        $item->update(['status' => $validated['status']]);
        $user->logActivity('updated_status', "Moved checklist item '{$item->title}' to {$item->status}", $item);

        return response()->json([
            'success' => true,
            'new_status' => $item->status,
        ]);
    }

    /**
     * Soft delete checklist item.
     */
    public function destroy(ChecklistItem $item)
    {
        /** @var User $user */
        $user = auth()->user();
        if (!$user || $item->user_id !== $user->id) {
            abort(403, 'Unauthorized.');
        }

        $title = $item->title;
        $item->delete();
        $user->logActivity('deleted', "Soft-deleted checklist item '{$title}'", $item);

        return redirect()->back()->with('success', "Checklist item '{$title}' soft-deleted.");
    }
}
