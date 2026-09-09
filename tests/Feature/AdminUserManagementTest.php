<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_user_list_screen_fr_adm_03(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.users.index'));

        $response->assertStatus(200);
        $response->assertSee('Daftar Pengguna Sistem (FR-ADM-03)');
    }

    public function test_regular_user_cannot_access_admin_screen_fr_ath_03(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get(route('admin.users.index'));

        $response->assertStatus(403);
    }

    public function test_admin_can_create_new_user_fr_adm_01(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => 'password123',
            'role' => 'user',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'budi@example.com',
            'name' => 'Budi Santoso',
            'role' => 'user',
        ]);
    }

    public function test_admin_can_delete_user_fr_adm_02(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $userToDelete = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $userToDelete->id));

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseMissing('users', [
            'id' => $userToDelete->id,
        ]);
    }

    public function test_admin_can_reset_user_password_fr_adm_04(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($admin)->patch(route('admin.users.reset-password', $user->id), [
            'password' => 'newSecretPass123',
            'password_confirmation' => 'newSecretPass123',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $user->refresh();
        $this->assertTrue(Hash::check('newSecretPass123', $user->password));
    }
}
