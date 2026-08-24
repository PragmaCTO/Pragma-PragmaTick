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

            $organizations = Organization::with(['users', 'projects'])->get();
            $projects = Project::with(['organization', 'users'])->withCount(['milestones', 'tasks'])->get();
            
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

            $organizations = Organization::whereIn('id', $userOrgIds)->with(['users', 'projects'])->get();
            $projects = Project::whereIn('id', $userProjIds)->with(['organization', 'users'])->withCount(['milestones', 'tasks'])->get();

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
        
        $myDayEvents = CalendarEvent::where(function ($q) use ($userId) {
            $q->where('organizer_id', $userId)
              ->orWhereHas('attendees', fn($q2) => $q2->where('users.id', $userId));
        })
        ->whereDate('start_time', '<=', $todayStr)
        ->whereDate('end_time', '>=', $todayStr)
        ->get();

        $myDayChecklist = ($user && $user->exists) 
            ? ChecklistItem::where('user_id', $userId)->whereIn('status', ['To-Do', 'In-Progress', 'Delayed'])->take(5)->get() 
            : collect();

        // 3. Activity Logs & Formatted Global Timezones with UTC offsets
        $activityLogs = ActivityLog::with(['user', 'subject'])->latest()->take(10)->get();
        $allUsers = User::orderBy('is_super_admin', 'desc')->orderBy('name')->get();
        
        $allTimezones = \DateTimeZone::listIdentifiers();
        $formattedTimezones = \Illuminate\Support\Facades\Cache::remember('system_timezones', 86400, function() use ($allTimezones) {
            $formatted = [];
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

            foreach ($allTimezones as $tz) {
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

                    $formatted[] = [
                        'id' => $tz,
                        'offset' => $offsetStr,
                        'label' => $displayLabel,
                        'search' => $searchStr,
                    ];
                } catch (\Exception $e) {
                    // Ignore invalid timezones if any
                }
            }
            return $formatted;
        });

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
