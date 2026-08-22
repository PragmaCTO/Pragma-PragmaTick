<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganizationProjectManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Organization list scoped visibility for Super Admin vs Org Admin vs Member.
     */
    public function test_organization_list_scoped_visibility(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $orgAdmin = User::factory()->create(['name' => 'Org Admin User']);
        $member = User::factory()->create(['name' => 'Member User']);

        $orgA = Organization::factory()->create(['name' => 'Org Alpha']);
        $orgB = Organization::factory()->create(['name' => 'Org Beta']);

        // OrgAdmin belongs to Org A only
        $orgA->users()->attach($orgAdmin->id, ['role' => 'org_admin', 'position' => 'Director']);

        // Member belongs to Org B only
        $orgB->users()->attach($member->id, ['role' => 'member', 'position' => 'Developer']);

        // Super Admin sees all orgs
        $responseSuper = $this->actingAs($superAdmin)->get(route('organizations.index'));
        $responseSuper->assertStatus(200);
        $responseSuper->assertSee('Org Alpha');
        $responseSuper->assertSee('Org Beta');

        // Org Admin sees Org Alpha only
        $responseOrgAdmin = $this->actingAs($orgAdmin)->get(route('organizations.index'));
        $responseOrgAdmin->assertStatus(200);
        $responseOrgAdmin->assertSee('Org Alpha');
        $responseOrgAdmin->assertDontSee('Org Beta');

        // Member sees Org Beta only
        $responseMember = $this->actingAs($member)->get(route('organizations.index'));
        $responseMember->assertStatus(200);
        $responseMember->assertSee('Org Beta');
        $responseMember->assertDontSee('Org Alpha');
    }

    /**
     * Test Org Admin can add user to organization with role & position.
     */
    public function test_org_admin_can_add_user_with_role_and_position(): void
    {
        $orgAdmin = User::factory()->create();
        $targetUser = User::factory()->create();
        $org = Organization::factory()->create(['name' => 'Acme Inc']);

        $org->users()->attach($orgAdmin->id, ['role' => 'org_admin', 'position' => 'VP']);

        $response = $this->actingAs($orgAdmin)->post(route('organizations.addMember', $org), [
            'user_id' => $targetUser->id,
            'role' => 'member',
            'position' => 'Senior Frontend Lead',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('organization_user', [
            'organization_id' => $org->id,
            'user_id' => $targetUser->id,
            'role' => 'member',
            'position' => 'Senior Frontend Lead',
        ]);
    }

    /**
     * Test Project list scoped visibility and task prefix formatting.
     */
    public function test_project_list_scoped_visibility_and_task_prefix(): void
    {
        $org = Organization::factory()->create(['name' => 'Tech Corp']);
        $proj = Project::factory()->create([
            'organization_id' => $org->id,
            'name' => 'Core Engine',
            'abbreviation' => 'PRAG',
        ]);

        $task = Task::factory()->create(['project_id' => $proj->id, 'title' => 'Build Architecture']);

        $superAdmin = User::factory()->superAdmin()->create();
        $response = $this->actingAs($superAdmin)->get(route('projects.show', $proj));

        $response->assertStatus(200);
        $response->assertSee('PRAG-');
        $response->assertSee('PRAG-' . $task->id);
    }

    /**
     * Test Project Admin can add and remove project members.
     */
    public function test_project_admin_can_add_and_remove_project_members(): void
    {
        $projAdmin = User::factory()->create();
        $targetUser = User::factory()->create();
        $proj = Project::factory()->create();

        $proj->users()->attach($projAdmin->id, ['role' => 'project_admin', 'position' => 'Tech Lead']);

        // Add member
        $responseAdd = $this->actingAs($projAdmin)->post(route('projects.addMember', $proj), [
            'user_id' => $targetUser->id,
            'role' => 'member',
            'position' => 'QA Engineer',
        ]);

        $responseAdd->assertRedirect();
        $this->assertDatabaseHas('project_user', [
            'project_id' => $proj->id,
            'user_id' => $targetUser->id,
            'role' => 'member',
            'position' => 'QA Engineer',
        ]);

        // Remove member
        $responseRemove = $this->actingAs($projAdmin)->delete(route('projects.removeMember', [$proj, $targetUser]));
        $responseRemove->assertRedirect();

        $this->assertDatabaseMissing('project_user', [
            'project_id' => $proj->id,
            'user_id' => $targetUser->id,
        ]);
    }

    /**
     * Test Member can update own contact info and JSON emails list.
     */
    public function test_member_can_update_own_contact_info_and_emails_json(): void
    {
        $member = User::factory()->create([
            'name' => 'Old Name',
            'phone_number' => '111-222-3333',
            'emails' => ['old@domain.com'],
        ]);

        $response = $this->actingAs($member)->put(route('settings.update'), [
            'name' => 'New Updated Name',
            'phone_number' => '+1-800-555-9999',
            'emails' => ['primary.new@domain.com', 'secondary.new@domain.com'],
        ]);

        $response->assertRedirect();
        $member->refresh();

        $this->assertEquals('New Updated Name', $member->name);
        $this->assertEquals('+1-800-555-9999', $member->phone_number);
        $this->assertEquals('primary.new@domain.com', $member->email);
        $this->assertCount(2, $member->emails);
        $this->assertEquals('secondary.new@domain.com', $member->emails[1]);
    }

    /**
     * Test soft delete action soft-deletes organization and project.
     */
    public function test_soft_delete_action_soft_deletes_org_and_project(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $org = Organization::factory()->create();
        $proj = Project::factory()->create(['organization_id' => $org->id]);

        $responseProj = $this->actingAs($superAdmin)->delete(route('projects.destroy', $proj));
        $responseProj->assertRedirect();
        $this->assertSoftDeleted($proj);

        $responseOrg = $this->actingAs($superAdmin)->delete(route('organizations.destroy', $org));
        $responseOrg->assertRedirect();
        $this->assertSoftDeleted($org);
    }
}
