<?php

namespace App\Observers;

use App\Models\Project;

class ProjectObserver
{
    public function created(Project $project): void
    {
        $user = auth()->user();
        if ($user) {
            $user->logActivity('created', "Created project {$project->name} ({$project->abbreviation})", $project);
        }
    }

    public function updated(Project $project): void
    {
        $user = auth()->user();
        if ($user && $project->isDirty()) {
            $user->logActivity('updated', "Updated project details for {$project->name}", $project);
        }
    }

    public function deleted(Project $project): void
    {
        $user = auth()->user();
        if ($user) {
            $user->logActivity('deleted', "Soft-deleted project {$project->name}", $project);
        }
    }
}
