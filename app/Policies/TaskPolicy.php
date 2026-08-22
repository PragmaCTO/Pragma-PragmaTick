<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * Determine whether the user can view the task.
     */
    public function view(User $user, Task $task): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if ($user->isOrgAdmin($task->project->organization_id)) {
            return true;
        }

        return $user->roleInProject($task->project) !== null;
    }

    /**
     * Determine whether the user can create tasks within a project.
     * Members, Project Admins, Org Admins, and Super Admins can create tasks.
     */
    public function create(User $user, Project $project): bool
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
     * Determine whether the user can update the task.
     * Super Admins, Org Admins, and Project Admins can update any task in the project.
     * Regular members can ONLY update tasks that are assigned to them.
     */
    public function update(User $user, Task $task): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if ($user->isOrgAdmin($task->project->organization_id)) {
            return true;
        }

        if ($user->isProjectAdmin($task->project)) {
            return true;
        }

        // Regular project member: check if user is assigned to this task
        if ($user->roleInProject($task->project) !== null) {
            return $task->assignees()->where('users.id', $user->id)->exists() || $task->assigned_to === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the task.
     * Only Project Admins, Org Admins, and Super Admins can delete tasks.
     */
    public function delete(User $user, Task $task): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if ($user->isOrgAdmin($task->project->organization_id)) {
            return true;
        }

        return $user->isProjectAdmin($task->project);
    }
}
