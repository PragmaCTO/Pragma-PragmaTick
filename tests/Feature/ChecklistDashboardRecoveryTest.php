<?php

namespace Tests\Feature;

use App\Models\ChecklistItem;
use App\Models\Organization;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChecklistDashboardRecoveryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Personal Checklist CRUD with Tabular and 4 strict Kanban statuses.
     */
    public function test_personal_checklist_crud_and_kanban_statuses(): void
    {
        $user = User::factory()->create();

        // 1. Create Checklist Item
        $response = $this->actingAs($user)->post(route('checklist.store'), [
            'title' => 'Personal Tax Return',
            'description' => 'File Q3 taxes',
            'priority' => 'high',
            'status' => 'To-Do',
            'due_date' => '2026-09-30',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('checklist_items', [
            'user_id' => $user->id,
            'title' => 'Personal Tax Return',
            'status' => 'To-Do',
        ]);

        $item = ChecklistItem::where('title', 'Personal Tax Return')->first();

        // 2. Update Status to Delayed
        $updateStatusRes = $this->actingAs($user)->postJson(route('checklist.updateStatus', $item), [
            'status' => 'Delayed',
        ]);

        $updateStatusRes->assertStatus(200);
        $this->assertEquals('Delayed', $item->fresh()->status);

        // 3. Tabular vs Kanban view page render
        $kanbanRes = $this->actingAs($user)->get(route('checklist.index', ['view' => 'kanban']));
        $kanbanRes->assertStatus(200);
        $kanbanRes->assertSee('Delayed');

        $tableRes = $this->actingAs($user)->get(route('checklist.index', ['view' => 'table']));
        $tableRes->assertStatus(200);
        $tableRes->assertSee('Personal Tax Return');
    }

    /**
     * Test Dashboard metric buckets and 'My Day' agenda section.
     */
    public function test_dashboard_metrics_and_my_day_aggregation(): void
    {
        $user = User::factory()->superAdmin()->create();
        $org = Organization::factory()->create();
        $proj = Project::factory()->create(['organization_id' => $org->id]);
        $task = Task::factory()->create(['project_id' => $proj->id, 'status' => 'In-Progress']);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Executive Dashboard');
        $response->assertSee('Nepal Time');
        $response->assertSee('My Day');
    }

    /**
     * Test System Recovery & Trash Bin strict Super Admin exclusive access.
     */
    public function test_system_recovery_super_admin_exclusive_access(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $member = User::factory()->create(['is_super_admin' => false]);

        // Super Admin access allowed
        $saRes = $this->actingAs($superAdmin)->get(route('recovery.index'));
        $saRes->assertStatus(200);

        // Member access returns 403 Forbidden
        $memberRes = $this->actingAs($member)->get(route('recovery.index'));
        $memberRes->assertStatus(403);
    }

    /**
     * Test System Recovery Trash Bin browser and physical record restoration.
     */
    public function test_system_recovery_trash_bin_and_physical_record_restoration(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();

        // 1. Soft delete an Organization and a Project
        $org = Organization::factory()->create(['name' => 'Org To Restore']);
        $proj = Project::factory()->create(['name' => 'Project To Restore', 'organization_id' => $org->id]);

        $org->delete();
        $proj->delete();

        $this->assertSoftDeleted($org);
        $this->assertSoftDeleted($proj);

        // 2. Physical Restore Organization via RecoveryController
        $restoreOrgRes = $this->actingAs($superAdmin)->post(route('recovery.restore'), [
            'model_type' => 'organization',
            'id' => $org->id,
        ]);

        $restoreOrgRes->assertRedirect();
        $this->assertDatabaseHas('organizations', [
            'id' => $org->id,
            'deleted_at' => null,
        ]);

        // 3. Physical Restore Project via RecoveryController
        $restoreProjRes = $this->actingAs($superAdmin)->post(route('recovery.restore'), [
            'model_type' => 'project',
            'id' => $proj->id,
        ]);

        $restoreProjRes->assertRedirect();
        $this->assertDatabaseHas('projects', [
            'id' => $proj->id,
            'deleted_at' => null,
        ]);

        // 4. Verify Activity Log recorded for restorations
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'restored',
            'user_id' => $superAdmin->id,
        ]);
    }
}
