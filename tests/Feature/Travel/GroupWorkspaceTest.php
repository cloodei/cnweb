<?php

namespace Tests\Feature\Travel;

use App\Models\Category;
use App\Models\Group;
use App\Models\GroupInvite;
use App\Models\GroupLocation;
use App\Models\Itinerary;
use App\Models\Location;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class GroupWorkspaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_group_creator_becomes_owner_member(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post(route('groups.store'), [
                'name' => 'Nhóm Đà Nẵng',
                'description' => 'Đi biển và Hội An.',
            ]);

        $group = Group::where('name', 'Nhóm Đà Nẵng')->firstOrFail();

        $response->assertRedirect(route('groups.show', $group, absolute: false));
        $this->assertSame($user->id, $group->owner_id);
        $this->assertDatabaseHas('group_user', [
            'group_id' => $group->id,
            'user_id' => $user->id,
            'role' => Group::ROLE_OWNER,
        ]);
    }

    public function test_editor_can_create_and_update_group_itinerary(): void
    {
        [$owner, $editor, $group] = $this->groupWithMembers();

        $createResponse = $this
            ->actingAs($editor)
            ->post(route('groups.itineraries.store', $group), [
                'title' => 'Đà Nẵng - Hội An',
                'description' => 'Đi biển trước, phố cổ sau.',
                'start_date' => '2026-07-20',
                'end_date' => '2026-07-22',
            ]);

        $itinerary = Itinerary::where('title', 'Đà Nẵng - Hội An')->firstOrFail();

        $createResponse->assertRedirect(route('groups.itineraries.show', [$group, $itinerary], absolute: false));
        $this->assertSame($group->id, $itinerary->group_id);
        $this->assertSame($editor->id, $itinerary->user_id);

        $updateResponse = $this
            ->actingAs($editor)
            ->patch(route('groups.itineraries.update', [$group, $itinerary]), [
                'title' => 'Đà Nẵng - Hội An cập nhật',
                'description' => 'Chỉnh lịch theo nhóm.',
                'start_date' => '2026-07-20',
                'end_date' => '2026-07-23',
            ]);

        $updateResponse->assertRedirect(route('groups.itineraries.show', [$group, $itinerary], absolute: false));
        $this->assertSame('Đà Nẵng - Hội An cập nhật', $itinerary->refresh()->title);
        $this->assertSame($owner->id, $group->owner_id);
    }

    public function test_viewer_can_view_but_cannot_mutate_stops(): void
    {
        [, , $group, $viewer] = $this->groupWithMembers();
        [$itinerary, $location] = $this->itineraryWithLocation($group);

        $this
            ->actingAs($viewer)
            ->get(route('groups.itineraries.show', [$group, $itinerary]))
            ->assertOk()
            ->assertSee($itinerary->title);

        $response = $this
            ->actingAs($viewer)
            ->post(route('groups.itineraries.add-location', [$group, $itinerary]), [
                'destination_ref' => 'shared:'.$location->id,
                'visit_time' => '2026-07-20 08:00:00',
                'note' => 'Không được thêm',
            ]);

        $response->assertForbidden();
    }

    public function test_non_member_cannot_view_group_itinerary(): void
    {
        $outsider = User::factory()->create();
        [, , $group] = $this->groupWithMembers();
        [$itinerary] = $this->itineraryWithLocation($group);

        $this
            ->actingAs($outsider)
            ->get(route('groups.itineraries.show', [$group, $itinerary]))
            ->assertForbidden();
    }

    public function test_group_owner_can_create_invite_and_user_can_accept_it(): void
    {
        [$owner, , $group] = $this->groupWithMembers();
        $joiner = User::factory()->create();

        $response = $this
            ->actingAs($owner)
            ->post(route('groups.invites.store', $group), [
                'role' => Group::ROLE_EDITOR,
                'duration' => '24_hours',
                'max_uses' => 1,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('createdInviteUrl');

        $invite = GroupInvite::firstOrFail();
        $token = basename((string) session('createdInviteUrl'));

        $this
            ->actingAs($joiner)
            ->post(route('group-invites.accept', $token))
            ->assertRedirect(route('groups.show', $group, absolute: false));

        $this->assertDatabaseHas('group_user', [
            'group_id' => $group->id,
            'user_id' => $joiner->id,
            'role' => Group::ROLE_EDITOR,
        ]);
        $this->assertSame(1, $invite->refresh()->uses_count);
        $this->assertDatabaseHas('group_invite_acceptances', [
            'group_invite_id' => $invite->id,
            'user_id' => $joiner->id,
        ]);
    }

    public function test_expired_or_maxed_invites_cannot_be_accepted(): void
    {
        [$owner, , $group] = $this->groupWithMembers();
        $expiredToken = Str::random(48);
        GroupInvite::create([
            'group_id' => $group->id,
            'created_by' => $owner->id,
            'token_hash' => hash('sha256', $expiredToken),
            'role' => Group::ROLE_VIEWER,
            'expires_at' => now()->subMinute(),
            'max_uses' => 1,
            'uses_count' => 0,
        ]);

        $this
            ->actingAs(User::factory()->create())
            ->post(route('group-invites.accept', $expiredToken))
            ->assertRedirect(route('groups.index', absolute: false));

        $usedToken = Str::random(48);
        GroupInvite::create([
            'group_id' => $group->id,
            'created_by' => $owner->id,
            'token_hash' => hash('sha256', $usedToken),
            'role' => Group::ROLE_VIEWER,
            'expires_at' => now()->addDay(),
            'max_uses' => 1,
            'uses_count' => 1,
        ]);

        $this
            ->actingAs(User::factory()->create())
            ->post(route('group-invites.accept', $usedToken))
            ->assertRedirect(route('groups.index', absolute: false));

        $this->assertSame(3, DB::table('group_user')->count());
    }

    public function test_editor_can_create_group_private_destination_and_schedule_it(): void
    {
        [, $editor, $group] = $this->groupWithMembers();
        [$itinerary] = $this->itineraryWithLocation($group);

        $response = $this
            ->actingAs($editor)
            ->post(route('groups.destinations.store', $group), [
                'name' => 'Khách sạn nhóm',
                'address' => 'Hải Châu, Đà Nẵng',
                'description' => 'Điểm hẹn riêng của nhóm.',
                'google_place_id' => 'place-test',
                'latitude' => 16.067783,
                'longitude' => 108.220833,
            ]);

        $groupLocation = GroupLocation::where('name', 'Khách sạn nhóm')->firstOrFail();

        $response->assertRedirect(route('groups.destinations.index', $group, absolute: false));
        $this->assertSame($group->id, $groupLocation->group_id);

        $this
            ->actingAs($editor)
            ->post(route('groups.itineraries.add-location', [$group, $itinerary]), [
                'destination_ref' => 'group:'.$groupLocation->id,
                'visit_time' => '2026-07-20 15:00:00',
                'note' => 'Nhận phòng.',
            ])
            ->assertRedirect(route('groups.itineraries.show', [$group, $itinerary], absolute: false));

        $this->assertDatabaseHas('itinerary_location', [
            'itinerary_id' => $itinerary->id,
            'location_id' => null,
            'group_location_id' => $groupLocation->id,
            'note' => 'Nhận phòng.',
        ]);
    }

    private function groupWithMembers(): array
    {
        $owner = User::factory()->create();
        $editor = User::factory()->create();
        $viewer = User::factory()->create();
        $group = Group::create([
            'owner_id' => $owner->id,
            'name' => 'Nhóm kiểm thử',
        ]);

        $group->members()->attach($owner->id, ['role' => Group::ROLE_OWNER]);
        $group->members()->attach($editor->id, ['role' => Group::ROLE_EDITOR]);
        $group->members()->attach($viewer->id, ['role' => Group::ROLE_VIEWER]);

        return [$owner, $editor, $group, $viewer];
    }

    private function itineraryWithLocation(Group $group): array
    {
        $creator = $group->owner;
        $category = Category::create(['name' => 'Biển']);
        $location = Location::create([
            'category_id' => $category->id,
            'user_id' => $creator->id,
            'name' => 'Bãi biển Mỹ Khê',
        ]);
        $itinerary = Itinerary::create([
            'group_id' => $group->id,
            'user_id' => $creator->id,
            'title' => 'Lịch trình nhóm',
            'start_date' => '2026-07-20',
            'end_date' => '2026-07-22',
        ]);

        $itinerary->locations()->attach($location->id, [
            'visit_time' => '2026-07-20 08:00:00',
            'note' => 'Đi biển',
        ]);

        return [$itinerary, $location];
    }
}
