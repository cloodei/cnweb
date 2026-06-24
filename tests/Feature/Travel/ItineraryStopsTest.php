<?php

namespace Tests\Feature\Travel;

use App\Models\Category;
use App\Models\Group;
use App\Models\Itinerary;
use App\Models\Location;
use App\Models\ScheduledStop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ItineraryStopsTest extends TestCase
{
    use RefreshDatabase;

    public function test_removing_a_stop_deletes_only_that_scheduled_stop(): void
    {
        $user = User::factory()->create();
        $group = Group::create([
            'owner_id' => $user->id,
            'name' => 'Nhóm Hạ Long',
        ]);
        $group->members()->attach($user->id, ['role' => Group::ROLE_OWNER]);
        $category = Category::create(['name' => 'Biển']);
        $location = Location::create([
            'category_id' => $category->id,
            'user_id' => $user->id,
            'name' => 'Vịnh Hạ Long',
        ]);
        $itinerary = Itinerary::create([
            'group_id' => $group->id,
            'user_id' => $user->id,
            'title' => 'Chuyến đi Hạ Long',
            'start_date' => '2026-06-10',
            'end_date' => '2026-06-12',
        ]);

        $itinerary->locations()->attach($location->id, [
            'visit_time' => '2026-06-10 08:00:00',
            'note' => 'Buổi sáng',
        ]);
        $itinerary->locations()->attach($location->id, [
            'visit_time' => '2026-06-10 18:00:00',
            'note' => 'Buổi tối',
        ]);

        $stopId = DB::table('itinerary_location')
            ->where('note', 'Buổi sáng')
            ->value('id');

        $response = $this
            ->actingAs($user)
            ->delete(route('groups.itineraries.remove-stop', [
                'group' => $group,
                'itinerary' => $itinerary,
                'stop' => $stopId,
            ]));

        $response->assertRedirect();

        $this->assertDatabaseMissing('itinerary_location', [
            'id' => $stopId,
        ]);
        $this->assertDatabaseHas('itinerary_location', [
            'itinerary_id' => $itinerary->id,
            'location_id' => $location->id,
            'note' => 'Buổi tối',
        ]);
    }

    public function test_non_owner_cannot_remove_stop(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $group = Group::create([
            'owner_id' => $owner->id,
            'name' => 'Nhóm Hạ Long',
        ]);
        $group->members()->attach($owner->id, ['role' => Group::ROLE_OWNER]);
        $category = Category::create(['name' => 'Biển']);
        $location = Location::create([
            'category_id' => $category->id,
            'user_id' => $owner->id,
            'name' => 'Vịnh Hạ Long',
        ]);
        $itinerary = Itinerary::create([
            'group_id' => $group->id,
            'user_id' => $owner->id,
            'title' => 'Chuyến đi Hạ Long',
            'start_date' => '2026-06-10',
            'end_date' => '2026-06-12',
        ]);

        $itinerary->locations()->attach($location->id, ['note' => 'Không được xóa']);

        $stopId = DB::table('itinerary_location')->value('id');

        $response = $this
            ->actingAs($otherUser)
            ->delete(route('groups.itineraries.remove-stop', [
                'group' => $group,
                'itinerary' => $itinerary,
                'stop' => $stopId,
            ]));

        $response->assertForbidden();
    }

    public function test_scheduled_stop_requires_exactly_one_destination_source(): void
    {
        $user = User::factory()->create();
        $group = Group::create([
            'owner_id' => $user->id,
            'name' => 'Nhóm Hạ Long',
        ]);
        $group->members()->attach($user->id, ['role' => Group::ROLE_OWNER]);
        $category = Category::create(['name' => 'Biển']);
        $location = Location::create([
            'category_id' => $category->id,
            'user_id' => $user->id,
            'name' => 'Vịnh Hạ Long',
        ]);
        $itinerary = Itinerary::create([
            'group_id' => $group->id,
            'user_id' => $user->id,
            'title' => 'Chuyến đi Hạ Long',
            'start_date' => '2026-06-10',
            'end_date' => '2026-06-12',
        ]);

        $this->expectException(ValidationException::class);

        ScheduledStop::create([
            'itinerary_id' => $itinerary->id,
            'location_id' => $location->id,
            'group_location_id' => $location->id,
            'note' => 'Không hợp lệ',
        ]);
    }
}
