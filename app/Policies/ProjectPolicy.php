<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    /**
     * Determine whether the user can view any projects.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the project.
     */
    public function view(User $user, Project $project): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if ($user->isOrgAdmin($project->organization_id)) {
            return true;
        }

        return $user->roleInProject($project) !== null;
    }

    /**
     * Determine whether the user can create projects within an organization.
     */
    public function create(User $user, Organization $organization): bool
    {
        return $user->isOrgAdmin($organization);
    }

    /**
     * Determine whether the user can update the project.
     */
    public function update(User $user, Project $project): bool
    {
        return $user->isProjectAdmin($project);
    }

    /**
     * Determine whether the user can manage members in the project.
     * Project Admins, Org Admins, and Super Admins can add/remove project members.
     */
    public function manageMembers(User $user, Project $project): bool
    {
        return $user->isProjectAdmin($project);
    }

    /**
     * Determine whether the user can delete the project.
     * Org Admins and Super Admins can delete projects.
     */
    public function delete(User $user, Project $project): bool
    {
        return $user->isOrgAdmin($project->organization_id);
    }
}
