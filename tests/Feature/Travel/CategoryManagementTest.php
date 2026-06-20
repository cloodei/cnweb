<?php

namespace Tests\Feature\Travel;

use App\Models\Category;
use App\Models\Location;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_regular_users_can_read_categories_without_write_controls(): void
    {
        Category::create(['name' => 'Biển']);

        $response = $this
            ->actingAs(User::factory()->create())
            ->get(route('categories.index'));

        $response
            ->assertOk()
            ->assertSee('Biển')
            ->assertDontSee('Thêm danh mục')
            ->assertDontSee('Xóa');
    }

    public function test_regular_users_cannot_open_category_management(): void
    {
        $response = $this
            ->actingAs(User::factory()->create())
            ->get(route('admin.categories'));

        $response->assertForbidden();
    }

    public function test_admin_can_create_category(): void
    {
        $response = $this
            ->actingAs(User::factory()->admin()->create())
            ->post(route('admin.categories.store'), [
                'name' => 'Núi',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.categories', absolute: false));

        $this->assertDatabaseHas('categories', [
            'name' => 'Núi',
        ]);
    }

    public function test_admin_can_rename_category(): void
    {
        $category = Category::create(['name' => 'Danh mục cũ']);

        $response = $this
            ->actingAs(User::factory()->admin()->create())
            ->patch(route('admin.categories.update', $category), [
                'name' => 'Di sản',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.categories', absolute: false));

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Di sản',
        ]);
    }

    public function test_category_with_locations_cannot_be_deleted(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::create(['name' => 'Biển']);

        Location::create([
            'category_id' => $category->id,
            'user_id' => $admin->id,
            'name' => 'Vịnh Hạ Long',
        ]);

        $response = $this
            ->actingAs($admin)
            ->delete(route('admin.categories.destroy', $category));

        $response
            ->assertRedirect(route('admin.categories', absolute: false))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
        ]);
    }
}
