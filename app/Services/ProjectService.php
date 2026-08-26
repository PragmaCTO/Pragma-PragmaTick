<?php

namespace App\Services;

use App\Models\Organization;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class ProjectService
{
    public function createProject(array $validated, User $user): Project
    {
        $organization = Organization::findOrFail($validated['organization_id']);
        
        if (!Gate::forUser($user)->allows('create', [Project::class, $organization])) {
            abort(403, 'Unauthorized to create projects in this organization.');
        }

        return Project::create([
            'organization_id' => $validated['organization_id'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'abbreviation' => strtoupper($validated['abbreviation']),
            'start_date' => $validated['start_date'] ?? null,
            'due_date' => $validated['due_date'] ?? null,
        ]);
    }

    public function updateProject(Project $project, array $validated, User $user): void
    {
        if (!Gate::forUser($user)->allows('update', $project)) {
            abort(403, 'Unauthorized to update this project.');
        }

        $project->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'abbreviation' => strtoupper($validated['abbreviation']),
            'start_date' => $validated['start_date'] ?? null,
            'due_date' => $validated['due_date'] ?? null,
        ]);
    }

    public function addMember(Project $project, array $validated, User $user): void
    {
        if (!Gate::forUser($user)->allows('manageMembers', $project)) {
            abort(403, 'Unauthorized to manage members in this project.');
        }

        $targetUser = User::findOrFail($validated['user_id']);

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
    }

    public function removeMember(Project $project, User $targetUser, User $user): void
    {
        if (!Gate::forUser($user)->allows('manageMembers', $project)) {
            abort(403, 'Unauthorized to remove members from this project.');
        }

        $project->users()->detach($targetUser->id);
        
        $user->logActivity('removed_member', "Removed {$targetUser->name} from project {$project->name}", $project);
    }
}
