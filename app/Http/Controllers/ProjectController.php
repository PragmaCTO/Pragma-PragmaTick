<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProjectController extends Controller
{
    /**
     * Display a listing of projects grouped/sorted by organization with scoped visibility.
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = auth()->user();

        if ($user->isSuperAdmin()) {
            $projects = Project::with(['organization', 'users', 'tasks', 'milestones'])->get();
            $organizations = Organization::with('projects')->get();
        } else {
            // Org Admins see all projects in their assigned orgs
            $adminOrgIds = $user->organizations()->wherePivot('role', 'org_admin')->pluck('organizations.id');

            // Member assigned project IDs
            $assignedProjectIds = $user->projects()->pluck('projects.id');

            $projects = Project::whereIn('organization_id', $adminOrgIds)
                ->orWhereIn('id', $assignedProjectIds)
                ->with(['organization', 'users', 'tasks', 'milestones'])
                ->get();

            $organizations = Organization::whereIn('id', $adminOrgIds)
                ->orWhereIn('id', $projects->pluck('organization_id'))
                ->get();
        }

        $canCreateProjects = $user->isSuperAdmin() || $user->organizations()->wherePivot('role', 'org_admin')->exists();

        $projectsByOrg = [];
        foreach ($organizations as $org) {
            $orgProjects = $projects->where('organization_id', $org->id);
            if ($user->isSuperAdmin() || $orgProjects->count() > 0 || $user->isOrgAdmin($org->id)) {
                $projectsByOrg[$org->id] = [
                    'organization' => $org,
                    'projects' => $orgProjects,
                ];
            }
        }

        return view('projects.index', compact('projectsByOrg', 'canCreateProjects', 'organizations', 'user'));
    }

    /**
     * Store a newly created project (Org Admin & Super Admin).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'organization_id' => 'required|exists:organizations,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'abbreviation' => 'required|string|max:10|alpha_dash',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $organization = Organization::findOrFail($validated['organization_id']);

        $user = auth()->user();
        if (!$user || !Gate::allows('create', [Project::class, $organization])) {
            abort(403, 'Unauthorized to create projects in this organization.');
        }

        $project = Project::create([
            'organization_id' => $validated['organization_id'],
            'name' => $validated['name'],
            'description' => $validated['description'],
            'abbreviation' => strtoupper($validated['abbreviation']),
            'start_date' => $validated['start_date'] ?? null,
            'due_date' => $validated['due_date'] ?? null,
        ]);

        $user->logActivity('created', "Created project {$project->name} ({$project->abbreviation})", $project);

        return redirect()->route('projects.index')->with('success', 'Project created successfully.');
    }

    /**
     * Update specified project details.
     */
    public function update(Request $request, Project $project)
    {
        $user = auth()->user();
        if (!$user || !Gate::allows('update', $project)) {
            abort(403, 'Unauthorized to update this project.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'abbreviation' => 'required|string|max:10',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $project->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'abbreviation' => strtoupper($validated['abbreviation']),
            'start_date' => $validated['start_date'] ?? null,
            'due_date' => $validated['due_date'] ?? null,
        ]);

        $user->logActivity('updated', "Updated project details for {$project->name}", $project);

        return redirect()->back()->with('success', "Project '{$project->name}' updated successfully.");
    }

    /**
     * Display the specified project with tasks formatted with prefixes (e.g. PRAG-1).
     */
    public function show(Project $project)
    {
        $user = auth()->user();
        if ($user && !Gate::allows('view', $project)) {
            abort(403, 'Unauthorized to view this project.');
        }

        $project->load(['organization', 'users', 'milestones', 'tasks.assignee']);
        $allUsers = User::orderBy('name')->get();

        return view('projects.show', compact('project', 'allUsers', 'user'));
    }

    /**
     * Add existing user to project with role & position.
     * Project Admins, Org Admins, and Super Admins can add project members.
     */
    public function addMember(Request $request, Project $project)
    {
        $user = auth()->user();
        if (!$user || !Gate::allows('manageMembers', $project)) {
            abort(403, 'Unauthorized to manage members in this project.');
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:project_admin,member',
            'position' => 'nullable|string|max:255',
        ]);

        $targetUser = User::findOrFail($validated['user_id']);

        // Sync/Attach with pivot role and position
        $project->users()->syncWithoutDetaching([
            $targetUser->id => [
                'role' => $validated['role'],
                'position' => $validated['position'] ?? 'Team Member',
            ]
        ]);

        $user->logActivity('assigned_role', "Assigned {$targetUser->name} as {$validated['role']} in {$project->name}", $project, [
            'target_user_id' => $targetUser->id,
            'role' => $validated['role'],
            'position' => $validated['position'] ?? 'Team Member',
        ]);

        return redirect()->back()->with('success', "{$targetUser->name} assigned to project successfully.");
    }

    /**
     * Remove user from project.
     */
    public function removeMember(Project $project, User $targetUser)
    {
        $user = auth()->user();
        if (!$user || !Gate::allows('manageMembers', $project)) {
            abort(403, 'Unauthorized to remove members from this project.');
        }

        $project->users()->detach($targetUser->id);
        $user->logActivity('removed_member', "Removed {$targetUser->name} from project {$project->name}", $project);

        return redirect()->back()->with('success', "Member removed from project.");
    }

    /**
     * Soft delete project.
     */
    public function destroy(Project $project)
    {
        $user = auth()->user();
        if (!$user || !Gate::allows('delete', $project)) {
            abort(403, 'Unauthorized to delete this project.');
        }

        $projectName = $project->name;
        $project->delete();
        $user->logActivity('deleted', "Soft-deleted project {$projectName}", $project);

        return redirect()->route('projects.index')->with('success', "Project {$projectName} soft-deleted.");
    }
}
