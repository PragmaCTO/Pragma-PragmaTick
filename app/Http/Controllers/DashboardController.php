<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\CalendarEvent;
use App\Models\ChecklistItem;
use App\Models\Milestone;
use App\Models\Organization;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Display Overview Dashboard with Dual Clocks, RBAC Metric Buckets, 'My Day' Section, and Activity Logs.
     */
    public function index()
    {
        /** @var User $user */
        $user = auth()->user();

        if (!$user) {
            $user = User::factory()->superAdmin()->make();
        }

        $userId = $user->id ?? 0;
        $isSuperAdmin = $user && $user->isSuperAdmin();

        // 1. RBAC Scoped Queries with SQL Aggregations
        if ($isSuperAdmin) {
            $orgsCount = Organization::count();
            $projectsCount = Project::count();
            $totalTasksCount = Task::count();
            $pendingTasksCount = Task::whereNotIn('status', ['Completed', 'Done'])->count();
            $upcomingMilestonesCount = Milestone::where('status', '!=', 'completed')->count();

            $myDayTasks = Task::where(function ($q) use ($userId) {
                $q->where('status', 'In-Progress')
                  ->orWhereHas('assignees', fn($q2) => $q2->where('users.id', $userId));
            })->with(['project', 'assignees'])->take(5)->get();
        } else {
            $userOrgIds = $user->organizations()->pluck('organizations.id');
            $userProjIds = $user->projects()->pluck('projects.id');

            $orgsCount = Organization::whereIn('id', $userOrgIds)->count();
            $projectsCount = Project::whereIn('id', $userProjIds)->count();
            $totalTasksCount = Task::whereIn('project_id', $userProjIds)->count();
            $pendingTasksCount = Task::whereIn('project_id', $userProjIds)->whereNotIn('status', ['Completed', 'Done'])->count();
            $upcomingMilestonesCount = Milestone::whereIn('project_id', $userProjIds)->where('status', '!=', 'completed')->count();

            $myDayTasks = Task::whereIn('project_id', $userProjIds)
                ->where(function ($q) use ($userId) {
                    $q->where('status', 'In-Progress')
                      ->orWhereHas('assignees', fn($q2) => $q2->where('users.id', $userId));
                })->with(['project', 'assignees'])->take(5)->get();
        }

        // Personal Checklist Items Metrics
        $totalChecklistCount = ($user && $user->exists) ? ChecklistItem::where('user_id', $userId)->count() : 0;
        $pendingChecklistCount = ($user && $user->exists) ? ChecklistItem::where('user_id', $userId)->whereNotIn('status', ['Completed'])->count() : 0;

        // 2. 'My Day' Aggregation
        $todayStr = now()->format('Y-m-d');
        
        $myDayEvents = CalendarEvent::with(['organizer', 'attendees'])
            ->where(function ($q) use ($userId) {
                $q->where('organizer_id', $userId)
                  ->orWhereHas('attendees', fn($q2) => $q2->where('users.id', $userId));
            })
            ->whereDate('start_time', '<=', $todayStr)
            ->whereDate('end_time', '>=', $todayStr)
            ->get();

        $myDayChecklist = ($user && $user->exists) 
            ? ChecklistItem::where('user_id', $userId)->whereIn('status', ['To-Do', 'In-Progress', 'Delayed'])->take(5)->get() 
            : collect();

        // 3. Activity Logs & Config-driven World Clock Timezones
        $activityLogs = ActivityLog::with(['user'])->latest()->take(10)->get();
        
        $formattedTimezones = collect(config('timezones.list', []))->map(function($label, $id) {
            // Extract offset string if present in label e.g. (UTC+05:45)
            preg_match('/\((UTC[+-]\d{2}:\d{2})\)/', $label, $matches);
            $offsetStr = $matches[1] ?? 'UTC+00:00';
            return [
                'id' => $id,
                'offset' => $offsetStr,
                'label' => $label,
                'search' => strtolower("{$id} {$label}"),
            ];
        })->values()->toArray();

        return view('welcome', compact(
            'user',
            'totalTasksCount',
            'pendingTasksCount',
            'orgsCount',
            'projectsCount',
            'upcomingMilestonesCount',
            'totalChecklistCount',
            'pendingChecklistCount',
            'myDayEvents',
            'myDayTasks',
            'myDayChecklist',
            'activityLogs',
            'formattedTimezones'
        ));
    }
}
