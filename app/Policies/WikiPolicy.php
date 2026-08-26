<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\Project;
use App\Models\User;
use App\Models\WikiBook;

class WikiPolicy
{
    /**
     * Determine whether the user can view the wiki book.
     */
    public function view(User $user, WikiBook $book): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Author always has access
        if ($book->author_id === $user->id) {
            return true;
        }

        // Check if shared explicitly
        if ($book->sharedUsers()->where('users.id', $user->id)->exists()) {
            return true;
        }

        // Organization owned book
        if ($book->owner_type === Organization::class) {
            $orgId = $book->owner_id;
            return $user->isOrgAdmin($orgId);
        }

        // Project owned book
        if ($book->owner_type === Project::class) {
            $projectId = $book->owner_id;
            $project = Project::find($projectId);
            if (!$project) return false;

            if ($user->isOrgAdmin($project->organization_id)) {
                return true;
            }

            return $user->roleInProject($project) !== null;
        }

        // Private / unassigned book
        return !$book->is_private;
    }

    /**
     * Determine whether the user can update/edit the wiki book.
     */
    public function update(User $user, WikiBook $book): bool
    {
        return $this->view($user, $book);
    }

    /**
     * Determine whether the user can delete the wiki book.
     */
    public function delete(User $user, WikiBook $book): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if ($book->author_id === $user->id) {
            return true;
        }

        if ($book->owner_type === Organization::class) {
            return $user->isOrgAdmin($book->owner_id);
        }

        if ($book->owner_type === Project::class) {
            $project = Project::find($book->owner_id);
            return $project ? $user->isProjectAdmin($project) : false;
        }

        return false;
    }
}
