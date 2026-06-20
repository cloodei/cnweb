<?php

namespace Tests\Feature\Travel;

use App\Models\Category;
use App\Models\Location;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocationManagementTest extends TestCase
{
    use RefreshDatabase;

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
}
