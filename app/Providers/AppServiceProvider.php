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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use App\Models\CalendarEvent;
use App\Models\WikiBook;
use App\Models\WikiChapter;
use App\Models\WikiPage;
use App\Observers\CalendarEventObserver;
use App\Observers\ProjectObserver;
use App\Observers\WikiObserver;

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
        Model::preventLazyLoading(!app()->isProduction());
        Model::preventSilentlyDiscardingAttributes(!app()->isProduction());

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

        CalendarEvent::observe(CalendarEventObserver::class);
        Project::observe(ProjectObserver::class);
        WikiBook::observe(WikiObserver::class);
        WikiChapter::observe(WikiObserver::class);
        WikiPage::observe(WikiObserver::class);
    }
}

