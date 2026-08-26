<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProjectRequest;
use App\Models\Organization;
use App\Models\Project;
use App\Models\User;
use App\Services\ProjectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProjectController extends Controller
{
    protected ProjectService $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    /**
     * Display a listing of projects grouped/sorted by organization with scoped visibility.
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = auth()->user();

        if ($user->isSuperAdmin()) {
            $projects = Project::with(['organization', 'users'])->withCount(['tasks', 'milestones'])->get();
            $organizations = Organization::with('projects')->get();
        } else {
            $adminOrgIds = $user->organizations()->wherePivot('role', 'org_admin')->pluck('organizations.id');
            $assignedProjectIds = $user->projects()->pluck('projects.id');

            $projects = Project::whereIn('organization_id', $adminOrgIds)
                ->orWhereIn('id', $assignedProjectIds)
                ->with(['organization', 'users'])
                ->withCount(['tasks', 'milestones'])
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
    public function store(UpdateProjectRequest $request)
    {
        $project = $this->projectService->createProject($request->validated(), auth()->user());
        return redirect()->route('projects.index')->with('success', 'Project created successfully.');
    }

    /**
     * Update specified project details.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $this->projectService->updateProject($project, $request->validated(), auth()->user());
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

        $project->load(['organization', 'users', 'milestones', 'tasks.assignee', 'comments.user']);
        $project->loadCount(['tasks', 'milestones']);
        $allUsers = User::orderBy('name')->get();

        return view('projects.show', compact('project', 'allUsers', 'user'));
    }

    /**
     * Add existing user to project with role & position.
     * Project Admins, Org Admins, and Super Admins can add project members.
     */
    public function addMember(UpdateProjectRequest $request, Project $project)
    {
        $this->projectService->addMember($project, $request->validated(), auth()->user());
        return redirect()->back()->with('success', "User assigned to project successfully.");
    }

    /**
     * Remove user from project.
     */
    public function removeMember(Project $project, User $targetUser)
    {
        $this->projectService->removeMember($project, $targetUser, auth()->user());
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
        $project->delete(); // Observer handles the logActivity
        return redirect()->route('projects.index')->with('success', "Project {$projectName} soft-deleted.");
    }
}
