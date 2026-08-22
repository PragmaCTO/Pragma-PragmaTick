<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserCreationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Super Admin exclusive user creation feature.
     */
    public function test_super_admin_can_create_new_users(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();

        $response = $this->actingAs($superAdmin)->post(route('users.store'), [
            'name' => 'Marcus Brody',
            'email' => 'marcus@pragmatick.io',
            'phone_number' => '+1-555-0188',
            'password' => 'secretpassword123',
            'is_super_admin' => 0,
            'secondary_emails' => ['marcus.dev@company.com'],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'name' => 'Marcus Brody',
            'email' => 'marcus@pragmatick.io',
            'phone_number' => '+1-555-0188',
            'is_super_admin' => false,
        ]);

        $user = User::where('email', 'marcus@pragmatick.io')->first();
        $this->assertCount(2, $user->emails);
    }

    /**
     * Test Non-Super-Admin is forbidden from creating new users.
     */
    public function test_non_super_admin_cannot_create_users(): void
    {
        $member = User::factory()->create(['is_super_admin' => false]);

        $response = $this->actingAs($member)->post(route('users.store'), [
            'name' => 'Unauthorized User',
            'email' => 'unauth@pragmatick.io',
            'password' => 'password123',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('users', ['email' => 'unauth@pragmatick.io']);
    }

    /**
     * Test Super Admin and Org Admin scoped user soft-delete feature.
     */
    public function test_super_admin_and_org_admin_can_soft_delete_users(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $targetUser = User::factory()->create();

        // Super Admin soft deletes user
        $response = $this->actingAs($superAdmin)->delete(route('users.destroy', $targetUser));
        $response->assertRedirect();
        $this->assertSoftDeleted('users', ['id' => $targetUser->id]);

        // Org Admin soft deletes member in their org
        $orgAdmin = User::factory()->create();
        $orgMember = User::factory()->create();
        $org = \App\Models\Organization::factory()->create();
        $org->users()->attach($orgAdmin->id, ['role' => 'org_admin']);
        $org->users()->attach($orgMember->id, ['role' => 'member']);

        $response = $this->actingAs($orgAdmin)->delete(route('users.destroy', $orgMember));
        $response->assertRedirect();
        $this->assertSoftDeleted('users', ['id' => $orgMember->id]);
    }

    /**
     * Test Super Admin can update user details and password.
     */
    public function test_super_admin_can_update_user_details_and_password(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $targetUser = User::factory()->create([
            'name' => 'Original Name',
            'email' => 'original@pragmatick.io',
        ]);

        $response = $this->actingAs($superAdmin)->put(route('users.update', $targetUser), [
            'name' => 'Updated Name',
            'email' => 'updated@pragmatick.io',
            'phone_number' => '+1-555-9999',
            'password' => 'newpassword123',
            'is_super_admin' => 1,
        ]);

        $response->assertRedirect();
        $targetUser->refresh();

        $this->assertEquals('Updated Name', $targetUser->name);
        $this->assertEquals('updated@pragmatick.io', $targetUser->email);
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('newpassword123', $targetUser->password));
        $this->assertTrue($targetUser->is_super_admin);
    }
}
