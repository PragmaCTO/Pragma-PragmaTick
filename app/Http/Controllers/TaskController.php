<?php

namespace App\Http\Controllers;

use App\Models\Milestone;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    /**
     * Display the Kanban Board and Tabular view for a project.
     */
    public function kanban(Project $project)
    {
        $user = auth()->user();

        if ($user && !Gate::allows('view', $project)) {
            abort(403, 'Unauthorized to view this project.');
        }

        // Ensure default 8 Kanban status columns exist
        $project->ensureDefaultStatuses();

        $project->load([
            'organization',
            'statuses',
            'milestones.assignees',
            'tasks' => fn($q) => $q->with(['assignees', 'parent', 'subtasks', 'milestone', 'comments.user'])->withCount(['assignees', 'comments']),
            'users'
        ]);

        $statuses = $project->statuses;
        $milestones = $project->milestones;
        $allTasks = $project->tasks;
        $allProjectTasks = $allTasks;
        $allProjectUsers = $project->users;
        $activeTab = request()->query('tab', 'kanban');
        $tasksByStatus = $allTasks->groupBy('status');

        return view('projects.kanban', compact(
            'project',
            'statuses',
            'milestones',
            'allTasks',
            'allProjectTasks',
            'allProjectUsers',
            'activeTab',
            'tasksByStatus',
            'user'
        ));
    }

    /**
     * Store a newly created task.
     */
    public function store(Request $request, Project $project)
    {
        $user = auth()->user();
        if (!$user || !Gate::allows('create', [Task::class, $project])) {
            abort(403, 'Unauthorized to create tasks in this project.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:bug,feature,documentation,operation',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|string',
            'milestone_id' => 'nullable|exists:milestones,id',
            'parent_id' => 'nullable|exists:tasks,id',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
            'assignees' => 'nullable|array',
            'assignees.*' => 'exists:users,id',
        ]);

        $latestTask = Task::withTrashed()->where('project_id', $project->id)->orderBy('id', 'desc')->first();
        $nextNumber = 1;
        if ($latestTask && $latestTask->code) {
            $parts = explode('-', $latestTask->code);
            $nextNumber = (int)end($parts) + 1;
        }
        $code = ($project->abbreviation ?: 'TASK') . '-' . $nextNumber;

        $task = Task::create([
            'project_id' => $project->id,
            'milestone_id' => $validated['milestone_id'] ?? null,
            'parent_id' => $validated['parent_id'] ?? null,
            'assigned_to' => !empty($validated['assignees']) ? $validated['assignees'][0] : null,
            'code' => $code,
            'title' => $validated['title'],
            'type' => $validated['type'],
            'description' => $validated['description'] ?? null,
            'priority' => $validated['priority'],
            'status' => $validated['status'],
            'start_date' => $validated['start_date'] ?? null,
            'due_date' => $validated['due_date'] ?? null,
        ]);

        if (!empty($validated['assignees'])) {
            $task->assignees()->sync($validated['assignees']);
        }

        $user->logActivity('created', "Created task {$task->code}: {$task->title}", $task, [
            'type' => $task->type,
            'priority' => $task->priority,
            'status' => $task->status,
        ]);

        return redirect()->back()->with('success', "Task {$task->code} created successfully.");
    }

    /**
     * Update an existing task.
     */
    public function update(Request $request, Task $task)
    {
        $user = auth()->user();
        if (!$user || !Gate::allows('update', $task)) {
            abort(403, 'Unauthorized to update this task.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:bug,feature,documentation,operation',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|string',
            'milestone_id' => 'nullable|exists:milestones,id',
            'parent_id' => 'nullable|exists:tasks,id',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'assignees' => 'nullable|array',
            'assignees.*' => 'exists:users,id',
        ]);

        $task->update([
            'title' => $validated['title'],
            'type' => $validated['type'],
            'description' => $validated['description'] ?? null,
            'priority' => $validated['priority'],
            'status' => $validated['status'],
            'milestone_id' => $validated['milestone_id'] ?? null,
            'parent_id' => $validated['parent_id'] ?? null,
            'start_date' => $validated['start_date'] ?? null,
            'due_date' => $validated['due_date'] ?? null,
            'assigned_to' => !empty($validated['assignees']) ? $validated['assignees'][0] : null,
        ]);

        if (isset($validated['assignees'])) {
            $task->assignees()->sync($validated['assignees']);
        }

        $user->logActivity('updated', "Updated task {$task->code}", $task);

        return redirect()->back()->with('success', "Task {$task->code} updated successfully.");
    }

    /**
     * AJAX endpoint to update task status column during drag-and-drop.
     */
    public function updateStatus(Request $request, Task $task)
    {
        $user = auth()->user();
        if (!$user || !Gate::allows('update', $task)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'status' => 'required|string',
        ]);

        $oldStatus = $task->status;
        $task->status = $validated['status'];
        $task->save();

        $user->logActivity('moved_task', "Moved task {$task->code} from {$oldStatus} to {$task->status}", $task, [
            'old_status' => $oldStatus,
            'new_status' => $task->status,
        ]);

        return response()->json([
            'success' => true,
            'task_code' => $task->code,
            'new_status' => $task->status,
        ]);
    }

    /**
     * Soft delete task (Restricted to Admins).
     */
    public function destroy(Task $task)
    {
        $user = auth()->user();
        if (!$user || !Gate::allows('delete', $task)) {
            abort(403, 'Unauthorized to delete tasks in this project.');
        }

        $taskCode = $task->code;
        $task->delete();
        $user->logActivity('deleted', "Soft-deleted task {$taskCode}", $task);

        return redirect()->back()->with('success', "Task {$taskCode} soft-deleted.");
    }
}
