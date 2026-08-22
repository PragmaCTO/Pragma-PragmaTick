<?php

namespace App\Http\Controllers;

use App\Models\Milestone;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class MilestoneController extends Controller
{
    /**
     * Store a newly created milestone with multiple assignees.
     */
    public function store(Request $request, Project $project)
    {
        $user = auth()->user();
        if (!$user || !Gate::allows('manage', [Milestone::class, $project])) {
            abort(403, 'Unauthorized to create milestones in this project.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:open,in_progress,completed,closed',
            'assignees' => 'nullable|array',
            'assignees.*' => 'exists:users,id',
        ]);

        $milestone = Milestone::create([
            'project_id' => $project->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'start_date' => $validated['start_date'] ?? null,
            'due_date' => $validated['due_date'] ?? null,
            'status' => $validated['status'],
        ]);

        if (!empty($validated['assignees'])) {
            $milestone->assignees()->sync($validated['assignees']);
        }

        $user->logActivity('created', "Created milestone {$milestone->title}", $milestone);

        return redirect()->back()->with('success', "Milestone '{$milestone->title}' created successfully.");
    }

    /**
     * Update an existing milestone.
     */
    public function update(Request $request, Milestone $milestone)
    {
        $user = auth()->user();
        if (!$user || !Gate::allows('manage', [Milestone::class, $milestone->project])) {
            abort(403, 'Unauthorized to update this milestone.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'status' => 'required|in:open,in_progress,completed,closed',
            'assignees' => 'nullable|array',
            'assignees.*' => 'exists:users,id',
        ]);

        $milestone->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'start_date' => $validated['start_date'] ?? null,
            'due_date' => $validated['due_date'] ?? null,
            'status' => $validated['status'],
        ]);

        if (isset($validated['assignees'])) {
            $milestone->assignees()->sync($validated['assignees']);
        }

        $user->logActivity('updated', "Updated milestone {$milestone->title}", $milestone);

        return redirect()->back()->with('success', "Milestone '{$milestone->title}' updated successfully.");
    }

    /**
     * Soft delete milestone.
     */
    public function destroy(Milestone $milestone)
    {
        $user = auth()->user();
        if (!$user || !Gate::allows('manage', [Milestone::class, $milestone->project])) {
            abort(403, 'Unauthorized to delete this milestone.');
        }

        $title = $milestone->title;
        $milestone->delete();
        $user->logActivity('deleted', "Soft-deleted milestone {$title}", $milestone);

        return redirect()->back()->with('success', "Milestone '{$title}' soft-deleted.");
    }
}
