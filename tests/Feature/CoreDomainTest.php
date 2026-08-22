<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\Milestone;
use App\Models\Organization;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\Wiki;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class CoreDomainTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test SoftDeletes trait on User, Organization, Project, Milestone, Task, Wiki.
     */
    public function test_soft_deletes_on_all_major_models(): void
    {
        $user = User::factory()->create();
        $org = Organization::factory()->create();
        $project = Project::factory()->create(['organization_id' => $org->id]);
        $milestone = Milestone::factory()->create(['project_id' => $project->id]);
        $task = Task::factory()->create(['project_id' => $project->id, 'milestone_id' => $milestone->id]);
        $wiki = Wiki::factory()->create(['organization_id' => $org->id, 'author_id' => $user->id]);

        $user->delete();
        $org->delete();
        $project->delete();
        $milestone->delete();
        $task->delete();
        $wiki->delete();

        $this->assertSoftDeleted($user);
        $this->assertSoftDeleted($org);
        $this->assertSoftDeleted($project);
        $this->assertSoftDeleted($milestone);
        $this->assertSoftDeleted($task);
        $this->assertSoftDeleted($wiki);
    }

    /**
     * Test User emails JSON array and phone_number.
     */
    public function test_user_emails_json_and_phone_number(): void
    {
        $user = User::factory()->create([
            'emails' => ['primary@domain.com', 'work@domain.com', 'personal@domain.com'],
            'phone_number' => '+1-555-9876',
        ]);

        $this->assertIsArray($user->emails);
        $this->assertCount(3, $user->emails);
        $this->assertEquals('work@domain.com', $user->emails[1]);
        $this->assertEquals('+1-555-9876', $user->phone_number);
    }

    /**
     * Test Super Admin exclusive permission for creating new users.
     */
    public function test_super_admin_exclusive_user_creation_permission(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $regularUser = User::factory()->create(['is_super_admin' => false]);

        $this->assertTrue($superAdmin->canCreateUsers());
        $this->assertFalse($regularUser->canCreateUsers());

        $this->assertTrue(Gate::forUser($superAdmin)->allows('create-user'));
        $this->assertFalse(Gate::forUser($regularUser)->allows('create-user'));
    }

    /**
     * Test Contextual/Scoped RBAC roles on Pivot tables.
     */
    public function test_contextual_rbac_roles_on_pivot_tables(): void
    {
        $john = User::factory()->create(['name' => 'John']);
        $orgA = Organization::factory()->create(['name' => 'Org A']);
        $orgB = Organization::factory()->create(['name' => 'Org B']);

        $projX = Project::factory()->create(['organization_id' => $orgA->id, 'name' => 'Project X']);
        $projY = Project::factory()->create(['organization_id' => $orgB->id, 'name' => 'Project Y']);

        // John is Org Admin in Org A, but Member in Org B
        $orgA->users()->attach($john->id, ['role' => 'org_admin', 'position' => 'CTO']);
        $orgB->users()->attach($john->id, ['role' => 'member', 'position' => 'Consultant']);

        // John is Project Admin in Project Y (under Org B)
        $projY->users()->attach($john->id, ['role' => 'project_admin', 'position' => 'Tech Lead']);

        $this->assertEquals('org_admin', $john->roleInOrganization($orgA));
        $this->assertEquals('member', $john->roleInOrganization($orgB));
        $this->assertTrue($john->isOrgAdmin($orgA));
        $this->assertFalse($john->isOrgAdmin($orgB));

        // In Project Y, John is Project Admin even though he is only a Member in Org B
        $this->assertEquals('project_admin', $john->roleInProject($projY));
        $this->assertTrue($john->isProjectAdmin($projY));
        $this->assertEquals('Tech Lead', $projY->users()->first()->pivot->position);
    }

    /**
     * Test ActivityLog polymorphic relationships.
     */
    public function test_activity_log_polymorphic_relationship(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create();

        $log = $user->logActivity('completed_task', 'Task completed successfully', $task, ['time_spent' => '2h']);

        $this->assertInstanceOf(ActivityLog::class, $log);
        $this->assertEquals($user->id, $log->user_id);
        $this->assertEquals(Task::class, $log->subject_type);
        $this->assertEquals($task->id, $log->subject_id);
        $this->assertInstanceOf(Task::class, $log->subject);
        $this->assertEquals('2h', $log->properties['time_spent']);
    }
}
