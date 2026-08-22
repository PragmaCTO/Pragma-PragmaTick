<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectStatusController extends Controller
{
    /**
     * Create a custom Kanban status column for a project.
     */
    public function store(Request $request, Project $project)
    {
        /** @var User $user */
        $user = auth()->user();
        if (!$user || !$user->isProjectAdmin($project)) {
            abort(403, 'Unauthorized to customize columns in this project.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:7',
        ]);

        $maxOrder = $project->statuses()->max('order') ?? 0;
        $slug = Str::slug($validated['name']);

        if ($project->statuses()->where('slug', $slug)->exists()) {
            return redirect()->back()->withErrors(['name' => 'A status column with this name already exists in the project.']);
        }

        $status = $project->statuses()->create([
            'name' => $validated['name'],
            'slug' => $slug,
            'color' => $validated['color'],
            'is_mandatory' => false,
            'order' => $maxOrder + 1,
        ]);

        $user->logActivity('created', "Added custom Kanban column status '{$status->name}' to project {$project->name}", $project);

        return redirect()->back()->with('success', "Status column '{$status->name}' added to project.");
    }

    /**
     * Delete a Kanban column status (Blocking deletion of mandatory columns).
     */
    public function destroy(ProjectStatus $status)
    {
        /** @var User $user */
        $user = auth()->user();
        if (!$user || !$user->isProjectAdmin($status->project)) {
            abort(403, 'Unauthorized to modify columns in this project.');
        }

        // Constraint check: Mandatory columns (New, In-Progress, Completed, On Hold) CANNOT be deleted!
        if ($status->is_mandatory) {
            return redirect()->back()->withErrors([
                'error' => "Cannot delete mandatory status column '{$status->name}'. Mandatory columns (New, In-Progress, Completed, On Hold) are required per project."
            ]);
        }

        $name = $status->name;
        $project = $status->project;
        $status->delete();

        $user->logActivity('deleted', "Deleted custom Kanban column status '{$name}' from project {$project->name}", $project);

        return redirect()->back()->with('success', "Status column '{$name}' removed.");
    }
}
