<?php

namespace Tests\Feature;

use App\Models\Milestone;
use App\Models\Organization;
use App\Models\Project;
use App\Models\ProjectStatus;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KanbanTaskManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Milestone CRUD with multiple assignees and dates.
     */
    public function test_milestone_crud_with_multiple_assignees(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $org = Organization::factory()->create();
        $project = Project::factory()->create(['organization_id' => $org->id]);

        $response = $this->actingAs($superAdmin)->post(route('milestones.store', $project), [
            'title' => 'Sprint Alpha Release',
            'description' => 'First release milestone',
            'start_date' => '2026-09-01',
            'due_date' => '2026-09-15',
            'status' => 'in_progress',
            'assignees' => [$user1->id, $user2->id],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('milestones', [
            'project_id' => $project->id,
            'title' => 'Sprint Alpha Release',
            'status' => 'in_progress',
        ]);

        $milestone = Milestone::where('title', 'Sprint Alpha Release')->first();
        $this->assertCount(2, $milestone->assignees);
    }

    /**
     * Test Task auto-generated code (e.g. PRAG-1) and Task Type enum.
     */
    public function test_task_auto_generated_code_and_type_enum(): void
    {
        $proj = Project::factory()->create(['abbreviation' => 'PRAG']);
        $user = User::factory()->superAdmin()->create();

        $response = $this->actingAs($user)->post(route('tasks.store', $proj), [
            'title' => 'Fix Auth Token Leak',
            'type' => 'bug',
            'priority' => 'urgent',
            'status' => 'New',
        ]);

        $response->assertRedirect();
        $task = Task::where('title', 'Fix Auth Token Leak')->first();

        $this->assertNotNull($task);
        $this->assertEquals('bug', $task->type);
        $this->assertEquals('PRAG-' . $task->id, $task->code);
    }

    /**
     * Test Parent-Child Task Dependency selector.
     */
    public function test_parent_child_task_dependency(): void
    {
        $proj = Project::factory()->create(['abbreviation' => 'ARCOS']);
        $user = User::factory()->superAdmin()->create();

        $parentTask = Task::factory()->create(['project_id' => $proj->id, 'title' => 'Parent System Architecture']);

        $response = $this->actingAs($user)->post(route('tasks.store', $proj), [
            'title' => 'Child Sub-Component',
            'type' => 'feature',
            'priority' => 'high',
            'status' => 'In-Progress',
            'parent_id' => $parentTask->id,
        ]);

        $response->assertRedirect();
        $childTask = Task::where('title', 'Child Sub-Component')->first();

        $this->assertEquals($parentTask->id, $childTask->parent_id);
        $this->assertEquals($parentTask->id, $childTask->parent->id);
        $this->assertCount(1, $parentTask->subtasks);
    }

    /**
     * Test AJAX Drag and Drop Task Status update.
     */
    public function test_ajax_task_drag_and_drop_status_update(): void
    {
        $user = User::factory()->superAdmin()->create();
        $task = Task::factory()->create(['status' => 'New']);

        $response = $this->actingAs($user)->postJson(route('tasks.updateStatus', $task), [
            'status' => 'In-Progress',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'new_status' => 'In-Progress',
        ]);

        $this->assertEquals('In-Progress', $task->fresh()->status);
    }

    /**
     * Test Kanban Column Customization & Mandatory Status Deletion Protection.
     */
    public function test_kanban_column_customization_and_mandatory_status_protection(): void
    {
        $user = User::factory()->superAdmin()->create();
        $proj = Project::factory()->create();
        $proj->ensureDefaultStatuses();

        // 1. Mandatory status deletion must be blocked
        $mandatoryStatus = $proj->statuses()->where('slug', 'new')->first();
        $this->assertTrue($mandatoryStatus->is_mandatory);

        $responseDeleteMandatory = $this->actingAs($user)->delete(route('statuses.destroy', $mandatoryStatus));
        $responseDeleteMandatory->assertSessionHasErrors('error');
        $this->assertDatabaseHas('project_statuses', ['id' => $mandatoryStatus->id]);

        // 2. Custom status addition & deletion
        $responseAddCustom = $this->actingAs($user)->post(route('statuses.store', $proj), [
            'name' => 'Security Audit',
            'color' => '#8b5cf6',
        ]);

        $responseAddCustom->assertRedirect();
        $customStatus = $proj->statuses()->where('slug', 'security-audit')->first();
        $this->assertNotNull($customStatus);
        $this->assertFalse($customStatus->is_mandatory);

        // Delete custom status should succeed
        $responseDeleteCustom = $this->actingAs($user)->delete(route('statuses.destroy', $customStatus));
        $responseDeleteCustom->assertRedirect();
        $this->assertDatabaseMissing('project_statuses', ['id' => $customStatus->id]);
    }

    /**
     * Test Member permissions: Members can View and Create/Edit tasks, but CANNOT delete tasks or modify columns.
     */
    public function test_member_permissions_restrictions(): void
    {
        $org = Organization::factory()->create();
        $proj = Project::factory()->create(['organization_id' => $org->id]);
        $member = User::factory()->create();

        $proj->users()->attach($member->id, ['role' => 'member', 'position' => 'Developer']);
        $task = Task::factory()->create(['project_id' => $proj->id]);
        $task->assignees()->attach($member->id);

        $unassignedTask = Task::factory()->create(['project_id' => $proj->id]);

        // Member CAN create task
        $responseCreate = $this->actingAs($member)->post(route('tasks.store', $proj), [
            'title' => 'Member Task',
            'type' => 'feature',
            'priority' => 'low',
            'status' => 'New',
        ]);
        $responseCreate->assertRedirect();

        // Member CAN edit task assigned to them
        $responseUpdate = $this->actingAs($member)->put(route('tasks.update', $task), [
            'title' => 'Updated Member Task',
            'type' => 'feature',
            'priority' => 'medium',
            'status' => 'In-Progress',
        ]);
        $responseUpdate->assertRedirect();

        // Member CANNOT edit task NOT assigned to them
        $responseUnassignedUpdate = $this->actingAs($member)->put(route('tasks.update', $unassignedTask), [
            'title' => 'Forbidden Update',
            'type' => 'feature',
            'priority' => 'medium',
            'status' => 'In-Progress',
        ]);
        $responseUnassignedUpdate->assertStatus(403);

        // Member CANNOT delete task
        $responseDelete = $this->actingAs($member)->delete(route('tasks.destroy', $task));
        $responseDelete->assertStatus(403);

        // Member CANNOT add status column
        $responseAddCol = $this->actingAs($member)->post(route('statuses.store', $proj), [
            'name' => 'Member Column',
            'color' => '#ffffff',
        ]);
        $responseAddCol->assertStatus(403);
    }
}
