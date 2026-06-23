<?php

namespace Tests\Feature\Travel;

use App\Models\Category;
use App\Models\Location;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LocationManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_location_creation_uses_internal_default_category(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post(route('locations.store'), [
                'name' => 'Địa điểm mới',
                'description' => 'Mô tả',
                'address' => 'Đà Nẵng',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('locations.index', absolute: false));

        $this->assertDatabaseHas('categories', [
            'name' => 'Chưa phân loại',
        ]);

        $this->assertDatabaseHas('locations', [
            'user_id' => $user->id,
            'name' => 'Địa điểm mới',
            'address' => 'Đà Nẵng',
        ]);
    }

    public function test_location_update_without_address_keeps_existing_address(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Biển']);
        $location = Location::create([
            'category_id' => $category->id,
            'user_id' => $user->id,
            'name' => 'Tên cũ',
            'description' => 'Mô tả cũ',
            'address' => 'Địa chỉ cũ',
        ]);

        $response = $this
            ->actingAs($user)
            ->put(route('locations.update', $location), [
                'name' => 'Tên mới',
                'category_id' => $category->id,
                'description' => 'Mô tả mới',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('locations.index', absolute: false));

        $this->assertDatabaseHas('locations', [
            'id' => $location->id,
            'name' => 'Tên mới',
            'description' => 'Mô tả mới',
            'address' => 'Địa chỉ cũ',
        ]);
    }

    public function test_location_update_can_clear_address(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Biển']);
        $location = Location::create([
            'category_id' => $category->id,
            'user_id' => $user->id,
            'name' => 'Tên cũ',
            'description' => 'Mô tả cũ',
            'address' => 'Địa chỉ cũ',
        ]);

        $response = $this
            ->actingAs($user)
            ->put(route('locations.update', $location), [
                'name' => 'Tên mới',
                'category_id' => $category->id,
                'description' => 'Mô tả mới',
                'address' => '',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('locations.index', absolute: false));

        $this->assertDatabaseHas('locations', [
            'id' => $location->id,
            'name' => 'Tên mới',
            'description' => 'Mô tả mới',
            'address' => null,
        ]);
    }

    public function test_non_owner_cannot_update_location(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $category = Category::create(['name' => 'Núi']);
        $location = Location::create([
            'category_id' => $category->id,
            'user_id' => $owner->id,
            'name' => 'Địa điểm',
        ]);

        $response = $this
            ->actingAs($otherUser)
            ->put(route('locations.update', $location), [
                'name' => 'Không được sửa',
                'category_id' => $category->id,
            ]);

        $response->assertForbidden();
    }

    public function test_location_owner_can_delete_location(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Biển']);
        $location = Location::create([
            'category_id' => $category->id,
            'user_id' => $user->id,
            'name' => 'Địa điểm cần xóa',
            'description' => 'Mô tả',
        ]);

        $response = $this
            ->actingAs($user)
            ->delete(route('locations.destroy', $location));

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('locations.index', absolute: false));

        $this->assertDatabaseMissing('locations', [
            'id' => $location->id,
        ]);
    }

    public function test_location_owner_can_remove_existing_image_during_update(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $category = Category::create(['name' => 'Biển']);
        $location = Location::create([
            'category_id' => $category->id,
            'user_id' => $user->id,
            'name' => 'Địa điểm có ảnh',
            'description' => 'Mô tả',
            'image' => 'locations/original.jpg',
        ]);

        Storage::disk('public')->put('locations/original.jpg', 'old-image');

        $response = $this
            ->actingAs($user)
            ->put(route('locations.update', $location), [
                'name' => 'Địa điểm có ảnh',
                'category_id' => $category->id,
                'description' => 'Mô tả',
                'remove_image' => '1',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('locations.index', absolute: false));

        $this->assertDatabaseHas('locations', [
            'id' => $location->id,
            'image' => null,
        ]);

        Storage::disk('public')->assertMissing('locations/original.jpg');
    }

    public function test_location_index_searches_by_address(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Biển']);

        Location::create([
            'category_id' => $category->id,
            'user_id' => $user->id,
            'name' => 'Địa điểm A',
            'address' => 'Phường Bãi Cháy, TP. Hạ Long',
        ]);

        Location::create([
            'category_id' => $category->id,
            'user_id' => $user->id,
            'name' => 'Địa điểm B',
            'address' => 'Đà Nẵng',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('locations.index', ['search' => 'Hạ Long']));

        $response->assertOk();
        $response->assertSee('Địa điểm A');
        $response->assertDontSee('Địa điểm B');
    }

    public function test_location_show_map_search_uses_name_and_address(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Biển']);

        $location = Location::create([
            'category_id' => $category->id,
            'user_id' => $user->id,
            'name' => 'Địa điểm A',
            'address' => 'Phường Bãi Cháy, TP. Hạ Long',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('locations.show', $location));

        $response->assertOk();
        $this->assertStringContainsString(
            urlencode('Địa điểm A Phường Bãi Cháy, TP. Hạ Long'),
            $response->getContent()
        );
    }
}
