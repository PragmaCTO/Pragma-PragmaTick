<?php

namespace Tests\Feature;

use App\Models\CalendarEvent;
use App\Models\Contact;
use App\Models\Organization;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CalendarAndContactsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test monthly calendar grid and event scheduling.
     */
    public function test_monthly_calendar_grid_and_event_scheduling(): void
    {
        $user = User::factory()->superAdmin()->create();
        $response = $this->actingAs($user)->get(route('calendar.index', ['month' => 9, 'year' => 2026]));

        $response->assertStatus(200);
        $response->assertSee('September 2026');

        $scheduleResponse = $this->actingAs($user)->post(route('calendar.store'), [
            'title' => 'Product Roadmap Sync',
            'description' => 'Q4 Roadmap Discussion',
            'start_time' => '2026-09-15 10:00:00',
            'end_time' => '2026-09-15 11:00:00',
        ]);

        $scheduleResponse->assertRedirect();
        $this->assertDatabaseHas('calendar_events', [
            'title' => 'Product Roadmap Sync',
            'organizer_id' => $user->id,
        ]);

        $event = CalendarEvent::where('title', 'Product Roadmap Sync')->first();
        $showResponse = $this->actingAs($user)->get(route('calendar.show', $event));
        $showResponse->assertStatus(200);
        $showResponse->assertSee('Product Roadmap Sync');
        $showResponse->assertSee('Q4 Roadmap Discussion');
    }

    /**
     * Test overlap conflict detection algorithm and override flag.
     */
    public function test_overlap_conflict_detection_and_override(): void
    {
        $user = User::factory()->superAdmin()->create();

        // Initial Event
        CalendarEvent::create([
            'organizer_id' => $user->id,
            'title' => 'Existing Meeting',
            'start_time' => Carbon::parse('2026-09-20 14:00:00'),
            'end_time' => Carbon::parse('2026-09-20 15:00:00'),
        ]);

        // Attempt overlapping event without override -> triggers conflict warning
        $overlapResponse = $this->actingAs($user)->post(route('calendar.store'), [
            'title' => 'Conflicting Meeting',
            'start_time' => '2026-09-20 14:30:00',
            'end_time' => '2026-09-20 15:30:00',
            'attendees' => [$user->id],
        ]);

        $overlapResponse->assertSessionHas('conflict_warning');

        // Submit with override_conflict = 1 -> succeeds
        $overrideResponse = $this->actingAs($user)->post(route('calendar.store'), [
            'title' => 'Overridden Meeting',
            'start_time' => '2026-09-20 14:30:00',
            'end_time' => '2026-09-20 15:30:00',
            'attendees' => [$user->id],
            'override_conflict' => '1',
        ]);

        $overrideResponse->assertRedirect();
        $this->assertDatabaseHas('calendar_events', ['title' => 'Overridden Meeting']);
    }

    /**
     * Test Super Admin personal event renders in distinct Rose color.
     */
    public function test_super_admin_aggregate_overlay_view_and_distinct_color(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();

        $response = $this->actingAs($superAdmin)->post(route('calendar.store'), [
            'title' => 'Executive Keynote',
            'start_time' => '2026-09-25 09:00:00',
            'end_time' => '2026-09-25 10:00:00',
        ]);

        $response->assertRedirect();

        $event = CalendarEvent::where('title', 'Executive Keynote')->first();
        $this->assertTrue($event->is_super_admin_event);
        $this->assertEquals('#f43f5e', $event->color);
    }

    /**
     * Test Contacts CRM strict Super Admin exclusive access restriction.
     */
    public function test_contacts_crm_super_admin_exclusive_access(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $member = User::factory()->create(['is_super_admin' => false]);

        // Super Admin access allowed
        $saResponse = $this->actingAs($superAdmin)->get(route('contacts.index'));
        $saResponse->assertStatus(200);

        // Standard Member access returns 403 Forbidden
        $memberResponse = $this->actingAs($member)->get(route('contacts.index'));
        $memberResponse->assertStatus(403);
    }

    /**
     * Test Contacts CRM CRUD and soft deletion with Activity Log tracking.
     */
    public function test_contacts_crm_crud_and_soft_delete(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();

        // Create Contact
        $createRes = $this->actingAs($superAdmin)->post(route('contacts.store'), [
            'name' => 'Miles Dyson',
            'company' => 'Cyberdyne Systems',
            'position' => 'Chief Scientist',
            'phone' => '+1-555-0100',
            'email' => 'miles@cyberdyne.com',
            'notes' => 'Neural Net Processor Architect',
        ]);
        $createRes->assertRedirect();

        $contact = Contact::where('name', 'Miles Dyson')->first();
        $this->assertNotNull($contact);

        // Update Contact
        $updateRes = $this->actingAs($superAdmin)->put(route('contacts.update', $contact), [
            'name' => 'Miles Dyson (Updated)',
            'company' => 'Cyberdyne Systems',
            'position' => 'VP Technology',
            'phone' => '+1-555-0199',
            'email' => 'miles@cyberdyne.com',
            'notes' => 'Updated notes',
        ]);
        $updateRes->assertRedirect();
        $this->assertEquals('Miles Dyson (Updated)', $contact->fresh()->name);

        // Soft Delete Contact
        $deleteRes = $this->actingAs($superAdmin)->delete(route('contacts.destroy', $contact));
        $deleteRes->assertRedirect();
        $this->assertSoftDeleted($contact);

        // Verify Activity Log recorded
        $this->assertDatabaseHas('activity_logs', ['action' => 'deleted', 'user_id' => $superAdmin->id]);
    }
}
