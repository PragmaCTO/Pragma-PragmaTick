<?php

namespace DatabaseSeeders;

// Note: Namespace is Database\Seeders in Laravel
namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\CalendarEvent;
use App\Models\ChecklistItem;
use App\Models\Contact;
use App\Models\Milestone;
use App\Models\Organization;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskComment;
use App\Models\User;
use App\Models\WikiBook;
use App\Models\WikiChapter;
use App\Models\WikiPage;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with realistic corporate demo data.
     */
    public function run(): void
    {
        // 1. Create Super Admin Account
        $superAdmin = User::updateOrCreate(
            ['email' => 'superadmin@pragmacto.com'],
            [
                'name' => 'Super Admin',
                'emails' => ['superadmin@pragmacto.com'],
                'phone_number' => '+1-555-0000',
                'password' => Hash::make('changemeplease'),
                'is_super_admin' => true,
            ]
        );

        // 2. Create Core Users
        $alice = User::firstOrCreate(
            ['email' => 'alice@pragmatick.io'],
            [
                'name' => 'Alice Vance',
                'emails' => ['alice@pragmatick.io', 'a.vance@pragmatick.org'],
                'phone_number' => '+1-555-0100',
                'password' => Hash::make('password'),
                'is_super_admin' => true,
            ]
        );

        $john = User::firstOrCreate(
            ['email' => 'john@pragmatick.io'],
            [
                'name' => 'John Doe',
                'emails' => ['john@pragmatick.io', 'john.doe@apexglobal.com'],
                'phone_number' => '+1-555-0101',
                'password' => Hash::make('password'),
                'is_super_admin' => false,
            ]
        );

        $jane = User::firstOrCreate(
            ['email' => 'jane@pragmatick.io'],
            [
                'name' => 'Jane Smith',
                'emails' => ['jane@pragmatick.io'],
                'phone_number' => '+1-555-0102',
                'password' => Hash::make('password'),
                'is_super_admin' => false,
            ]
        );

        $alex = User::firstOrCreate(
            ['email' => 'alex@pragmatick.io'],
            [
                'name' => 'Alex Rivera',
                'emails' => ['alex@pragmatick.io'],
                'phone_number' => '+1-555-0103',
                'password' => Hash::make('password'),
                'is_super_admin' => false,
            ]
        );

        $marcus = User::firstOrCreate(
            ['email' => 'marcus@pragmatick.io'],
            [
                'name' => 'Marcus Brody',
                'emails' => ['marcus@pragmatick.io'],
                'phone_number' => '+1-555-0104',
                'password' => Hash::make('password'),
                'is_super_admin' => false,
            ]
        );

        // 2. Create Organizations
        $apexOrg = Organization::firstOrCreate(
            ['name' => 'Apex Global Infrastructure'],
            [
                'description' => 'Enterprise cloud computing and global network infrastructure management.',
                'color_code' => '#008b8b',
            ]
        );

        $vanguardOrg = Organization::firstOrCreate(
            ['name' => 'Vanguard Quantum Systems'],
            [
                'description' => 'Next-generation quantum cryptography & security platform.',
                'color_code' => '#2563eb',
            ]
        );

        // Attach Users to Orgs with Roles & Positions if not attached
        if (!$apexOrg->users()->where('users.id', $john->id)->exists()) {
            $apexOrg->users()->attach([
                $john->id => ['role' => 'org_admin', 'position' => 'Director of Engineering'],
                $jane->id => ['role' => 'member', 'position' => 'Lead Systems Architect'],
                $alex->id => ['role' => 'member', 'position' => 'Principal Software Engineer'],
            ]);
        }

        if (!$vanguardOrg->users()->where('users.id', $john->id)->exists()) {
            $vanguardOrg->users()->attach([
                $john->id => ['role' => 'member', 'position' => 'Security Advisor'],
                $marcus->id => ['role' => 'org_admin', 'position' => 'DevOps Lead'],
            ]);
        }

        // 3. Create Projects
        $pragProject = Project::create([
            'organization_id' => $apexOrg->id,
            'name' => 'PragmaTick Core Engine',
            'abbreviation' => 'PRAG',
            'description' => 'Core high-throughput microservices architecture and API engine.',
        ]);
        $pragProject->ensureDefaultStatuses();

        $heliosProject = Project::create([
            'organization_id' => $apexOrg->id,
            'name' => 'Helios Telemetry Pipeline',
            'abbreviation' => 'HELI',
            'description' => 'Distributed real-time telemetry and metrics ingestion cluster.',
        ]);
        $heliosProject->ensureDefaultStatuses();

        $qvtProject = Project::create([
            'organization_id' => $vanguardOrg->id,
            'name' => 'Quantum Vault Shield',
            'abbreviation' => 'QVT',
            'description' => 'Post-quantum encryption vault for high-security enterprise data.',
        ]);
        $qvtProject->ensureDefaultStatuses();

        // Attach Project Roles & Positions
        $pragProject->users()->attach([
            $john->id => ['role' => 'project_admin', 'position' => 'Technical Lead'],
            $jane->id => ['role' => 'member', 'position' => 'Core Architect'],
            $alex->id => ['role' => 'member', 'position' => 'Backend Developer'],
        ]);

        $heliosProject->users()->attach([
            $alex->id => ['role' => 'project_admin', 'position' => 'Pipeline Lead'],
        ]);

        $qvtProject->users()->attach([
            $john->id => ['role' => 'project_admin', 'position' => 'Project Lead'],
            $marcus->id => ['role' => 'member', 'position' => 'Infrastructure Engineer'],
        ]);

        // 4. Create Milestones
        $m1 = Milestone::create([
            'project_id' => $pragProject->id,
            'title' => 'Sprint Alpha 1.0 Release',
            'description' => 'Core framework architecture deployment & authentication pipeline.',
            'start_date' => '2026-09-01',
            'due_date' => '2026-09-15',
            'status' => 'in_progress',
        ]);
        $m1->assignees()->sync([$john->id, $jane->id]);

        $m2 = Milestone::create([
            'project_id' => $heliosProject->id,
            'title' => 'Q4 Telemetry Cluster Scaling',
            'description' => 'Scalability benchmarks for 10M events/sec.',
            'start_date' => '2026-09-15',
            'due_date' => '2026-09-30',
            'status' => 'planned',
        ]);
        $m2->assignees()->sync([$alex->id]);

        // 5. Create Tasks & Parent-Child Subtasks
        $parentTask = Task::create([
            'project_id' => $pragProject->id,
            'milestone_id' => $m1->id,
            'title' => 'Implement OAuth2.0 JWT Token Exchange Engine',
            'description' => 'Build high-security JWT token issuer with RSA key rotation.',
            'type' => 'feature',
            'priority' => 'urgent',
            'status' => 'In-Progress',
            'start_date' => '2026-09-02',
            'due_date' => '2026-09-10',
        ]);
        $parentTask->assignees()->sync([$jane->id, $alex->id]);

        $subTask = Task::create([
            'project_id' => $pragProject->id,
            'milestone_id' => $m1->id,
            'parent_id' => $parentTask->id,
            'title' => 'Audit JWT Signature Verification Cache',
            'description' => 'Implement in-memory redis cache for public key validation.',
            'type' => 'bug',
            'priority' => 'high',
            'status' => 'New',
            'start_date' => '2026-09-05',
            'due_date' => '2026-09-12',
        ]);
        $subTask->assignees()->sync([$alex->id]);

        $t2 = Task::create([
            'project_id' => $heliosProject->id,
            'milestone_id' => $m2->id,
            'title' => 'Fix Real-Time Stream Backpressure Degradation',
            'description' => 'Resolve memory leak when kafka consumer group rebalances under high load.',
            'type' => 'bug',
            'priority' => 'urgent',
            'status' => 'In-Progress',
            'start_date' => '2026-09-08',
            'due_date' => '2026-09-18',
        ]);
        $t2->assignees()->sync([$alex->id]);

        $t3 = Task::create([
            'project_id' => $qvtProject->id,
            'title' => 'Deploy Quantum Key Distribution Node Cluster',
            'description' => 'Provision hardware security module nodes across availability zones.',
            'type' => 'operation',
            'priority' => 'high',
            'status' => 'Testing',
            'start_date' => '2026-09-10',
            'due_date' => '2026-09-22',
        ]);
        $t3->assignees()->sync([$marcus->id]);

        // 6. Task Comments
        TaskComment::create([
            'task_id' => $parentTask->id,
            'user_id' => $john->id,
            'content' => 'RSA key rotation verified against security standards. Ready for load testing.',
        ]);

        TaskComment::create([
            'task_id' => $parentTask->id,
            'user_id' => $jane->id,
            'content' => 'Updated JWT claims schema to include org context roles.',
        ]);

        // 7. Wiki Books, Chapters & Pages with Mermaid.js Diagrams
        $wikiBook = WikiBook::create([
            'author_id' => $alice->id,
            'owner_type' => Organization::class,
            'owner_id' => $apexOrg->id,
            'title' => 'System Architecture & Protocol Specification',
            'slug' => 'system-architecture-protocol-specification',
            'description' => 'Official architecture blueprint & protocol docs for Apex Infrastructure.',
        ]);

        $chap1 = WikiChapter::create([
            'wiki_book_id' => $wikiBook->id,
            'title' => 'Authentication & Identity Engine',
            'slug' => 'authentication-identity-engine',
            'description' => 'Deep dive into token flows and RBAC security policies.',
            'order' => 1,
        ]);

        WikiPage::create([
            'wiki_chapter_id' => $chap1->id,
            'author_id' => $jane->id,
            'title' => 'OAuth2 Token Exchange Sequence Flow',
            'slug' => 'oauth2-token-exchange-sequence-flow',
            'content' => "## Architecture Overview\n\nHigh-throughput authentication pipeline.\n\n```mermaid\nsequenceDiagram\n    autonumber\n    Client->>Gateway: POST /oauth/token\n    Gateway->>AuthService: Validate Client Secret\n    AuthService->>Database: Query User RBAC Context\n    Database-->>AuthService: Return Roles & Permissions\n    AuthService-->>Gateway: Issue JWT Signed Token\n    Gateway-->>Client: 200 OK (Bearer Token)\n```\n\n### Security Directives\n- Tokens expire every 3600 seconds.\n- Soft-deleted users are revoked immediately.",
            'order' => 1,
        ]);

        // 8. Calendar Events
        CalendarEvent::create([
            'organizer_id' => $alice->id,
            'title' => 'Executive Q4 Strategic Sync',
            'description' => 'Global roadmap review with department directors.',
            'start_time' => Carbon::parse('2026-09-10 10:00:00'),
            'end_time' => Carbon::parse('2026-09-10 11:30:00'),
            'is_super_admin_event' => true,
            'color' => '#f43f5e',
        ]);

        $meeting = CalendarEvent::create([
            'organizer_id' => $john->id,
            'title' => 'Core Engine Architecture Review',
            'description' => 'Technical alignment on token caching performance.',
            'start_time' => Carbon::parse('2026-09-12 14:00:00'),
            'end_time' => Carbon::parse('2026-09-12 15:00:00'),
            'color' => '#008b8b',
        ]);
        $meeting->attendees()->sync([$jane->id, $alex->id]);

        // 9. External Contacts CRM
        Contact::create([
            'name' => 'Sarah Connor',
            'company' => 'Cyberdyne Security Systems',
            'position' => 'Director of Defense Operations',
            'phone' => '+1-555-0199',
            'email' => 'sarah@cyberdyne.com',
            'notes' => 'Primary liaison for quantum vault hardware HSM modules.',
        ]);

        Contact::create([
            'name' => 'Miles Dyson',
            'company' => 'Cyberdyne Technologies',
            'position' => 'Chief Technology Officer',
            'phone' => '+1-555-0188',
            'email' => 'miles@cyberdyne.com',
            'notes' => 'Executive sponsor for distributed neural cluster project.',
        ]);

        // 10. Personal Checklist Items
        ChecklistItem::create([
            'user_id' => $alice->id,
            'title' => 'Review Annual Cloud Security Audit Compliance Report',
            'description' => 'Verify ISO/IEC 27001 & SOC2 Type II audit attestations.',
            'priority' => 'urgent',
            'status' => 'In-Progress',
            'due_date' => '2026-09-15',
        ]);

        ChecklistItem::create([
            'user_id' => $alice->id,
            'title' => 'Authorize Hardware Security Module Procurement',
            'description' => 'Approve purchase order for Vanguard Quantum HSM nodes.',
            'priority' => 'high',
            'status' => 'To-Do',
            'due_date' => '2026-09-18',
        ]);

        ChecklistItem::create([
            'user_id' => $john->id,
            'title' => 'Benchmark Redis Cache Latency under 50k Concurrent Connections',
            'description' => 'Run loadtest script against staging cluster.',
            'priority' => 'high',
            'status' => 'To-Do',
            'due_date' => '2026-09-14',
        ]);

        // 11. Initial Activity Logs
        ActivityLog::create([
            'user_id' => $alice->id,
            'action' => 'created_user',
            'description' => "Initialized system administrator accounts & organizational schemas.",
            'subject_type' => User::class,
            'subject_id' => $alice->id,
        ]);
    }
}
