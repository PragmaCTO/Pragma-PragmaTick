<?php

namespace App\Providers;

use App\Models\Organization;
use App\Models\Project;
use App\Models\User;
use App\Policies\ContactPolicy;
use App\Policies\MilestonePolicy;
use App\Policies\OrganizationPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\TaskPolicy;
use App\Policies\UserPolicy;
use App\Policies\WikiPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Organization::class, OrganizationPolicy::class);
        Gate::policy(Project::class, ProjectPolicy::class);
        Gate::policy(\App\Models\Task::class, TaskPolicy::class);
        Gate::policy(\App\Models\Milestone::class, MilestonePolicy::class);
        Gate::policy(\App\Models\WikiBook::class, WikiPolicy::class);
        Gate::policy(\App\Models\Contact::class, ContactPolicy::class);

        Gate::define('create-user', function (User $user) {
            return $user->isSuperAdmin();
        });
    }
}

