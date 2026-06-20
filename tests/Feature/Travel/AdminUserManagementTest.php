<?php

namespace Tests\Feature\Travel;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_regular_users_cannot_manage_user_information(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get(route('admin.users.edit', $user));

        $response->assertForbidden();
    }

    public function test_admin_can_update_user_information_without_deleting_account(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $response = $this
            ->actingAs($admin)
            ->patch(route('admin.users.update', $user), [
                'name' => 'Người dùng đã cập nhật',
                'email' => 'updated@example.com',
                'role' => 'admin',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.users', absolute: false));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Người dùng đã cập nhật',
            'email' => 'updated@example.com',
            'role' => 'admin',
            'email_verified_at' => null,
        ]);
    }

    public function test_final_admin_cannot_remove_their_admin_role(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this
            ->actingAs($admin)
            ->from(route('admin.users.edit', $admin))
            ->patch(route('admin.users.update', $admin), [
                'name' => $admin->name,
                'email' => $admin->email,
                'role' => 'user',
            ]);

        $response
            ->assertRedirect(route('admin.users.edit', $admin, absolute: false))
            ->assertSessionHas('error');

        $this->assertSame('admin', $admin->refresh()->role);
    }
}
