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

        // 1. RBAC Scoped Queries
        if ($user && $user->isSuperAdmin()) {
            $organizations = Organization::with(['users', 'projects'])->get();
            $projects = Project::with(['organization', 'users', 'milestones', 'tasks'])->get();
            $tasks = Task::with(['project', 'assignees'])->get();
            $milestones = Milestone::with(['project', 'assignees'])->get();
        } else {
            $userOrgIds = $user->organizations()->pluck('organizations.id');
            $userProjIds = $user->projects()->pluck('projects.id');

            $organizations = Organization::whereIn('id', $userOrgIds)->with(['users', 'projects'])->get();
            $projects = Project::whereIn('id', $userProjIds)->with(['organization', 'users', 'milestones', 'tasks'])->get();
            $tasks = Task::whereIn('project_id', $userProjIds)->with(['project', 'assignees'])->get();
            $milestones = Milestone::whereIn('project_id', $userProjIds)->with(['project', 'assignees'])->get();
        }

        // Metric calculations
        $totalTasksCount = $tasks->count();
        $pendingTasksCount = $tasks->whereNotIn('status', ['Completed', 'Done'])->count();

        $orgsCount = $organizations->count();
        $projectsCount = $projects->count();
        $upcomingMilestonesCount = $milestones->where('status', '!=', 'completed')->count();

        // Personal Checklist Items Metrics
        $checklistItems = ($user && $user->exists) ? $user->checklistItems()->get() : collect();
        $totalChecklistCount = $checklistItems->count();
        $pendingChecklistCount = $checklistItems->whereNotIn('status', ['Completed'])->count();

        // 2. 'My Day' Aggregation
        $todayStr = now()->format('Y-m-d');
        
        $userId = $user->id ?? 0;
        $myDayEvents = CalendarEvent::where(function ($q) use ($userId) {
            $q->where('organizer_id', $userId)
              ->orWhereHas('attendees', fn($q2) => $q2->where('users.id', $userId));
        })
        ->whereDate('start_time', '<=', $todayStr)
        ->whereDate('end_time', '>=', $todayStr)
        ->get();

        $myDayTasks = $tasks->filter(function ($t) use ($user) {
            return $t->status === 'In-Progress' || $t->assignees->pluck('id')->contains($user->id);
        })->take(5);

        $myDayChecklist = $checklistItems->whereIn('status', ['To-Do', 'In-Progress', 'Delayed'])->take(5);

        // 3. Activity Logs & Formatted Global Timezones with UTC offsets
        $activityLogs = ActivityLog::with(['user', 'subject'])->latest()->take(10)->get();
        $allUsers = User::orderBy('is_super_admin', 'desc')->orderBy('name')->get();
        
        $rawTimezones = \DateTimeZone::listIdentifiers();
        $formattedTimezones = [];
        $nowUtc = new \DateTime('now', new \DateTimeZone('UTC'));
        
        $countryMap = [
            'Asia/Kathmandu' => 'Nepal',
            'Asia/Tokyo' => 'Japan',
            'Europe/London' => 'United Kingdom UK',
            'America/New_York' => 'United States US USA East EST',
            'America/Los_Angeles' => 'United States US USA West PST Pacific',
            'America/Chicago' => 'United States US USA Central CST',
            'America/Denver' => 'United States US USA Mountain MST',
            'Asia/Kolkata' => 'India',
            'Asia/Calcutta' => 'India',
            'Asia/Dhaka' => 'Bangladesh',
            'Asia/Bangkok' => 'Thailand',
            'Asia/Dubai' => 'United Arab Emirates UAE',
            'Asia/Singapore' => 'Singapore',
            'Asia/Hong_Kong' => 'Hong Kong China',
            'Asia/Shanghai' => 'China',
            'Asia/Seoul' => 'South Korea Korea',
            'Europe/Paris' => 'France',
            'Europe/Berlin' => 'Germany',
            'Europe/Rome' => 'Italy',
            'Europe/Madrid' => 'Spain',
            'Europe/Amsterdam' => 'Netherlands',
            'Europe/Brussels' => 'Belgium',
            'Europe/Zurich' => 'Switzerland',
            'Europe/Vienna' => 'Austria',
            'Europe/Stockholm' => 'Sweden',
            'Europe/Oslo' => 'Norway',
            'Europe/Helsinki' => 'Finland',
            'Europe/Athens' => 'Greece',
            'Europe/Istanbul' => 'Turkey',
            'Europe/Moscow' => 'Russia',
            'Australia/Sydney' => 'Australia',
            'Australia/Melbourne' => 'Australia',
            'Australia/Brisbane' => 'Australia',
            'Australia/Perth' => 'Australia',
            'Pacific/Auckland' => 'New Zealand',
            'America/Toronto' => 'Canada',
            'America/Vancouver' => 'Canada',
            'America/Mexico_City' => 'Mexico',
            'America/Sao_Paulo' => 'Brazil',
            'America/Buenos_Aires' => 'Argentina',
            'Africa/Cairo' => 'Egypt',
            'Africa/Johannesburg' => 'South Africa',
            'Africa/Lagos' => 'Nigeria',
            'Africa/Nairobi' => 'Kenya',
        ];

        foreach ($rawTimezones as $tz) {
            try {
                $tzObj = new \DateTimeZone($tz);
                $offsetSec = $tzObj->getOffset($nowUtc);
                $hours = floor(abs($offsetSec) / 3600);
                $minutes = floor((abs($offsetSec) % 3600) / 60);
                $sign = $offsetSec >= 0 ? '+' : '-';
                $offsetStr = sprintf("UTC%s%02d:%02d", $sign, $hours, $minutes);
                
                $city = str_replace('_', ' ', count(explode('/', $tz)) > 1 ? explode('/', $tz)[1] : $tz);
                $country = $countryMap[$tz] ?? str_replace('_', ' ', explode('/', $tz)[0]);
                $searchStr = strtolower("{$tz} {$city} {$country} {$offsetStr}");
                $displayLabel = "({$offsetStr}) {$tz}" . (isset($countryMap[$tz]) ? " - {$countryMap[$tz]}" : "");

                $formattedTimezones[] = [
                    'id' => $tz,
                    'offset' => $offsetStr,
                    'label' => $displayLabel,
                    'search' => $searchStr,
                ];
            } catch (\Exception $e) {
                // Ignore invalid timezones if any
            }
        }

        $allTimezones = $rawTimezones;

        return view('welcome', compact(
            'user',
            'organizations',
            'projects',
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
            'allUsers',
            'allTimezones',
            'formattedTimezones'
        ));
    }
}
