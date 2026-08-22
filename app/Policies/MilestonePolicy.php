<?php

namespace App\Policies;

use App\Models\Milestone;
use App\Models\Project;
use App\Models\User;

class MilestonePolicy
{
    /**
     * Determine whether the user can view the milestone.
     */
    public function view(User $user, Milestone $milestone): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if ($user->isOrgAdmin($milestone->project->organization_id)) {
            return true;
        }

        return $user->roleInProject($milestone->project) !== null;
    }

    /**
     * Determine whether the user can manage milestones in a project.
     * Project Admins, Org Admins, and Super Admins can manage milestones.
     */
    public function manage(User $user, Project $project): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if ($user->isOrgAdmin($project->organization_id)) {
            return true;
        }

        return $user->isProjectAdmin($project);
    }
}
